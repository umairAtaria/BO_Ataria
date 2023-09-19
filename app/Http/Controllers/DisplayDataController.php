<?php


namespace App\Http\Controllers;

use DataTables;
use MTAPI\MTWebAPI;
use App\Models\User;
use App\Models\Symbols;

use Illuminate\Http\Request;
use MTAPI\Mt5_Api\MTRetCode;
use App\Models\Mt5_Connection;
use Yajra\DataTables\DataTablesServiceProvider;

// use App\User;

class DisplayDataController extends Controller
{

    public function index()
    {
        // $mtApi = new MTWebAPI();
        // $creds = Mt5_Connection::first(); // assuming you have one connection detail
        
        // if (!$mtApi->IsConnected()) {
        //     if (($error_code = $mtApi->Connect($creds->ip, $creds->port, 1, $creds->login, $creds->password)) != MTRetCode::MT_RET_OK) {
        //         echo MTRetCode::GetError($error_code);
        //         return;
        //     }
        // }
        // $mtApi->GroupGet('demo\forex-hedge-usd-01-Test', $group);
        // dd($group);
        return view('settings.symbols_index');
    }

    public function newfun()
    {
        // return Datatables::of(Symbols::query())
        // ->addColumn('action', function ($data) {
        //     $button = '<button type="button" name="edit" data-id="'.$data->id.'" class="edit btn btn-primary btn-sm editBtn">Edit</button>';
        //     return $button;
        // })
        // ->make(true);

        $symbols = Symbols::all();

        // Prepare the data for Tabulator
        $data = [];
        foreach ($symbols as $symbol) {
            $row = [
                'id' => $symbol->id,
                'Symbol' => $symbol->Symbol,
                'Description' => $symbol->Description,
                'SwapMode' => $symbol->SwapMode,
                'SwapLong' => $symbol->SwapLong,
                'SwapShort' => $symbol->SwapShort,
                'Swap3Day' => $symbol->Swap3Day,
                // 'action' => '<button type="button" name="edit" data-id="' . $symbol->id . '" class="edit btn btn-primary btn-sm editBtn">Edit</button>',
            ];
            $data[] = $row;
        }
    
        return response()->json(['data' => $data]);
   


    public function sync_api()
    {
        $creds = Mt5_Connection::first(); // assuming you have one connection detail
        $mtApi = new MTWebAPI();

        if (!$mtApi->IsConnected()) {
            if (($error_code = $mtApi->Connect($creds->ip, $creds->port, 1, $creds->login, $creds->password)) != MTRetCode::MT_RET_OK) {
                echo MTRetCode::GetError($error_code);
                return;
            }
        }

        $totalSymbols = [];
        $total = 0;

        $mtApi->SymbolTotal($total);

        for ($j = 0; $j < $total; $j++) {
            $symbolInfo = $mtApi->SymbolNext($j, $totals);
            $totalSymbols[] = [
                'Symbol' => $totals->Symbol,
                'Path' => $totals->Path,
                'Description' => $totals->Description,
                'Digits' => $totals->Digits,
                'SwapMode' => $totals->SwapMode,
                'SwapLong' => $totals->SwapLong,
                'SwapShort' => $totals->SwapShort,
                'Swap3Day' => $totals->Swap3Day
            ];
        }

        $Symbol = new Symbols();
        $Symbol->truncate();
        $Symbol->insert($totalSymbols);

        return response()->json(['message' => 'success']);
    }

    public function update(Request $request){
        // First Update to DB
        $data = $request->all();
        $id = $request->id;
        $symbol = Symbols::find($id);
        $new_symbol = [
            'Symbol' => $data['Symbol']
        ];

        if(isset($request->SwapMode)){
            $symbol->SwapMode = $request->SwapMode;  
            $new_symbol['SwapMode'] = $request->SwapMode;
        }  
        if(isset($request->SwapLong)){
            $symbol->SwapLong = $request->SwapLong;  
            $new_symbol['SwapLong'] = $request->SwapLong;
        }
        if(isset($request->SwapShort)){
            $symbol->SwapShort = $request->SwapShort;  
            $new_symbol['SwapShort'] = $request->SwapShort;
        }
        if(isset($request->SwapDay)){
            $symbol->Swap3Day = $request->SwapDay;  
            $new_symbol['Swap3Day'] = $request->SwapDay;
        }
        $symbol->save();

        
        $data = $new_symbol;
        // Update in MT5
        $creds = Mt5_Connection::first(); // assuming you have one connection detail
        $mtApi = new MTWebAPI();
    
        if (!$mtApi->IsConnected()) {
            if (($error_code = $mtApi->Connect($creds->ip, $creds->port, 1, $creds->login, $creds->password)) != MTRetCode::MT_RET_OK) {
                echo MTRetCode::GetError($error_code);
                return;
            }
        }

        if (($error_code = $mtApi->SymbolAdd($data,$updated_symbol)) != MTRetCode::MT_RET_OK) {
            echo MTRetCode::GetError($error_code);
            return;
        }


        return response()->json(['message' => 'success']);

    }
}
