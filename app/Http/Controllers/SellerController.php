<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use App\Support\Helpers\ApiHelper;
use Illuminate\Support\Facades\Log;
use App\Services\DailySalesReportService;
use App\Http\Requests\StoreEditSellerRequest;
use App\Http\Requests\SendDailySalesReportRequest;

class SellerController extends Controller
{
    public function index()
    {
        return ApiHelper::responseSuccess(['sellers' => Seller::all()]);
    }

    public function store(StoreEditSellerRequest $request): JsonResponse
    {
        try {
            $seller = new Seller($request->all());
            $seller->save();
            return ApiHelper::responseSuccess(['seller' => $seller]);
        } catch (\Exception $e) {
            Log::error("Falha ao salvar vendedor", ['errorMessage' => $e->getMessage()]);
            return ApiHelper::responseUnsuccess("Falha ao salvar vendedor");
        }
    }

    public function update(StoreEditSellerRequest $request, Seller $seller)
    {
        try {
            $seller->update($request->all());
            return ApiHelper::responseSuccess(['seller' => $seller]);
        } catch (\Exception $e) {
            Log::error("Falha ao atualizar vendedor", ['errorMessage' => $e->getMessage()]);
            return ApiHelper::responseUnsuccess("Falha ao atualizar vendedor");
        }
    }

    public function destroy(Seller $seller): JsonResponse
    {
        try {
            $seller->delete();
            return ApiHelper::responseSuccess(["seller" => $seller]);
        } catch (\Exception $e) {
            Log::error("Falha ao remover vendedor", ['errorMessage' => $e->getMessage()]);
            return ApiHelper::responseUnsuccess("Falha ao remover vendedor");
        }
    }

    public function restore(int $sellerId)
    {
        try {
            if (!Seller::withTrashed()->whereId($sellerId)->restore()) {
                return ApiHelper::responseUnsuccess("Vendedor não localizado.");
            }
            return ApiHelper::responseSuccess();
        } catch (\Exception $e) {
            Log::error("Falha ao restaurar vendedor", ['errorMessage' => $e->getMessage()]);
            return ApiHelper::responseUnsuccess("Falha ao restaurar vendedor");
        }
    }

    public function show(Seller $seller)
    {
        try {
            return ApiHelper::responseSuccess(['seller' => $seller]);
        } catch (\Exception $e) {
            Log::error("Falha ao exibir vendedor", ['errorMessage' => $e->getMessage()]);
            return ApiHelper::responseUnsuccess("Falha ao localizar vendedor");
        }
    }

    public function sendDailySalesReport(SendDailySalesReportRequest $request, Seller $seller, DailySalesReportService $service)
    {
        try {
            if (!$service->sendMailToSeller($seller, $request->date)) {
                return ApiHelper::responseUnsuccess("Ocorreu um erro ao enviar email para o vendedor");
            }
            return ApiHelper::responseSuccess();
        } catch (\Exception $e) {
            Log::error('Falha ao enviar email de resumo diário de vendas ao vendedor', ['errorMessage' => $e->getMessage()]);
            return ApiHelper::responseUnsuccess("Falha ao enviar email de resumo diário de vendas ao vendedor");
        }
    }
}
