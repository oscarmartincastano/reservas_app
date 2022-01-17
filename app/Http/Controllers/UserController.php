<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Instalacion;
use App\Models\Pista;
use DateTime;

class UserController extends Controller
{
    public function index() {
        $instalacion = Instalacion::first();
        return view('home', compact('instalacion'));
    }

    public function pistas(Request $request) {

        $current_date = new DateTime();

        $plus_date = new DateTime();
        $plus_date->add(new \DateInterval('P6D'));

        $period = new \DatePeriod($current_date, \DateInterval::createFromDateString('1 day'), $plus_date);

        $pistas = Pista::where('tipo', $request->deporte)->get();

        if (isset($request->id_pista)) {
            $pista_selected = Pista::find($request->id_pista);
        } else{
            $pista_selected = $pistas[0];
        }

        return view('pista', compact('period', 'pistas', 'pista_selected'));
    }

    public function reserva(Request $request)
    {
        $pista = Pista::find($request->id_pista);
        $fecha = $request->timestamp;
        
        foreach ($pista->horario_deserialized as $item){
            if (in_array(date('w', $fecha), $item['dias']) || ( date('w', $fecha) == 0 && in_array(7, $item['dias']) )){
                foreach ($item['intervalo'] as $index => $intervalo){
                    $a = new \DateTime($intervalo['hfin']);
                    $b = new \DateTime($intervalo['hinicio']);
                    $interval = $a->diff($b);
                    $diff_minutes = $interval->format("%h") * 60;
                    $diff_minutes += $interval->format("%i");
                    $numero_veces = $diff_minutes/$intervalo['secuencia'];

                    $hora = new \DateTime($intervalo['hinicio']);
                    for ($i=0; $i < floor($numero_veces); $i++) { 
                        if ($hora->format('h:i') == date(date('h:i', $fecha))) {
                            $secuencia = $intervalo['secuencia'];
                            /* $hfin = date('h:i',strtotime (date('h:i', $fecha) . " +{$intervalo['secuencia']} minutes")); */
                        }
                        $hora->modify("+{$intervalo['secuencia']} minutes");
                    }
                }
            }
        }

        return view('reserva', compact('pista', 'fecha', 'secuencia'));
    }
}