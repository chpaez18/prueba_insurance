<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CiudadesController extends Controller
{
    public function getCitiesByCountry($id)
    {
        try
		{                             
            $cities = DB::table(DB::raw('ciudades a'))
            ->where('a.codigo_pais','=',$id)
            ->get();
		}
		catch(\Exception $e)
		{
            return response()->json(['error' => $e->getMessage()], 500);
        }
        //return DataTables::queryBuilder($zones)->toJson();
        $response = array(
            "data" => $cities
        );

        return response()->json($response, 200);
    }
}
