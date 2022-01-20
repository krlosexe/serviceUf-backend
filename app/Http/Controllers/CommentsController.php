<?php

namespace App\Http\Controllers;

use App\Comments;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function store(Request $request, $tabla){


        $array = [];
        $array["id_event"]   = $request["id"];
        $array["table"]      = $tabla;
        $array["id_user"]    = $request["id_user"];
        $array["comment"]    = $request["comment"];
        Comments::insert($array);


        return response()->json("Ok")->setStatusCode(200);

    }

    public function get($table, $id){
        
        $data = Comments::select('comments.*', 'users.email', 'users.img_profile', "datos_personales.nombres as name_user", "datos_personales.apellido_p as last_name_user")
                            ->where("id_event", $id)
                            ->join('users', 'users.id', '=', 'comments.id_user')  
                            ->join('datos_personales', 'datos_personales.id_usuario', '=', 'comments.id_user')     
                            ->where("table", $table)
                            ->get();

        return response()->json($data)->setStatusCode(200);
    }
}
