<?php

namespace App\Http\Controllers;

use App\AccountStatus;
use App\ClientsServicesCategory;
use App\RequestChangeCategory;
use App\Clients;
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


    public function RequestUpdateCategoriesClient(Request $request){

        $insert = RequestChangeCategory::create([
            "id_client" => $request["id_client"]
        ]);

        db::table("request_chage_category_items")->insert([
            "id_request_chage_category" => $insert->id,
            "id_category"               => 1,
            "value"                     => $request["barber"],
        ]);

        db::table("request_chage_category_items")->insert([
            "id_request_chage_category" => $insert->id,
            "id_category"               => 2,
            "value"                     => $request["trenzas"],
        ]);

        db::table("request_chage_category_items")->insert([
            "id_request_chage_category" => $insert->id,
            "id_category"               => 3,
            "value"                     => $request["pedicure"],
        ]);

        return response()->json("Ok")->setStatusCode(200);
    }



    public function GetChangeCategoriesClient(){
        $data = RequestChangeCategory::SelectRaw("request_chage_category.*, clientes.names, clientes.last_names")
                                    ->join("clientes", "clientes.id", "=", "request_chage_category.id_client")
                                    ->with("items")
                                    ->where("request_chage_category.status", "Pendiente")
                                    ->get();
        return response()->json($data)->setStatusCode(200);
    }




    public function GetCategoriesClient($id_client){
        $data   = DB::table("clientes_services_category")
                    ->join("category", "category.id", "=", "clientes_services_category.id_category")
                    ->where("id_client", $id_client)
                    ->get();

        return response()->json($data)->setStatusCode(200);
    }

    
    public function AprovedChangeCategoriesClient($id){
        
        $request = RequestChangeCategory::where("id", $id)->with("items")->first();

        
      

       RequestChangeCategory::where("id", $id)->update(["status" => "Procesado"]);
        
       ClientsServicesCategory::where("id_client", $request->id_client)->delete();
        foreach($request->items as $value){

           if($value->value == 1){
                ClientsServicesCategory::insert(['id_client' =>  $request->id_client, "id_category" =>  $value->id_category]);
           }
        }

        $token = Clients::where("id", $request->id_client)->pluck('fcmToken')->toArray();


        $ConfigNotification = [
            "tokens" => $token,
            "tittle" => "ServiUf",
            "body"   => "Se aprobaron tus cambios de categorias",
            "data"   => ['type' => 'refferers']
        ];
    
        $code = SendNotifications($ConfigNotification);



        
        
        return response()->json($request->items)->setStatusCode(200);
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
