<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreIngredientRequest;
use App\Http\Requests\UpdateIngredientRequest;
use App\Models\Ingredient;

use Illuminate\Http\Request;

class IngredientController extends Controller
{
    public function index(){
        return response()->json(Ingredient::all());
    }
    public function store(StoreIngredientRequest $request){
        $ingredient = Ingredient::create($request->validated());
        return response()->json([
            'message' => 'Ingrediente creado correctamente',
            'ingredient' => $ingredient
        ]);
    }
    public function show(Ingredient $ingredient){
        return response()->json($ingredient);
    }
    public function update(UpdateIngredientRequest $request, Ingredient $ingredient){
        $ingredient->update($request->validated());
        return response()->json([
            'message' => 'Ingrediente actualizado correctamente',
            'ingredient' => $ingredient
        ]);
    }
    public function destroy(Ingredient $ingredient){
        $ingredient->delete();
        return response()->json([
            'message' => 'Ingrediente eliminado correctamente',
            'ingredient' => $ingredient
        ]);
    }
}
