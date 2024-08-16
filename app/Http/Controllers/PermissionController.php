<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(){
        return view('permissions.list');
    }
    public function create(){
        return view('permissions.create');
    }
    public function store(Request $request){
        $validater = $request->validate([
            'name' => 'required | unique:permissions|min:3',
        ]);
        // print_r($validater);
        if($validater){
            Permission::create(['name'=> $request->name]);
            return redirect()->route('permissions.index')->with('success', 'Permission added successfully.');
        }else{
            return redirect()->route('permissions.create')->withErrors($validater);
        }
    }
    public function edit(){

    }
    public function update(){

    }
    public function delete(){

    }
}
