<?php

namespace App\Http\Controllers;

use App\Calification;
use App\RequestOfferts;
use App\RequestService;
use Illuminate\Http\Request;

class CalificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $offert  = RequestOfferts::where("id_service", $request["id_service"])
                                    ->join("clientes", "clientes.id", "=", "request_offerts.id_client", "left")
                                    ->first();

        $token = [$offert->fcmToken];
        $request["id_service_provider"] = $offert->id_client;
        
        Calification::create($request->all());



        $ConfigNotification = [
            "tokens" => $token,
            "tittle" => "Te han Calificado",
            "body"   => "han calificado tus servicios",
            "data"   => ['type' => 'refferers']
        ];
    
        $code = SendNotifications($ConfigNotification);


        RequestOfferts::where("id_service", $request["id_service"])->update([
            "status" => "Finalizada"
        ]);

        RequestService::where("id", $request["id_service"])->update([
            "status" => "Finalizada"
        ]);






        $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
        return response()->json($data)->setStatusCode(200);
    }



    public function GetRatingServiceProvider($id){
        
        $data = Calification::selectRaw("SUM(rating) as rating, count(id) as count")
                                ->where("id_service_provider", $id)
                                ->first();

        if($data->rating == null){
            return response()->json(0)->setStatusCode(200);
        }else{
            return response()->json($data->rating / $data->count)->setStatusCode(200);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Calification  $calification
     * @return \Illuminate\Http\Response
     */
    public function show(Calification $calification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Calification  $calification
     * @return \Illuminate\Http\Response
     */
    public function edit(Calification $calification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Calification  $calification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Calification $calification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Calification  $calification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Calification $calification)
    {
        //
    }
}
