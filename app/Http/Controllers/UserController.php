<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{

    public function index(Request $request): View
    {
        $data = User::all();
        // dd($data);

        return view('users.index', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {

        $roles = Role::pluck('name', 'name')->all();
        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $validate = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($validate['password']);

        $user = User::create($input);
        // dd($input['password']);

        $r = $user->assignRole($validate['roles']);
        //   dd( $r);


        return redirect()->route('users.index')
            ->with('success', 'User created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id): View
    {
        $user = User::find($id);
        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->all();
        return view('users.edit', compact('user', 'roles', 'userRoles'));
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
        $validate = $request->validate( [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|same:confirm-password',
            'roles' => 'required|array', // Ensure roles is an array
        ]);

        $input = $request->except(['_token', '_method']); // Exclude unnecessary fields

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            unset($input['password']);
        }

        $user = User::findOrFail($id);
        $user->update($input);


        // Ensure proper role IDs are passed
        $roleIds = array_map('intval', $validate['roles']);

        // Sync roles
        $user->roles()->sync($roleIds);

        return redirect()->route('users.index')
            ->with('update', 'User Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();


        return redirect()->route('users.index')
            ->with('delete', 'User Deleted Successfully');
    }
}
