<?php

namespace App\Http\Controllers;

use App\AccountStatus;
use Illuminate\Http\Request;
use DB;
class AccountStatusController extends Controller
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


    public function StoreCredit(Request $request){
        DB::table("account_status")->insert($request->all());
        $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
        return response()->json($request->all())->setStatusCode(200);
    }


    public function GetBalanceClient($id_client){
        $payment   = DB::table("account_status")
                    ->selectRaw("SUM(payment) as amount")
                    ->where("id_client", $id_client)
                    ->first();


        $discharges = DB::table("account_status")
                    ->selectRaw("SUM(discharge) as amount")
                    ->where("id_client", $id_client)
                    ->first();

        return response()->json(["balance" => $payment->amount - $discharges->amount])->setStatusCode(200);
    }

    public function GetAccountStatusClient($id_client){
        $data   = DB::table("account_status")
                    ->where("id_client", $id_client)
                    ->orderBy("id", "desc")
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AccountStatus  $accountStatus
     * @return \Illuminate\Http\Response
     */
    public function show(AccountStatus $accountStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AccountStatus  $accountStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(AccountStatus $accountStatus)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AccountStatus  $accountStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AccountStatus $accountStatus)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AccountStatus  $accountStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccountStatus $accountStatus)
    {
        //
    }
}
