<?php

namespace App\Http\Controllers;

use App\Defecto;
use DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class dni extends Controller {

    use \App\Traits\ApiResponser;

    public function dni_data(Request $request) {
        $regla = [
            'dni.required' => 'Ingree su DNI',
            'dni.max' => 'No puede ser mas de 8 digitos'
            ];
        $validator = Validator::make($request->all(), [
                    'dni' => 'required|max:8'
                        ], $regla);
        if ($validator->fails()) {
            $errors = $validator->errors();
            return $this->successResponse($errors);
        } else {
            $consulta = file_get_contents('https://eldni.com/buscar-por-dni?dni=' . $request->dni, false);
            $extract_th = "#<th.*>(.+)</th#Ui";
            $extract_tr = "/<tr>(.*)<\\/tr>/isU";
            $extract_td = "/<td.*>(.*)<\\/td>/Ui";

            preg_match_all($extract_tr, $consulta, $match_tr, PREG_SET_ORDER);

            for ($i = 0; $i < count($match_tr); $i++) {
                for ($td = 0; $td < count($match_tr[$i]); $td++) {

                    preg_match_all($extract_td, $match_tr[$i][$td], $match_td, PREG_SET_ORDER);
                }
            }
            $array_final = Array();
            for ($i = 0; $i < count($match_td); $i++) {
                for ($td = 1; $td < count($match_td[$i]); $td++) {
                    $array_final[]=$match_td[$i][$td];
                    //array_push($array_final, );
                }
            }
            return $this->successResponse($array_final);
        }
    }

}
