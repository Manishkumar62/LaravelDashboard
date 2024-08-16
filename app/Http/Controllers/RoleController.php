<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(){
        $roles = Role::orderBy('name', 'ASC')->paginate(8);
        return view('roles.list', ['roles'=>$roles]);
    }
    public function create(){
        $permissions = Permission::orderBy('name','ASC')->get();

        return view('roles.create', [
            'permissions'=>$permissions
        ]);
    }
    public function store(Request $request){
        $validater = $request->validate([
            'name' => 'required | unique:roles| min:3',
        ]);
        // print_r($validater);
        if ($validater) {
            $role = Role::create(['name' => $request->name]);
            if(!empty($request->permission)){
                foreach($request->permission as $name){
                    $role->givePermissionTo($name);
                }
            }
            return redirect()->route('roles.index')->with('success', 'Role added successfully.');
        } else {
            return redirect()->route('roles.create')->withErrors($validater);
        }
    }

}
