<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Cliente;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
            /**
             * para los filtros se puede trabajar con el request_has para validar que filtros estás recibiendo del cliente
             * esto facilita el trabajar con varios filtros a la vez
             * puedes ir agregando querys a la consulta las veces que quieras hasta que agregues el metodo paginate,get,first,etc
             * esto permite ir anexando los filtros uno por uno sin que se ejecute el query automaticamente
             */
            switch ($filter) {
                case 1:
                    $data = Cliente::where('email', 'like', '%' . $key . '%')->paginate(5);
                    break;
                case 2:
                    $data = Cliente::whereMonth('created_at' , $key)->paginate();
                    break;
                default:
                    $data = Cliente::with('viajes')->paginate(5); // el with te permite anexar la relación que se encuentra en el modelo de cliente
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
        /**
         * si se debe de realizar una api rest, en la creación de un modelo
         * lo más recomendable es recibir un objeto json y no form params en el request.
         * para el caso de la imagen, se puede agregar como base 64 o agregarla como form params
         */

         /**
          * NOTA IMPORTANTE: siempre debes de tener en cuenta el codigo http cuando se está trabajando con Api
          * esto permite una validación mas sencilla en el cliente
          */
        try{
            $validator = Validator::make($request->all(), [
                'nombre' => 'required',
                'telefono' => 'required',
                'email' => 'required|email|unique:clientes',
                'direccion' => 'required',
                'foto' => 'required|mimes:jpeg,png', 
            ]);

            if ($validator->fails()) {
                $messages=$validator->getMessageBag();
                $errors=$messages->all();
                return response()->json([
                    'Estado' => 'Error',
                    'Mensaje' => $errors,
                ]);
            }

            /**
             * para crear un nuevo registro usando su modelo correspondiente se puede realizar con el metodo create()
             * este metodo te permite pasar como argumento un array con los datos del nuevo registro:
             * $cliente = Cliente::create([
             *   'nombre' => 'jose',
             *   'telefono' => '345402',
             *   ...
             * ]);
             * esto evita que el servidor realice cambios a los datos del modelo y genere de manera automatica el nuevo registro
             * en la base de datos
            */ 

            /**
             * consejo para trabajar con arrays:
             * LARAVEL ofrece una gran cantidad de helpers que te facilitarán la vida al trabajar con arrays, los mas
             * comunes son: array_get, array_has usados para obtener y validar un valor del array
             */

            $cliente = new Cliente;
            $cliente->nombre = $request->nombre;
            $cliente->telefono = $request->telefono;
            $cliente->email = $request->email;
            $cliente->direccion = $request->direccion;

            if ( !empty($request->file('foto')) ){
                $cliente->foto = $request->file('foto')->store('');
            }

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
    public function edit( $id)
    {

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

        try{
            $cliente = Cliente::findOrFail($id);
            if($request->hasFile('foto')){
                Storage::delete($cliente->foto);
                $cliente->foto = $request->foto->store('');
            }
            $cliente->nombre = $request->nombre;
            $cliente->telefono = $request->telefono;
            $cliente->email = $request->email;
            $cliente->direccion = $request->direccion;

            $cliente->save();

            $data = Cliente::find($id);
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $cliente = Cliente::findOrFail($id);
            Storage::delete($cliente->foto);
            $cliente->delete();
            return response()->json([
                'Estado' => 'Ok',
                'Mensaje' => 'Eliminado correctamente',
            ]);
        }
        catch(\Exception $e){
            return response()->json([
                'Estado' => 'Error',
                'Mensaje' => $e->getMessage(),
            ]);
        }

    }
}
