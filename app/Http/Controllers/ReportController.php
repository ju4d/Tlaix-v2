<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Ingredient;

class ReportController extends Controller {
    public function index(){
        $ingredients = Ingredient::all();
        // Simple waste report: list of expired items (today > expiration_date)
        $expired = Ingredient::whereNotNull('expiration_date')->where('expiration_date','<',date('Y-m-d'))->get();
        return view('reports.index', compact('ingredients','expired'));
    }
}
