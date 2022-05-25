<?php

namespace App\Http\Controllers;

use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view dashboard');

        $this->middleware('can:list,App\User', ['only' => ['list']]);
        $this->middleware('can:create,App\User', ['only' => ['create', 'store']]);
        $this->middleware('can:update,user', ['only' => ['edit', 'update']]);
        $this->middleware('can:delete,user', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        $users = User::all();

        return view('users.list', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function create(User $user)
    {
        $roles = Role::all();

        return view('users.create', compact('user', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        if ($request->role) {
            $user->syncRoles($request->role);
        }

        flash('User has been created')->success()->important();

        return redirect()->route('users.edit', $user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json(['data' => User::all()->except($user->id), 'user' => $user], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.create', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        if (!empty($request->attribute_user)) {
            $user->projects()->each(function ($project, $key) use ($request) {
                $project->user_id = $request->attribute_user;
                $project->save();
            });
            return response()->json(['success' => true], 200);
        }
        $data = [
            'name' => $request->name,
            'email' => $request->email
        ];

        if (!empty($request->password)) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        if ($request->role && auth()->user()->hasPermissionTo('assign roles')) {
            $user->syncRoles($request->role);
        }

        flash('User has been updated')->success()->important();

        return redirect()->route('users.edit', $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // $user->delete();

        flash('User has been deleted')->success();

        // return redirect()->route('users.list');
        return response()->json(['success' => true], 200);
    }
}
