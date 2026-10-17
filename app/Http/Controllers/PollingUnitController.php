<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PollingUnitController extends Controller
{
    public function index()
    {
        $lgas = DB::table('lga')
            ->where('state_id', 25)
            ->orderBy('lga_name')
            ->get(['uniqueid', 'lga_id', 'lga_name']);

        return view('polling-unit.index', compact('lgas'));
    }

    public function wards($lga_id)
    {
        $wards = DB::table('ward')
            ->where('lga_id', $lga_id)
            ->orderBy('ward_name')
            ->get(['uniqueid', 'ward_id', 'ward_name']);

        return response()->json($wards);
    }

    public function pollingUnits($ward_id)
    {
        $pus = DB::table('polling_unit')
            ->where('ward_id', $ward_id)
            ->whereNotNull('polling_unit_name')
            ->where('polling_unit_name', '<>', '')
            ->orderBy('polling_unit_name')
            ->get(['uniqueid', 'polling_unit_name', 'polling_unit_number']);

        return response()->json($pus);
    }

    public function result($uniqueid)
    {
        $pu = DB::table('polling_unit')
            ->where('uniqueid', $uniqueid)
            ->first();

        if (!$pu) {
            return response()->json(['error' => 'Polling unit not found'], 404);
        }

        $results = DB::table('announced_pu_results')
            ->where('polling_unit_uniqueid', (string) $uniqueid)
            ->orderBy('party_abbreviation')
            ->get(['party_abbreviation', 'party_score']);

        return response()->json([
            'polling_unit' => $pu,
            'results'      => $results,
        ]);
    }
}