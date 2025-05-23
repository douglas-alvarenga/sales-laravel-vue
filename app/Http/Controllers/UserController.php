<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use App\Support\Helpers\ApiHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreEditUserRequest;

class UserController extends Controller
{
    public function index()
    {
        return ApiHelper::responseSuccess(['users' => User::all()]);
    }

    public function store(StoreEditUserRequest $request): JsonResponse
    {
        try {
            $user = new User($request->all());
            $user->password = $request->password;
            $user->save();
            return ApiHelper::responseSuccess(['user' => $user]);
        } catch (\Exception $e) {
            Log::error("Falha ao salvar usuário", ['errorMessage' => $e->getMessage()]);
            return ApiHelper::responseUnsuccess("Falha ao salvar usuário");
        }
    }

    public function update(StoreEditUserRequest $request, User $user)
    {
        try {
            if ($request->has('password')) {
                $user->password = $request->password;
            }
            $user->update($request->all());
            return ApiHelper::responseSuccess(['user' => $user]);
        } catch (\Exception $e) {
            Log::error("Falha ao atualizar usuário", ['errorMessage' => $e->getMessage()]);
            return ApiHelper::responseUnsuccess("Falha ao atualizar usuário");
        }
    }

    public function destroy(User $user): JsonResponse
    {
        try {
            $user->delete();
            return ApiHelper::responseSuccess(["user" => $user]);
        } catch (\Exception $e) {
            Log::error("Falha ao remover usuário", ['errorMessage' => $e->getMessage()]);
            return ApiHelper::responseUnsuccess("Falha ao remover usuário");
        }
    }

    public function restore(int $userId)
    {
        try {
            if (!User::withTrashed()->whereId($userId)->restore()) {
                return ApiHelper::responseUnsuccess("Usuário não localizado.");
            }

            return ApiHelper::responseSuccess();
        } catch (\Exception $e) {
            Log::error("Falha ao restaurar usuário", ['errorMessage' => $e->getMessage()]);
            return ApiHelper::responseUnsuccess("Falha ao restaurar usuário");
        }
    }

    public function show(User $user)
    {
        try {
            return ApiHelper::responseSuccess(['user' => $user]);
        } catch (\Exception $e) {
            Log::error("Falha ao exibir usuário", ['errorMessage' => $e->getMessage()]);
            return ApiHelper::responseUnsuccess("Falha ao localizar usuário");
        }
    }
}
