<?php

namespace App\Http\Controllers;
use App\Models\Viaje;

use Illuminate\Http\Request;

class ViajeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $data = Viaje::all();
            return response()->json([
                'Estado' => 'Ok',
                'Data' => $data,
            ]);
        } catch (\Exception $e) {
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
        try {
            $xml = simplexml_load_string($request->getContent());

            $json = json_encode($xml);

            $array = json_decode($json,TRUE);
            $viaje = new Viaje;
            $viaje->fecha_viaje = $array['fecha_viaje'];
            $viaje->pais = $array['pais'];
            $viaje->ciudad = $array['ciudad'];
            $viaje->email = $array['email'];
            //Guardamos el cambio en nuestro modelo
            $viaje->save();
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
        try {
            $data = Viaje::find($id);
            return response()->json([
                'Estado' => 'Ok',
                'Data' => $data,
            ]);
        } catch (\Exception $e) {
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
