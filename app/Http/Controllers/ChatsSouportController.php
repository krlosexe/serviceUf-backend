<?php

namespace App\Http\Controllers;

use App\ChatsSouport;
use App\Clients;

use Illuminate\Http\Request;

class ChatsSouportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
 
    }


    public function GetChatByClient($id_service)
    {
        $data = ChatsSouport::where("id_service", $id_service)->get();
        return response()->json($data)->setStatusCode(200);
    }

    


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ChatsSouport::create($request->all());

        $token = Clients::where("id", $request["receiver"])->first();
        $sender = Clients::where("id", $request["sender"])->first();

        $ConfigNotification = [
            "tokens" => [$token->fcmToken],
            "tittle" => "Chat del servicio: ". $sender->names." ". $sender->last_names ,
            "body"   => $request["message"],
            "data"   => ['type' => 'chat']
        ];
    
        $code = SendNotifications($ConfigNotification);


        $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
        return response()->json($data)->setStatusCode(200);
    }

    public function StoreSouport(Request $request){
        ChatsSouport::create($request->all());


        $token = Clients::where("id", $request["receiver"])->first();

        $ConfigNotification = [
            "tokens" => [$token->fcmToken],
            "tittle" => "Soporte en Linea",
            "body"   => "Has recibido un nuevo mensaje",
            "data"   => ['type' => 'refferers']
        ];
    
        $code = SendNotifications($ConfigNotification);


        $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
        return response()->json($data)->setStatusCode(200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ChatsSouport  $chatsSouport
     * @return \Illuminate\Http\Response
     */
    public function show(ChatsSouport $chatsSouport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ChatsSouport  $chatsSouport
     * @return \Illuminate\Http\Response
     */
    public function edit(ChatsSouport $chatsSouport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ChatsSouport  $chatsSouport
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ChatsSouport $chatsSouport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ChatsSouport  $chatsSouport
     * @return \Illuminate\Http\Response
     */
    public function destroy(ChatsSouport $chatsSouport)
    {
        //
    }
}
