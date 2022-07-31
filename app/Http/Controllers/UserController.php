<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class UserController extends Controller
{
    public function index(){
        
        
        $user_role = auth()->user()->role;

        if($user_role == 0){
            $users = User::where('status',1)->with('ciudad')->orderBy('id');
        }else{
            $users = User::where('id',auth()->user()->id)->with('ciudad')->orderBy('id');
        }
        return DataTables::of($users)->addColumn('ciudad', function (User $user) {
            return $user->ciudad->ciudad;
        })->toJson();
    }

    public function show($id){
        
        DB::beginTransaction();
        try {

            $user = User::where('id', $id)->where('status', 1)->with('ciudad')->first();
            

            DB::commit();
        } catch (\Exception $e) {

            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }

        $response = array(
            "data" => $user
        );

        return response()->json($response, 200);
    }

    public function update(Request $request, $id){
        
        DB::beginTransaction();
        try {

            $user = User::where('id', $id)->first();
            $user->name = $request["name"];
            $user->telefono = $request["telefono"];
            $user->id_ciudad = $request["ciudad"];
            $user->fecha_nacimiento = date('Y-m-d H:i:s', strtotime($request['fecha_nacimiento']));
            $user->update();            

            DB::commit();
        } catch (\Exception $e) {

            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }

        $response = array(
            "data" => $user
        );

        return response()->json($response, 200);
    }

    public function destroy(Request $request, $id){
        
        DB::beginTransaction();
        try {

            $user = User::where('id', $id)->first();
            $user->status = 0;
            $user->update();            

            DB::commit();
        } catch (\Exception $e) {

            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }

        $response = array(
            "data" => $user
        );

        return response()->json($response, 200);
    }
    
}
