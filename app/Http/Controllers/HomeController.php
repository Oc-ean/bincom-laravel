<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'lgas'          => DB::table('lga')->where('state_id', 25)->count(),
            'wards'         => DB::table('ward')->count(),
            'polling_units' => DB::table('polling_unit')->count(),
        ];

        return view('home', compact('stats'));
    }
}