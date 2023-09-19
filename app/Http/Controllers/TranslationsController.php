<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Translations;

class TranslationsController extends Controller
{
    public function translations(){
        return view('settings.translations');
    }

    public function ajax(){
        $translations = Translations::all();
        $data = [];
        foreach($translations as $tra){
            $row = [
                'id' => $tra->id,
                'Translation' => $tra->t_name,
                'Symbol' => $tra->s_name,
                'Action' => '<button type="button" name="delete" data-id="' . $tra->id . '" class="btn btn-danger btn-sm delbtn">Delete</button>',
            ];
            $data[] = $row;
        }
        return response()->json(['data' => $data]);
    }

    public function save(Request $request){
        // dd($request->all());

        $trans = Translations::create([
            't_name' => $request->t_name,
            's_name' => $request->s_name,
        ]);

      
        return response()->json(['message' => 'success']);
    }

    public function destroy(Request $request){
        $trans = Translations::find($request->id);
        $trans->delete();

        return response()->json(['message' => 'success']);
    }
}
