<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmailController extends Controller
{
    public function index(){
        
        $id_user = auth()->user()->id;
        $emails = Email::where('id_user',$id_user)->orderBy('id');
        return DataTables::of($emails)->toJson();
    }

    public function store(Request $request){
        
        DB::beginTransaction();
        try {
            
            $email = new Email();
            $email->asunto = $request["asunto"];
            $email->destinatario = $request["destinatario"];
            $email->mensaje = $request["mensaje"];
            $email->id_user = auth()->user()->id;
            $email->save();            

            DB::commit();
        } catch (\Exception $e) {

            DB::rollback();
            return response()->json(['error' => $e->getMessage()], 500);
        }

        $response = array(
            "data" => $email
        );

        return response()->json($response, 200);
    }
}
