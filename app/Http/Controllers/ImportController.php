<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;


use App\{
    Clients,
    ClientRequestCredit,
    Auditoria,
    ClientCreditInformation,
    ClientInformationAditionalSurgery,
    Tasks,
    ClientClinicHistory,
    ClientRequestCreditPaymentPlan,
};
use DateTime;
class ImportController extends Controller
{
    public function clients()
    {
        ini_set("default_charset", "UTF-8");
        $fila = 1;

        $data = [];
        if (($gestor = fopen("datos.csv", "r")) !== FALSE) {
            while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {

                $numero = count($datos);
                    echo "<p> $numero de campos en la línea $fila: <br /></p>\n";

                if($fila != 1){

                    $permitted_chars        = '0123456789abcdefghijklmnopqrstuvwxyz';
                    $code                   = substr(str_shuffle($permitted_chars), 0, 4);

                    $city = 3;
                    // if($datos[12] == "Cali"){
                    //     $city = 5;
                    // }

                    // if($datos[12] == "Medellin"){
                    //     $city = 3;
                    // }


                    // if($datos[12] == "Bogota"){
                    //     $city = 4;
                    // }

                  //  $date=date_create($datos[3]);

                    // using date_format() function to format date
                    //$datos[3] = date_format($date, "Y-m-d");


                    $row = array(
                        "nombres" => $datos[1],
                        "apellidos" => "",
                        "identificacion" =>  "",
                        "identificacion_verify" => 0,
                        //"fecha_nacimiento" => $datos[3] != "" ? $datos[3] : null,
                       // "city" => 3 ,
                        //"clinic" => 6,
                        "telefono" => $datos[3],
                        "email" => $datos[2],
                        "id_line" => 7,
                        "id_user_asesora" => 69,
                        //"direccion" => isset($datos[12]) ? $datos[12] : null,
                        "origen"    => "Formulario",
                        "state"     => "No Contactada",
                        "code_client" => strtoupper($code),
                        "to_db" => 1
                    );


                $data[] = $row;
                $cliente = Clients::create($row);

                $auditoria              = new Auditoria;
                $auditoria->tabla       = "clientes";
                $auditoria->cod_reg     = $cliente["id_cliente"];
                $auditoria->status      = 1;
                $auditoria->fec_regins  = date("2021-07-27 16:23:59");
                $auditoria->fec_update  = date("2021-07-27 16:23:59");
                $auditoria->usr_regins  = 69;
                $auditoria->save();


                    $row = array(
                        "id_client"    => $cliente["id_cliente"],
                        "name_surgery" => isset($datos[32]) ? $datos[32] : null
                    );



                    ClientInformationAditionalSurgery::create($row);
                    ClientClinicHistory::create($row);
                    ClientCreditInformation::create($row);
                }


                $fila++;


            }
           echo json_encode($data);

            fclose($gestor);
        }

    }









    public function Calendar(){
        ini_set("default_charset", "UTF-8");
        $fila = 1;

        $data = [];
        if (($gestor = fopen("data.ics", "r")) !== FALSE) {
            while (($datos = fgetcsv($gestor, 1000, "|")) !== FALSE) {
                $numero = count($datos);
                //echo "<p> $numero de campos en la línea $fila: <br /></p>\n";
                $fila++;

                // $row = array(
                //     "nombres" => $datos[3],
                //     "apellidos" => $datos[4],
                //     "identificacion" =>  isset($datos[57]) ? $datos[57] : null,
                //     "identificacion_verify" => 0,
                //     "fecha_nacimiento" => $datos[6] != "" ? $datos[6] : null,
                //     "city" => 3,
                //     "clinic" => null,
                //     "telefono" => $datos[11],
                //     "email" => isset($datos[25]) ? $datos[25] : null,
                //     "id_line" => 11,
                //     "id_user_asesora" => 69,
                //     "direccion" => isset($datos[66]) ? $datos[66] : null,
                //     "state"     =>isset($datos[63]) ? $datos[63] != "" ? $datos[63] : null : null,
                // );

                // $data[] = $row;
                echo json_encode($datos)."<br><br>";
            }


            fclose($gestor);
        }

    }

