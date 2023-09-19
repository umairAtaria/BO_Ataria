<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class DashboardController extends Controller
{
    public function index(){
        //  $role = Role::create(['name' => 'user']);
        // Permission::create(['name' => 'Symbols']);
        // Permission::create(['name' => 'Add Symbols']);
        // Permission::create(['name' => 'File Upload']);
        // Permission::create(['name' => 'Translations']);
        // Permission::create(['name' => 'Groups']);
        // Permission::create(['name' => 'Add Groups']);



        // Role::findById(1)->givePermissionTo(Permission::all());
        // Role::findById(2)->givePermissionTo('Symbols','Groups');
        
        // dd(Role::findById(2)->permissions);
        


        // $a = Auth::user();
        // $b = $a->getAllPermissions();
        // dd($b);
        // dd($a);
        // $user = Auth::user();
        // $b = $user->getPermissionNames();
        // dd($b);

        // dd(User::with('roles')->get());
        // $superAdminCount = Auth::user()->getAllPermissions();
        // dd($superAdminCount);
        // auth()->user()->givePermissionTo('Symbols');
        return view('landing');
    }
}
