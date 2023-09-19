<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mt5_Connection;
use MTAPI\MTWebAPI;
use MTAPI\Mt5_Api\MTRetCode;


class Mt5ConnectionController extends Controller
{
    public function mt5_connect()
    {
        $credentials = Mt5_Connection::first();
        return view('settings.mt5_connection',compact('credentials'));
    }
    
    public function save_connection(Request $request){

        $request->validate([
            'mt5_ip' => 'required',
            'mt5_port' => 'required',
            'mt5_login' => 'required',
            'mt5_password' => 'required',
        ]);

        $creds = Mt5_Connection::all();
        $mtApi = new MTWebAPI();

        if (($error_code = $mtApi->Connect($request->mt5_ip, $request->mt5_port, 300, $request->mt5_login, $request->mt5_password)) != MTRetCode::MT_RET_OK) {
            return redirect()->route('mt5_connection')->with('error', 'Connection details are not correct');
        } else {
            if (count($creds) > 0) {
                $creds[0]->delete();
            }

            $mt5_con = new Mt5_Connection();
            $mt5_con->ip = $request->mt5_ip;
            $mt5_con->port = $request->mt5_port;
            $mt5_con->login = $request->mt5_login;
            $mt5_con->password = $request->mt5_password;
            $mt5_con->save();

            return redirect()->route('mt5_connection')->with('success', 'Connection details saved successfully');
        }
    }

    public function con()
    {
        $creds = Mt5_Connection::all();
        $mtApi = new MTWebAPI();

        if (!$mtApi->IsConnected()) {
            if (($error_code = $mtApi->Connect($creds[0]->ip, $creds[0]->port, 1, $creds[0]->login, $creds[0]->password)) != MTRetCode::MT_RET_OK) {
                $error_code_ = "Not Connected";
                echo MTRetCode::GetError($error_code);
            } else {
                $error_code = 0;
            }
        }

        $data = ['message' => $error_code];

        return response()->json($data);
    }
}
