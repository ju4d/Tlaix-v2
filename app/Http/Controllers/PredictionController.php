<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class PredictionController extends Controller {
    public function predict(Request $r){
        $days = $r->input('days',7);
        $datafile = base_path('storage/app/predictions/history.csv'); // optional path
        // Build command safely
        $cmd = escapeshellcmd("py " . base_path('predict.py') . " --file " . escapeshellarg($datafile) . " --days " . intval($days));
        $output = [];
        $returnVar = 0;
        exec($cmd, $output, $returnVar);
        if($returnVar !== 0) return response()->json(['error'=>'Prediction failed','output'=>$output],500);
        $json = implode("\n",$output);
        // assume python prints JSON
        return response($json,200)->header('Content-Type','application/json');
    }
}
