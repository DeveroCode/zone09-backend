<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\UpdatePassword;
use App\Http\Resources\UserCollection;
use App\Http\Requests\CreateAccountRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Http\Requests\UpdateUserRequest;
use Exception;

class UserController extends Controller
{
    
    public function index()
    {
        $user = User::where('type_user', '!=', 'admin')->orderBy('id', 'DESC')->get();
        return new UserCollection($user);
    }
    
    public function store(CreateAccountRequest $request)
    {
        $data = $request->validated();

        try{
            User::create([
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'type_user' => $data['type_user'],
                'created_at' => date('Y-m-d H:i:s'),
            ]);
    
            return response()->json(['message' => "Usuario creado con exito"], 201);    
        }catch(Exception $e){
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $data = $request->validated();
        $user = User::findOrFail($id);

        if($user){
            $user->update([
                'name' => $data['name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'type_user' => $data['type_user'],
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            return response()->json(['message' => 'Usuario actualizado correctamente'], 200);
        }else {
            return response()->json(['message' => "Usuario no encontrado"], 404);
        }
    }

    public function updatePassword(UpdatePasswordRequest $request, $id)
    {
        $data = $request->validated();
        $user = User::findOrFail($id);
        

        if($user){
            $user->update($data);
            return response()->json(['message' => 'Contraseña actualizada correctamente'], 200);
        }else {
            return response()->json(['message' => "Usuario no encontrado"], 404);
        }
    }

    public function show($word){
        $user = User::where('name', 'like', '%' . $word . '%')->orWhereRaw("CONCAT(name, ' ', last_name) LIKE ?", ["%{$word}%"])->get();
        
        if($user->isNotEmpty()){
            return new UserCollection($user);
        }else{
            return response()->json(['message' => "Usuario no encontrado"], 404);
        }
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if($user){
            $user->delete();
            return response()->json(['message' => "Usuario eliminado con exito"], 200);
        }else{
            return response()->json(['message' => "Usuario no encontrado"], 404);
        }
    }
}