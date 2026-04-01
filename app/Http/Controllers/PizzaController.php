<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Pizza;
use App\Models\Ingredient;
use App\Http\Requests\StorePizzaRequest;
use App\Http\Requests\UpdatePizzaRequest;

class PizzaController extends Controller
{
    public function index(){
        $pizzas=Pizza::with('ingredients')->get();
        return response()->json($pizzas);
    }

    public function store(StorePizzaRequest $request){
        $pizza = Pizza::create($request->validated());
        if($request->filled('ingredients')){
            $pizza->ingredients()->attach($request->ingredients);
        }
        return response()->json([
            'message' => 'Pizza creada correctamente',
            'pizza' => $pizza
        ]);
    }
    public function show(Pizza $pizza){
        return response()->json($pizza->load('ingredients'));
    }
    public function update(UpdatePizzaRequest $request, Pizza $pizza){
        $pizza->update($request->validated());
        if($request->filled('ingredients')){
            $pizza->ingredients()->sync($request->ingredients);
        }
        return response()->json([
            'message' => 'Pizza actualizada correctamente',
            'pizza' => $pizza->load('ingredients')
        ]);
    }
    public function destroy(Pizza $pizza){
        $pizza->delete();
        return response()->json([
            'message' => 'Pizza eliminada correctamente',
            'pizza' => $pizza
        ]);
    }
}
