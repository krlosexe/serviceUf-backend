<?php

namespace App\Http\Controllers;

use App\RequestService;
use App\RequestOfferts;

use Illuminate\Http\Request;

class RequestServiceController extends Controller
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
    public function RequestByClient($id_client)
    {
        $data = RequestService::selectRaw("request_service.*, category.name as name_category")
                                ->where("id_client", $id_client)
                                ->where("status", "Pendiente")
                                ->join("category", "category.id", "=", "request_service.id_category")
                                ->first();
        return response()->json($data)->setStatusCode(200);
    }


    public function RequestOffertsByService($id_service){

        $data = RequestOfferts::selectRaw("request_offerts.*, clientes.names as name_client, clientes.last_names as last_name_client, clientes.photo_profile")
                                ->join("request_service", "request_service.id", "=", "request_offerts.id_service")
                                ->join("clientes", "clientes.id", "=", "request_offerts.id_client")
                                ->where("id_service", $id_service)
                                ->get();
        return response()->json($data)->setStatusCode(200);
    }
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $folder = "img/request_services";

        $img      = str_replace('data:image/png;base64,', '', $request["photo"]);
        $fileData = base64_decode($img);
        $fileName = rand(0, 100000000) . '-photo.png';
        file_put_contents($folder . "/" . $fileName, $fileData);

        $request["photo"] = $fileName;

        RequestService::create($request->all());
        $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
        return response()->json($data)->setStatusCode(200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\RequestService  $requestService
     * @return \Illuminate\Http\Response
     */
    public function show(RequestService $requestService)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\RequestService  $requestService
     * @return \Illuminate\Http\Response
     */
    public function edit(RequestService $requestService)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\RequestService  $requestService
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RequestService $requestService)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\RequestService  $requestService
     * @return \Illuminate\Http\Response
     */
    public function destroy(RequestService $requestService)
    {
        //
    }
}
