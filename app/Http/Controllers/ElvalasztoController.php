<?php

namespace App\Http\Controllers;

use App\Models\Diak;
use App\Models\Ertekeles;
use App\Models\Szulo;
use App\Models\Tanar;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PHPUnit\TextUI\XmlConfiguration\Group;

class ElvalasztoController extends Controller
{
   public function Dash()
   {
    $azonositoValaszto = mb_substr(Auth::user()->azonosito, 0, 1);
    switch ($azonositoValaszto) {
        case 'd':
            $user = Diak::where(['azonosito' => Auth::user()->azonosito])->first();
            return View('diak.diak_dashboard',['user'=>$user]);
            break;
        case 's':
            $user = Szulo::where(['azonosito' => Auth::user()->azonosito])->first();
            return View('szulo.szulo_dashboard',['user'=>$user]);
            break;
        case 't':
            $user = Tanar::where(['azonosito' => Auth::user()->azonosito])->first();
            return View('tanar.tanar_dashboard',['user'=>$user]);
            break;
    }
   }
   public function fiok()
   {
    $azonositoValaszto = mb_substr(Auth::user()->azonosito, 0, 1);

    switch ($azonositoValaszto) {
        case 'd':
            $user = Diak::where(['azonosito' => Auth::user()->azonosito])->first();
            return View('diak.fiok',['user'=>$user]);
            break;
        case 's':
            $user = Szulo::where(['azonosito' => Auth::user()->azonosito])->first();
            return View('szulo.fiok',['user'=>$user]);
            break;
        case 't':
            $user = Tanar::where(['azonosito' => Auth::user()->azonosito])->first();
            return View('tanar.fiok',['user'=>$user]);
            break;
    }
   }
   public function hianyzas()
   {
    $azonositoValaszto = mb_substr(Auth::user()->azonosito, 0, 1);

    switch ($azonositoValaszto) {
        case 'd':
            $user = Diak::where(['azonosito' => Auth::user()->azonosito])->first();
            return View('diak.hianyzas',['user'=>$user]);
            break;
        case 's':
            $user = Szulo::where(['azonosito' => Auth::user()->azonosito])->first();
            return View('szulo.hianyzas',['user'=>$user]);
            break;
        case 't':
            $user = Tanar::where(['azonosito' => Auth::user()->azonosito])->first();
            return View('tanar.hianyzas',['user'=>$user]);
            break;
    }
   }
   public function ora()
   {
    $azonositoValaszto = mb_substr(Auth::user()->azonosito, 0, 1);

    switch ($azonositoValaszto) {
        case 'd':
            $user = Diak::where(['azonosito' => Auth::user()->azonosito])->first();
            return View('diak.ora',['user'=>$user]);
            break;
        case 's':
            $user = Szulo::where(['azonosito' => Auth::user()->azonosito])->first();
            return View('szulo.ora',['user'=>$user]);
            break;
        case 't':
            $user = Tanar::where(['azonosito' => Auth::user()->azonosito])->first();
            return View('tanar.ora',['user'=>$user]);
            break;
    }
   }
   public function ertekeles()
   {
    $azonositoValaszto = mb_substr(Auth::user()->azonosito, 0, 1);

    switch ($azonositoValaszto) {
        case 'd':
            $ertekelesek=DB::table('ertekeles')->join('diaks', function ($join) {
                $join->on('diaks.azonosito', '=', 'ertekeles.Diak_azonosito')->where('Diak_azonosito', '=', Auth::user()->azonosito);
        })->orderBy('datum','desc')->get();

            return View('diak.ertekeles',['ertekelesek'=>$ertekelesek]);
            break;
        case 's':

            $gyerekek=DB::table('diaks_szulos')->where('Szulo_azonosito', '=', Auth::user()->azonosito)->get();
            return View('szulo.ertekeles',['gyerekek'=>$gyerekek]);
            break;
        case 't':
                $adatok=DB::table('diaks')->select(['diaks.vnev','diaks.knev','diaks.azonosito','tantargies.nev','ertekeles.Tanar_Azonosito','ertekeles.jegy','ertekeles.datum'])
                    ->join('ertekeles', function ($join) {
                    $join->on('diaks.azonosito', '=', 'ertekeles.Diak_azonosito');
                    })
                    ->join('tanars', function ($join) {
                        $join->on('tanars.azonosito', '=', 'ertekeles.Tanar_Azonosito');
                    })
                    ->join('tantargies', function ($join) {
                        $join->on('tantargies.ID', '=', 'ertekeles.Tantargy_ID');
                    })->where([
                        ['ertekeles.Tanar_Azonosito', '=', Auth::user()->azonosito ]
                    ])->get();
                    return View('tanar.ertekeles',['status'=>0,'adatok'=>$adatok]);
            break;
    }
   }

   public function tantargyvalaszt()
   {
        $adatok=DB::table('tanars')->select(['tantargies.nev','tantargies.ID','tanars.azonosito'])
        ->join('tanoras', function ($join) {
            $join->on('tanars.azonosito', '=', 'tanoras.Tanar_azonosito');
        })
        ->join('tantargies', function ($join) {
            $join->on('tantargies.ID', '=', 'tanoras.Tantargy_ID');
        })
        ->join('diaks_tanoras', function ($join) {
            $join->on('tanoras.ID', '=', 'diaks_tanoras.Tanora_ID');
        })->where([
            ['tanoras.Tanar_Azonosito', '=', Auth::user()->azonosito ]
            ])->groupBy('tantargies.ID','tantargies.nev','tanars.azonosito')->get();
            //dd($adatok);
        return View('tanar.ertekeles',['status'=>1,'adatok'=>$adatok]);
    }
    public function diakvalaszt(Request $request)
   {

    $jegyek=DB::table('jegyeks')->select('jegy')->get();

    $adatok=DB::table('diaks')->select(['diaks.vnev','diaks.knev','diaks.azonosito','tantargies.nev','tantargies.ID','tanoras.Tanar_Azonosito'])
    ->join('diaks_tanoras', function ($join) {
        $join->on('diaks.azonosito', '=', 'diaks_tanoras.Diak_azonosito');
    })
    ->join('tanoras', function ($join) {
        $join->on('tanoras.ID', '=', 'diaks_tanoras.Tanora_ID');
    })
    ->join('tantargies', function ($join) {
        $join->on('tantargies.ID', '=', 'tanoras.Tantargy_ID');
    })->where([
        ['tanoras.Tanar_Azonosito', '=', Auth::user()->azonosito ],
        [ 'tantargies.ID','=',request('id')]
        ])->get();
        //dd($adatok);
    return View('tanar.ertekeles',['status'=>2,'adatok'=>$adatok,'jegyek'=>$jegyek]);
    }
    public function tarolas(Request $request)
   {
    $e=new Ertekeles();
    $e->datum=now();
    $e->Tanar_Azonosito=Auth::user()->azonosito;
    $e->Diak_Azonosito=request('azonosito');
    $e->jegy=request('jegy');
    $e->Tantargy_ID=request('id');
    $e->save();
        return redirect('/ertekeles');
   }
}
