<?php

namespace App\Http\Controllers;

use App\Models\Occupation;
use Illuminate\Http\Request;

class OccupationController extends Controller
{
    public function getOccupations(Request $request)
    {
        $search = $request->get('search');

        $occupations = Occupation::query()
            ->when($search, function($query, $search) {
                return $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('name', 'asc')
            ->select('id', 'name')
            ->get()
            ->map(function($occupation) {
                return [
                    'id' => $occupation->id,
                    'text' => $occupation->name // ✅ Cambia 'name' por 'text'
                ];
            });

        return response()->json($occupations);
    }

}
