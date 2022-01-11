<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pista;
use DateTime;

class UserController extends Controller
{
    public function pista() {
        $current_date = new DateTime();

        $plus_date = new DateTime();
        $plus_date->add(new \DateInterval('P6D'));

        $period = new \DatePeriod($current_date, \DateInterval::createFromDateString('1 day'), $plus_date);

        $pistas = Pista::all();
        return view('home', compact('period', 'pistas'));
    }
}