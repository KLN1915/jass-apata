<?php

namespace App\Http\Controllers;

use App\Http\Requests\ZoneRequest;
use App\Models\Zone;
use Illuminate\Http\Request;

class ZoneController extends Controller
{
    public function getZones(){
        return response()->json(Zone::select('id', 'name')->get());
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $zones = Zone::all();

        return view('settings.zones.index', compact('zones'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ZoneRequest $request)
    {
        try{
            $zone = Zone::create([
                'name' => $request->name
            ]);
    
            return response()->json([
                'message' => 'Se registró el barrio correctamente',
                'data' => $zone
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'error' => 'Error al registrar barrio',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $zone = Zone::findOrFail($id);

            return response()->json([
                'data' => $zone
            ]);
        }catch(\Exception $e){
            return response()->json([
                'error' => $e
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ZoneRequest $request, string $id)
    {
        try{
            $zone = Zone::find($id)->update([
                'name' => $request->name
            ]);
    
            return response()->json([
                'message' => 'Se actualizó el barrio correctamente',
                'data' => $zone
            ], 200);
        }catch(\Exception $e){
            return response()->json([
                'error' => 'Error al actualizar barrio',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
