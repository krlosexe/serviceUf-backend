<?php

namespace App\Http\Controllers;

use DB;
use Mail;
use App\User;
use App\Modulos;
use App\Clients;
use App\funciones;
use App\AuthUsers;
use App\AuthUsersApp;
use App\AuthUserAppLaserAdviser;
use App\AuthUserAppFinancing;
use App\LogsSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Login extends Controller
{
    public function Auth(request $request)
    {
    	$messages = [
		    'required' => 'El Campo :attribute es requirdo.',
		];


    	$validator = Validator::make($request->all(), [
            'email'    => 'required',
            'password' => 'required',
        ], $messages);


        if ($validator->fails()) {
            return response()->json($validator->errors())->setStatusCode(400);
        }else{

            $users = User::join("auditoria", "auditoria.cod_reg", "=", "users.id")
                        ->join("datos_personales", "datos_personales.id_usuario", "users.id")
                         ->where("email", $request["email"])
                         ->where("password", md5($request["password"]))
                         ->where("auditoria.tabla", "users")
                         ->where("auditoria.status", "!=", "0")
	    				 ->get();

	    	if (sizeof($users) > 0) {
	    		$token = bin2hex(random_bytes(64));


	    		$token_user  = AuthUsers::where("id_user", $users[0]->id)->get();

	    		foreach ($token_user as $key => $value) {
					$value->delete();
	    		}

	    		$AuthUsers          = new AuthUsers;
		        $AuthUsers->id_user = $users[0]->id;
		        $AuthUsers->token   = $token;
                $AuthUsers->save();



                LogsSession::create(["id_user" => $users[0]->id, "date_login" => date("Y-m-d G:i:s")]);


	    		$data = array('user_id'  => $users[0]->id,
                              'email'    => $users[0]->email,
                              'rol'      => $users[0]->id_rol,
                              'nombres'  => $users[0]->nombres." ".$users[0]->apellido_p,
	    					  'token'    => $token,
	    					  'mensagge' => "Ha iniciado sesion exitosamente"
	    		);

	    		return response()->json($data)->setStatusCode(200);
	    	}else{
	    		return response()->json("Usuario o contrasena invalida")->setStatusCode(400);
	    	}
        }


    }




    public function AuthApp(request $request)
    {

        if($request["email"] == "" || $request["password"] == ""){
            return response()->json("El Email y Contraseña son Requeridos")->setStatusCode(400);
        }

        $users = Clients::where("email", $request["email"])
                         ->where("password", md5($request["password"]))
	    				 ->get();

        if (sizeof($users) > 0) {
            
            Clients::where("id", $users[0]->id)
	    				 ->update(["fcmToken" => $request["fcmToken"]]);

            //$data = array($users[0]);

            return response()->json($users[0])->setStatusCode(200);
        }else{
            return response()->json("Usuario o contrasena invalida")->setStatusCode(400);
	    }
    }












    public function VerifyToken(request $request)
    {

    	$AuthUsers = AuthUsers::where("token", $request["token"])
                                ->where("id_user", $request["user_id"])
                                ->get();

        if (sizeof($AuthUsers) > 0) {

            $modulos = Modulos::join("auditoria", "auditoria.cod_reg", "=", "modulos.id_modulo")
                                ->where("auditoria.tabla", "modulos")
                                ->where("auditoria.status", 1)
                                ->orderBy("posicion", "asc")
                                ->get();


            $funciones = User::select("users.*", "rol_operaciones.*", "funciones.*")
                                ->join("roles", "roles.id_rol", "=", "users.id_rol")
                                ->join("rol_operaciones", "rol_operaciones.id_rol", "=", "roles.id_rol")
                                ->join("funciones", "funciones.id_funciones", "=", "rol_operaciones.id_funciones")
                                ->join("auditoria", "auditoria.cod_reg", "=", "roles.id_rol")

                                ->where("id", $request["user_id"])
                                ->where("auditoria.tabla", "roles")
                                ->where("funciones.visibilidad", 1)

                                ->where(function($q) {
                                  $q->orWhere('rol_operaciones.general', 1)
                                    ->orWhere('rol_operaciones.detallada', 1)
                                    ->orWhere('rol_operaciones.registrar', 1)
                                    ->orWhere('rol_operaciones.actualizar', 1)
                                    ->orWhere('rol_operaciones.eliminar', 1);
                                })

                                ->orderBy("funciones.posicion", "asc")
                                ->get();


                $data_user = User::select("users.*", "roles.nombre_rol", "datos_personales.*")
                                      ->join('datos_personales', 'datos_personales.id_usuario', '=', 'users.id')
                                      ->join("roles", "roles.id_rol", "=", "users.id_rol")
                                      ->where("id", $request["user_id"])
                                      ->get();


            $modulos_disponibles = $this->control($modulos, $funciones);

        	$data = array('mensagge'            => "ok",
                          'data'                => $AuthUsers[0],
                          'data_user'           => $data_user[0],
                          'modulos_disponibles' => $modulos_disponibles,
                          'funciones'           => $funciones
                    );

    		return response()->json($data)->setStatusCode(200);

        }else{
        	$data = array('mensagge' => "error");
    		return response()->json($data)->setStatusCode(500);
        }
    }



    public function control($modulos, $funciones)
    {
        $data = array();
        foreach ($modulos as $modulo) {
            foreach ($funciones as $vista) {
                if($modulo->id_modulo == $vista->id_modulo){
                    $data["modulo_user"][] = $modulo->id_modulo;
                }
            }
        }

        if (isset($data)) {
            $ids = array_unique($data['modulo_user']);
            foreach ($ids as $value) {

                $data['modulos_enconctrados'][] = $this->modulosbyid($value);
            }

            $oneDim = array();
            foreach($data['modulos_enconctrados'] as $i) {
              $oneDim[] = $i[0];
            }


             return $data['modulos_vistas'] = $oneDim;
        }

    }


    public function RecoveryAccount(Request $request){

        $data = DB::table("clientes")
                    ->where("email", $request["email"])
                    ->first();
        if($data){

            $token = md5($data->id);
            DB::table("clientes")->where("email", $request["email"])->update(["token_recovery" => $token]);
            $mensaje = "Bienvenido, ingresa al siguiente enlace para restablecer tu contraseña : https://serviceuf.pdtcomunicaciones.com/recovery/account/".$token;

            $info_email = [
                "email" => $data->email,
                "issue"   => "Recuperar Contraseña",
                "mensage" => $mensaje,
            ];

            $this->SendEmail($info_email);
            return response()->json("ok")->setStatusCode(200);

        }else{
            return response()->json("La cuenta no existe")->setStatusCode(400);
        }

        
    }



    public function SendEmail($data){

        $subject = $data["issue"];
        //$for = "cardenascarlos18@gmail.com";
        $for = $data["email"];
        $request["msg"] = $data["mensage"];

        Mail::send('emails.notification',$request, function($msj) use($subject,$for){
            $msj->from("admin@pdtcomunicaciones.com","ServiUf");
            $msj->subject($subject);
            $msj->to($for);
        });

        return true;

    }






    public function modulosbyid($id)
    {
        $modulos = Modulos::join("auditoria", "auditoria.cod_reg", "=", "modulos.id_modulo")
                                ->where("auditoria.tabla", "modulos")
                                ->where("auditoria.status", 1)
                                ->where("modulos.id_modulo", $id)
                                ->orderBy("posicion", "asc")
                                ->get();
        return $modulos;
    }




    public function VerifyPermiso(Request $request)
    {

        $users = User::where("id", $request["user_id"])
                       ->join("roles", "roles.id_rol", "=", "users.id_rol")
                       ->join("rol_operaciones", "rol_operaciones.id_rol", "=", "roles.id_rol")
                       ->join("funciones", "funciones.id_funciones", "=", "rol_operaciones.id_funciones")
                            ->where("funciones.route", $request["route"])
                        ->get();


        return response()->json($users[0])->setStatusCode(200);
    }



    public function GenerateCode(Request $request){

        $client = DB::table("clientes")->where("code_client", $request["code"])->where("prp", "Si")->first();
        if($client){

           // $code = rand(100000,900000);

            //DB::table("clientes")->where("code_client", $request["code"])->update(["code_verify" => $code]);

            $data = [
               //"issue"   => "Código de Acceso Multiestica $code",
              // "message" => "Hola, $client->nombres tu código de acceso a Multiestica es $code",
              // "email"   => $client->email,
               "id_line" => $client->id_line
            ];

            //$this->SendEmail2($data);

            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("error")->setStatusCode(400);
        }

    }


    public function CreateCode(Request $request){

        $client = DB::table("clientes")->where("code_client", $request["code"])->first();

      //  dd($client->code_verify);
        if($client){


            if($client->code_verify != null || $client->code_verify != ""){
                $code = $client->code_verify;
            }else{
                $code = rand(100000,900000);
                DB::table("clientes")->where("code_client", $request["code"])->update(["code_verify" => $code]);
            }

            $data = [
               "issue"   => "Código de Acceso Multiestica $code",
               "message" => "Hola, $client->nombres tu código de acceso a Multiestica es $code",
               "email"   => $client->email,
               "phone"   => str_replace("+57", "", $client->telefono),
               "code"    => $code,
               "id_line" => $client->id_line
            ];
            $this->SendEmail2($data);
            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("error")->setStatusCode(400);
        }
    }



    public function TestTwilo(){
        dd("HOLA");
    }

    public function GenerateCodeAdviser(Request $request){


        $user = DB::table("users")
                    ->where("code_user", $request["code"])
                    ->join("datos_personales", "datos_personales.id_usuario", "=", "users.id")
                    ->join("users_line_business", "users_line_business.id_user", "=", "users.id")
                    ->first();

        if($user){
            $code = rand(100000,900000);
            DB::table("users")->where("code_user", $request["code"])->update(["code_verify" => $code]);

            $data = [
               "issue"   => "Código de Acceso Multiestica $code",
               "message" => "Hola, $user->nombres tu código de acceso a Multiestica es $code",
               "email"   => $user->email,
               "id_line" => $user->id_line
            ];

            $this->SendEmail2($data);

            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("error")->setStatusCode(400);
        }

    }



    public function ChangePassword(Request $request){
        if($request["password"] !=  $request["repeat_password"]){
            return response()->json("Las contraseñas no coinciden")->setStatusCode(400);
        }else{

            DB::table("clientes")->where("id", $request["id_client"])->update(["password" => md5($request["password"])]);

            return response()->json("La contraseña se cambio exitosamente")->setStatusCode(200);
        }
    }



    public function VerifyCode(Request $request){

        $client = DB::table("clientes")->where("code_client", $request["code"])->where("code_verify", $request["code_verify"])->first();
        if($client){

            $token_user  = AuthUsersApp::where("id_user", $client->id_cliente)->get();

            foreach ($token_user as $key => $value) {
                $value->delete();
            }


            DB::table("clientes")->where("id_cliente", $client->id_cliente)->update(["auth_app" => 1]);

            $AuthUsers                       = new AuthUsersApp;
            $AuthUsers->id_user              = $client->id_cliente;
            $AuthUsers->token                = "123";
            $AuthUsers->token_notifications  = $request["fcmToken"];
            $AuthUsers->save();


            $data = array('email'      => $client->email,
                          'nombres'    => $client->nombres,
                          'avatar'     => null,
                          'token'      => "124",
                          'sync_token' => "14242",
                          'mensagge'   => "Ha iniciado sesion exitosamente",
                          "type_user"  => "Afiliado",
                          "line"       => $client->id_line,
                          "code_client" => $client->code_client,
                          "id_client"  => $client->id_cliente
            );

            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("error")->setStatusCode(400);
        }

    }



    public function VerifyCodeAdviser(Request $request){

        $client = DB::table("users")
                    ->join("datos_personales", "datos_personales.id_usuario", "=", "users.id")
                    ->join("users_line_business", "users_line_business.id_user", "=", "users.id")
                    ->where("code_user", $request["code"])
                    ->where("code_verify", $request["code_verify"])

                    ->first();
        if($client){
            $token_user  = AuthUsersApp::where("id_user", $client->id)->get();
            foreach ($token_user as $key => $value) {
                $value->delete();
            }

            $AuthUsers                       = new AuthUsersApp;
            $AuthUsers->id_user              = $client->id;
            $AuthUsers->token                = "123";
            $AuthUsers->token_notifications  = $request["fcmToken"];
            $AuthUsers->save();


            $data = array('email'       => $client->email,
                          'nombres'     => $client->nombres." ".$client->apellido_p,
                          'avatar'      => $client->img_profile,
                          'token'       => "124",
                          'sync_token'  => "14242",
                          'mensagge'    => "Ha iniciado sesion exitosamente",
                          "type_user"   => "Asesor",
                          "line"        => $client->id_line,
                          "code_client" => $client->code_user,
                          "user_id"     => $client->id
            );

            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("error")->setStatusCode(400);
        }

    }




    public function SendEmail2($data){

        $subject = $data["issue"];
        $for = $data["email"];
        $request["msg"] = $data["message"];
        Mail::send('emails.notification',$request, function($msj) use($subject,$for){
            $msj->from("crm@pdtagencia.com","CRM");
            $msj->subject($subject);
            $msj->to($for);
        });
        return true;
    }



    public function Logout($user_id)
    {

    	$token_user = AuthUsers::where("id_user", $user_id)->get();

		foreach ($token_user as $key => $value) {
			$value->delete();
        }


        LogsSession::create(["id_user" => $user_id, "date_logout" => date("Y-m-d G:i:s")]);




		return redirect(url('/'));

    }





}
