<?php

namespace App\Http\Controllers;

use App\RequestService;
use App\RequestOfferts;
use App\ClientsServicesCategory;
use App\Clients;
use DB;
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
        $offerts = RequestOfferts::where("id_service", $data->id)->where("status", "Pendiente")->get();

        $data->count_offerts = sizeof($offerts);
       
        return response()->json($data)->setStatusCode(200);
    }

    public function RequestsByClient($id_client)
    {
        $data = RequestService::selectRaw("request_service.*, request_service.photo as photo_service, category.name as name_category, 
                                            clientes.names as name_client, clientes.photo_profile, 
                                            clientes.last_names as last_name_client, request_offerts.time, request_offerts.price,
                                            rating.rating, rating.comments as comment_rating"
                                        )
                                ->join("category", "category.id", "=", "request_service.id_category")
                                ->join("request_offerts", "request_offerts.id", "=", "request_service.id_offert", "left")
                                ->join("clientes", "clientes.id", "=", "request_offerts.id_client", "left")
                                ->join("rating", "rating.id_service", "=", "request_service.id", "left")
                                ->where("request_service.id_client", $id_client)
                                ->orderBy("id", "desc")
                                ->get();
       
        return response()->json($data)->setStatusCode(200);
    }
    public function RequestOffertsByService($id_service){

        $data = RequestOfferts::selectRaw("request_offerts.*, clientes.names as name_client, 
                                          clientes.last_names as last_name_client, 
                                          clientes.photo_profile, SUM(rating.rating) as total_rating,  COUNT(rating.id) count_rating")
                                ->join("request_service", "request_service.id", "=", "request_offerts.id_service")
                                ->join("clientes", "clientes.id", "=", "request_offerts.id_client")
                                ->join("rating", "rating.id_service_provider", "=", "request_offerts.id_client", "left")
                                ->where("request_offerts.id_service", $id_service)
                                ->where("request_offerts.status", "Pendiente")
                                ->get();

       

        if($data[0]->id == null){
            $data = [];
        }else{
            $data->map(function($item){
                $item->offerts_ready = sizeof(RequestOfferts::where('id_client',$item->id_client)->where("status", "Finalizada")->get());
                
                return $item;
            });
        }
        
        
        return response()->json($data)->setStatusCode(200);
    }



    public function RequestOffertsByClient($id_client){
        $data = RequestOfferts::selectRaw("request_offerts.*, category.name as name_category, clientes.names as name_client, clientes.last_names as last_name_client, clientes.photo_profile, request_service.address, request_service.date, request_service.phone, request_service.latitude, request_service.longitude, request_service.type, request_service.photo, rating.rating, rating.comments as comment_rating")
                                ->join("request_service", "request_service.id", "=", "request_offerts.id_service")
                                ->join("category", "category.id", "=", "request_service.id_category")
                                ->join("clientes", "clientes.id", "=", "request_service.id_client")
                                ->join("rating", "rating.id_service", "=", "request_offerts.id_service", "left")
                                ->where("request_offerts.id_client", $id_client)
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
        $folder = "img/request_services";

        $img      = str_replace('data:image/png;base64,', '', $request["photo"]);
        $fileData = base64_decode($img);
        $fileName = rand(0, 100000000) . '-photo.png';
        file_put_contents($folder . "/" . $fileName, $fileData);

        $request["photo"] = $fileName;

        RequestService::create($request->all());


        $services_providers_tokens = Clients::join("clientes_services_category", "clientes_services_category.id_client", "=", "clientes.id")
                            ->where("clientes_services_category.id_category", $request["id_category"])->pluck('fcmToken')->toArray();


        $ConfigNotification = [
            "tokens" => $services_providers_tokens,
            "tittle" => "ServiUf",
            "body"   => "Nuevo servicio",
            "data"   => ['type' => 'refferers']
        ];
    
        $code = SendNotifications($ConfigNotification);


        $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
        return response()->json($data)->setStatusCode(200);
    }



    public function StoreOffert(Request $request){


        $commission = $request["price"] * 0.20;
        $balance = $this->GetBalanceClient($request["id_provider"]);                
        if($balance - $commission < 1){
            $data = array('mensagge' => "No tienes saldo sufuciente, te invitamos a recargar");
            return response()->json($data)->setStatusCode(400);
        }


        RequestOfferts::create([
            "id_client"  => $request["id_provider"],
            "id_service" => $request["id_service"],
            "price"      => $request["price"],
            "time"       => $request["time"],
            "comments"   => $request["comments"],
        ]);


        $service = RequestService::join("clientes", "clientes.id", "=", "request_service.id_client")
                                    ->where("request_service.id", $request["id_service"])
                                    ->first();


        $ConfigNotification = [
            "tokens" => [$service->fcmToken],
            "tittle" => "ServiUf",
            "body"   => "Nueva oferta",
            "data"   => ['type' => 'refferers']
        ];
    
        $code = SendNotifications($ConfigNotification);

        $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
        return response()->json($data)->setStatusCode(200);
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

        return $payment->amount - $discharges->amount;
    }




    public function ProcessService($id_service){

     
        $service = RequestService::where("id", $id_service)->update([
            "status"    => "Pendiente por calificar"
        ]);

        $offert  = RequestOfferts::where("request_offerts.id_service", $id_service)->update([
            "status" => "Pendiente por calificar"
        ]);


        $service = RequestService::join("clientes", "clientes.id", "=", "request_service.id_client")
                                    ->where("request_service.id", $id_service)
                                    ->first();


        $ConfigNotification = [
            "tokens" => [$service->fcmToken],
            "tittle" => "ServcieUf: Calificar servicio",
            "body"   => "Servicio finalizado, te invitamos a Calificar el servicio",
            "data"   => ['type' => 'refferers']
        ];
    
        $code = SendNotifications($ConfigNotification);
        $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
        return response()->json($data)->setStatusCode(200);

    }

    public function CancelService($id_service){
     
        $service = RequestService::where("id", $id_service)->update([
            "status"    => "Cancelado"
        ]);

        $offert  = RequestOfferts::where("request_offerts.id_service", $id_service)->update([
            "status" => "Cancelado"
        ]);

        $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
        return response()->json($data)->setStatusCode(200);

    }


    public function RefuseOffert($id_offert){
        $offert  = RequestOfferts::where("request_offerts.id", $id_offert)->update([
            "status" => "Rechazada"
        ]);

        $offert = RequestOfferts::join("clientes", "clientes.id", "=", "request_offerts.id_client")
                                        ->where("request_offerts.id", $id_offert)
                                        ->first();

        $ConfigNotification = [
            "tokens" => [$offert->fcmToken],
            "tittle" => "ServiUf: Informacion sobre tu oferta",
            "body"   => "Cliente rechaza la oferta",
            "data"   => ['type' => 'refferers']
        ];
    
        $code = SendNotifications($ConfigNotification);

        $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
        return response()->json($data)->setStatusCode(200);


    }
    



    public function AcceptOffert(Request $request){

        
        $service = RequestService::where("id", $request["id_service"])->update([
            "status"    => "En proceso",
            "id_offert" => $request["id_offert"]
        ]);

        $offert  = RequestOfferts::where("request_offerts.id", $request["id_offert"])->update([
            "status" => "Aprobada"
        ]);


        $offert  = RequestOfferts::join("clientes", "clientes.id", "=", "request_offerts.id_client")
                                    ->where("request_offerts.id", $request["id_offert"])->first();

        $commission = $offert->price * 0.20;
        DB::table("account_status")->insert([
            "id_client"  => $offert->id_client,
            "discharge"  => $commission,
            "id_service" => $request["id_service"]
        ]);

        $ConfigNotification = [
            "tokens" => [$offert["fcmToken"]],
            "tittle" => "ServcieUf: Cliente acepta la oferta",
            "body"   => "Oferta aceptada",
            "data"   => ['type' => 'refferers']
        ];
    
        $code = SendNotifications($ConfigNotification);
                            
       
        $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
        return response()->json($data)->setStatusCode(200);
    }


    public function GetRequestServicesForProvider($id_client){
       $categories_service_provider = ClientsServicesCategory::where("id_client", $id_client)->pluck('id_category')->toArray();

       $services = RequestService::selectRaw("request_service.*, clientes.names as name_client, clientes.last_names as last_name_client, clientes.photo_profile, category.name as name_category")
                                            ->join("clientes", "clientes.id", "=", "request_service.id_client")
                                            ->join("category", "category.id", "=", "request_service.id_category")
                                            ->whereIn("id_category", $categories_service_provider)
                                            ->where("request_service.status", "Pendiente")
                                            ->orderBy("request_service.id", "desc")
                                            ->get();

       return response()->json($services)->setStatusCode(200);
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
