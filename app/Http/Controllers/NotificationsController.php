<?php

namespace App\Http\Controllers;

use DB;
use Mail;
use App\User;
use App\Tasks;
use App\Clients;
use App\Queries;
use App\Valuations;
use App\Notification;
use App\Preanesthesia;
use App\Surgeries;
use App\AppointmentsAgenda;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{


    public function Get(request $request){

        $data = Notification::where("id_user", $request["id_user"])
                            ->where("view", "No")
                            ->orderBy("id_notifications", "desc")
                            ->get();
        echo json_encode($data);
    }


    public function Tasks(){

        $data_today    = Tasks::where("fecha", date("Y-m-d"))->get();
        $data_tomorrow = Tasks::whereRaw("fecha = DATE_FORMAT(date_add(NOW(), INTERVAL 1 DAY),'%Y-%m-%d')")->get();
        $data_thre_day = Tasks::whereRaw("fecha = DATE_FORMAT(date_add(NOW(), INTERVAL 3 DAY),'%Y-%m-%d')")->get();


        $this->SaveNotificationTasks($data_today, 1);
        $this->SaveNotificationTasks($data_tomorrow, 2);
        $this->SaveNotificationTasks($data_thre_day, 3);

    }


    public function SaveNotificationTasks($data, $day){

        $notification = [];

        if($day == 1){ $moment = "Hoy"; }
        if($day == 2){ $moment = "Mañana"; }
        if($day == 3){ $moment = "3 dias para"; }

        foreach($data as $key => $value){
            $array["fecha"]    = $value["fecha"];
            $array["title"]    = $moment." Tarea #".$value["id_tasks"].": ".$value["issue"];
            $array["id_user"]  = $value["responsable"];
            $array["id_event"] = $value["id_tasks"];
            $array["type"]     = "task";
            array_push($notification, $array);
        }

        Notification::insert($notification);

    }




    public function Queries(){


        $data_today    = Queries::where("fecha", date("Y-m-d"))
                                  ->join("clientes", "clientes.id_cliente", "=", "queries.id_cliente")
                                  ->get();

        $data_tomorrow = Queries::whereRaw("fecha = DATE_FORMAT(date_add(NOW(), INTERVAL 1 DAY),'%Y-%m-%d')")
                                  ->join("clientes", "clientes.id_cliente", "=", "queries.id_cliente")
                                  ->get();

        $data_thre_day = Queries::whereRaw("fecha = DATE_FORMAT(date_add(NOW(), INTERVAL 3 DAY),'%Y-%m-%d')")
                                  ->join("clientes", "clientes.id_cliente", "=", "queries.id_cliente")
                                  ->get();


        $this->SaveNotificationQueries($data_today, 1);
        $this->SaveNotificationQueries($data_tomorrow, 2);
        $this->SaveNotificationQueries($data_thre_day, 3);

    }




    public function SaveNotificationQueries($data, $day){

        $notification = [];

        if($day == 1){ $moment = "Hoy"; }
        if($day == 2){ $moment = "Mañana"; }
        if($day == 3){ $moment = "3 dias para"; }

        foreach($data as $key => $value){
            $array["fecha"]    = $value["fecha"];
            $array["title"]    = $moment." Consulta con Paciente ".$value["nombres"]." ".$value["apellidos"];
            $array["id_user"]  = $value["id_user_asesora"];
            $array["id_event"] = $value["id_queries"];
            $array["type"]     = "queries";
            array_push($notification, $array);
        }

        Notification::insert($notification);

    }




    public function Valuations(){


        $data_today    = Valuations::where("fecha", date("Y-m-d"))
                                    ->join("clientes", "clientes.id_cliente", "=", "valuations.id_cliente")
                                    ->get();

        $data_tomorrow = Valuations::whereRaw("fecha = DATE_FORMAT(date_add(NOW(), INTERVAL 1 DAY),'%Y-%m-%d')")
                                    ->join("clientes", "clientes.id_cliente", "=", "valuations.id_cliente")
                                    ->get();

        $data_thre_day = Valuations::whereRaw("fecha = DATE_FORMAT(date_add(NOW(), INTERVAL 3 DAY),'%Y-%m-%d')")
                                    ->join("clientes", "clientes.id_cliente", "=", "valuations.id_cliente")
                                    ->get();


        $this->SaveNotificationValuations($data_today, 1);
        $this->SaveNotificationValuations($data_tomorrow, 2);
        $this->SaveNotificationValuations($data_thre_day, 3);

    }

    public function SaveNotificationValuations($data, $day){

        $notification = [];

        if($day == 1){ $moment = "Hoy"; }
        if($day == 2){ $moment = "Mañana"; }
        if($day == 3){ $moment = "3 dias para"; }

        foreach($data as $key => $value){
            $array["fecha"]    = $value["fecha"];
            $array["title"]    = $moment." VLR con Paciente ".$value["nombres"]." ".$value["apellidos"];
            $array["id_user"]  = $value["id_user_asesora"];
            $array["id_event"] = $value["id_valuations"];
            $array["type"]     = "valuations";
            array_push($notification, $array);
        }

        Notification::insert($notification);

    }





    public function PreAnestisia(){


        $data_today    = Preanesthesia::where("fecha", date("Y-m-d"))
                                       ->join("clientes", "clientes.id_cliente", "=", "preanesthesias.id_cliente")
                                       ->get();

        $data_tomorrow = Preanesthesia::whereRaw("fecha = DATE_FORMAT(date_add(NOW(), INTERVAL 1 DAY),'%Y-%m-%d')")
                                    ->join("clientes", "clientes.id_cliente", "=", "preanesthesias.id_cliente")
                                    ->get();

        $data_thre_day = Preanesthesia::whereRaw("fecha = DATE_FORMAT(date_add(NOW(), INTERVAL 3 DAY),'%Y-%m-%d')")
                                    ->join("clientes", "clientes.id_cliente", "=", "preanesthesias.id_cliente")
                                    ->get();


       $this->SaveNotificationPreAnestesia($data_today, 1);
       $this->SaveNotificationPreAnestesia($data_tomorrow, 2);
       $this->SaveNotificationPreAnestesia($data_thre_day, 3);

    }

    public function SaveNotificationPreAnestesia($data, $day){

        $notification = [];

        if($day == 1){ $moment = "Hoy"; }
        if($day == 2){ $moment = "Mañana"; }
        if($day == 3){ $moment = "3 dias para"; }

        foreach($data as $key => $value){
            $array["fecha"]    = $value["fecha"];
            $array["title"]    = $moment." Pre Anestesia con Paciente ".$value["nombres"]." ".$value["apellidos"];
            $array["id_user"]  = $value["id_user_asesora"];
            $array["id_event"] = $value["id_preanesthesias"];
            $array["type"]     = "preanesthesias";
            array_push($notification, $array);
        }

        Notification::insert($notification);

    }









    public function Surgeries(){


        $data_today    = Surgeries::where("fecha", date("Y-m-d"))
                                    ->join("clientes", "clientes.id_cliente", "=", "surgeries.id_cliente")
                                    ->get();

        $data_tomorrow = Surgeries::whereRaw("fecha = DATE_FORMAT(date_add(NOW(), INTERVAL 1 DAY),'%Y-%m-%d')")
                                    ->join("clientes", "clientes.id_cliente", "=", "surgeries.id_cliente")
                                    ->get();

        $data_thre_day = Surgeries::whereRaw("fecha = DATE_FORMAT(date_add(NOW(), INTERVAL 3 DAY),'%Y-%m-%d')")
                                    ->join("clientes", "clientes.id_cliente", "=", "surgeries.id_cliente")
                                    ->get();

        $this->SaveNotificationSurgeries($data_today, 1);
        $this->SaveNotificationSurgeries($data_tomorrow, 2);
        $this->SaveNotificationSurgeries($data_thre_day, 3);

    }

    public function SaveNotificationSurgeries($data, $day){

        $notification = [];

        if($day == 1){ $moment = "Hoy"; }
        if($day == 2){ $moment = "Mañana"; }
        if($day == 3){ $moment = "3 dias para"; }

        foreach($data as $key => $value){
            $array["fecha"]    = $value["fecha"];
            $array["title"]    = $moment." CX con Paciente ".$value["nombres"]." ".$value["apellidos"];
            $array["id_user"]  = $value["id_user_asesora"];
            $array["id_event"] = $value["id_surgeries"];
            $array["type"]     = "surgeries";
            array_push($notification, $array);
        }

        Notification::insert($notification);

    }




    public function Revision(){

        $data_today  = AppointmentsAgenda::where("fecha", date("Y-m-d"))
                                        ->join("revision_appointment", "revision_appointment.id_revision", "=", "appointments_agenda.id_revision")
                                        ->join("clientes", "clientes.id_cliente", "=", "revision_appointment.id_paciente")
                                        ->get();


        $data_tomorrow  = AppointmentsAgenda::whereRaw("fecha = DATE_FORMAT(date_add(NOW(), INTERVAL 1 DAY),'%Y-%m-%d')")
                                            ->join("revision_appointment", "revision_appointment.id_revision", "=", "appointments_agenda.id_revision")
                                            ->join("clientes", "clientes.id_cliente", "=", "revision_appointment.id_paciente")
                                            ->get();


        $data_thre_day  = AppointmentsAgenda::whereRaw("fecha = DATE_FORMAT(date_add(NOW(), INTERVAL 3 DAY),'%Y-%m-%d')")
                                            ->join("revision_appointment", "revision_appointment.id_revision", "=", "appointments_agenda.id_revision")
                                            ->join("clientes", "clientes.id_cliente", "=", "revision_appointment.id_paciente")
                                            ->get();

        $this->SaveNotificationRevision($data_today, 1);
        $this->SaveNotificationRevision($data_tomorrow, 2);
        $this->SaveNotificationRevision($data_thre_day, 3);

    }


    public function SaveNotificationRevision($data, $day){

        $notification = [];

        if($day == 1){ $moment = "Hoy"; }
        if($day == 2){ $moment = "Mañana"; }
        if($day == 3){ $moment = "3 dias para"; }

        foreach($data as $key => $value){
            $array["fecha"]    = $value["fecha"];
            $array["title"]    = $moment." Revision con Paciente ".$value["nombres"]." ".$value["apellidos"];
            $array["id_user"]  = $value["id_user_asesora"];
            $array["id_event"] = $value["id_revision"];
            $array["type"]     = "revision";
            array_push($notification, $array);
        }

        Notification::insert($notification);

    }



    public function Generate(){
        $this->Tasks();
        $this->Valuations();
        $this->PreAnestisia();
        $this->Surgeries();
        $this->Revision();

        echo "SI";
    }

    public function Read(request $request){

        Notification::where("id_user", $request["id_user"])->update(["view" => "Si"]);

        $data = array('mensagge' => "Exito");

        return response()->json($data)->setStatusCode(200);

    }




    public function Email(Request $request){

        $user = User::find($request["user_id"]);
        $subject = "Formulario Web";
        //$for = "cardenascarlos18@gmail.com";
        $for = $user["email"];

        $request["msg"]  = "Un Paciente a registrado un Formulario Web";

        Mail::send('emails.forms_authorizatio',$request->all(), function($msj) use($subject,$for){
            $msj->from("crm@pdtagencia.com","CRM");
            $msj->subject($subject);
            $msj->to($for);
        });

        return true;

    }




    public function NotificationsPost(Request $request){



        $users = Clients::selectRaw("clientes.*")
                        //  ->join("auth_users_app", "auth_users_app.id_user", "=", "clientes.id_cliente")
                          ->whereIn("clientes.id_line", $request["lines"])
                          ->get();



        if($request["lines"][0] == 17){
            $token_fcm = "AAAAg-p1HsU:APA91bHJHYE__7tBgvxXHPbMwR2cm7-KyYOknyMz7fAfBYm34YrFMF9QK4FieAEPL54o7EPXilihGevzxoBSf3X4CCHAswTk9NctvFTYY1ftYTYI5hj_-qXVFtCizHHzM060Ojphq62q";
        }
        if($request["lines"][0] == 3){
            $token_fcm = "AAAADwEmOL8:APA91bGN_99QtWkpjpvLTjByVISmGtM7qZSgvZNixW1o7d1udxNp_hHI8n6Tn-ukuGgmnLAIABuWYo-Kdea253E-jE1tCVBLQmRikVBbdxy2Th-j64BAr80U9FeCpr3gGmPW66W58ZYF";
        }
        if($request["lines"][0] == 16){
            $token_fcm = "AAAA5qKc4M8:APA91bG3q9BAo323Bje7_eIs8sGa-G37pRr67n4IgYK8xnoYHsSOx397JPecFVVaWCHfHjcqiaTFOjaZPbEtzXbzakZ2kwWqP_2x9RT3_Z883-lKpbRRgALZSq5MXK51Cb-W6Db5xAQu";
        }


        if($request["lines"][0] == 2){
            $token_fcm = "AAAAJdN9JHM:APA91bEXsAKoXgwiQEDZUeqyxlLnEjyxvIP7rKKOQVV7s0bu5IfzZmPJ6E-iCCjtjUuRa70Xb_IXE8yPBU0crw4U_fPMfLIiP6l-sSohylin9-Jspst0qqBwe6jqP4qQsXkFsU__W5wx";
        }
        if($request["lines"][0] == 18){
            $token_fcm = "AAAAJdN9JHM:APA91bEXsAKoXgwiQEDZUeqyxlLnEjyxvIP7rKKOQVV7s0bu5IfzZmPJ6E-iCCjtjUuRa70Xb_IXE8yPBU0crw4U_fPMfLIiP6l-sSohylin9-Jspst0qqBwe6jqP4qQsXkFsU__W5wx";
        }
        if($request["lines"][0] == 22){
            $token_fcm = "AAAAJdN9JHM:APA91bEXsAKoXgwiQEDZUeqyxlLnEjyxvIP7rKKOQVV7s0bu5IfzZmPJ6E-iCCjtjUuRa70Xb_IXE8yPBU0crw4U_fPMfLIiP6l-sSohylin9-Jspst0qqBwe6jqP4qQsXkFsU__W5wx";
        }

        $tokens = [];
        foreach($users as $user){

            if($user->fcmToken != "" || $user->fcmToken != null){
                $tokens[] = $user->fcmToken;
            }

        }

        $url = "https://fcm.googleapis.com/fcm/send";
        //$token = $client2->fcmToken;
        $serverKey = $token_fcm;
        $title = "Contenido para Publicar";
        $body = "Se ha publicado un nuevo Post";
        $data = ['type' => 'post'];
        $notification = array('title' => $title, 'body' => $body, 'sound' => 'default', 'badge' => '1', "data" => $data);
        $arrayToSend = array('registration_ids' => $tokens, 'notification' => $notification, 'priority' => 'high', "data" => $data);
        $json = json_encode($arrayToSend);
        $headers = array();
        $headers[] = 'Content-Type: application/json';
        $headers[] = 'Authorization: key=' . $serverKey;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //Send the request
        $response = curl_exec($ch);



        return response()->json(true)->setStatusCode(200);
    }




    public function EmailsMasivos(){

        $data = DB::table("clientes")
                    ->join("client_information_aditional_surgery", "client_information_aditional_surgery.id_client", "=", "clientes.id_cliente", "left")


                    ->where("clientes.id_line", 17)
                    ->where("clientes.email", "!=", "")
                    ->whereRaw("clientes.email is not null")
                    ->where("clientes.send_email", "=", 0)

                    ->where(function ($query){
                        $query->OrWhereRaw('client_information_aditional_surgery.name_surgery like "%mamo%"');
                        $query->OrWhereRaw('client_information_aditional_surgery.name_surgery like "%pexia%"');
                        $query->OrWhereRaw('client_information_aditional_surgery.name_surgery like "%abdomino%"');
                        $query->OrWhereRaw('client_information_aditional_surgery.name_surgery like "%gluteo%"');
                    })

                    ->limit(10)
                    ->get();


        //return response()->json($data)->setStatusCode(200);

        foreach($data as $value){
            $info_email = [
                "id_cliente" => $value->id_cliente,
                "issue"      => "¡El tratamiento ideal para mejorar TUS CICATRICES!",
                "name"       => $value->nombres,
                "for"        => $value->email
            ];

            $this->SendEmail2($info_email);
        }



       return response()->json(sizeof($data))->setStatusCode(200);

    }


    public function SendEmail2($data){

       // $user = User::find($data["user_id"]);
        $subject = $data["issue"];

       // $for = "cardenascarlos18@gmail.com";
        $for = $data["for"];
        $mail_send = Mail::send('emails.masivos',["nombre" =>  $data["name"]], function($msj) use($subject,$for){
            $msj->from("crm@pdtagencia.com","Cirujano Daniel Correa");
            $msj->subject($subject);
            $msj->to($for);
        });

        DB::table("clientes")->where("id_cliente", $data["id_cliente"])->update(["send_email" => 1]);

        return true;

    }




    public function EmailsMasivosTest(){

        $info_email = [
            "id_cliente" => 1,
            "issue"      => "¡El tratamiento ideal para mejorar TUS CICATRICES!",
            "name"       => "carlos cardenas",
            "for"        => "cardenascarlos18@gmail.com"
        ];
        $this->SendEmail2($info_email);


       return response()->json(sizeof([]))->setStatusCode(200);

    }




}
