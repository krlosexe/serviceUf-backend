<?php

namespace App\Http\Controllers;
use DB;
use Mail;
use App\User;
use App\Auditoria;
use App\datosPersonaesModel;
use App\UsersLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class UsuariosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($this->VerifyLogin($request["id_user"],$request["token"])) {
            $User = User::select("users.*", "datos_personales.*", "roles.nombre_rol", "auditoria.status", "auditoria.fec_regins", "user_registro.email as user_registro")
                          ->join('datos_personales', 'datos_personales.id_usuario', '=', 'users.id')
                          ->join("auditoria", "auditoria.cod_reg", "=", "users.id")
                          ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                          ->join("roles", "roles.id_rol", "=", "users.id_rol")
                          ->where("auditoria.tabla", "users")

                          ->with("lines")

                          ->where("auditoria.status", "!=", "0")
                          ->orderBy("users.id", "desc")
                          ->get();

            return response()->json($User)->setStatusCode(200);
        }else{
            return response()->json("No esta autorizado")->setStatusCode(400);
        }
    }



    public function GetAsesoras(Request $request)
    {

        if ($this->VerifyLogin($request["id_user"],$request["token"])) {
            $User = User::select("users.*", "datos_personales.*", "roles.nombre_rol", "auditoria.status", "auditoria.fec_regins", "user_registro.email as user_registro")
                          ->join('datos_personales', 'datos_personales.id_usuario', '=', 'users.id')
                          ->join("auditoria", "auditoria.cod_reg", "=", "users.id")
                          ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                          ->join("roles", "roles.id_rol", "=", "users.id_rol")
                       //   ->where("roles.nombre_rol", "Asesor")
                          ->where("auditoria.tabla", "users")
                          ->where("auditoria.status", "!=", "0")
                          ->orderBy("users.id", "desc")
                          ->get();

            return response()->json($User)->setStatusCode(200);
        }else{
            return response()->json("No esta autorizado")->setStatusCode(400);
        }
    }



    public function GetAsesorasByBusinessLine($id_line, Request $request)
    {

        $User = User::select("users.*", "datos_personales.*", "roles.nombre_rol", "auditoria.status", "auditoria.fec_regins", "user_registro.email as user_registro")
                        ->join('datos_personales', 'datos_personales.id_usuario', '=', 'users.id')
                        ->join("auditoria", "auditoria.cod_reg", "=", "users.id")
                        ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                        ->join("roles", "roles.id_rol", "=", "users.id_rol")

                        ->join('users_line_business', 'users_line_business.id_user', '=', 'users.id')

                        ->where("users_line_business.id_line", $id_line)

                        // ->where("roles.nombre_rol", "Asesor")
                        ->where("auditoria.tabla", "users")
                        ->where("auditoria.status", "!=", "0")
                        // ->where("users.id_line", "=", $id_line)
                        ->orderBy("users.id", "desc")
                        ->get();

        return response()->json($User)->setStatusCode(200);

    }



    public function RecoveryAccount(Request $request){


        $data = DB::table("clientes")
                    ->where("identificacion", $request["id"])
                    ->first();

        if($data){

            DB::table("clientes")
                    ->where("identificacion", $request["id"])
                    ->update(["password" => md5(123456789)]);

            $mensaje = "Bienvenido, tus datos de acceso son: ".$data->email." clave: ".$data->code_client;


            $info_email = [
                "user_id"   => $data->id_cliente,
                "subject"   => "Recuperar Contraseña",
                "msg"       => $mensaje,
                "for"       => $data->email,
            ];

            $this->SendEmail($info_email);

            $data = array('mensagge' => "Los datos fueron actualizados satisfactoriamente");
            return response()->json($data)->setStatusCode(200);
        }else{
            $data = array('mensagge' => "Error");
            return response()->json($data)->setStatusCode(400);
        }


    }



    public function SendEmail($data){

        $request["msg"]  = $data["msg"];
        $subject         = $data["subject"];
        $for             = $data["for"];
        Mail::send('emails.notification',$request, function($msj) use($subject,$for){
            $msj->from("crm@pdtagencia.com","CRM");
            $msj->subject($subject);
            $msj->to($for);
        });

        $data = array('mensagge' => "Los datos fueron actualizados satisfactoriamente");
        return response()->json($data)->setStatusCode(200);

    }


    public function GetAsesorasByBusinessLineArray(Request $request)
    {


        $business_line = 0;
        if(isset($request["array_line"])){
            $business_line = $request["array_line"];
        }



        $User = User::select("users.*", "datos_personales.*", "roles.nombre_rol", "auditoria.status", "auditoria.fec_regins", "user_registro.email as user_registro")
                        ->join('datos_personales', 'datos_personales.id_usuario', '=', 'users.id')
                        ->join("auditoria", "auditoria.cod_reg", "=", "users.id")
                        ->join("users as user_registro", "user_registro.id", "=", "auditoria.usr_regins")
                        ->join("roles", "roles.id_rol", "=", "users.id_rol")

                        ->join('users_line_business', 'users_line_business.id_user', '=', 'users.id')

                        //->where("users_line_business.id_line", $id_line)

                        ->where(function ($query) use ($business_line) {
                            if($business_line != 0){
                                $query->whereIn("users_line_business.id_line", $business_line);
                            }
                        })


                        // ->where("roles.nombre_rol", "Asesor")
                        ->where("auditoria.tabla", "users")
                        ->where("auditoria.status", "!=", "0")
                        // ->where("users.id_line", "=", $id_line)

                        ->orderBy("users.id", "desc")


                        ->get();

        return response()->json($User)->setStatusCode(200);

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
         //

            $id_rol = $request["rol"];

    if ($id_rol == '22'){
        $id_clients = DB::table("clientes")->where("identificacion", $request["n_cedula"])->first();

        if ($id_clients != NULL){
       /*    $ext_mail = DB::table("clientes")->where("email", $request["email"])->first();

        if ($ext_mail!= NULL) {*/

        if ($this->VerifyLogin($request["id_user"],$request["token"])){
            $messages = [
                'required' => 'El Campo :attribute es requirdo.',
                'unique'   => 'El Campo :attribute ya se encuentra en uso.'
            ];


            $validator = Validator::make($request->all(), [
                'img-profile'     => 'required',
                'email'           => 'required',
                'password'        => 'required',
                'repeat-password' => 'required',
                'rol'             => 'required'

            ], $messages);


            if ($request["password"] != $request["repeat-password"]) {
                return response()->json("Las contrasenas no coinciden")->setStatusCode(400);
            }



            if ($validator->fails()) {
                return response()->json($validator->errors())->setStatusCode(400);

            }else{



                $file = $request->file('img-profile');

                $destinationPath = 'img/usuarios/profile';
                $file->move($destinationPath,$file->getClientOriginalName());
                $request["img_profile"] = " asdasdasd asdas"; //add request

                $datos = User::where('code_user',$request->code_user)->exists();
                if($datos){
                    return ['mensaje'=>'el codigo que intenta ingresar ya esxiste'];
                }else{
                    $User              = new User;
                    $User->email       = $request["email"];
                    $User->password    = md5($request["password"]);
                    $User->img_profile = $file->getClientOriginalName();
                    $User->id_rol      = $request["rol"];
                    if ($User->id_rol == '22'){
                        $User->id_client      = $id_clients->id_cliente;
                    }


                   // $auditoria->fec_regins  = date("Y-m-d H:i:s");
                    $User->id_line     = $request["id_line"];
                    $User->code_user = $request->code_user;
                    $User->save();
                }

                $datos_personales                   = new datosPersonaesModel;
                $datos_personales->nombres          = $request["nombres"];
                $datos_personales->apellido_p       = $request["apellido_p"];
                $datos_personales->apellido_m       = $request["apellido_m"];
                $datos_personales->n_cedula         = $request["n_cedula"];
                $datos_personales->fecha_nacimiento = $request["fecha_nacimiento"];
                $datos_personales->telefono         = $request["telefono"];
                $datos_personales->direccion        = $request["direccion"];
                $datos_personales->id_usuario       = $User->id;
                $datos_personales->save();


                $auditoria              = new Auditoria;
                $auditoria->tabla       = "users";
                $auditoria->cod_reg     = $User->id;
                $auditoria->status      = 1;
                $auditoria->usr_regins  = $request["id_user"];
                $auditoria->save();


                foreach($request["id_lines"] as $key => $value){
                    $request["id_line"] = $value;
                    $request["id_user"] = $User->id;
                    UsersLine::create($request->all());
                }

                if ($User) {
                    $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
                    return response()->json($data)->setStatusCode(200);
                }else{
                    return response()->json("A ocurrido un error")->setStatusCode(400);
                }
            }
        }else{
            return response()->json("No esta autorizado")->setStatusCode(400);
        }




        }else{
            return response()->json("El cliente no se encuentra registrado")->setStatusCode(400);
        }

    }else{


        if ($this->VerifyLogin($request["id_user"],$request["token"])){
            $messages = [
                'required' => 'El Campo :attribute es requirdo.',
                'unique'   => 'El Campo :attribute ya se encuentra en uso.'
            ];


            $validator = Validator::make($request->all(), [
                'img-profile'     => 'required',
                'email'           => 'required|unique:users',
                'password'        => 'required',
                'repeat-password' => 'required',
                'rol'             => 'required'

            ], $messages);


            if ($request["password"] != $request["repeat-password"]) {
                return response()->json("Las contrasenas no coinciden")->setStatusCode(400);
            }



            if ($validator->fails()) {
                return response()->json($validator->errors())->setStatusCode(400);

            }else{



                $file = $request->file('img-profile');

                $destinationPath = 'img/usuarios/profile';
                $file->move($destinationPath,$file->getClientOriginalName());
                $request["img_profile"] = " asdasdasd asdas"; //add request

                $datos = User::where('code_user',$request->code_user)->exists();

                if($datos){
                    return ['mensaje'=>'el codigo que intenta ingresar ya esxiste'];
                }else{
                    $User              = new User;
                    $User->email       = $request["email"];
                    $User->password    = md5($request["password"]);
                    $User->img_profile = $file->getClientOriginalName();
                    $User->id_rol      = $request["rol"];
                    if ($User->id_rol == '22'){
                        $User->id_client      = $id_clients->id_cliente;
                    }


                   // $auditoria->fec_regins  = date("Y-m-d H:i:s");
                    $User->id_line     = $request["id_line"];
                    $User->code_user = $request->code_user;
                    $User->save();
                }

                $datos_personales                   = new datosPersonaesModel;
                $datos_personales->nombres          = $request["nombres"];
                $datos_personales->apellido_p       = $request["apellido_p"];
                $datos_personales->apellido_m       = $request["apellido_m"];
                $datos_personales->n_cedula         = $request["n_cedula"];
                $datos_personales->fecha_nacimiento = $request["fecha_nacimiento"];
                $datos_personales->telefono         = $request["telefono"];
                $datos_personales->direccion        = $request["direccion"];
                $datos_personales->id_usuario       = $User->id;
                $datos_personales->save();


                $auditoria              = new Auditoria;
                $auditoria->tabla       = "users";
                $auditoria->cod_reg     = $User->id;
                $auditoria->status      = 1;
                $auditoria->usr_regins  = $request["id_user"];
                $auditoria->save();


                foreach($request["id_lines"] as $key => $value){
                    $request["id_line"] = $value;
                    $request["id_user"] = $User->id;
                    UsersLine::create($request->all());
                }

                if ($User) {
                    $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
                    return response()->json($data)->setStatusCode(200);
                }else{
                    return response()->json("A ocurrido un error")->setStatusCode(400);
                }
            }
        }else{
            return response()->json("No esta autorizado")->setStatusCode(400);
        }
    }
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        echo "STRING";
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());
        if ($this->VerifyLogin($request["id_user"],$request["token"])){

            $messages = [
                'required' => 'El Campo :attribute es requirdo.',
                'unique'   => 'El Campo :attribute ya se encuentra en uso.'
            ];


            $validator = Validator::make($request->all(), [
                //'email'           => 'required|unique:users',
                'rol'             => 'required'

            ], $messages);


            if($request["password"] != "" || $request["repeat-password"] != ""){

                if ($request["password"] != $request["repeat-password"]) {
                    return response()->json("Las contrasenas no coinciden")->setStatusCode(400);
                }

            }


            if ($validator->fails()) {
                return response()->json($validator->errors())->setStatusCode(400);
            }else{

               $file = $request->file('img-profile');

               $datos = User::where('code_user',$request->code_user)->where('id','!=',$id)->exists();
               if($datos){
                   return ['mensaje'=>'el codigo que intenta ingresar ya esxiste'];
               }else{
                   $User = User::find($id);

                   $User->email   = $request["email"];
                   $User->id_rol  = $request["rol"];
                   $User->id_line = $request["id_line"];

                   if($file != null){
                       $destinationPath = 'img/usuarios/profile';
                       $file->move($destinationPath,$file->getClientOriginalName());
                       $User->img_profile = $file->getClientOriginalName();
                   }

                   if($request["password"] != "" && $request["repeat-password"] != ""){
                       $User->password = md5($request["password"]);
                   }

                   $User->code_user = $request->code_user;
                   $User->update();
               }




                $datos_personales = datosPersonaesModel::where("id_usuario", $id)->first();


                $datos_personales->nombres          = $request["nombres"];
                $datos_personales->apellido_p       = $request["apellido_p"];
                $datos_personales->apellido_m       = $request["apellido_m"];
                $datos_personales->n_cedula         = $request["n_cedula"];
                $datos_personales->fecha_nacimiento = $request["fecha_nacimiento"];
                $datos_personales->telefono         = $request["telefono"];
                $datos_personales->direccion        = $request["direccion"];

                $datos_personales->save();


                UsersLine::where("id_user", $id)->delete();

                foreach($request["id_lines"] as $key => $value){
                    $request["id_line"] = $value;
                    $request["id_user"] = $id;
                    UsersLine::create($request->all());
                }


                $data = array('mensagge' => "Los datos fueron actualizados satisfactoriamente");
                return response()->json($data)->setStatusCode(200);

            }

        }else{
            return response()->json("No esta autorizado")->setStatusCode(400);
        }

    }


    public function statusUser($id_user, $status, Request $request)
    {
        if ($this->VerifyLogin($request["id_user"],$request["token"])){
            $auditoria =  Auditoria::where("cod_reg", $id_user)
                                     ->where("tabla", "users")->first();

            $auditoria->status = $status;

            if($status == 0){
                $auditoria->usr_regmod = $request["id_user"];
                $auditoria->fec_regmod = date("Y-m-d");
            }

            $auditoria->save();

            $data = array('mensagge' => "Los datos fueron actualizados satisfactoriamente");
            return response()->json($data)->setStatusCode(200);
        }else{
            return response()->json("No esta autorizado")->setStatusCode(400);
        }
    }


    public function GetCodeAdviser($code){

        $users = User::join("users_line_business", "users_line_business.id_user", "=", "users.id")
                        ->join("datos_personales", "datos_personales.id_usuario", "=", "users.id")
                        ->where("users.code_user", "=", $code)
                        ->first();

        if($users){
            return response()->json($users)->setStatusCode(200);
        }else{
            return response()->json("El codigo es incorrecto")->setStatusCode(400);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
