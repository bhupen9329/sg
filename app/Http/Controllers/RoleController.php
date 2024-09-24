<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;

use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controllers\Middleware;


class RoleController extends Controller
{


    function __construct()
    {
         $this->middleware('permission:Role-index', ['only' => ['index']]);
         $this->middleware('permission:Role-create', ['only' => ['create','store']]);
         $this->middleware('permission:Role-edit', ['only' => ['edit','update']]);
         $this->middleware('permission:Role-delete', ['only' => ['destroy']]);

    }

    public function index(Request $request): View
    {
        $roles = Role::orderBy('id', 'DESC')->get();
        return view('roles.index', compact('roles'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {

        $permission = Permission::get();
        // dd( $permission);
        return view('roles.create', compact('permission'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        // Dump and inspect the request data
        // dd($request->all());

        // Validate the request
        $validated = $request->validate([
            'name' => 'required|unique:roles,name',
            'permission' => 'required|array',
        ]);

        //   dd($validated);

        // Create a new role
        $role = Role::create(['name' => $validated['name']]);

        // Sync permissions
        $role->syncPermissions($validated['permission']);


        // Redirect with success message
        return redirect()->route('roles.index')->with('success', 'Role created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('roles.show', compact('role', 'rolePermissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')
            ->all();

        return view('roles.edit', compact('role', 'permission', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id): RedirectResponse
    {
        // dd($request);
        $validate = $request->validate( [
            'name' => 'required',
            'permission' => 'required',
        ]);

        // dd($validate);
        $role = Role::find($id);
        $role->name = $validate['name'];
        $role->save();

        $role->syncPermissions($validate['permission']);

        return redirect()->route('roles.index')
            ->with('update', 'Role Updated Successfully');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        DB::table("roles")->where('id', $id)->delete();

        return redirect()->route('roles.index')
            ->with('delete', 'Role Deleted Successfully');
    }
}
