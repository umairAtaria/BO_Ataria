<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Groups;
use MTAPI\MTWebAPI;
use App\Models\Mt5_Connection;
use App\Models\Translations;
use MTAPI\Mt5_Api\MTRetCode;
use PDO;

class SwapUpdateController extends Controller
{
    public function index(){
        return view('settings.swap_update');
    }

    public function import(Request $request){
        $this->validate($request, [
            'uploaded_file' => 'required|file|mimes:xls,xlsx'
        ]);

        $the_file = $request->file('uploaded_file');

        $creds = Mt5_Connection::first(); // assuming you have one connection detail
        $mtApi = new MTWebAPI();
    
        if (!$mtApi->IsConnected()) {
            if (($error_code = $mtApi->Connect($creds->ip, $creds->port, 1, $creds->login, $creds->password)) != MTRetCode::MT_RET_OK) {
                echo MTRetCode::GetError($error_code);
                return;
            }
        }


            $spreadsheet = IOFactory::load($the_file->getRealPath());
            $sheet        = $spreadsheet->getActiveSheet();
            $row_limit    = $sheet->getHighestDataRow();
            $column_limit = $sheet->getHighestDataColumn();
            $row_range    = range( 2, $row_limit );
            $column_range = range( 'A', $column_limit );
            $k=0;
            $data = array();
            foreach ( $row_range as $row ) {
                $data[$k] = [
                    'Symbol' =>$sheet->getCell( 'A' . $row )->getValue(),
                    'SwapLong' => $sheet->getCell( 'B' . $row )->getValue(),
                    'SwapShort' => $sheet->getCell( 'C' . $row )->getValue(),
                ];
               
                $check = Translations::where('t_name', $sheet->getCell( 'A' . $row )->getValue())->first();
                if($check != null){
                    $data[$k]['Symbol'] = $check->s_name;
                }


                $new_symbol = [
                    'Symbol' => $data[$k]['Symbol'],
                    'SwapLong' => $data[$k]['SwapLong'],
                    'SwapShort' => $data[$k]['SwapShort'],
                ];

                if (($error_code = $mtApi->SymbolAdd($new_symbol,$updated_symbol)) != MTRetCode::MT_RET_OK) {
                    echo MTRetCode::GetError($error_code);
                    return;
                }

                $k =$k+1;
            }

      
        return back()->withSuccess('Great! Data has been successfully Updated.');
 
    }

    public function group(){
        return view('settings.group_swap');
    }

    public function displayData(){
        $symbols = Groups::all();

        // Prepare the data for Tabulator
        $data = [];
        foreach ($symbols as $symbol) {
            $row = [
                'id' => $symbol->id,
                'Group' => $symbol->Group,
                'Company' => $symbol->Company,
                'Symbol' => $symbol->Symbol,
                'SwapMode' => $symbol->SwapMode,
                'SwapLong' => $symbol->SwapLong,
                'SwapShort' => $symbol->SwapShort,
                'Swap3Day' => $symbol->Swap3Day,
                // 'action' => '<button type="button" name="edit" data-id="' . $symbol->id . '" class="edit btn btn-primary btn-sm editBtn">Edit</button>',
            ];
            $data[] = $row;
        }
    
        return response()->json(['data' => $data]);
    }

    public function sync_groups(){
        $creds = Mt5_Connection::first(); // assuming you have one connection detail
        $mtApi = new MTWebAPI();
    
        if (!$mtApi->IsConnected()) {
            if (($error_code = $mtApi->Connect($creds->ip, $creds->port, 1, $creds->login, $creds->password)) != MTRetCode::MT_RET_OK) {
                echo MTRetCode::GetError($error_code);
                return;
            }
        }
    
        $totalGroups = [];
        $total = 0;
    
        $mtApi->GroupTotal($total);
    
        for ($j = 0; $j < $total; $j++) {
            $symbolInfo = $mtApi->GroupNext($j, $totals);
            $totalSymbols[] = [
                'Group' => $totals->Group,
                'Company' => $totals->Company,
                'Symbol' => $totals->Symbols[0]->Path,
                'SwapMode' => $totals->Symbols[0]->SwapMode,
                'SwapLong' => $totals->Symbols[0]->SwapLong,
                'SwapShort' => $totals->Symbols[0]->SwapShort,
                'Swap3Day' => $totals->Symbols[0]->Swap3Day
            ];
        }
    
        $group = new Groups();
        $group->truncate();
        $group->insert($totalSymbols);
    
        return response()->json(['message' => 'success']);
    }

    public function group_update(Request $request){
        // First Update to DB
        $data = $request->all();

        $creds = Mt5_Connection::first(); // assuming you have one connection detail
        $mtApi = new MTWebAPI();
    
        if (!$mtApi->IsConnected()) {
            if (($error_code = $mtApi->Connect($creds->ip, $creds->port, 1, $creds->login, $creds->password)) != MTRetCode::MT_RET_OK) {
                echo MTRetCode::GetError($error_code);
                return;
            }
        }

        $id = $request->id;
        $groups = Groups::find($id);

        $mtApi->GroupGet($data['Group'],$updated_gro);
        // dd($updated_gro);
        if(isset($request->SwapMode)){
            $groups->SwapMode = $request->SwapMode;  
            $updated_gro->Symbols[0]->SwapMode = $request->SwapMode;
        }  
        if(isset($request->SwapLong)){
            $groups->SwapLong = $request->SwapLong;  
            $updated_gro->Symbols[0]->SwapLong = $request->SwapLong;
        }
        if(isset($request->SwapShort)){
            $groups->SwapShort = $request->SwapShort;  
            $updated_gro->Symbols[0]->SwapShort = $request->SwapShort;
        }
        if(isset($request->Swap3Day)){
            $groups->Swap3Day = $request->Swap3Day;  
            $updated_gro->Symbols[0]->Swap3Day = $request->Swap3Day;
        }
        $groups->save();

        // var_dump($updated_gro);
        if (($error_code = $mtApi->GroupAdd($updated_gro,$updated_symbol)) != MTRetCode::MT_RET_OK) {
            echo MTRetCode::GetError($error_code);
            return;
        } 
        // dd($updated_symbol,$updated_gro);
        return response()->json(['message' => 'success']);
    }
}
