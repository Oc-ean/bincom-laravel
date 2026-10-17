<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AddPollingUnitController extends Controller
{
    public function index()
    {
        $lgas    = DB::table('lga')->where('state_id', 25)->orderBy('lga_name')
                     ->get(['uniqueid', 'lga_id', 'lga_name']);
        $parties = DB::table('party')->orderBy('id')->get();

        return view('add-polling-unit.index', compact('lgas', 'parties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'polling_unit_name'        => 'required|string|max:50',
            'polling_unit_number'      => 'nullable|string|max:50',
            'polling_unit_description' => 'nullable|string',
            'ward_id'                  => 'required|integer',
            'lga_id'                   => 'required|integer',
            'lat'                      => 'nullable|string|max:255',
            'long'                     => 'nullable|string|max:255',
            'entered_by_user'          => 'nullable|string|max:50',
            'scores'                   => 'required|array',
            'scores.*'                 => 'nullable|integer|min:0',
        ]);

        try {
            $newId = DB::transaction(function () use ($validated, $request) {
                $ip  = $request->ip();
                $now = now();

                $newId = DB::table('polling_unit')->insertGetId([
                    'polling_unit_id'         => 0,
                    'ward_id'                 => $validated['ward_id'],
                    'lga_id'                  => $validated['lga_id'],
                    'uniquewardid'            => null,
                    'polling_unit_number'     => $validated['polling_unit_number'] ?? null,
                    'polling_unit_name'       => $validated['polling_unit_name'],
                    'polling_unit_description'=> $validated['polling_unit_description'] ?? null,
                    'lat'                     => $validated['lat'] ?? null,
                    'long'                    => $validated['long'] ?? null,
                    'entered_by_user'         => $validated['entered_by_user'] ?? 'web',
                    'date_entered'            => $now,
                    'user_ip_address'         => $ip,
                ], 'uniqueid');

                $rows = [];
                foreach ($validated['scores'] as $party => $score) {
                    $rows[] = [
                        'polling_unit_uniqueid' => (string) $newId,
                        'party_abbreviation'    => $party,
                        'party_score'           => (int) ($score ?? 0),
                        'entered_by_user'       => $validated['entered_by_user'] ?? 'web',
                        'date_entered'          => $now,
                        'user_ip_address'       => $ip,
                    ];
                }
                DB::table('announced_pu_results')->insert($rows);

                return $newId;
            });

            return redirect()
                ->route('add-polling-unit')
                ->with('success', "Polling unit #$newId saved with " . count($validated['scores']) . " party results.");
        } catch (\Throwable $e) {
            Log::error('Add PU failed', ['error' => $e->getMessage()]);
            return back()
                ->withInput()
                ->with('error', 'Failed to save: ' . $e->getMessage());
        }
    }
}