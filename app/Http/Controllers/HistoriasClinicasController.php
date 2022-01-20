<?php

namespace App\Http\Controllers;

use App\HistoriaClinicaPreanestesia;
use App\HistoriaClinicaQuirurgica;
use App\HistoriaClinicaHistoria;
use App\HistoriaClinicaCunsultas;
use App\HistoriaClinicaIncapacidad;
use App\HistoriaClinicaRemision;
use App\HistoriaClinicaServicios;
use App\HistoriaClinicaMedicamentos;
use App\NotasEnfermeria;
use App\RegistrosAnestesicos;
use App\AnestesiaPremedicacion;
use App\AnestesiaMonitoria;
use App\AnestesiaIntraOperatorio;
use App\EnfermeriaClient;
use App\HistoriaClienteSedacion;
use App\SedacionAlegicos;
use App\SedacionFamiliares;
use App\SedacionPatologicos;
use App\SedacionQuirurgicas;
use App\SedacionToxicologicas;
use App\SedacionMonitorizacion;
use App\HistoriaClinicaPreoperatorio;
use App\PreoperatorioSistem;




use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HistoriasClinicasController extends Controller
{
   

    public function SaveFormPreanestesia(Request $request){
      

        HistoriaClinicaPreanestesia::updateOrCreate(
            ["id_client" => $request["id_client"]],
            $request->all()
        );

    }

    
      public function SaveFormQuirurgica(Request $request){
   
 
        HistoriaClinicaQuirurgica::updateOrCreate(
             ["id_client" => $request["id_client"]],
             $request->all()
         );
 
     }

     
     public function SaveFormhistoria(Request $request){
    
 
       $data_father = HistoriaClinicaHistoria::updateOrCreate(
             ["id_client" => $request["id_client"]],
             $request->all()
         ); 

         if(isset($request["consultas_data"])){

            HistoriaClinicaCunsultas::where("id_history_cliente_historia", $data_father->id)->delete();
            $consul_data = [];
            foreach($request["consultas_data"] as $key => $consulta){
               $consul_data["his_cons_consulta"]  = $consulta;
               $consul_data["his_cons_valor"]     = $request["valorconsult_data"][$key];
               $consul_data["his_cons_resultado"] = $request["resultadoconsult_data"][$key];
               $consul_data["id_history_cliente_historia"] = $data_father->id;
   
               HistoriaClinicaCunsultas::insert($consul_data);
            }
         }
        
         if(isset($request["incapacidad_data"])){
            HistoriaClinicaIncapacidad::where("id_history_cliente_historia", $data_father->id)->delete();
            $incapacidad_data = [];
            foreach($request["incapacidad_data"] as $key => $incapacidad){
               $incapacidad_data["his_inc_motivo"] = $incapacidad;
               $incapacidad_data["his_inc_tipo"]     = $request["his_inc_tipo"][$key];
               $incapacidad_data["his_inc_dias"] = $request["his_inc_dias"][$key];
               $incapacidad_data["his_inc_fecha"] = $request["his_inc_fecha"][$key];
               $incapacidad_data["id_history_cliente_historia"] = $data_father->id;
   
               HistoriaClinicaIncapacidad::insert($incapacidad_data);

   
            }
         }


         if(isset($request["remision_data"])){
            HistoriaClinicaRemision::where("id_history_cliente_historia", $data_father->id)->delete();
            $remision_data = [];
            foreach($request["remision_data"] as $key => $remision){
               $remision_data["his_rem_nombre"] = $remision;
               $remision_data["his_rem_remision"] = $request["his_rem_remision"][$key];
               $remision_data["his_rem_fecha"] = $request["his_rem_fecha"][$key];
               $remision_data["id_history_cliente_historia"] = $data_father->id;
   
               HistoriaClinicaRemision::insert($remision_data);

   
            }
         }


         if(isset($request["remision_data"])){
            HistoriaClinicaServicios::where("id_history_cliente_historia", $data_father->id)->delete();
            $servicios_data = [];
            foreach($request["remision_data"] as $key => $nombre){
               $servicios_data["his_ser_nombre"] = $nombre;
               $servicios_data["his_ser_observaciones"] = $request["his_ser_observaciones"][$key];
               $servicios_data["his_ser_cantidad"] = $request["his_ser_cantidad"][$key];
               $servicios_data["his_ser_fecha"] = $request["his_ser_fecha"][$key];
               $servicios_data["id_history_cliente_historia"] = $data_father->id;
   
               HistoriaClinicaServicios::insert($servicios_data);

   
            }
         }
        
         if(isset($request["his_med_nombre"])){
            HistoriaClinicaMedicamentos::where("id_history_cliente_historia", $data_father->id)->delete();
            $medicamento_data = [];
            foreach($request["his_med_nombre"] as $key => $medicamento){
               $medicamento_data["his_med_nombre"] = $medicamento;
               $medicamento_data["his_med_posologia"] = $request["his_med_posologia"][$key];
               $medicamento_data["his_med_cantidad"] = $request["his_med_cantidad"][$key];
               $medicamento_data["his_med_fecha"] = $request["his_med_fecha"][$key];
               $medicamento_data["id_history_cliente_historia"] = $data_father->id;
   
               HistoriaClinicaMedicamentos::insert($medicamento_data);
   
            }
         }


     }


   public function SaveFromNotas(Request $request){
 
      NotasEnfermeria::updateOrCreate(
         ["id_client" => $request["id_client"]],
         $request->all()
     );


   }

   
   public function SaveFromAnestesia(Request $request){
 
      $data_father = RegistrosAnestesicos::updateOrCreate(
         ["id_client" => $request["id_client"]],
         $request->all()
     ); 

      if(isset($request["ane_premedicacion"])){
         AnestesiaPremedicacion::where("id_history_cliente_historia", $data_father->id)->delete();
         $premed_anestesia = [];
         foreach($request["ane_premedicacion"] as $key => $medicamento){
            $premed_anestesia["ane_premedicacion"] = $medicamento;
            $premed_anestesia["id_history_cliente_anestesico"] = $data_father->id;

            AnestesiaPremedicacion::insert($premed_anestesia);

         }
      }

      if(isset($request["mon_monitoria"])){
         AnestesiaMonitoria::where("id_history_cliente_historia", $data_father->id)->delete();
         $monitoria_anestesia = [];
         foreach($request["mon_monitoria"] as $key => $monitoria){
            $monitoria_anestesia["mon_monitoria"] = $monitoria;
            $monitoria_anestesia["id_history_cliente_anestesico"] = $data_father->id;

            AnestesiaMonitoria::insert($monitoria_anestesia);

         }
      }

      if(isset($request["int_numero"])){
         AnestesiaIntraOperatorio::where("id_history_cliente_historia", $data_father->id)->delete();
         $intraoperatotio_data = [];
         foreach($request["int_numero"] as $key => $intraoperatorio){
            $intraoperatotio_data["int_numero"] = $intraoperatorio;
            $intraoperatotio_data["int_descripcion"] =  $request["int_descripcion"][$key];
            $intraoperatotio_data["id_history_cliente_anestesico"] = $data_father->id;

            AnestesiaIntraOperatorio::insert($intraoperatotio_data);

         }
      }
      
   }

   public function SaveFromEnfermeria(Request $request){
 
      $data_father =  EnfermeriaClient::updateOrCreate(
         ["id_client" => $request["id_client"]],
         $request->all()
     );


   }

      public function SaveFromSedacion(Request $request){
 
         $data_father = HistoriaClienteSedacion::updateOrCreate(
            ["id_client" => $request["id_client"]],
            $request->all()
        ); 

        if(isset($request["aler_item"])){
         SedacionAlegicos::where("id_history_cliente_historia", $data_father->id)->delete();
         $sedacion_data = [];
         foreach($request["aler_item"] as $key => $sedacion){
            $sedacion_data["aler_item"] = $sedacion;
            $sedacion_data["aler_observacion"] =  $request["aler_observacion"][$key];
            $sedacion_data["id_history_cliente_sedacion"] = $data_father->id;

            SedacionAlegicos::insert($sedacion_data);

            }
         }

         if(isset($request["fam_item"])){
            SedacionFamiliares::where("id_history_cliente_historia", $data_father->id)->delete();
            $monitoria_familiares = [];
            foreach($request["fam_item"] as $key => $monitoria){
               $monitoria_familiares["fam_item"] = $monitoria;
               $monitoria_familiares["fam_observacion"] =  $request["fam_observacion"][$key];
               $monitoria_familiares["id_history_cliente_sedacion"] = $data_father->id;
   
               SedacionFamiliares::insert($monitoria_familiares);
   
            }
         }


         if(isset($request["pat_item"])){
            SedacionPatologicos::where("id_history_cliente_historia", $data_father->id)->delete();
            $patologicos_data = [];
            foreach($request["pat_item"] as $key => $patologico){
               $patologicos_data["pat_item"] = $patologico;
               $patologicos_data["pat_observacion"] =  $request["pat_observacion"][$key];
               $patologicos_data["id_history_cliente_sedacion"] = $data_father->id;
   
               SedacionPatologicos::insert($patologicos_data);
   
            }
         }

         if(isset($request["qui_item"])){
            SedacionQuirurgicas::where("id_history_cliente_historia", $data_father->id)->delete();
            $quirurgica_data = [];
            foreach($request["qui_item"] as $key => $quirurgicas){
               $quirurgica_data["qui_item"] = $quirurgicas;
               $quirurgica_data["qui_observacion"] =  $request["qui_observacion"][$key];
               $quirurgica_data["id_history_cliente_sedacion"] = $data_father->id;
   
               SedacionQuirurgicas::insert($quirurgica_data);
   
            }
         }
         

         if(isset($request["tox_item"])){
            SedacionToxicologicas::where("id_history_cliente_historia", $data_father->id)->delete();
            $toxicologicos_data = [];
            foreach($request["tox_item"] as $key => $toxicologico){
               $toxicologicos_data["tox_item"] = $toxicologico;
               $toxicologicos_data["tox_observacion"] =  $request["tox_observacion"][$key];
               $toxicologicos_data["id_history_cliente_sedacion"] = $data_father->id;
   
               SedacionToxicologicas::insert($toxicologicos_data);
   
            }
         }


         if(isset($request["incapacidad_data"])){
            SedacionMonitorizacion::where("id_history_cliente_historia", $data_father->id)->delete();
            $monitorizacion_data = [];
            foreach($request["incapacidad_data"] as $key => $monitoreo){
               $monitorizacion_data["mon_tiempo"] = $monitoreo;
               $monitorizacion_data["mon_farmaco"] = $request["mon_farmaco"][$key];
               $monitorizacion_data["mon_dosis"] = $request["mon_dosis"][$key];
               $monitorizacion_data["mon_ta"] = $request["mon_ta"][$key];
               $monitorizacion_data["mon_fc"] = $request["mon_fc"][$key];
               $monitorizacion_data["mon_sat"] = $request["mon_sat"][$key];
               $monitorizacion_data["mon_ramsay"] =  $request["mon_ramsay"][$key];
               $monitorizacion_data["id_history_cliente_sedacion"] = $data_father->id;
   
               SedacionMonitorizacion::insert($monitorizacion_data);
   
            }
         }


      }
      
      public function SaveFromPreoperatorio(Request $request){
 
         $data_father = HistoriaClinicaPreoperatorio::updateOrCreate(
            ["id_client" => $request["id_client"]],
            $request->all()
        );
        
        if(isset($request["sis_nombre"])){
         PreoperatorioSistem::where("id_history_cliente_historia", $data_father->id)->delete();
         $sistema_data = [];
         foreach($request["sis_nombre"] as $key => $toxicologico){
            $sistema_data["sis_nombre"] = $toxicologico;
            $sistema_data["sis_hallazgo"] =  $request["sis_hallazgo"][$key];
            $sistema_data["id_history_cliente_preoperatorio"] = $data_father->id;

            PreoperatorioSistem::insert($sistema_data);

            }
         }
      }


public function getFormPreanestesia($id_client){
   $data = HistoriaClinicaPreanestesia::where("id_client", $id_client)->first();
   return response()->json($data)->setStatusCode(200);
}

public function getFormQuirurgica($id_client){
   $data = HistoriaClinicaQuirurgica::where("id_client", $id_client)->first();
      return response()->json($data)->setStatusCode(200);
}

public function getFormhistroia($id_client){
   $data = HistoriaClinicaHistoria::where("id_client", $id_client)
                                 ->with("consultas")
                                 ->with("medicamentos")
                                 ->with("servicios")
                                 ->with("Remision")
                                 ->with("incapacidad")
                                 ->first();
      return response()->json($data)->setStatusCode(200);
}

public function getFormNotas($id_client){
   $data = NotasEnfermeria::where("id_client", $id_client)
                                 ->first();
      return response()->json($data)->setStatusCode(200);
}

public function getFormRegistros($id_client){
   $data = RegistrosAnestesicos::where("id_client", $id_client)
                                 ->with("pre_medicacion")
                                 ->with("monitoria")
                                 ->with("operatorio")
                                 ->first();
      return response()->json($data)->setStatusCode(200);
}

public function getFormEnfermeria($id_client){
   $data = EnfermeriaClient::where("id_client", $id_client)->first();
   return response()->json($data)->setStatusCode(200);
}

public function getFormSedacion($id_client){
   $data = HistoriaClienteSedacion::where("id_client", $id_client)
                                 ->with("consulta")
                                 ->with("familiares")
                                 ->with("patologico")
                                 ->with("quirurgicos")
                                 ->with("monitoria")
                                 ->with("toxicologico")
                                 ->first();
   return response()->json($data)->setStatusCode(200);
}

public function getFormPreoperatorio($id_client){
   $data = HistoriaClinicaPreoperatorio::where("id_client", $id_client)
                                 ->with("operatorio")
                                 ->first();
   return response()->json($data)->setStatusCode(200);
}


}
