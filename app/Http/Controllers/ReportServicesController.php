<?php

namespace App\Http\Controllers;

use App\ReportServices;
use Illuminate\Http\Request;
use DB;
class ReportServicesController extends Controller
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
        ReportServices::create($request->all());

        DB::table("request_service")->where("id", $request->id_service)->update([
            "status"    => "Reportado"
        ]);

        DB::table("request_offerts")
                ->where("id_service", $request->id_service)
                ->where("status", "Aprobada")
                ->update([
                    "status"    => "Reportado"
                ]);


        $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
        return response()->json($data)->setStatusCode(200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\ReportServices  $reportServices
     * @return \Illuminate\Http\Response
     */
    public function show($reportServices)
    {
        $data = ReportServices::join("clientes", "clientes.id", "=", "reports_services.id_client")
                                ->where("id_service", $reportServices)
                                ->first();
        return response()->json($data)->setStatusCode(200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\ReportServices  $reportServices
     * @return \Illuminate\Http\Response
     */
    public function edit(ReportServices $reportServices)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\ReportServices  $reportServices
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ReportServices $reportServices)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\ReportServices  $reportServices
     * @return \Illuminate\Http\Response
     */
    public function destroy(ReportServices $reportServices)
    {
        //
    }
}
