<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter');
        $key = $request->get('key');
        try{
            switch ($filter) {
                case 1:
                    $data = Cliente::where('email', 'like', '%' . $key . '%')->paginate(5);
                    break;
                case 2:
                    $data = Cliente::whereMonth('created_at' , $key)->paginate();
                    break;
                default:
                    $data = Cliente::paginate(5);
                    break;
            }
            return response()->json([
                'Estado' => 'Ok',
                'Data' => $data,
            ]);
         }
         catch(\Exception $e){
            return response()->json([
                'Estado' => 'Error',
                'Mensaje' => $e->getMessage(),
            ]);
         }
    }

    public function listar($filtro)
    {
        try{
            $data = Cliente::paginate(5);

            return response()->json([
                'Estado' => 'Ok',
                'Data' => $data,
            ]);
         }
         catch(\Exception $e){
            return response()->json([
                'Estado' => 'Error',
                'Mensaje' => $e->getMessage(),
            ]);
         }
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
        try{
            $cliente = new Cliente;
            $cliente->nombre = $request->nombre;
            $cliente->telefono = $request->telefono;
            $cliente->email = $request->email;
            $cliente->direccion = $request->direccion;
            $cliente->foto = $request->foto;
            //Guardamos el cambio en nuestro modelo
            $cliente->save();
            return response()->json([
                'Estado' => 'Ok',
                'Mensaje' => 'El cliente se guardo correctamente.',
            ]);
         }
         catch(\Exception $e){
            return response()->json([
                'Estado' => 'Error',
                'Mensaje' => $e->getMessage(),
            ]);
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
        try{
            $data = Cliente::where('id', $id)->get();

            return response()->json([
                'Estado' => 'Ok',
                'Data' => $data,
            ]);
         }
         catch(\Exception $e){
            return response()->json([
                'Estado' => 'Error',
                'Mensaje' => $e->getMessage(),
            ]);
         }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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
