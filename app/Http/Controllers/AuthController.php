<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Models\User;
use App\Support\Helpers\ApiHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Realiza o login utilizando usuário e senha
     *
     * @param LoginUserRequest $request
     * @return JsonResponse
     */
    public function login(LoginUserRequest $request)
    {
        $user = User::orWhere(['username' => $request->login, 'email' => $request->login])->first();

        if (empty($user)) {
            return ApiHelper::responseUnsuccess("Usuário não encontrado", [], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return ApiHelper::responseUnsuccess("Senha incorreta", [
                Hash::check(
                    $request->password,
                    $user->password
                )
            ], 401);
        }

        $token = auth()->claims([

            "exp" => $exp ?? strtotime('now +' . env('JWT_TOKEN_MINUTES', 60) . ' minutes'),
        ])->login($user);

        return ApiHelper::responseSuccess(['access_token' => $token, 'user' => $user]);
    }

    public function logout()
    {
        auth()->logout();
        return ApiHelper::responseSuccess();
    }

    public function me()
    {
        return response()->json(auth()->user());
    }
}
