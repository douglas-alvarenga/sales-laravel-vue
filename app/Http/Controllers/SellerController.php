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
    /**
     * Lista vendedores
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        return ApiHelper::responseSuccess(['sellers' => Seller::all()]);
    }

    /**
     * Cadastra vendedor
     *
     * @param StoreEditSellerRequest $request
     * @return JsonResponse
     */
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

    /**
     * Atualiza vendedor
     *
     * @param StoreEditSellerRequest $request
     * @param Seller $seller
     * @return JsonResponse
     */
    public function update(StoreEditSellerRequest $request, Seller $seller): JsonResponse
    {
        try {
            $seller->update($request->all());
            return ApiHelper::responseSuccess(['seller' => $seller]);
        } catch (\Exception $e) {
            Log::error("Falha ao atualizar vendedor", ['errorMessage' => $e->getMessage()]);
            return ApiHelper::responseUnsuccess("Falha ao atualizar vendedor");
        }
    }

    /**
     * Remove vendedor
     *
     * @param Seller $seller
     * @return JsonResponse
     */
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

    /**
     * Restaura vendedor removido
     *
     * @param integer $sellerId
     * @return JsonResponse
     */
    public function restore(int $sellerId): JsonResponse
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

    /**
     * Exibe vendedor
     *
     * @param Seller $seller
     * @return JsonResponse
     */
    public function show(Seller $seller): JsonResponse
    {
        try {
            return ApiHelper::responseSuccess(['seller' => $seller]);
        } catch (\Exception $e) {
            Log::error("Falha ao exibir vendedor", ['errorMessage' => $e->getMessage()]);
            return ApiHelper::responseUnsuccess("Falha ao localizar vendedor");
        }
    }

    /**
     * Envia email com resumo diário da vendas ao vendedor
     *
     * @param SendDailySalesReportRequest $request
     * @param Seller $seller
     * @param DailySalesReportService $service
     * @return JsonResponse
     */
    public function sendDailySalesReport(SendDailySalesReportRequest $request, Seller $seller, DailySalesReportService $service): JsonResponse
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
