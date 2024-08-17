<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $auth;

    public function __construct()
    {
        $this->auth = Auth::guard();
    }

    public function index()
    {
        // Sertakan kolom password dalam hasil query dan urutkan berdasarkan kolom 'updated_at' secara menurun (terbaru di atas)
        return User::select(['id', 'name', 'email', 'password'])
                    ->orderBy('updated_at', 'desc')
                    ->get();
    }

    public function show($id)
    {
        // Sertakan kolom password dalam hasil query
        return User::select(['id', 'name', 'email', 'password'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        // Jika password di-update, pastikan untuk meng-hash password baru
        if ($request->has('password')) {
            $request->merge(['password' => bcrypt($request->input('password'))]);
        }

        $user->update($request->all());

        // Ambil semua data user dan tempatkan user yang baru di-update di paling atas
        $users = User::select(['id', 'name', 'email', 'password'])
                    ->orderBy('updated_at', 'desc')
                    ->get();

        return response()->json($users, 200);
    }

    public function destroy($id)
    {
        User::destroy($id);

        return response()->json(User::select(['id', 'name', 'email', 'password'])->get(), 200);
    }
}
