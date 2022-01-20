<?php

namespace App\Http\Controllers;
use DB;
use App\Orders;
use App\OrdersDetail;
use Illuminate\Http\Request;

class OrdersController extends Controller
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


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */





    public function store(Request $request)
    {



        if (isset($request["paymentSupport"])) {
            $folder = "img/payment_support";

            $img      = str_replace('data:image/png;base64,', '', $request["paymentSupport"]);
            $fileData = base64_decode($img);
            $fileName = rand(0, 100000000) . '-comprobante.png';
            file_put_contents($folder . "/" . $fileName, $fileData);

            $request["payment_support"] = $fileName;
        }







        $store = Orders::create($request->all());

        $products = [];
        foreach($request["products"] as $value){
            $item["id_order"]    = $store->id;
            $item["id_product"]  = $value["id_product"];
            $item["qty"]         = $value["qty"];
            $item["price"]       = $value["price_cop"];
            $item["price_total"] = $value["price_cop"] * $value["qty"];
            $products[] = $item;
        }

        OrdersDetail::insert($products);

        $data = array('mensagge' => "Los datos fueron registrados satisfactoriamente");
        return response()->json($data)->setStatusCode(200);
    }













    /**
     * Display the specified resource.
     *
     * @param  \App\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function show($client)
    {
       $data = Orders::where("id_client", $client)->with("products")->get();
       return response()->json($data)->setStatusCode(200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function edit(Orders $orders)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Orders $orders)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Orders  $orders
     * @return \Illuminate\Http\Response
     */
    public function destroy(Orders $orders)
    {
        //
    }





    public function GetCodePRPPatienteCarStore($code){
        try{
            $data = DB::table('clientes')
            ->where('code_client', '=', $code)
            ->select('*')
            ->first();
            if($data){$res=[true, $data];}
            else{$res =[false, null];}
            return response()->json($res)->setStatusCode(200);
        }
        catch(\Throwable $th){
            return $th;
        }
    }





}
