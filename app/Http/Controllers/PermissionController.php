<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::orderBy('created_at', 'DESC')->paginate(7);
        return view('permissions.list', [
            'permissions' => $permissions
        ]);
    }
    public function create()
    {
        return view('permissions.create');
    }
    public function store(Request $request)
    {
        $validater = $request->validate([
            'name' => 'required | unique:permissions|min:3',
        ]);
        // print_r($validater);
        if ($validater) {
            Permission::create(['name' => $request->name]);
            return redirect()->route('permissions.index')->with('success', 'Permission added successfully.');
        } else {
            return redirect()->route('permissions.create')->withErrors($validater);
        }
    }
    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        return view('permissions.edit', ['permission' => $permission]);
    }
    public function update($id, Request $req)
    {
        $permission = Permission::findOrFail($id);
        $validater = $req->validate([
            'name' => 'required | min:3 | unique:permissions,name,' . $id . ',id'
        ]);
        // print_r($validater);
        if ($validater) {
            $permission->name = $req->name;
            $permission->save();
            return redirect()->route('permissions.index')->with('success', 'Permission updated successfully.');
        } else {
            return redirect()->route('permissions.edit', $id)->withErrors($validater);
        }
    }
    public function delete(Request $req)
    {
        $id = $req->id;
        $permission = Permission::find($id);
        if ($permission == null) {
            session()->flash('error', 'Permission not found');
            return response()->json([
                'status' => false,
            ]);
        }
        $permission->delete();
        session()->flash('success', 'Permission deleted successfully');
        return response()->json([
            'status' => true,
        ]);
    }
}