    public function ImportCredits()
    {
        ini_set("default_charset", "UTF-8");
        $fila = 1;

        $data = [];
        if (($gestor = fopen("credits.csv", "r")) !== FALSE) {
            while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {

                $numero         = count($datos);
                $cedula         = $datos[1];
                $monto_credito  = str_replace(",", "", $datos[4]);
                $periodo        = $datos[2];
                $monto_cuota    = str_replace(",", "", $datos[3]);
                $date           = $datos[5];

                $client = Clients::where("identificacion", trim($cedula))->first();

                $datetime = $date;
               // dd($this->validateDate('2012-02-30', 'Y-m-d'));
                $d = DateTime::createFromFormat("d/m/Y", $datetime);
                $date = $d->format("Y-m-d"); // or any you want

                $date = date("Y-m-d", strtotime($date));

                if($client){
                         echo $client["id_cliente"];
                        $head = new ClientRequestCredit;
                        $head->id_client          = $client["id_cliente"];
                        $head->required_amount    = $monto_credito;
                        $head->period             = $periodo;
                        $head->monthly_fee	      = $monto_cuota;
                        $head->status             = "Desembolsado";
                        $head->save();


                        $date = explode("-", $date);
                        $day_cobro = $date[2];
                        $date_new= $date[0]."-".$date[1]."-".$day_cobro;

                    for ($i=1; $i <= $periodo; $i++) {

                        $items = new ClientRequestCreditPaymentPlan;
                        $items->id_request_credit  = $head->id;
                        $items->number             = $i;
                        $items->monthly_fees	       = $monto_cuota;
                        $items->balance	           = 0;
                        $items->monthly_fees	   = $monto_cuota;
                        $items->date	           = $date_new;
                        $items->status             = "Pendiente";
                        $items->save();

                        $date = explode("-", $date_new);

                        if($date[1] <= 8){
                            $mes = "0".($date[1] + 1);
                        }else{
                           $mes =  $date[1] + 1;
                        }
                        $year = $date[0];
                        if($mes == 13){
                            $mes = "01";
                            $year = $date[0] + 1;
                        }
                        $date_new = $year."-".$mes."-".$day_cobro;
                        $date_new = $this->newFecha($date_new);
                       // dd($this->newFecha($date_new));
                        //$date_new = date("Y-m-d", strtotime($date . "+ 1 month"));
                    }

                    dd("Carlos");
                }
                echo "<br>";
                $fila++;

                $row = array(
                    "responsable"   => 69,
                   // "issue"         => $datos[1]

                );
            }
          // echo json_encode($datos);
           fclose($gestor);
        }

    }



    public function newFecha($date){
        echo $date;
        if($this->validateDate($date, 'Y-m-d')){
            return $date;
        }else{

            $date = explode("-", $date);
            $date_new= $date[0]."-".$date[1]."-".($date[2] - 1);
            return $this->newFecha($date_new);
        }
    }


    public function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }




    public function ImportCreditsFaltantaes()
    {
        ini_set("default_charset", "UTF-8");
        $fila = 1;

        $data = [];
        if (($gestor = fopen("credits.csv", "r")) !== FALSE) {
            while (($datos = fgetcsv($gestor, 1000, ",")) !== FALSE) {

               // dd($datos);
                $cedula         = $datos[1];
                $client = Clients::where("identificacion", trim($cedula))->first();
                if(!$client){
                   echo  $cedula;
                   echo "<br>";
                }

            }
          // echo json_encode($datos);
           fclose($gestor);
        }

    }








    public function ImportTasks()
    {
        ini_set("default_charset", "UTF-8");
        $fila = 1;

        $data = [];
        if (($gestor = fopen("tasks.csv", "r")) !== FALSE) {
            while (($datos = fgetcsv($gestor, 1000, ";")) !== FALSE) {
                $numero = count($datos);
                //echo "<p> $numero de campos en la línea $fila: <br /></p>\n";
                $fila++;

                $row = array(
                    "responsable"   => 69,
                    "issue"         => $datos[1],
                    "fecha"         => $datos[2],
                    "time"          => "18:00:00",
                    "status_task"   => $datos[5],
                    "observaciones" => $datos[16]

                );

                $data[] = $row;
                $store = Tasks::create($row);

                $auditoria              = new Auditoria;
                $auditoria->tabla       = "tasks";
                $auditoria->cod_reg     = $store["id_tasks"];
                $auditoria->status      = 1;
                $auditoria->usr_regins  = 69;
                $auditoria->save();

            }
            echo "asd";
           echo json_encode($data);

            fclose($gestor);
        }

    }


}
