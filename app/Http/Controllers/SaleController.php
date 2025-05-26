<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Support\Helpers\ApiHelper;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreEditSaleRequest;

class SaleController extends Controller
{

    /**
     * Lista vendas
     * Possível filtrar por vendedor, caso informado
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $seller = $request->input('seller');
        $sales = ($seller) ? Sale::where(['seller_id' => $seller])->get() : Sale::all();
        return ApiHelper::responseSuccess(['sales' => $sales]);
    }

    /**
     * Cadastra venda
     *
     * @param StoreEditSaleRequest $request
     * @return JsonResponse
     */
    public function store(StoreEditSaleRequest $request): JsonResponse
    {
        try {
            $sale = new Sale($request->all());
            $sale->save();
            return ApiHelper::responseSuccess(['sale' => $sale]);
        } catch (\Exception $e) {
            Log::error("Falha ao salvar venda", ['errorMessage' => $e->getMessage()]);
            return ApiHelper::responseUnsuccess("Falha ao salvar venda");
        }
    }

    /**
     * Atualiza venda
     *
     * @param StoreEditSaleRequest $request
     * @param Sale $sale
     * @return JsonResponse
     */
    public function update(StoreEditSaleRequest $request, Sale $sale): JsonResponse
    {
        try {
            $sale->update($request->all());
            return ApiHelper::responseSuccess(['sale' => $sale]);
        } catch (\Exception $e) {
            Log::error("Falha ao atualizar venda", ['errorMessage' => $e->getMessage()]);
            return ApiHelper::responseUnsuccess("Falha ao atualizar venda");
        }
    }

    /**
     * Exclui venda
     *
     * @param Sale $sale
     * @return JsonResponse
     */
    public function destroy(Sale $sale): JsonResponse
    {
        try {
            $sale->delete();
            return ApiHelper::responseSuccess(["sale" => $sale]);
        } catch (\Exception $e) {
            Log::error("Falha ao remover venda", ['errorMessage' => $e->getMessage()]);
            return ApiHelper::responseUnsuccess("Falha ao remover venda");
        }
    }

    /**
     * Restaura venda excluída
     *
     * @param integer $saleId
     * @return JsonResponse
     */
    public function restore(int $saleId): JsonResponse
    {
        try {
            if (!Sale::withTrashed()->whereId($saleId)->restore()) {
                return ApiHelper::responseUnsuccess("Venda não localizada.");
            }

            return ApiHelper::responseSuccess();
        } catch (\Exception $e) {
            Log::error("Falha ao restaurar venda", ['errorMessage' => $e->getMessage()]);
            return ApiHelper::responseUnsuccess("Falha ao restaurar venda");
        }
    }

    /**
     * Exibe uma venda específica
     *
     * @param Sale $sale
     * @return JsonResponse
     */
    public function show(Sale $sale): JsonResponse
    {
        try {
            return ApiHelper::responseSuccess(['sale' => $sale]);
        } catch (\Exception $e) {
            Log::error("Falha ao exibir venda", ['errorMessage' => $e->getMessage()]);
            return ApiHelper::responseUnsuccess("Falha ao localizar venda");
        }
    }
}
