<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;


class mailout extends Controller
{

    public function SendEmail(Request $request){

        $request["msg"]  = "Mensaje: ".$request["comentario"]."<br>". $request["nombre"]."<br> Email: ". $request["email"];
        $subject         = "Formulario Blue: ".$request["nombre"];


        Mail::send('emails.notification',$request->all(), function($msj) use($subject){
            $msj->from("crm@pdtagencia.com","CRM");
            $msj->subject($subject);
            $msj->to("blueasesora@gmail.com");
        });



        Mail::send('emails.notification',$request->all(), function($msj) use($subject){
            $msj->from("crm@pdtagencia.com","CRM");
            $msj->subject($subject);
            $msj->to("cardenascarlos18@gmail.com");
        });



        $data = array('mensagge' => "Los datos fueron actualizados satisfactoriamente");
        return response()->json($data)->setStatusCode(200);

    }




    public function SendEmailBlue(Request $request){

        $request["msg"]  = "Nombre: ".$request["nombre"].", Fecha de Nacimiento". $request["fechanac"].", Cedula: ". $request["numerocedula"].", numceltel: ".$request["numceltel"]. ", email: ".$request["email"].", ocupacion: ".$request["ocupacion"].", Cirugia: ".$request["cirugia"].", Fecha Operacion: ".$request["fechaope"].", Couta Inicial: ".$request["cuotainicial"]." Ciudad: .".$request["ciudad"].", Como nos conocio?: ".$request["formaconocio"];
        $subject         = "Formulario Blue: ".$request["nombre"];


        Mail::send('emails.notification',$request->all(), function($msj) use($subject){
            $msj->from("crm@pdtagencia.com","CRM");
            $msj->subject($subject);
            $msj->to("blueasesora@gmail.com");
        });



        Mail::send('emails.notification',$request->all(), function($msj) use($subject){
            $msj->from("crm@pdtagencia.com","CRM");
            $msj->subject($subject);
            $msj->to("cardenascarlos18@gmail.com");
        });



        $data = array('mensagge' => "Los datos fueron actualizados satisfactoriamente");
        return response()->json($data)->setStatusCode(200);

    }



}
