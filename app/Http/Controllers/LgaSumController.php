<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class LgaSumController extends Controller
{
    public function index()
    {
        $lgas = DB::table('lga')
            ->where('state_id', 25)
            ->orderBy('lga_name')
            ->get(['uniqueid', 'lga_id', 'lga_name']);

        return view('lga-sum.index', compact('lgas'));
    }

    public function data($lga_id)
    {
        $lga = DB::table('lga')->where('lga_id', $lga_id)->first();

        if (!$lga) {
            return response()->json(['error' => 'LGA not found'], 404);
        }

        $puIds = DB::table('polling_unit')
            ->where('lga_id', $lga_id)
            ->pluck('uniqueid')
            ->map(fn ($id) => (string) $id)
            ->all();

        if (empty($puIds)) {
            return response()->json([
                'lga'                 => $lga,
                'polling_unit_count'  => 0,
                'summed'              => [],
                'announced'           => [],
            ]);
        }

        $summed = DB::table('announced_pu_results')
            ->whereIn('polling_unit_uniqueid', $puIds)
            ->selectRaw('TRIM(party_abbreviation) as party_abbreviation, SUM(party_score) as total')
            ->groupBy(DB::raw('TRIM(party_abbreviation)'))
            ->orderBy('party_abbreviation')
            ->get();

        $announced = DB::table('announced_lga_results')
            ->where('lga_name', (string) $lga_id)
            ->orderBy('party_abbreviation')
            ->get(['party_abbreviation', 'party_score']);

        return response()->json([
            'lga'                => $lga,
            'polling_unit_count' => count($puIds),
            'summed'             => $summed,
            'announced'          => $announced,
        ]);
    }
}