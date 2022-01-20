<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;


use App\ClientsTasks;
use App\Notification;
use App\User;

use Mail;


class NotificationsTasksLaser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'registered:NotificationsTasksLaser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notificar tareas vencidas de la linea del Laser';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line("Some text");

        $data = ClientsTasks::where("fecha", "<", date("Y-m-d"))
                    ->where("status_task", "Abierta")
                    ->join("clientes", "clientes.id_cliente", "=", "clients_tasks.id_client")
                    ->join("auditoria", "auditoria.cod_reg","clients_tasks.id_clients_tasks")
                    ->where("clientes.id_line", 9)
                    ->where("auditoria.tabla","clients_tasks")
                    ->where("auditoria.status", 1)
                    ->get();


        foreach($data as $task){
            $this->comment("Tarea ".$task->id_clients_tasks." esta vencida: ".$task->issue." Responsable: ". $task->responsable);

            $notification             = [];
            $notification["fecha"]    = $task->fecha;
            $notification["title"]    = "Tarea ".$task->id_clients_tasks." esta vencida: ".$task->issue;
            $notification["id_user"]  = $task->responsable;
            $notification["id_event"] = $task->id_clients_tasks;
            $notification["type"]     = "task";

            Notification::insert($notification);


            $info_email = [
                "user_id" => $task->responsable,
                "issue"   => $notification["title"],
                "mensage" => $notification["title"],
            ];
           // $this->SendEmail($info_email);

        }
    }


    // public function SendEmail($data){

    //     $user = User::find($data["user_id"]);
    //     $subject = $data["issue"];

    //     //$for = "cardenascarlos18@gmail.com";
    //     $for = $user["email"];

    //     $request["msg"] = $data["mensage"];

    //     Mail::send('emails.notification',$request, function($msj) use($subject,$for){
    //         $msj->from("comercial@pdtagencia.com","CRM");
    //         $msj->subject($subject);
    //         $msj->to($for);
    //     });

    //     return true;

    // }



}
