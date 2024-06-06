<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserDetail;
use App\Models\Division;
use App\Http\Requests\UserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the users
     *
     * @param  \App\Models\User  $model
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $datas = User::with(['roles'])->get();
        return view('users.index', compact(
            'datas'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        $divisions = Division::all();
        return view('users.create',compact('roles', 'divisions'));
    }

    /**
     * Store a newly created resource in storage.
     */
     
    public function store(UserRequest $request)
    {
        // Ambil semua input dari request
        $input = $request->all();

        // Buat pengguna baru
        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'username' => $input['username'],
            'password' => Hash::make($input['password']),
        ]);

        // Sinkronisasi role pengguna
        $user->syncRoles($request->input('roles'));

        // Buat user detail baru
        $userDetail = UserDetail::create([
            'nip' => $input['nip'],
            'posision' => $input['posision'],
            'user_id' => $user->id,
            'division_id' => $input['division_id']
        ]);

        // Ambil divisi berdasarkan ID
        $division = Division::find($input['division_id']);

        // Hubungkan user detail dengan divisi
        $userDetail->division()->associate($division);
        $userDetail->save();

        // Redirect kembali ke halaman utama dengan pesan sukses
        return redirect()->route('user.index')->with('status', 'User Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, User $user)
    {
        $user->where('id', $id)->first();
        return view('users.detail', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $roles = Role::all()->pluck('name', 'id');

        $user = User::with('userDetail.division')->findOrFail($id);
        $user->load('roles');
        
        $divisions = Division::all();

        return view('users.edit', compact('user', 'roles', 'divisions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, $id)
    {
        
        $user = User::findOrFail($id);
        
        // Update user details
        $user->update([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
        ]);

        // Sinkronisasi role pengguna
        $user->syncRoles($request->input('roles'));

        // Update user detail
        $userDetail = $user->userDetail;
        $userDetail->update([
            'nip' => $request->input('nip'),
            'posision' => $request->input('posision'),
        ]);

        // Ambil divisi berdasarkan ID
        $division = Division::find($request->input('division_id'));

        // Hubungkan user detail dengan divisi
        $userDetail->division()->associate($division);

        $userDetail->save();
        
        //jika data berhasil ditambahkan, akan kembali ke halaman utama
        return redirect()->route('user.index')->with('status', 'User Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('user.index')->with('status', 'User Berhasil Dihapus');
    }
}