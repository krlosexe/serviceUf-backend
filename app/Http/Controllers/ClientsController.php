<?php

namespace App\Http\Controllers;

use Mail;
use App\AuthUsersApp;
use App\AuthUserAppFinancing;
use App\Clients;
use App\Auditoria;
use App\ClientClinicHistory;
use App\ClientCreditInformation;
use App\ClientInformationAditionalSurgery;
use App\ClientsProcedure;
use App\Surgeries;

use App\ClientsTasks;
use App\ClientsTasksFollowers;
use App\ClientsTasksComments;
use App\User;
use App\datosPersonaesModel;
use App\Comments;
use App\Notification;
use App\Valuations;
use App\GalleryImage;
use App\FollwersEvents;
use App\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\LogsClients;

use App\Exports\ClientsExport;
use Maatwebsite\Excel\Facades\Excel;

use Orchestra\Parser\Xml\Facade as XmlParser;

use DB;
use DateTime;
use PhpParser\Node\Stmt\TryCatch;


use App\RevisionAppointment;
use App\AppointmentsAgenda;



class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $rol     = $request["rol"];
        $id_user = $request["id_user"];
            return response()->json([])->setStatusCode(200);
        
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      
        $valid = Clients::where("email", $request["email"])->get();
    
        if(sizeof($valid) > 0){
            return response()->json("El usuario se encuentra registrado en la base de datos")->setStatusCode(400);
        }

        $request["password"] = md5($request["password"]);



        $folder = "img/usuarios/profile";

        $img      = str_replace('data:image/png;base64,', '', $request["photoProfile"]);
        $fileData = base64_decode($img);
        $fileName = rand(0, 100000000) . '-profile.png';
        file_put_contents($folder . "/" . $fileName, $fileData);
        $request["photo_profile"] = $fileName;

        $cliente  = Clients::create($request->all());

        if ($cliente) {
            $cliente  = Clients::where("id", $cliente->id)->first();
            return response()->json($cliente)->setStatusCode(200);
        }else{
            return response()->json("A ocurrido un error")->setStatusCode(400);
        }

    }



    public function SendEmail($data){

        $user = User::find($data["user_id"]);
        $subject = $data["issue"];

        //$for = "cardenascarlos18@gmail.com";
        $for = $user["email"];

        $request["msg"] = $data["mensage"];

        Mail::send('emails.notification',$request, function($msj) use($subject,$for){
            $msj->from("crm@pdtagencia.com","CRM");
            $msj->subject($subject);
            $msj->to($for);
        });

        return true;

    }



    /**
     * Display the specified resource.
     *
     * @param  \App\Clients  $clients
     * @return \Illuminate\Http\Response
     */
    public function show($clients)
    {
        $data = Clients::where("clientes.id", $clients) ->first();
        return response()->json($data)->setStatusCode(200);
    }










    public function ShowByCode($code)
    {
        $data = Clients::select("clientes.*", "users.id as user_id")

                            ->join("users", "users.id_client", "=", "clientes.id_cliente")

                            ->where("clientes.code_client", $code)

                            ->first();

        if($data){
            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json([])->setStatusCode(204);
        }



    }






    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Clients  $clients
     * @return \Illuminate\Http\Response
     */
    public function edit(Clients $clients)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Clients  $clients
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id_client)
    {
        $cliente = Clients::where("id", $id_client)->update($request->all());

        if ($cliente) {
            $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("A ocurrido un error")->setStatusCode(400);
        }

    }   


    public function UpdatePhotoProfile(Request $request, $id_client){

        $folder = "img/usuarios/profile";

        $img      = str_replace('data:image/png;base64,', '', $request["photo"]);
        $fileData = base64_decode($img);
        $fileName = rand(0, 100000000) . '-profile.png';
        file_put_contents($folder . "/" . $fileName, $fileData);
        $request["photo"] = $fileName;

        $cliente = Clients::where("id", $id_client)->update(["photo_profile" => $request["photo"]]);

        $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
        return response()->json($request["photo"])->setStatusCode(200);


    }


    public function PostulatedServiceProvider(Request $request){

        if($request["barber"] == true){
            DB::table("clientes_services_category")->insert([
                "id_client"   => $request["id_client"],
                "id_category" => 1
            ]);
        }


        if($request["trenzado"] == true){
            DB::table("clientes_services_category")->insert([
                "id_client"   => $request["id_client"],
                "id_category" => 2
            ]);
        }


        if($request["pedicure"] == true){
            DB::table("clientes_services_category")->insert([
                "id_client"   => $request["id_client"],
                "id_category" => 3
            ]);
        }

        $request["service_provider"] = "Reviewing";
        $cliente = Clients::where("id", $request["id_client"])->update([
            "names"               => $request["names"],
            "last_names"          => $request["last_names"],
            "email"               => $request["email"],
            "identification"      => $request["identification"],
            "type_identification" => $request["type_identification"],
            "phone"               => $request["phone"],
            "municipality"        => $request["municipality"],
            "service_provider"    => $request["service_provider"]
        ]);


        $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
        return response()->json($data)->setStatusCode(200);

    }


    public function GetStatusServiceProvider($id_client){
        $data = Clients::where("id", $id_client)->first();
        return response()->json($data)->setStatusCode(200);
    }   



    public function UpdateStatusServiceProvider($id_client, $status){
        $data = Clients::where("id", $id_client)->update([
            "service_provider_status" => $status
        ]);
        return response()->json($data)->setStatusCode(200);
    }







    public function status($id_cliente, $status, Request $request)
    {
        //if ($this->VerifyLogin($request["id_user"],$request["token"])){
            $auditoria =  Auditoria::where("cod_reg", $id_cliente)
                                     ->where("tabla", "clientes")->first();

            $auditoria->status = $status;

            if($status == 0){
                $auditoria->usr_regmod = $request["id_user"];
                $auditoria->fec_regmod = date("Y-m-d");
                User::where('id_client',$id_cliente)->delete();

            }
            $auditoria->save();

            $data = array('mensagge' => "Los datos fueron actualizados satisfactoriamente");
            return response()->json($data)->setStatusCode(200);
      //  }else{
           //return response()->json("No esta autorizado")->setStatusCode(400);
        //}
    }




    public function Tasks(Request $request){

        if ($this->VerifyLogin($request["id_user"],$request["token"])){
          //  $request["responsable"] = $request["id_user"];
            $store = ClientsTasks::create($request->all());


            $auditoria              = new Auditoria;
            $auditoria->tabla       = "clients_tasks";
            $auditoria->cod_reg     = $store["id_clients_tasks"];
            $auditoria->status      = 1;
            $auditoria->fec_regins  = date("Y-m-d H:i:s");
            $auditoria->usr_regins  = $request["id_user"];
            $auditoria->save();



            $state_px = $request["state_px"];

            if($state_px != "0"){
                $data_client = Clients::select("state")->find($request["id_client"]);

                DB::table("clientes")->where("id_cliente", $request["id_client"])->update(["state" => $state_px]);

                if($data_client->state != $state_px){

                    $version["id_user"]   = $request["id_user"];
                    $version["id_client"] = $request["id_client"];
                    $version["event"]     = "Actualizo el estado de: ".$data_client->state." a ".$request['state_px'];

                    LogsClients::create($version);
                }
            }





            $followers = [];
            foreach($request->followers as $key => $value){
                $array = [];
                $array["id_task"]     = $store["id_clients_tasks"];
                $array["id_follower"] = $value;
                array_push($followers, $array);
            }

            ClientsTasksFollowers::insert($followers);
            $request["id_task"] = $store["id_clients_tasks"];

            if($request->comments != "<p><br></p>"){
                ClientsTasksComments::create($request->all());
            }


            $data = array('mensagge' => "Los datos fueron actualizados satisfactoriamente");
            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("No esta autorizado")->setStatusCode(400);
        }

    }


    public function TasksUpdate(Request $request, $id_task){



        if ($this->VerifyLogin($request["id_user"],$request["token"])){

            $update = ClientsTasks::find($id_task)->update($request->all());

            ClientsTasksFollowers::where("id_task", $id_task)->delete();


            if(isset($request["state_px"])){

                $state_px = $request["state_px"];

                if($state_px != "0"){
                    $data_client = Clients::select("state")->find($request["id_cliente"]);

                    DB::table("clientes")->where("id_cliente", $request["id_cliente"])->update(["state" => $state_px]);

                    if($data_client->state != $state_px){

                        $version["id_user"]   = $request["id_user"];
                        $version["id_client"] = $request["id_cliente"];
                        $version["event"]     = "Actualizo el estado de: ".$data_client->state." a ".$request['state_px'];

                        LogsClients::create($version);
                    }
                }
            }





            if(isset($request->followers)){
                $followers = [];
                foreach($request->followers as $key => $value){
                    $array = [];
                    $array["id_task"]     = $id_task;
                    $array["id_follower"] = $value;
                    array_push($followers, $array);
                }

                ClientsTasksFollowers::insert($followers);
            }

            if($request->comments != "<p><br></p>"){
                $array = [];
                $array["id_task"]     = $id_task;
                $array["id_user"] = $request["id_user"];
                $array["comments"] = $request->comments;

                ClientsTasksComments::insert($array);

            }


            if ($update) {
                $data = array('mensagge' => "Los datos fueron actualizados satisfactoriamente");
                return response()->json($data)->setStatusCode(200);
            }else{
                return response()->json("A ocurrido un error")->setStatusCode(400);
            }

        }else{
            return response()->json("No esta autorizado")->setStatusCode(400);
        }




    }





    public function TasksUpdateCalendar(Request $request, $id_task){



        if ($this->VerifyLogin($request["id_user"],$request["token"])){

            $update = ClientsTasks::find($id_task)->update($request->all());


            if(isset($request["state_px"])){

                $state_px = $request["state_px"];

                if($state_px != "0"){
                    $data_client = Clients::select("state")->find($request["id_cliente"]);

                    DB::table("clientes")->where("id_cliente", $request["id_cliente"])->update(["state" => $state_px]);

                    if($data_client->state != $state_px){

                        $version["id_user"]   = $request["id_user"];
                        $version["id_client"] = $request["id_cliente"];
                        $version["event"]     = "Actualizo el estado de: ".$data_client->state." a ".$request['state_px'];

                        LogsClients::create($version);
                    }
                }
            }


            if($request->comments != "<p><br></p>"){
                $array = [];
                $array["id_task"]     = $id_task;
                $array["id_user"] = $request["id_user"];
                $array["comments"] = $request->comments;

               // ClientsTasksComments::insert($array);

            }


            if ($update) {
                $data = array('mensagge' => "Los datos fueron actualizados satisfactoriamente");
                return response()->json($data)->setStatusCode(200);
            }else{
                return response()->json("A ocurrido un error")->setStatusCode(400);
            }

        }else{
            return response()->json("No esta autorizado")->setStatusCode(400);
        }




    }





    public function GetTasksByClient(Request $request, $id_client){
        if ($this->VerifyLogin($request["id_user"],$request["token"])){
            ini_set('memory_limit', '-1');


            $tasks = ClientsTasks::select("clients_tasks.*", "responsable.email as email_responsable", "datos_personales.nombres as name_responsable",
                                   "datos_personales.apellido_p as last_name_responsable", "auditoria.*", "users.email as email_regis", "clientes.nombres as name_client")

                                    ->join("auditoria", "auditoria.cod_reg", "=", "clients_tasks.id_clients_tasks","left")
                                    ->join("users", "users.id", "=", "auditoria.usr_regins","left")

                                    ->join("users as responsable", "responsable.id", "=", "clients_tasks.responsable","left")

                                    ->join("clientes", "clientes.id_cliente", "=", "clients_tasks.id_client","left")


                                    ->join("datos_personales", "datos_personales.id_usuario", "=", "responsable.id","left")

                                    ->with("followers")
                                   // ->with("comments")

                                    ->where("clients_tasks.id_client", $id_client)

                                    ->where("auditoria.tabla", "clients_tasks")
                                    ->where("auditoria.status", "!=", "0")
                                    ->orderBy("clients_tasks.id_clients_tasks", "DESC")
                                    ->get();


                echo json_encode($tasks);
        }else{
            return response()->json("No esta autorizado")->setStatusCode(400);
        }
    }



    public function ClientsComment(Request $request,$id_clients_tasks){
        ini_set('memory_limit', '-1');

        $comments= DB::table("clients_tasks_comments")
                                ->SELECT ("clients_tasks_comments.*","users.img_profile","datos_personales.nombres as name_user","datos_personales.apellido_p as last_name_user")
                                ->join("users","users.id","=","clients_tasks_comments.id_user")
                                ->join('datos_personales', 'datos_personales.id_usuario', '=', 'clients_tasks_comments.id_user')
                                ->where("clients_tasks_comments.id_task", $id_clients_tasks)
                                ->get();
                                echo json_encode($comments);
    }


    public function GetTasks(Request $request){
        if ($this->VerifyLogin($request["id_user"],$request["token"])){


            $rol     = $request["rol"];
            $id_user = $request["id_user"];


            $adviser = 0;
            if(isset($request["adviser"])){
              $adviser = $request["adviser"];
            }


            $overdue = "all";
            if(isset($request["overdue"])){
              $overdue = $request["overdue"];
            }





            $date_init = 0;
            if(isset($request["date_init"]) && $request["date_init"] != ""){
                $date_init = $request["date_init"];
            }


            $date_finish = 0;
            if(isset($request["date_finish"]) && $request["date_finish"] != ""){
                $date_finish = $request["date_finish"];
            }




            ini_set('memory_limit', '-1');

            $tasks = ClientsTasks::select("clients_tasks.*", "responsable.email as email_responsable", "datos_personales.nombres as name_responsable",
                                   "datos_personales.apellido_p as last_name_responsable", "auditoria.*", "users.email as email_regis", "clientes.nombres as name_client",
                                   "asesor.nombres as name_asesora", "asesor.apellido_p as last_name_asesora")

                                    ->join("auditoria", "auditoria.cod_reg", "=", "clients_tasks.id_clients_tasks")
                                    ->join("users", "users.id", "=", "auditoria.usr_regins")

                                    ->join("clientes", "clientes.id_cliente", "=", "clients_tasks.id_client")


                                    ->join("users as responsable", "responsable.id", "=", "clients_tasks.responsable")
                                    ->join("datos_personales", "datos_personales.id_usuario", "=", "responsable.id")

                                    ->join("users as asesora", "asesora.id", "=", "clientes.id_user_asesora")
                                    ->join("datos_personales as asesor", "asesor.id_usuario", "=", "asesora.id")

                                    ->join("clients_tasks_followers", "clients_tasks_followers.id_task", "=", "clients_tasks.id_clients_tasks")
                                    ->with("followers")
                                   // ->with("comments")


                                    ->where(function ($query) use ($rol, $id_user) {
                                        if($rol == "Asesor"){
                                           // $query->where("clients_tasks.responsable", $id_user);
                                        }
                                    })


                                    ->where(function ($query) use ($overdue) {
                                        if($overdue == "overdue"){
                                            $query->where("clients_tasks.fecha", "<" ,date("Y-m-d"));
                                            $query->where("clients_tasks.status_task", "!=" ,"Finalizada");
                                            $query->where("clients_tasks.status_task" ,"Abierta");
                                        }

                                        if($overdue == "Abierta"){
                                            $query->where("clients_tasks.status_task" ,"Abierta");
                                        }
                                    })


                                    ->where(function ($query) use ($adviser) {
                                        if($adviser != 0){
                                            $query->whereIn("clients_tasks.responsable", $adviser);
                                            $query->orWhere("clients_tasks_followers.id_follower", $adviser);
                                        }
                                    })



                                    ->where(function ($query) use ($date_init) {
                                        if($date_init != 0){
                                            $query->where("clients_tasks.fecha", ">=", $date_init);
                                        }
                                    })

                                    ->where(function ($query) use ($date_finish) {
                                        if($date_finish != 0){
                                            $query->where("clients_tasks.fecha", "<=", $date_finish);
                                        }
                                    })





                                    ->where("auditoria.tabla", "clients_tasks")
                                    ->where("auditoria.status", "!=", "0")
                                    ->orderBy("clients_tasks.fecha", "ASC")
                                    ->where("clients_tasks.status_task", "Abierta")
                                    ->whereOr("clients_tasks.status_task", "En progreso")
                                    ->groupBy("clients_tasks.id_clients_tasks")
                                    ->get();


                echo json_encode($tasks);
        }else{
            return response()->json("No esta autorizado")->setStatusCode(400);
        }
    }




    public function GetCommentsTasks($id){
        ini_set('memory_limit', '-1');
        $data = DB::table("clients_tasks_comments")->select('clients_tasks_comments.*', 'users.email', 'users.img_profile', "datos_personales.nombres as name_user",
                                                            "datos_personales.apellido_p as last_name_user")

                    ->join('users', 'users.id', '=', 'clients_tasks_comments.id_user')
                    ->join('datos_personales', 'datos_personales.id_usuario', '=', 'clients_tasks_comments.id_user')
                    ->where("id_task", $id)
                    ->get();


        return response()->json($data)->setStatusCode(200);
    }


    public function TasksStatus($id_cliente, $status, Request $request)
    {

        $auditoria =  Auditoria::where("cod_reg", $id_cliente)
                                    ->where("tabla", "clients_tasks")->first();

        $auditoria->status = $status;

        if($status == 0){
            $auditoria->usr_regmod = $request["id_user"];
            $auditoria->fec_regmod = date("Y-m-d");
        }
        $auditoria->save();

        $data = array('mensagge' => "Los datos fueron actualizados satisfactoriamente");
        return response()->json($data)->setStatusCode(200);

    }


    public function AddCommentTask(Request $request){


        $array["id_task"]  = $request["id"];
        $array["id_user"]  = $request["id_user"];
        $array["comments"] = $request["comment"];
        ClientsTasksComments::insert($array);

        return response()->json("Ok")->setStatusCode(200);

    }


    public function Excel($linea_negocio, $adviser, $origen, $date_init, $date_finish, $state, $search = 5, $city = 0, $have_initial = 0, $to_prp = 0, $use_app = 0, $cumple = 0){


        $xls = new ClientsExport;

        $xls->linea_negocio = $linea_negocio == 0 ? 0 :  explode(",", $linea_negocio);
        $xls->asesor        = $adviser == 0 ? 0 :  explode(",", $adviser);
        $xls->origen        = $origen;
        $xls->date_init     = $date_init;
        $xls->date_finish   = $date_finish;
        $xls->state         = $state;
        $xls->search        = $search;
        $xls->city          = $city;
        $xls->have_initial  = $have_initial;
        $xls->to_prp        = $to_prp;
        $xls->use_app       = $use_app;
        $xls->cumple        = $cumple;


        return Excel::download($xls, 'ClientExport.xlsx');
    }



    public function Import(Request $request){
        $file            = $request->file('file_import');
        $destinationPath = 'import_csv';
        $name_file       = $file->getClientOriginalName();
        $file->move($destinationPath,$name_file);

        $xmldata = simplexml_load_file("import_csv/".$name_file) or die("Failed to load");

        $fila = 0;

        $data = [];

        foreach($xmldata->Worksheet->Table->Row as $key => $data) {

            if($fila != 0){

                $array = [];
                $array["origen"]          = "Pauta ".(string) $data->Cell[1]->{'Data'};
                $array["nombres"]         = (string) $data->Cell[2]->{'Data'};
                $array["telefono"]        = (string) $data->Cell[3]->{'Data'};
                $array["email"]           = (string) $data->Cell[4]->{'Data'};
                $array["direccion"]       = (string) $data->Cell[5]->{'Data'};
                $array["id_user_asesora"] = $request["id_user_asesora"];
                $array["id_line"]         = $request["id_line"];

             //  echo json_encode($array)."<br><br>";
                $array["pauta"] = 1;
                $cliente = Clients::create($array);

                $array["id_client"]   = $cliente["id_cliente"];

                ClientInformationAditionalSurgery::create($array);
                ClientClinicHistory::create($array);
                ClientCreditInformation::create($array);

                $auditoria              = new Auditoria;
                $auditoria->tabla       = "clientes";
                $auditoria->cod_reg     = $cliente["id_cliente"];
                $auditoria->status      = 1;
                $auditoria->fec_regins  = date("Y-m-d H:i:s");
                $auditoria->usr_regins  = $request["id_user"];
                $auditoria->fec_update  = date("Y-m-d H:i:s");
                $auditoria->save();

            }
            $fila++;
        }

        $data = array('mensagge' => "Se importaron ".$fila." contactos");
        return response()->json($data)->setStatusCode(200);
    }






    public function GetRequestCredit2($id_client, $id_line = 0)
    {
        try{
            $data = DB::table('client_request_credit')
           // ->join('clientes','clientes.id_cliente', $id_client)
            ->where('client_request_credit.id_client', $id_client)
            ->join('clientes','clientes.id_cliente','client_request_credit.id_client')
            ->select('*')
            ->get();
            return response()->json($data)->setStatusCode(200);
        }
        catch(\Throwable $th){
            return $th;
    }
    }



















    public function GetRequestCredit($id_client, $id_line = 0){

        $data = DB::table("client_request_credit")
                    ->selectRaw("
                                client_request_credit.*,
                                client_request_credit_requirements.working_letter,
                                client_request_credit_requirements.payment_stubs,
                                client_request_credit_requirements.copy_of_id,
                                client_request_credit_requirements.bank_statements,
                                client_request_credit_requirements.co_debtor,
                                client_request_credit_requirements.property_tradition,
                                client_request_credit_requirements.license_plate_copy,
                                client_request_credit_requirements.extractos_bancarios_dependiente,
                                client_request_credit_requirements.rut_chamber_of_commerce,
                                client_request_credit_requirements.declaracion_renta,
                                client_request_credit_requirements.cedula_codeudor,
                                client_request_credit_requirements.rut_camara_comercio_codeudor,
                                client_request_credit_requirements.extractos_bancarios_codeudor,
                                client_request_credit_requirements.declaracion_renta_codeudor,
                                client_request_credit_requirements.carta_laboral_codeudor,
                                client_request_credit_requirements.colillas_nomina_codeudor
                            ")
                    ->join("client_request_credit_requirements", "client_request_credit_requirements.id_client", "=", "client_request_credit.id_client", "left")
                    ->where("client_request_credit.id_client", $id_client)
                    ->where(function ($query) use ($id_line) {
                        if($id_line != 0){
                            $query->where("client_request_credit.id_line", $id_line);
                        }
                    })
                    ->first();

        $data->required_amount = number_format($data->required_amount, 2, ',', '.');
        $data->monthly_fee     = number_format($data->monthly_fee, 2, ',', '.');
        $requeriments = [];
        $data->working_letter                 == 1 ? $requeriments[] = "Carta Laboral"                  : '';
        $data->payment_stubs                   == 1 ? $requeriments[] = "Ultimas tres colillas de pago"  : '';
        $data->copy_of_id                      == 1 ? $requeriments[] = "Copia de la cedula"             : '';
        $data->co_debtor                       == 1 ? $requeriments[] = "Codeudor"                       : '';
        $data->license_plate_copy              == 1 ? $requeriments[] = "Copia de la matriculas"         : '';
        $data->bank_statements                 == 1 ? $requeriments[] = "Extractos bancarios del ultimo trimestre O Certificación de ingresos por parte de un contador" : '';
        $data->property_tradition              == 1 ? $requeriments[] = "Certificado de libertad y tradicion del inmueble" : '';
        $data->extractos_bancarios_dependiente == 1 ? $requeriments[] = "Extractos bancarios" : '';
        $data->rut_chamber_of_commerce         == 1 ? $requeriments[] = "Rut o cámara de comercio" : '';
        $data->declaracion_renta               == 1 ? $requeriments[] = "Declaración de Renta (si no declara, presentar una carta de un contador con la tarjeta profesional, certificando los ingresos)" : '';
        $data->cedula_codeudor                 == 1 ? $requeriments[] = "Copia de la cedula (Codeudor)" : '';
        $data->rut_camara_comercio_codeudor    == 1 ? $requeriments[] = "Rut o cámara de comercio (Codeudor)" : '';
        $data->extractos_bancarios_codeudor    == 1 ? $requeriments[] = "Extractos bancarios del ultimo trimestre (Codeudor)" : '';
        $data->declaracion_renta_codeudor      == 1 ? $requeriments[] = "Declaración de renta (si no declara renta, presentar una carta de un contador con la tarjeta profesional, certificando los ingresos) (Codeudor)" : '';
        $data->carta_laboral_codeudor          == 1 ? $requeriments[] = "Carta Laboral (Codeudor)" : '';
        $data->colillas_nomina_codeudor        == 1 ? $requeriments[] = "Colillas de los últimos tres (3) desprendibles de pago de nomina (Codeudor)" : '';

        if($data->co_debtor == 1){
            $requeriments[] = "El codeudor no debe estar Reportado";
        }
        $data->lista_requisitos = $requeriments;


        $date1 = new DateTime($data->date_aproved);
        $date2 = new DateTime(date("Y-m-d"));
        $diff = $date1->diff($date2);

        $data->days_limit = $data->days_limit -  $diff->days;




        if($data){
            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json([])->setStatusCode(204);
        }

    }



    public function AppRequestCredit(Request $request){

        $request["monthly_fee"] = str_replace(",", "", $request["monthly_fee"]);

        DB::table("client_request_credit")->insert($request->all());





        $client = DB::table("clientes")->where("id_cliente", $request["id_client"])->first();
        $mensaje = "Px: $client->nombres acaba de realizar la solicutid de Credito por la Aplicacion, codigo del Px: $client->code_client";
        $info_email = [
            "user_id" => $client->id_user_asesora,
            "issue"   => "App de Financiacion, Solicitud de Credito Px : $client->nombres, codigo del Px: $client->code_client",
            "mensage" => $mensaje,
        ];

        $this->SendEmail2($info_email);


        $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
        return response()->json($data)->setStatusCode(200);

    }


    public function SendEmail2($data){

        $user = User::find($data["user_id"]);
        $subject = $data["issue"];

        //$for = "cardenascarlos18@gmail.com";
        $for = $user["email"];
        $request["msg"] = $data["mensage"];

        Mail::send('emails.notification',$request, function($msj) use($subject,$for){
            $msj->from("crm@pdtagencia.com","CRM");
            $msj->subject($subject);
            $msj->to($for);
        });



        $for = "cardenascarlos18@gmail.com";
        $request["msg"] = $data["mensage"];

        Mail::send('emails.notification',$request, function($msj) use($subject,$for){
            $msj->from("crm@pdtagencia.com","CRM");
            $msj->subject($subject);
            $msj->to($for);
        });


        $for = "getionfinanmed@gmail.com";
        $request["msg"] = $data["mensage"];

        Mail::send('emails.notification',$request, function($msj) use($subject,$for){
            $msj->from("crm@pdtagencia.com","CRM");
            $msj->subject($subject);
            $msj->to($for);
        });

        return true;

    }



    public function RequestCredit(Request $request){

        $id_line =  $request["id_line"];

        $users = User::join("users_line_business", "users_line_business.id_user", "=", "users.id")
                        ->where("users_line_business.id_line", $request["id_line"])
                        ->where("users.queue", 0)
                        ->where("users.id", "!=", 106)

                        ->where(function ($query) use ($id_line) {
                            if($id_line == "8"){
                                $query->where("users.id", "!=", 75);
                            }
                        })


                        ->first();

        if($users){


            $client = Clients::where("identificacion", $request["identificacion"])->first();
            if(($client) && ($request["identificacion"] != "")){

                $data = [
                    "id_client"       => $client["id_cliente"],
                    "required_amount" => str_replace(",", "", $request["required_amount"]),
                    "period"          => $request["period"],
                    "monthly_fee"     => str_replace(",", "", $request["monthly_fee"]),
                    "interest_rate"   => $request["interest_rate"]
                ];

              //  DB::table("client_request_credit")->insert($data);

                DB::table('clientes')->where("id_cliente", $client["id_cliente"])
                            ->update(['id_user_asesora' => $users["id"], "id_line" => $request["id_line"]]);


                DB::table('auditoria')->where("cod_reg", $client["id_cliente"])
                            ->where("tabla", "clientes")
                            ->update(['fec_update' => date("Y-m-d H:i:s")]);

            }else{

                $request["id_user_asesora"] =  $users["id"];

                $permitted_chars        = '0123456789abcdefghijklmnopqrstuvwxyz';
                $code                   = substr(str_shuffle($permitted_chars), 0, 4);
                $request["code_client"] = strtoupper($code);
                $request["origen"]      = "Formulario Credito";


                $cliente = Clients::create($request->all());

                $request["id_client"] = $cliente["id_cliente"];



                $data = [
                    "id_client"       => $cliente["id_cliente"],
                    "required_amount" => str_replace(",", "", $request["required_amount"]),
                    "period"          => $request["period"],
                    "monthly_fee"     => str_replace(",", "", $request["monthly_fee"]),
                    "interest_rate"   => $request["interest_rate"]
                ];

               // DB::table("client_request_credit")->insert($data);



                $id_client = $cliente["id_cliente"];

                ClientInformationAditionalSurgery::create($request->all());
                ClientClinicHistory::create($request->all());
                ClientCreditInformation::create($request->all());

                $auditoria              = new Auditoria;
                $auditoria->tabla       = "clientes";
                $auditoria->cod_reg     = $cliente["id_cliente"];
                $auditoria->status      = 1;
                $auditoria->fec_regins  = date("Y-m-d H:i:s");
                $auditoria->fec_update  = date("Y-m-d H:i:s");
                $auditoria->usr_regins  = $users["id"];
                $auditoria->save();


                $update = User::find($users["id"]);
                $update->queue = 1;
                $update->save();


                isset($request["password"]) ? $request["password"] = md5($request["password"]) : $request["password"] = md5("123456789");
                $User =  User::create([
                    "email"       => $request["email"],
                    "password"    => $request["password"],
                    "id_rol"      => 17,
                    "id_client"   => $id_client
                ]);

                $datos_personales                   = new datosPersonaesModel;
                $datos_personales->nombres          = $request["nombres"];
                $datos_personales->apellido_p       = "";
                $datos_personales->apellido_m       = "afasfa";
                $datos_personales->n_cedula         = "12412124";
                $datos_personales->fecha_nacimiento = null;
                $datos_personales->telefono         = null;
                $datos_personales->direccion        = null;
                $datos_personales->id_usuario       = $User->id;
                $datos_personales->save();

            }



            $data_adviser   = AuthUsersApp::where("id_user", $request["id_user_asesora"])->first();


            $ConfigNotification = [
                "tokens" => [$data_adviser["token_notifications"]],

                "tittle" => "Financiacion",
                "body"   => "Formulario Contacto : ".$request["nombres"]."  Interesado en Financiacion",
                "data"   => ['type' => 'refferers']

            ];

            $code = SendNotifications($ConfigNotification);






            $subject = "Formulario Contacto : ".$request["nombres"]."  Interesado en Financiacion";

            $for = $users["email"];
            //$for = "cardenascarlos18@gmail.com";

            $request["msg"]  = "Un Paciente a registrado un Formulario de Credito";
            $request["apellidos"]        = ".";
            $request["direccion"]        = ".";
            $request["fecha_nacimiento"] = date("Y-m-d");
            Mail::send('emails.forms',$request->all(), function($msj) use($subject,$for){
                $msj->from("crm@pdtagencia.com","CRM");
                $msj->subject($subject);
                $msj->to($for);
            });


        }else{

           $users = User::join("users_line_business", "users_line_business.id_user", "=", "users.id")
                        ->where("users_line_business.id_line", $request["id_line"])
                        ->where("users.queue", 1)
                        ->update(["queue" => 0]);

            $this->RequestCredit($request);
       }


        $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
        return response()->json($data)->setStatusCode(200);


        echo json_encode($request->all());
    }






    public function AppStoreRequestCredit(Request $request){

        $id_line =  $request["id_line"];


        $linea      = DB::table("lines_business")->where("id_line", $request["id_line"])->first();
        $name_lines = $linea->nombre_line;

        if($request["city"] == 15){

            if($request["code_adviser"] == 5825){
                $users = User::join("users_line_business", "users_line_business.id_user", "=", "users.id")
                                ->where("id", 40060)->first();
            }else{
                $users = User::join("users_line_business", "users_line_business.id_user", "=", "users.id")
                                ->where("id", 23692)->first();
            }

        }else{

            if($request["code_adviser"] != 0){
                $users = User::join("users_line_business", "users_line_business.id_user", "=", "users.id")
                                ->where("code_user", $request["code_adviser"])->first();
                if(!$users){
                    return response()->json("El codigo de descuento no es Valido")->setStatusCode(500);
                   // dd($users);
                }
            }else{

                $users = User::join("users_line_business", "users_line_business.id_user", "=", "users.id")
                            ->where("users_line_business.id_line", $request["id_line"])
                          //  ->where("users.queue", 0)
                            ->where("users.id", "!=", 106)

                            ->where(function ($query) use ($id_line) {
                                if($id_line == "8"){
                                    $query->where("users.id", "!=", 75);
                                }
                            })

                            ->inRandomOrder()
                            ->first();

            }
        }



        if($users){

            if($request["city"] == 5){
                $id_line =  $request["id_line"];
            }else{
                $id_line =  $users->id_line;
            }



            $client = Clients::where("identificacion", $request["identificacion"])->first();
            if(($client) && ($request["identificacion"] != "")){


                if($request["code_adviser"] != 0){
                    Clients::where("id_cliente", $client["id_cliente"])
                    ->update(['id_user_asesora' => $users->id, "id_line" => $id_line]);
                }else{
                    Clients::where("id_cliente", $client["id_cliente"])
                    ->update(['id_user_asesora' => $users->id, "id_line" => $id_line]);
                }


                DB::table('auditoria')->where("cod_reg", $client["id_cliente"])
                            ->where("tabla", "clientes")
                            ->update(['fec_update' => date("Y-m-d H:i:s")]);



                $User = User::where("id_client", $client["id_cliente"])->first();

                isset($request["password"]) ? $request["password"] = md5($request["password"]) : $request["password"] = md5("123456789");

                if(!$User){

                  // echo "No existe usesr";

                    //    $User =  User::create([
                    //         "email"       => $request["email"],
                    //         "password"    => $request["password"],
                    //         "id_rol"      => 17,
                    //         "id_client"   => $client["id_cliente"]
                    //     ]);



                    $User = User::updateOrCreate(
                        ["email" => $request["email"]],
                        [
                            "password"    => $request["password"],
                            "id_rol"      => 17,
                            "id_client"   => $client["id_cliente"]
                        ]
                    );




                    $datos_personales                   = new datosPersonaesModel;
                    $datos_personales->nombres          = $request["nombres"];
                    $datos_personales->apellido_p       = "";
                    $datos_personales->apellido_m       = "afasfa";
                    $datos_personales->n_cedula         = "12412124";
                    $datos_personales->fecha_nacimiento = null;
                    $datos_personales->telefono         = null;
                    $datos_personales->direccion        = null;
                    $datos_personales->id_usuario       = $User->id;
                    $datos_personales->save();

                }

                $token_user  = AuthUserAppFinancing::where("id_user", $User->id)->get();

                foreach ($token_user as $key => $value) {
                    $value->delete();
                }

                $token = bin2hex(random_bytes(64));



                $AuthUsers                       = new AuthUserAppFinancing;
                $AuthUsers->id_user              = $User->id;
                $AuthUsers->token                = $token;
                $AuthUsers->token_notifications  = $request["fcmToken"];
                $AuthUsers->save();


                $data = array('user_id'   => $User->id,
                          'email'     => $User->email,
                          'nombres'   => $request["nombres"],
                          'avatar'    => "http://pdtclientsolutions.com/crm-public/img/usuarios/profile/",
                          'token'     => $token,
                          'line'      => 3,
                          'id_client' => $User->id_client,
                          'mensagge'  => "Ha iniciado sesion exitosamente",
                          "type_user" => "Afiliado"
                );

                $User = User::updateOrCreate(
                    ["email" => $request["email"]],
                    [
                        "password"    => $request["password"],
                        "id_rol"      => 17,
                        "id_client"   => $client["id_cliente"]
                    ]
                );

                // $User = User::where("id_client", $client["id_cliente"])->update(["email" => $request["email"], "password" => $request["password"]]);






                    $data_adviser   = AuthUsersApp::where("id_user", $request["id_user_asesora"])->first();


                    $ConfigNotification = [
                        "tokens" => [$data_adviser["token_notifications"]],

                        "tittle" => "Financiacion",
                        "body"   => "Formulario Contacto : ".$request["nombres"]."  Interesado en Financiacion",
                        "data"   => ['type' => 'refferers']

                    ];

                    $code = SendNotifications($ConfigNotification);





                    $linea = DB::table("lines_business")->where("id_line", $request["id_line"])->first();
                    $subject = "App Financiacion : ".$request["nombres"]."  Interesado en Financiacion";

                    $for = $users["email"];
                // $for = "cardenascarlos18@gmail.com";

                    $request["msg"]  = "Un Paciente se ha registrado por el App con el codigo ".$request["code_adviser"].", Linea: ".$name_lines;
                    $request["apellidos"]        = ".";
                    $request["direccion"]        = ".";
                    $request["fecha_nacimiento"] = date("Y-m-d");
                    Mail::send('emails.forms',$request->all(), function($msj) use($subject,$for){
                        $msj->from("crm@pdtagencia.com","CRM");
                        $msj->subject($subject);
                        $msj->to($for);
                    });




                    //$for = $users["email"];
                    $for = "cardenascarlos18@gmail.com";

                    $request["msg"]  = "Un Paciente se ha registrado por el App con el codigo ".$request["code_adviser"].", Linea: ".$name_lines;
                    $request["apellidos"]        = ".";
                    $request["direccion"]        = ".";
                    $request["fecha_nacimiento"] = date("Y-m-d");
                    Mail::send('emails.forms',$request->all(), function($msj) use($subject,$for){
                        $msj->from("crm@pdtagencia.com","CRM");
                        $msj->subject($subject);
                        $msj->to($for);
                    });





                    $for = "pdtagenciademedios@gmail.com";

                    $request["msg"]  = "Un Paciente se ha registrado por el App con el codigo ".$request["code_adviser"].", Linea: ".$name_lines;
                    $request["apellidos"]        = ".";
                    $request["direccion"]        = ".";
                    $request["fecha_nacimiento"] = date("Y-m-d");
                    Mail::send('emails.forms',$request->all(), function($msj) use($subject,$for){
                        $msj->from("crm@pdtagencia.com","CRM");
                        $msj->subject($subject);
                        $msj->to($for);
                    });


                    if($id_line == 16){
                        $for = "comunicacionesmed49@gmail.com";

                        $request["msg"]  = "Un Paciente se ha registrado por el App con el codigo ".$request["code_adviser"].", Linea: ".$name_lines;
                        $request["apellidos"]        = ".";
                        $request["direccion"]        = ".";
                        $request["fecha_nacimiento"] = date("Y-m-d");
                        Mail::send('emails.forms',$request->all(), function($msj) use($subject,$for){
                            $msj->from("crm@pdtagencia.com","CRM");
                            $msj->subject($subject);
                            $msj->to($for);
                        });
                    }







                return response()->json($data)->setStatusCode(200);


            }else{

                $request["id_user_asesora"] =  $users["id"];

                $permitted_chars        = '0123456789abcdefghijklmnopqrstuvwxyz';
                $code                   = substr(str_shuffle($permitted_chars), 0, 4);
                $request["code_client"] = strtoupper($code);
                $request["origen"]      = "App Financiacion con el codigo ".$request["code_adviser"];

                $request["id_line"] = $id_line;


                if($request["city"] == "Seleccione"){
                    $request["city"] = 3;
                }

                $cliente = Clients::create($request->all());

                $request["id_client"] = $cliente["id_cliente"];
                $id_client = $cliente["id_cliente"];

                ClientInformationAditionalSurgery::create($request->all());
                ClientClinicHistory::create($request->all());
                ClientCreditInformation::create($request->all());

                $auditoria              = new Auditoria;
                $auditoria->tabla       = "clientes";
                $auditoria->cod_reg     = $cliente["id_cliente"];
                $auditoria->status      = 1;
                $auditoria->fec_regins  = date("Y-m-d H:i:s");
                $auditoria->fec_update  = date("Y-m-d H:i:s");
                $auditoria->usr_regins  = $users["id"];
                $auditoria->save();


                $update = User::find($users["id"]);
                $update->queue = 1;
                $update->save();


                isset($request["password"]) ? $request["password"] = md5($request["password"]) : $request["password"] = md5("123456789");


                // $User =  User::create([
                //     "email"       => $request["email"],
                //     "password"    => $request["password"],
                //     "id_rol"      => 17,
                //     "id_client"   => $id_client
                // ]);

                $User = User::updateOrCreate(
                    ["email" => $request["email"]],
                    [
                        "password"    => $request["password"],
                        "id_rol"      => 17,
                        "id_client"   => $id_client
                    ]
                );


                $datos_personales                   = new datosPersonaesModel;
                $datos_personales->nombres          = $request["nombres"];
                $datos_personales->apellido_p       = "";
                $datos_personales->apellido_m       = "afasfa";
                $datos_personales->n_cedula         = "12412124";
                $datos_personales->fecha_nacimiento = null;
                $datos_personales->telefono         = null;
                $datos_personales->direccion        = null;
                $datos_personales->id_usuario       = $User->id;
                $datos_personales->save();




                $token_user  = AuthUserAppFinancing::where("id_user", $User->id)->get();

                foreach ($token_user as $key => $value) {
                    $value->delete();
                }

                $token = bin2hex(random_bytes(64));

                $AuthUsers                       = new AuthUserAppFinancing;
                $AuthUsers->id_user              = $User->id;
                $AuthUsers->token                = $token;
                $AuthUsers->token_notifications  = $request["fcmToken"];
                $AuthUsers->save();




                $data = array('user_id'   => $User->id,
                          'email'     => $User->email,
                          'nombres'   => $request["nombres"],
                          'avatar'    => "http://pdtclientsolutions.com/crm-public/img/usuarios/profile/",
                          'token'     => $token,
                          'line'      => 3,
                          'id_client' => $User->id_client,
                          'mensagge'  => "Ha iniciado sesion exitosamente",
                          "type_user" => "Afiliado"
                );





                $data_adviser   = AuthUsersApp::where("id_user", $request["id_user_asesora"])->first();


                $ConfigNotification = [
                    "tokens" => [$data_adviser["token_notifications"]],

                    "tittle" => "Financiacion",
                    "body"   => "Formulario Contacto : ".$request["nombres"]."  Interesado en Financiacion",
                    "data"   => ['type' => 'refferers']

                ];

                $code = SendNotifications($ConfigNotification);




                $subject = "App Financiacion : ".$request["nombres"]."  Interesado en Financiacion";

                $for = $users["email"];
            // $for = "cardenascarlos18@gmail.com";

                $request["msg"]  = "Un Paciente se ha registrado por el App con el codigo ".$request["code_adviser"]." linea: ".$name_lines;
                $request["apellidos"]        = ".";
                $request["direccion"]        = ".";
                $request["fecha_nacimiento"] = date("Y-m-d");
                Mail::send('emails.forms',$request->all(), function($msj) use($subject,$for){
                    $msj->from("crm@pdtagencia.com","CRM");
                    $msj->subject($subject);
                    $msj->to($for);
                });




                //$for = $users["email"];
            $for = "cardenascarlos18@gmail.com";

            $request["msg"]  = "Un Paciente se ha registrado por el App con el codigo ".$request["code_adviser"]." Linea : ".$name_lines;
                $request["apellidos"]        = ".";
                $request["direccion"]        = ".";
                $request["fecha_nacimiento"] = date("Y-m-d");
                Mail::send('emails.forms',$request->all(), function($msj) use($subject,$for){
                    $msj->from("crm@pdtagencia.com","CRM");
                    $msj->subject($subject);
                    $msj->to($for);
                });




                $for = "pdtagenciademedios@gmail.com";

                $request["msg"]  = "Un Paciente se ha registrado por el App con el codigo ".$request["code_adviser"]." Linea : ".$name_lines;
                $request["apellidos"]        = ".";
                $request["direccion"]        = ".";
                $request["fecha_nacimiento"] = date("Y-m-d");
                Mail::send('emails.forms',$request->all(), function($msj) use($subject,$for){
                    $msj->from("crm@pdtagencia.com","CRM");
                    $msj->subject($subject);
                    $msj->to($for);
                });



                if($id_line == 16){
                    $for = "comunicacionesmed49@gmail.com";

                    $request["msg"]  = "Un Paciente se ha registrado por el App con el codigo ".$request["code_adviser"]." Linea : ".$name_lines;
                    $request["apellidos"]        = ".";
                    $request["direccion"]        = ".";
                    $request["fecha_nacimiento"] = date("Y-m-d");
                    Mail::send('emails.forms',$request->all(), function($msj) use($subject,$for){
                        $msj->from("crm@pdtagencia.com","CRM");
                        $msj->subject($subject);
                        $msj->to($for);
                    });
                }






                return response()->json($data)->setStatusCode(200);

            }




        }else{

           $users = User::join("users_line_business", "users_line_business.id_user", "=", "users.id")
                        ->where("users_line_business.id_line", $request["id_line"])
                        ->where("users.queue", 1)
                        ->update(["queue" => 0]);

            $this->AppStoreRequestCredit($request);

            $data = array('user_id'   => $User->id,
                          'email'     => $User->email,
                          'nombres'   => $request["nombres"],
                          'avatar'    => "http://pdtclientsolutions.com/crm-public/img/usuarios/profile/",
                          'token'     => $token,
                          'line'      => 3,
                          'id_client' => $User->id_client,
                          'mensagge'  => "Ha iniciado sesion exitosamente",
                          "type_user" => "Afiliado"
                );


                return response()->json($data)->setStatusCode(200);

       }

    }





    public function ClientForms(Request $request){

        $id_line =  $request["id_line"];
        $id_user =  $request["id_user"];

        $users = User::join("users_line_business", "users_line_business.id_user", "=", "users.id")
                        ->where("users_line_business.id_line", $request["id_line"])
                        ->where("users.queue", 0)
                        ->where("users.id", "!=", 106)

                        ->where(function ($query) use ($id_line) {
                            if($id_line == "8"){
                                $query->where("users.id", "!=", 75);
                            }
                        })


                        ->first();


       if($users){



            $client = Clients::where("identificacion", $request["identificacion"])->get();
            if((sizeof($client) > 0) && ($request["identificacion"] != "")){

                $data = array('mensagge' => "Ya te encuentras registrado en nuestra base de datos");
                return response()->json($data)->setStatusCode(200);

            }


            $request["id_user_asesora"] =  $users["id"];

            $permitted_chars        = '0123456789abcdefghijklmnopqrstuvwxyz';
            $code                   = substr(str_shuffle($permitted_chars), 0, 4);
            $request["code_client"] = strtoupper($code);


            $cliente = Clients::create($request->all());

            $request["id_client"] = $cliente["id_cliente"];





            $id_client = $cliente["id_cliente"];

            ClientInformationAditionalSurgery::create($request->all());
            ClientClinicHistory::create($request->all());
            ClientCreditInformation::create($request->all());

            $auditoria              = new Auditoria;
            $auditoria->tabla       = "clientes";
            $auditoria->cod_reg     = $cliente["id_cliente"];
            $auditoria->status      = 1;
            $auditoria->fec_regins  = date("Y-m-d H:i:s");
            $auditoria->fec_update  = date("Y-m-d H:i:s");
            $auditoria->usr_regins  = $users["id"];
            $auditoria->save();


            $update = User::find($users["id"]);
            $update->queue = 1;
            $update->save();


            $User =  User::create([
                "email"       => $request["email"],
                "password"    => md5("123456789"),
                "id_rol"      => 17,
                "id_client"   => $id_client
            ]);

            $datos_personales                   = new datosPersonaesModel;
            $datos_personales->nombres          = $request["nombres"];
            $datos_personales->apellido_p       = "";
            $datos_personales->apellido_m       = "afasfa";
            $datos_personales->n_cedula         = "12412124";
            $datos_personales->fecha_nacimiento = null;
            $datos_personales->telefono         = null;
            $datos_personales->direccion        = null;
            $datos_personales->id_usuario       = $User->id;
            $datos_personales->save();





            $subject = "Formulario Web";

            //$for = "cardenascarlos18@gmail.com";
            $for = $users["email"];
         //   $for = "cardenascarlos18@gmail.com";

            $request["msg"]  = "Un Paciente a registrado un Formulario Web";

            $request["direccion"] = ($request["city"] == 3 )? "Medellin" : "Cali";

            Mail::send('emails.forms',$request->all(), function($msj) use($subject,$for){
                $msj->from("crm@pdtagencia.com","CRM");
                $msj->subject($subject);
                $msj->to($for);
            });



       }else{

           $users = User::join("users_line_business", "users_line_business.id_user", "=", "users.id")
                        ->where("users_line_business.id_line", $request["id_line"])
                        ->where("users.queue", 1)
                        ->update(["queue" => 0]);

            $this->ClientForms($request);
       }


        $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
        return response()->json($data)->setStatusCode(200);




    }


    public function ClientFormsPersonalizado(Request $request){

        $id_line =  $request["id_line"];
        $id_user =  $request["id_user"];

        $users = User::join("users_line_business", "users_line_business.id_user", "=", "users.id")

                        ->where("users.id", $id_user)
                        ->first();

        if($users){
            $client = Clients::where("identificacion", $request["identificacion"])->get();
            if((sizeof($client) > 0) && ($request["identificacion"] != "")){

                $data = array('mensagge' => "Ya te encuentras registrado en nuestra base de datos");
                return response()->json($data)->setStatusCode(200);

            }


            $request["id_user_asesora"] =  $users["id"];

            $permitted_chars        = '0123456789abcdefghijklmnopqrstuvwxyz';
            $code                   = substr(str_shuffle($permitted_chars), 0, 4);
            $request["code_client"] = strtoupper($code);


            $cliente = Clients::create($request->all());

            $request["id_client"] = $cliente["id_cliente"];





            $id_client = $cliente["id_cliente"];

            ClientInformationAditionalSurgery::create($request->all());
            ClientClinicHistory::create($request->all());
            ClientCreditInformation::create($request->all());

            $auditoria              = new Auditoria;
            $auditoria->tabla       = "clientes";
            $auditoria->cod_reg     = $cliente["id_cliente"];
            $auditoria->status      = 1;
            $auditoria->fec_regins  = date("Y-m-d H:i:s");
            $auditoria->fec_update  = date("Y-m-d H:i:s");
            $auditoria->usr_regins  = $users["id"];
            $auditoria->save();


            $update = User::find($users["id"]);
            $update->queue = 1;
            $update->save();


            $User =  User::create([
                "email"       => $request["email"],
                "password"    => md5("123456789"),
                "id_rol"      => 17,
                "id_client"   => $id_client
            ]);

            $datos_personales                   = new datosPersonaesModel;
            $datos_personales->nombres          = $request["nombres"];
            $datos_personales->apellido_p       = "";
            $datos_personales->apellido_m       = "afasfa";
            $datos_personales->n_cedula         = "12412124";
            $datos_personales->fecha_nacimiento = null;
            $datos_personales->telefono         = null;
            $datos_personales->direccion        = null;
            $datos_personales->id_usuario       = $User->id;
            $datos_personales->save();





            $subject = "Formulario Web";

            //$for = "cardenascarlos18@gmail.com";
            $for = $users["email"];
         //   $for = "cardenascarlos18@gmail.com";

            $request["msg"]  = "Un Paciente a registrado un Formulario Web";

            Mail::send('emails.forms',$request->all(), function($msj) use($subject,$for){
                $msj->from("crm@pdtagencia.com","CRM");
                $msj->subject($subject);
                $msj->to($for);
            });
        }


        $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
        return response()->json($data)->setStatusCode(200);
    }




    public function ClientFormsPrp(Request $request){

        $users = User::join("users_line_business", "users_line_business.id_user", "=", "users.id")
                        ->join("datos_personales", "datos_personales.id_usuario", "=", "users.id")
                        ->where("users_line_business.id_line", $request["id_line"])
                        ->where("users.queue_prp", 0)
                        ->where("users.id", "!=", 69)
                        ->first();
       if($users){

            $request["name_user"]   = $users["nombres"]." ".$users["apellido_p"];

            $permitted_chars        = '0123456789abcdefghijklmnopqrstuvwxyz';
            $code                   = substr(str_shuffle($permitted_chars), 0, 4);
            $request["code_client"] = strtoupper($code);
            $request["prp"]         = "Si";
            $request["to_db"]       = "1";
            $request["created_prp"] = date("Y-m-d");

            $request["id_user_asesora"] =  $users["id"];
            $request["origen"] =  "PRP Agencia";

            $client = Clients::where("identificacion", $request["identificacion"])->get();


            if((sizeof($client) > 0) && ($request["identificacion"] != "")){

                foreach($client as $value){

                    if($value["prp"] == "Si"){
                        $data = array('mensagge' => "Ya se encuentra registrado en el PRP con el codigo: ".$value["code_client"]);
                        return response()->json($data)->setStatusCode(400);
                    }

                    $update = array(
                        "code_client"     => $request["code_client"],
                        "prp"             => "Si",
                        "to_db"           => "1",
                        "created_prp"     => date("Y-m-d"),
                        "origen"          =>  $request["origen"],
                        "telefono"        =>  $request["telefono"],
                        "id_user_asesora" => $request["id_user_asesora"],
                        "id_line"         => $request["id_line"]
                    );
                    $request["state"] = "Afiliada";
                    Clients::find($value["id_cliente"])->update($update);
                    DB::table('auditoria')->where("cod_reg", $value["id_cliente"])
                            ->where("tabla", "clientes")
                            ->update(['fec_update' => date("Y-m-d H:i:s")]);

                    $id_client = $value["id_cliente"];




                    $comment = "<b>FECHA EN LA QUE TE OPERASTE CON NOSOTROS:</b> ".$request["fecha_opero"]."<br>";
                    $comment .= "<b>¿QUE CIRUGÍA TE PRACTICASTE?:</b> ".$request["surgeri"]."<br>";
                    $comment .= "<b>¿DESEAS QUE TE PROGRAMEMOS UNA CITA DE CONTROL?:</b> ".$request["radios"]."<br>";
                    $comment .= "<b>EL PAGO DE LA BONIFICACION PREFIERES QUE SEA:</b> ".$request["radiosPago"]."<br>";
                    $comment .= "<b>SI ELEGISTE PAGO POR TRANSFERENCIA:</b><br>";
                    $comment .= "<b>Nombre del Titular:</b> ".$request["name_titular"]."<br>";
                    $comment .= "<b>Numero de Cedula:</b> ".$request["cedula_titular"]."<br>";
                    $comment .= "<b>Número de Cuenta:</b> ".$request["cuenta_titular"]."<br>";
                    $comment .= "<b>¿TIENES ALGUNA SUGERENCIA PARA NUESTRO GRUPO?:</b> ".$request["sugrencias"]."<br>";

                    $data["table"]    = "clients";
                    $data["id_event"] = $id_client;
                    $data["comments"] = $comment;

                    Comments::create($data);


                }


            }else{
                $request["state"] = "Afiliada";
                $cliente = Clients::create($request->all());

                $request["id_client"] = $cliente["id_cliente"];

                ClientInformationAditionalSurgery::create($request->all());
                ClientClinicHistory::create($request->all());
                ClientCreditInformation::create($request->all());

                $auditoria              = new Auditoria;
                $auditoria->tabla       = "clientes";
                $auditoria->cod_reg     = $cliente["id_cliente"];
                $auditoria->status      = 1;
                $auditoria->fec_regins  = date("Y-m-d H:i:s");
                $auditoria->fec_update  = date("Y-m-d H:i:s");
                $auditoria->usr_regins  = $users["id"];
                $auditoria->save();

                $update            = User::find($users["id"]);
                $update->queue_prp = 1;
                $update->save();

                $id_client = $cliente["id_cliente"];





                $comment = "<b>FECHA EN LA QUE TE OPERASTE CON NOSOTROS:</b> ".$request["fecha_opero"]."<br>";
                $comment .= "<b>¿QUE CIRUGÍA TE PRACTICASTE?:</b> ".$request["surgeri"]."<br>";
                $comment .= "<b>¿DESEAS QUE TE PROGRAMEMOS UNA CITA DE CONTROL?:</b> ".$request["radios"]."<br>";
                $comment .= "<b>EL PAGO DE LA BONIFICACION PREFIERES QUE SEA:</b> ".$request["radiosPago"]."<br>";
                $comment .= "<b>SI ELEGISTE PAGO POR TRANSFERENCIA:</b><br>";
                $comment .= "<b>Nombre del Titular:</b> ".$request["name_titular"]."<br>";
                $comment .= "<b>Numero de Cedula:</b> ".$request["cedula_titular"]."<br>";
                $comment .= "<b>Número de Cuenta:</b> ".$request["cuenta_titular"]."<br>";
                $comment .= "<b>¿TIENES ALGUNA SUGERENCIA PARA NUESTRO GRUPO?:</b> ".$request["sugrencias"]."<br>";

                $data["table"]    = "clients";
                $data["id_event"] = $id_client;
                $data["id_user"]  = $users["id"];
                $data["comment"] = $comment;

                Comments::create($data);





                $User =  User::create([
                    "email"       => $request["email"],
                    "password"    => md5("123456789"),
                    "id_rol"      => 17,
                    "id_client"   => $id_client
                ]);



                $datos_personales                   = new datosPersonaesModel;
                $datos_personales->nombres          = $request["nombres"];
                $datos_personales->apellido_p       = "";
                $datos_personales->apellido_m       = "afasfa";
                $datos_personales->n_cedula         = "12412124";
                $datos_personales->fecha_nacimiento = null;
                $datos_personales->telefono         = null;
                $datos_personales->direccion        = null;
                $datos_personales->id_usuario       = $User->id;
                $datos_personales->save();



            }







            $notification["fecha"]    = date("Y-m-d");
            $notification["title"]    = "Hoy Ingreso de PRP ".$request["nombres"]." codigo: ".$request["code_client"];
            $notification["id_user"]  = $users["id"];
            $notification["id_event"] = $id_client;
            $notification["type"]     = "prp";

            Notification::insert($notification);









            if($request["id_line"] == 2){
                $request["name_line"] = "Clínica Especialistas del Poblado (CEP)";
            }

            if($request["id_line"] == 3){
                $request["name_line"] = "CiruCredito";
            }

            if($request["id_line"] == 17){
                $request["name_line"] = "Doctor Daniel Correa";
            }
            if($request["id_line"] == 16){
                $request["name_line"] = "Planmed";
            }


            if($request["id_line"] == 18){
                $request["name_line"] = "CEP";
            }

            if($request["id_line"] == 14){
                $request["name_line"] = "Mas Estetic";
            }

            if($request["id_line"] == 15){
                $request["name_line"] = "Global Medical";
            }


            if($request["id_line"] == 20){
                $request["name_line"] = "Linea de Carlos Cardenas no Tocar :D";
            }


            if($request["id_line"] == 21){
                $request["name_line"] = "Clinica Laser (Financiacion)";
            }






            if(($request["id_line"] == 9)){
                $subject = "Formulario Trabaja con Nosotros para Paulina  Clinica Laser: ".$request["nombres"];
            }

            if(($request["id_line"] == 21)){
                $subject = "Formulario Trabaja con Nosotros para Manuela  Clinica Laser: ".$request["nombres"];
            }



            if(($request["id_line"] == 2) || ($request["id_line"] == 3) || ($request["id_line"] == 17)){
                $subject = "Formulario Trabaja con Nosotros para Paulina ".$request["name_line"]." : ".$request["nombres"];
            }

            if(($request["id_line"] == 18) || ($request["id_line"] == 14) || ($request["id_line"] == 15)  || ($request["id_line"] == 16)){
                $subject = "Formulario Trabaja con Nosotros para Manuela ".$request["name_line"].": ".$request["nombres"];
            }



            if(($request["id_line"] == 20)){
                $subject = "Formulario Trabaja con Nosotros para Carlos Cardenas ".$request["name_line"].": ".$request["nombres"];
            }





            //$for = "cardenascarlos18@gmail.com";
              $for = $users["email"];
           // $for = "cardenascarlos18@gmail.com..";

            $request["msg"]  = "Wiiii :D";

            Mail::send('emails.formsPrp',$request->all(), function($msj) use($subject,$for){
                $msj->from("crm@pdtagencia.com","CRM");
                $msj->subject($subject);
                $msj->to($for);
            });


            Mail::send('emails.formsPrp',$request->all(), function($msj) use($subject,$for){
                $msj->from("crm@pdtagencia.com","CRM");
                $msj->subject($subject);
                $msj->to("pdtagenciademedios@gmail.com");
            });









            $data_adviser   = AuthUsersApp::where("id_user", $request["id_user_asesora"])->first();


            $ConfigNotification = [
                "tokens" => [$data_adviser["token_notifications"]],

                "tittle" => "PRP",
                "body"   => 'Se ha registrado un Afiliado PRP: '.$request["nombres"].'codigo: '.$request["code_client"],
                "data"   => ['type' => 'refferers']

            ];

            $code = SendNotifications($ConfigNotification);


       }else{

           $users = User::join("users_line_business", "users_line_business.id_user", "=", "users.id")
                        ->where("users_line_business.id_line", $request["id_line"])
                        ->where("users.queue_prp", 1)
                        ->update(["queue_prp" => 0]);

            $this->ClientFormsPrp($request);
       }


       $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente.");
        return response()->json($data)->setStatusCode(200);


    }






    public function ClientFormsPrpAdviser(Request $request){


        $users = User::join("users_line_business", "users_line_business.id_user", "=", "users.id")
                        ->join("datos_personales", "datos_personales.id_usuario", "=", "users.id")
                        ->where("users_line_business.id_line", $request["id_line"])
                        ->where("users.id", $request["adviser"])
                        ->where("users.id", "!=", 69)
                        ->first();


       if($users){

            $request["name_user"]   = $users["nombres"]." ".$users["apellido_p"];

            $permitted_chars        = '0123456789abcdefghijklmnopqrstuvwxyz';
            $code                   = substr(str_shuffle($permitted_chars), 0, 4);
            $request["code_client"] = strtoupper($code);
            $request["prp"]         = "Si";
            $request["created_prp"] = date("Y-m-d");

            $request["to_db"]       = "1";

            $request["id_user_asesora"] =  $users["id"];
            $request["origen"] =  "PRP Asesora ". $request["name_user"];


            $client = Clients::where("identificacion", $request["identificacion"])->get();


            if((sizeof($client) > 0) && ($request["identificacion"] != "")){


                foreach($client as $value){


                    if($value["prp"] == "Si"){
                        $data = array('mensagge' => "Ya se encuentra registrado en el PRP con el codigo: ".$value["code_client"]);
                        return response()->json($data)->setStatusCode(400);
                    }
                    $update = array(
                        "code_client"     => $request["code_client"],
                        "prp"             => "Si",
                        "created_prp"     => date("Y-m-d"),
                        "to_db"           => "1",
                        "origen"          =>  $request["origen"],
                        "telefono"        =>  $request["telefono"],
                        "id_user_asesora" => $request["id_user_asesora"],
                        "id_line"         => $request["id_line"]
                    );

                    Clients::find($value["id_cliente"])->update($update);
                    DB::table('auditoria')->where("cod_reg", $value["id_cliente"])
                            ->where("tabla", "clientes")
                            ->update(['fec_update' => date("Y-m-d H:i:s")]);

                    $id_client = $value["id_cliente"];



                    $comment = "<b>FECHA EN LA QUE TE OPERASTE CON NOSOTROS:</b> ".$request["fecha_opero"]."<br>";
                    $comment .= "<b>¿QUE CIRUGÍA TE PRACTICASTE?:</b> ".$request["surgeri"]."<br>";
                    $comment .= "<b>¿DESEAS QUE TE PROGRAMEMOS UNA CITA DE CONTROL?:</b> ".$request["radios"]."<br>";
                    $comment .= "<b>EL PAGO DE LA BONIFICACION PREFIERES QUE SEA:</b> ".$request["radiosPago"]."<br>";
                    $comment .= "<b>SI ELEGISTE PAGO POR TRANSFERENCIA:</b><br>";
                    $comment .= "<b>Nombre del Titular:</b> ".$request["name_titular"]."<br>";
                    $comment .= "<b>Numero de Cedula:</b> ".$request["cedula_titular"]."<br>";
                    $comment .= "<b>Número de Cuenta:</b> ".$request["cuenta_titular"]."<br>";
                    $comment .= "<b>¿TIENES ALGUNA SUGERENCIA PARA NUESTRO GRUPO?:</b> ".$request["sugrencias"]."<br>";

                    $data["table"]    = "clients";
                    $data["id_event"] = $id_client;
                    $data["id_user"]  = $users["id"];
                    $data["comment"] = $comment;

                    Comments::create($data);
                }

            }else{


                $cliente = Clients::create($request->all());

                $request["id_client"] = $cliente["id_cliente"];

                ClientInformationAditionalSurgery::create($request->all());
                ClientClinicHistory::create($request->all());
                ClientCreditInformation::create($request->all());

                $auditoria              = new Auditoria;
                $auditoria->tabla       = "clientes";
                $auditoria->cod_reg     = $cliente["id_cliente"];
                $auditoria->status      = 1;
                $auditoria->fec_regins  = date("Y-m-d H:i:s");
                $auditoria->fec_update  = date("Y-m-d H:i:s");
                $auditoria->usr_regins  = $users["id"];
                $auditoria->save();


                $id_client = $cliente["id_cliente"];


                $comment = "<b>FECHA EN LA QUE TE OPERASTE CON NOSOTROS:</b> ".$request["fecha_opero"]."<br>";
                $comment .= "<b>¿QUE CIRUGÍA TE PRACTICASTE?:</b> ".$request["surgeri"]."<br>";
                $comment .= "<b>¿DESEAS QUE TE PROGRAMEMOS UNA CITA DE CONTROL?:</b> ".$request["radios"]."<br>";
                $comment .= "<b>EL PAGO DE LA BONIFICACION PREFIERES QUE SEA:</b> ".$request["radiosPago"]."<br>";
                $comment .= "<b>SI ELEGISTE PAGO POR TRANSFERENCIA:</b><br>";
                $comment .= "<b>Nombre del Titular:</b> ".$request["name_titular"]."<br>";
                $comment .= "<b>Numero de Cedula:</b> ".$request["cedula_titular"]."<br>";
                $comment .= "<b>Número de Cuenta:</b> ".$request["cuenta_titular"]."<br>";
                $comment .= "<b>¿TIENES ALGUNA SUGERENCIA PARA NUESTRO GRUPO?:</b> ".$request["sugrencias"]."<br>";

                $data["table"]    = "clients";
                $data["id_event"] = $id_client;
                $data["id_user"]  = $users["id"];
                $data["comment"] = $comment;

                Comments::create($data);




                $User =  User::create([
                    "email"       => $request["email"],
                    "password"    => md5("123456789"),
                    "id_rol"      => 17,
                    "id_client"   => $id_client
                ]);


                $datos_personales                   = new datosPersonaesModel;
                $datos_personales->nombres          = $request["nombres"];
                $datos_personales->apellido_p       = "";
                $datos_personales->apellido_m       = "afasfa";
                $datos_personales->n_cedula         = "12412124";
                $datos_personales->fecha_nacimiento = null;
                $datos_personales->telefono         = null;
                $datos_personales->direccion        = null;
                $datos_personales->id_usuario       = $User->id;
                $datos_personales->save();



            }

           /* $data_user = AuthUsersApp::where("id_user", $users["id"])->first();

            $ConfigNotification = [
                "tokens" => [$data_user["token_notifications"]],

                "tittle" => "PRP",
                "body"   => "Se ah registrado un nuevo Afiliado",
                "data"   => ['type' => 'affiliates']

            ];
            $code = SendNotifications($ConfigNotification);

            */

            $notification["fecha"]    = date("Y-m-d");
            $notification["title"]    = "Hoy Ingreso de PRP ".$request["nombres"]." codigo: ".$request["code_client"];
            $notification["id_user"]  = $users["id"];
            $notification["id_event"] = $id_client;
            $notification["type"]     = "prp";

            Notification::insert($notification);



            if($request["id_line"] == 2){
                $request["name_line"] = "Clínica Especialistas del Poblado (CEP)";
            }

            if($request["id_line"] == 3){
                $request["name_line"] = "CiruCredito";
            }

            if($request["id_line"] == 17){
                $request["name_line"] = "Doctor Daniel Correa";
            }
            if($request["id_line"] == 16){
                $request["name_line"] = "Planmed";
            }




            if($request["id_line"] == 18){
                $request["name_line"] = "CEP";
            }

            if($request["id_line"] == 14){
                $request["name_line"] = "Mas Estetic";
            }

            if($request["id_line"] == 15){
                $request["name_line"] = "Global Medical";
            }


            if($request["id_line"] == 20){
                $request["name_line"] = "Linea de Carlos Cardenas No Tocar :D";
            }


            if($request["id_line"] == 24){
                $request["name_line"] = "Interquirurgica";
            }


            if($request["id_line"] == 13){
                $request["name_line"] = "Tu Cirujano seguro";
            }


            if($request["id_line"] == 27){
                $request["name_line"] = "Finanmed";
            }


            if($request["id_line"] == 30){
                $request["name_line"] = "Blue";
            }





            if(($request["id_line"] == 9)){
                $subject = "Formulario PRP Asesora  Clinica Laser: ".$request["nombres"];
            }

            if(($request["id_line"] == 21)){
                $subject = "Formulario PRP Asesora  Clinica Laser (Financiacion): ".$request["nombres"];
            }

            if(($request["id_line"] == 22)){
                $subject = "Formulario PRP Asesora  Clínica Especialistas del Poblado (Financiacion): ".$request["nombres"];
            }


            if(($request["id_line"] == 24)){
                $subject = "Formulario PRP Asesora Interquirurgica: ".$request["nombres"];
            }





            if(($request["id_line"] == 2) || ($request["id_line"] == 3) || ($request["id_line"] == 17)){
                $subject = "Formulario PRP Asesora  ".$request["name_line"]." : ".$request["nombres"];
            }

            if(($request["id_line"] == 18) || ($request["id_line"] == 14) || ($request["id_line"] == 15)  || ($request["id_line"] == 16)){
                $subject = "Formulario PRP Asesora  ".$request["name_line"].": ".$request["nombres"];
            }



            if(($request["id_line"] == 20)){
                $subject = "Formulario PRP Asesora  ".$request["name_line"].": ".$request["nombres"];
            }


            if(($request["id_line"] == 13)){
                $subject = "Formulario PRP Asesora  ".$request["name_line"].": ".$request["nombres"];
            }


            if(($request["id_line"] == 27)){
                $subject = "Formulario PRP Asesora  ".$request["name_line"].": ".$request["nombres"];
            }


            if(($request["id_line"] == 30)){
                $subject = "Formulario PRP Asesora  ".$request["name_line"].": ".$request["nombres"];
            }


           // $for = "cardenascarlos18@gmail.com";
            $for = $users["email"];
           // $for = "cardenascarlos18@gmail.com";

            $request["msg"]  = "Wiiii :D";

            Mail::send('emails.formsPrp',$request->all(), function($msj) use($subject,$for){
                $msj->from("crm@pdtagencia.com","CRM");
                $msj->subject($subject);
                $msj->to($for);
            });

            /*
                Mail::send('emails.formsPrp',$request->all(), function($msj) use($subject,$for){
                    $msj->from("cardenascarlos18@gmail.com","CRM");
                    $msj->subject($subject);
                    $msj->to("pdtagenciademedios@gmail.com");
                });
            */





            $data_user = AuthUsersApp::where("id_user", $users["id"])->first();


            $ConfigNotification = [
                "tokens" => [$data_user["token_notifications"]],

                "tittle" => "PRP",
                "body"   => 'Se ha registrado un Afiliado PRP: '.$request["nombres"].' codigo: '.$request["code_client"],
                "data"   => ['type' => 'refferers']

            ];

            $code = SendNotifications($ConfigNotification);





       }


       $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
       return response()->json($data)->setStatusCode(200);


    }



    public function ClientFormsPrpAdviserLuisa(Request $request){


        $users = User::join("users_line_business", "users_line_business.id_user", "=", "users.id")
                        ->join("datos_personales", "datos_personales.id_usuario", "=", "users.id")
                        ->where("users_line_business.id_line", $request["id_line"])
                        ->where("users.id", $request["adviser"])
                        ->first();


       if($users){

            $request["name_user"]   = $users["nombres"]." ".$users["apellido_p"];

            $permitted_chars        = '0123456789abcdefghijklmnopqrstuvwxyz';
            $code                   = substr(str_shuffle($permitted_chars), 0, 4);
            $request["code_client"] = strtoupper($code);
            $request["prp"]         = "Si";
            $request["to_db"]       = "1";

            $request["id_user_asesora"] =  $users["id"];
            $request["origen"]          =  "PRP Asesora ". $request["name_user"];
            $request["created_prp"] = date("Y-m-d");


            $client = Clients::where("identificacion", $request["identificacion"])->get();

            if((sizeof($client) > 0) && ($request["identificacion"] != "")){

                foreach($client as $value){

                    if($value["prp"] == "Si"){
                        $data = array('mensagge' => "Ya se encuentra registrado en el PRP con el codigo: ".$value["code_client"]);
                        return response()->json($data)->setStatusCode(400);
                    }


                    $update = array(
                        "code_client"     => $request["code_client"],
                        "prp"             => "Si",
                        "to_db"           => "1",
                        "origen"          =>  $request["origen"],
                        "telefono"        =>  $request["telefono"],
                        "id_user_asesora" =>  $request["id_user_asesora"],
                        "id_line"         =>  $request["id_line"],
                        "created_prp"     => date("Y-m-d")
                    );

                    Clients::find($value["id_cliente"])->update($update);
                    DB::table('auditoria')->where("cod_reg", $value["id_cliente"])
                            ->where("tabla", "clientes")
                            ->update(['fec_update' => date("Y-m-d H:i:s")]);

                    $id_client = $value["id_cliente"];



                    $comment = "<b>FECHA EN LA QUE TE OPERASTE CON NOSOTROS:</b> ".$request["fecha_opero"]."<br>";
                    $comment .= "<b>¿QUE CIRUGÍA TE PRACTICASTE?:</b> ".$request["surgeri"]."<br>";
                    $comment .= "<b>¿DESEAS QUE TE PROGRAMEMOS UNA CITA DE CONTROL?:</b> ".$request["radios"]."<br>";
                    $comment .= "<b>EL PAGO DE LA BONIFICACION PREFIERES QUE SEA:</b> ".$request["radiosPago"]."<br>";
                    $comment .= "<b>SI ELEGISTE PAGO POR TRANSFERENCIA:</b><br>";
                    $comment .= "<b>Nombre del Titular:</b> ".$request["name_titular"]."<br>";
                    $comment .= "<b>Numero de Cedula:</b> ".$request["cedula_titular"]."<br>";
                    $comment .= "<b>Número de Cuenta:</b> ".$request["cuenta_titular"]."<br>";
                    $comment .= "<b>¿TIENES ALGUNA SUGERENCIA PARA NUESTRO GRUPO?:</b> ".$request["sugrencias"]."<br>";

                    $data["table"]    = "clients";
                    $data["id_event"] = $id_client;
                    $data["id_user"]  = $users["id"];
                    $data["comment"] = $comment;

                    Comments::create($data);



                }

            }else{
                $cliente = Clients::create($request->all());

                $request["id_client"] = $cliente["id_cliente"];

                ClientInformationAditionalSurgery::create($request->all());
                ClientClinicHistory::create($request->all());
                ClientCreditInformation::create($request->all());

                $auditoria              = new Auditoria;
                $auditoria->tabla       = "clientes";
                $auditoria->cod_reg     = $cliente["id_cliente"];
                $auditoria->status      = 1;
                $auditoria->fec_regins  = date("Y-m-d H:i:s");
                $auditoria->fec_update  = date("Y-m-d H:i:s");
                $auditoria->usr_regins  = $users["id"];
                $auditoria->save();

                $id_client = $cliente["id_cliente"];



                $comment = "<b>FECHA EN LA QUE TE OPERASTE CON NOSOTROS:</b> ".$request["fecha_opero"]."<br>";
                $comment .= "<b>¿QUE CIRUGÍA TE PRACTICASTE?:</b> ".$request["surgeri"]."<br>";
                $comment .= "<b>¿DESEAS QUE TE PROGRAMEMOS UNA CITA DE CONTROL?:</b> ".$request["radios"]."<br>";
                $comment .= "<b>EL PAGO DE LA BONIFICACION PREFIERES QUE SEA:</b> ".$request["radiosPago"]."<br>";
                $comment .= "<b>SI ELEGISTE PAGO POR TRANSFERENCIA:</b><br>";
                $comment .= "<b>Nombre del Titular:</b> ".$request["name_titular"]."<br>";
                $comment .= "<b>Numero de Cedula:</b> ".$request["cedula_titular"]."<br>";
                $comment .= "<b>Número de Cuenta:</b> ".$request["cuenta_titular"]."<br>";
                $comment .= "<b>¿TIENES ALGUNA SUGERENCIA PARA NUESTRO GRUPO?:</b> ".$request["sugrencias"]."<br>";

                $data["table"]    = "clients";
                $data["id_event"] = $id_client;
                $data["id_user"]  = $users["id"];
                $data["comment"] = $comment;

                Comments::create($data);



                $User =  User::create([
                    "email"       => $request["email"],
                    "password"    => md5("123456789"),
                    "id_rol"      => 17,
                    "id_client"   => $id_client
                ]);



                $datos_personales                   = new datosPersonaesModel;
                $datos_personales->nombres          = $request["nombres"];
                $datos_personales->apellido_p       = "";
                $datos_personales->apellido_m       = "afasfa";
                $datos_personales->n_cedula         = "12412124";
                $datos_personales->fecha_nacimiento = null;
                $datos_personales->telefono         = null;
                $datos_personales->direccion        = null;
                $datos_personales->id_usuario       = $User->id;
                $datos_personales->save();




            }








            $notification["fecha"]    = date("Y-m-d");
            $notification["title"]    = "Hoy Ingreso de PRP ".$request["nombres"]." codigo: ".$request["code_client"];
            $notification["id_user"]  = $users["id"];
            $notification["id_event"] = $id_client;
            $notification["type"]     = "prp";

            Notification::insert($notification);




            if($request["id_line"] == 8){
                $request["name_line"] = "Cirugía Plástica Medellin CPEI";
            }

            if($request["id_line"] == 6){
                $request["name_line"] = "Cirufacil";
            }



            $subject = "Formulario PRP para ".$request["name_user"]." : ".$request["name_line"].": ".$request["nombres"];

            //$for = "cardenascarlos18@gmail.com";
            $for = $users["email"];
            $for2 = "cherrybomb.lu@gmail.com";
           // $for2 = "cardenascarlos18@gmail.com";
            $request["msg"]  = "..";

            Mail::send('emails.formsPrp',$request->all(), function($msj) use($subject,$for){
                $msj->from("crm@pdtagencia.com","CRM");
                $msj->subject($subject);
                $msj->to($for);
            });


            Mail::send('emails.formsPrp',$request->all(), function($msj) use($subject,$for2){
                $msj->from("crm@pdtagencia.com","CRM");
                $msj->subject($subject);
                $msj->to($for2);
            });

       }


       $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
       return response()->json($data)->setStatusCode(200);




    }







    public function changeName(){

        $data = Clients::get();

        foreach($data as $value){

           $new_nombre = $value["nombres"]." ".$value["apellidos"];

           $cliente = Clients::find($value["id_cliente"])->update(["nombres" => $new_nombre]);

           echo $new_nombre."<br><br>";

        }
    }

    public function GenerateCodes(){
        $data = Clients::get();

        foreach($data as $value){



            $permitted_chars        = '0123456789abcdefghijklmnopqrstuvwxyz';
            $code                   = substr(str_shuffle($permitted_chars), 0, 4);

           $cliente = Clients::find($value["id_cliente"])->update(["code_client" => strtoupper($code)]);

           echo $code."<br><br>";

        }



    }


    public function GetComments($id_client){

        $data = Comments::select('comments.*', 'users.email', 'users.img_profile', "datos_personales.nombres as name_user", "datos_personales.apellido_p as last_name_user")
                            ->where("id_event", $id_client)
                            ->join('users', 'users.id', '=', 'comments.id_user')
                            ->join('datos_personales', 'datos_personales.id_usuario', '=', 'comments.id_user')
                            ->where("table", "clients")
                            ->get();

        return response()->json($data)->setStatusCode(200);
    }


    public function UpdateHc(Request $request, $client){

        $request["children"]    = 1;
        $request["surgery"]    = 1;
        $request["disease"]    = 1;
        $request["medication"] = 1;
        $request["allergic"]   = 1;

        $alcohol = strtoupper($request["alcohol"]);
        $alcohol   == "SI" ? $request["alcohol"] = 1 : $request["alcohol"] = 0;


        $smoke = strtoupper($request["smoke"]);
        $smoke   == "SI" ? $request["smoke"] = 1 : $request["smoke"] = 0;



        ClientClinicHistory::find($client)->update($request->all());

        return response()->json("Ok")->setStatusCode(200);
    }




    public function UpdateHcByUserId(Request $request, $user_id){

        $request["children"]   = 1;
        $request["surgery"]    = 1;
        $request["disease"]    = 1;
        $request["medication"] = 1;
        $request["allergic"]   = 1;

        $alcohol = strtoupper($request["alcohol"]);
        $alcohol   == "SI" ? $request["alcohol"] = 1 : $request["alcohol"] = 0;


        $smoke = strtoupper($request["smoke"]);
        $smoke   == "SI" ? $request["smoke"] = 1 : $request["smoke"] = 0;

        $user = DB::table("users")->where("id", $user_id)->first();

        ClientClinicHistory::find($user->id_client)->update($request->all());

        return response()->json("Ok")->setStatusCode(200);
    }



    public function CreateUserPrp(){

        $where = array(
            "prp" => "Si"
        );

        $data = Clients::where($where)
                ->get();
        foreach($data as $client){

            $User              = new User;
            $User->email       = $client["email"];
            $User->password    = md5($client["code_client"]);
            $User->img_profile = null;
            $User->id_client   = $client["id_cliente"];
            $User->id_rol      = 17;

            echo json_encode($User)."<br><br>";


            $User->save();

            $datos_personales                   = new datosPersonaesModel;
            $datos_personales->nombres          = $client["nombres"];
            $datos_personales->apellido_p       = "";
            $datos_personales->apellido_m       = "";
            $datos_personales->n_cedula         = $client["identificacion"];;
            $datos_personales->fecha_nacimiento = $client["fecha_nacimiento"];
            $datos_personales->telefono         = $client["telefono"];
            $datos_personales->direccion        = $client["direccion"];
            $datos_personales->id_usuario       = $User->id;
            $datos_personales->save();


        }

    }


    public function getHc($id_client){

        $data = DB::table("client_clinic_history")->where("id_client", $id_client)->first();
        return response()->json($data)->setStatusCode(200);
    }









    public function GetTestimonials($client, $limit)
    {

        $client     = ClientInformationAditionalSurgery::where("id_client", $client)->first();
        $procedures = DB::table("clients_procedures")->where("id_client", $client->id_client)->get();
        $id_category     = $client->id_category;
        $id_sub_category = $client->id_sub_category;


        $sub_categorys = [];

        foreach($procedures as $procedure){
            $sub_categorys[] = $procedure->id_sub_category;
        }

       $data = GalleryImage::select("gallery_photos.*")
                              ->whereIn("gallery_photos.id_sub_category", $sub_categorys)
                              ->orderBy("gallery_photos.id", "DESC")
                              ->limit($limit)
                              ->get();

        if($data){
            $response = [
                "path_gallery" => "img/gallery/",
                "data"         => $data
            ];
            return response()->json($response)->setStatusCode(200);
        }else{
            return response()->json([])->setStatusCode(200);
        }

    }



    public function GetTasksAdvisers($id_client){

        $data = DB::table("clients_tasks_adsviser")->where("id_client", $id_client)->first();


        $satisfaction_survey = DB::table("satisfaction_survey")->where("id_client", $id_client)->first();

        if($satisfaction_survey){
            $data->survey = 1;
        }


        return response()->json($data)->setStatusCode(200);
    }




    public function ClientFormsESteticaVaginal(Request $request){
        $id_line =  $request["id_line"];
        $id_user =  $request["id_user"];

        $users = User::join("users_line_business", "users_line_business.id_user", "=", "users.id")
                        ->where("users_line_business.id_line", $request["id_line"])
                        ->where("users.queue", 0)
                        ->where("users.id", "!=", 106)

                        ->where(function ($query) use ($id_line) {
                            if($id_line == "8"){
                                $query->where("users.id", "!=", 75);
                            }
                        })


                        ->first();


       if($users){

            $client = Clients::where("identificacion", $request["identificacion"])->get();
            if((sizeof($client) > 0) && ($request["identificacion"] != "")){

                $data = array('mensagge' => "Ya te encuentras registrado en nuestra base de datos");
                return response()->json($data)->setStatusCode(200);

            }


            $request["id_user_asesora"] =  $users["id"];

            $permitted_chars        = '0123456789abcdefghijklmnopqrstuvwxyz';
            $code                   = substr(str_shuffle($permitted_chars), 0, 4);
            $request["code_client"] = strtoupper($code);


            $cliente = Clients::create($request->all());

            $request["id_client"] = $cliente["id_cliente"];





            $id_client = $cliente["id_cliente"];

            ClientInformationAditionalSurgery::create($request->all());
            ClientClinicHistory::create($request->all());
            ClientCreditInformation::create($request->all());

            $auditoria              = new Auditoria;
            $auditoria->tabla       = "clientes";
            $auditoria->cod_reg     = $cliente["id_cliente"];
            $auditoria->status      = 1;
            $auditoria->fec_regins  = date("Y-m-d H:i:s");
            $auditoria->fec_update  = date("Y-m-d H:i:s");
            $auditoria->usr_regins  = $users["id"];
            $auditoria->save();


            $update = User::find($users["id"]);
            $update->queue = 1;
            $update->save();


            $User =  User::create([
                "email"       => $request["email"],
                "password"    => md5("123456789"),
                "id_rol"      => 17,
                "id_client"   => $id_client
            ]);

            $datos_personales                   = new datosPersonaesModel;
            $datos_personales->nombres          = $request["nombres"];
            $datos_personales->apellido_p       = "";
            $datos_personales->apellido_m       = "afasfa";
            $datos_personales->n_cedula         = "12412124";
            $datos_personales->fecha_nacimiento = null;
            $datos_personales->telefono         = null;
            $datos_personales->direccion        = null;
            $datos_personales->id_usuario       = $User->id;
            $datos_personales->save();


            foreach($request["fotos"] as $value){
                DB::table("clients_fotos_estetica_vaginal")->insert(["id_client" => $id_client, "foto" => $value]);
            }

            $subject = "Formulario Web";

            //$for = "cardenascarlos18@gmail.com";
            $for = $users["email"];
         //   $for = "cardenascarlos18@gmail.com";

            $request["msg"]  = "Un Paciente a registrado un Formulario Web";

            Mail::send('emails.forms',$request->all(), function($msj) use($subject,$for){
                $msj->from("crm@pdtagencia.com","CRM");
                $msj->subject($subject);
                $msj->to($for);
            });



       }else{

           $users = User::join("users_line_business", "users_line_business.id_user", "=", "users.id")
                        ->where("users_line_business.id_line", $request["id_line"])
                        ->where("users.queue", 1)
                        ->update(["queue" => 0]);

            $this->ClientFormsESteticaVaginal($request);
       }


        $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
        return response()->json($data)->setStatusCode(200);
    }



    public function uploads(Request $request){

        if($file = $request->file('file_data')){

            $destinationPath = 'img/estetica_vaginal';
            $file->move($destinationPath,$file->getClientOriginalName());
        }

        return response()->json(1)->setStatusCode(200);
    }



    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Clients  $clients
     * @return \Illuminate\Http\Response
     */
    public function destroy(Clients $clients)
    {
        //
    }

    public function GetIdentification($id)
    {
        try {
            $data = Clients::select(
                'clientes.id_cliente',
                'clientes.nombres as nombre',
                'clientes.apellidos as apellido',
                'clientes.email',
                'valuations.cotizacion'
                )
            ->join('valuations','clientes.id_cliente','valuations.id_cliente')
            ->where('identificacion',$id)
            ->with('procedures')
            ->first();

            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function Identification($id)
    {
        // dd($id);
        try {
            $data = Clients::where('identificacion',$id)->first();

            return response()->json($data)->setStatusCode(200);
        } catch (\Throwable $th) {
            return $th;
        }
    }


    public function EditProfileApp(Request $request){

        $update_clients = Clients::find($request["id"])->update([
            "nombres"  => $request["nombres"],
            "telefono" => $request["telefono"],
            "email" => $request["email"],
        ]);

        $data = Clients::SelectRaw("id_cliente, nombres, telefono, email, identificacion")->where("id_cliente", $request["id"])->first();
        return response()->json($data)->setStatusCode(200);

    }

}
