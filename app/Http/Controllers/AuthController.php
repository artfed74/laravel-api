<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pc_number' => 'required|string|unique:users,pc_number|max:255',
            'group_id' => 'required|exists:groups,id',
            'variant' => 'required|in:A,B,C,D',
        ]);

        // Проверка, есть ли уже пользователь с таким вариантом в группе
        $existingUser = User::where('group_id', $request->group_id)
            ->where('variant', $request->variant)
            ->exists();

        if ($existingUser) {
            return response()->json([
                'message' => 'В группе уже есть пользователь с таким вариантом.'
            ], 400);
        }

        // Создание пользователя
        $user = User::create([
            'name' => $request->name,
            'pc_number' => $request->pc_number,
            'group_id' => $request->group_id,
            'variant' => $request->variant,
        ]);

        // Авторизация пользователя
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Пользователь успешно зарегистрирован',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = Admin::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Неверные учетные данные'], 401);
        }

        $user->tokens()->delete();
        $token = $user->createToken('admin-token')->plainTextToken;

        return response()->json([
            'message' => 'Админ успешно авторизован',
            'user' => $user,
            'token' => $token
        ], 200);
    }
}
