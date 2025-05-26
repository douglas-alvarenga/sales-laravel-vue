<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Sale;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailySalesReportToAdminMail;
use App\Mail\DailySalesReportToSellerMail;

class DailySalesReportService
{
    public function sendMailToSeller(Seller $seller, ?string $paramDate = null)
    {
        $dates = $this->getDates($paramDate);
        $sales = $this->getSales($seller, $dates['dateUTC'], $dates['dateUTC']);
        $totals = $this->getTotals($sales);

        try {
            Mail::to($seller->email)->send(
                new DailySalesReportToSellerMail(
                    $seller,
                    $totals['amount'],
                    $totals['sellerCommission'],
                    $totals['count'],
                    $dates['paramDate']
                )
            );
        } catch (\Exception $e) {
            Log::error("Falha ao enviar email de relatório diário para vendedor", [$e->getMessage()]);
            return false;
        }
        return true;
    }

    public function sendMailToAdmin(User $user, ?string $paramDate = null)
    {
        $dates = $this->getDates($paramDate);
        $sales = $this->getSales(null, $dates['dateUTC'], $dates['dateUTC']);
        $totals = $this->getTotals($sales);

        try {
            Mail::to($user->email)->send(
                new DailySalesReportToAdminMail(
                    $totals['amount'],
                    $totals['sellerCommission'],
                    $totals['count'],
                    $dates['paramDate']
                )
            );
        } catch (\Exception $e) {
            Log::error("Falha ao enviar email de relatório diário para admin", [$e->getMessage()]);
            return false;
        }
        return true;
    }

    private function getDates(?string $date = null)
    {
        $timezone = 'America/Sao_Paulo';
        $date = $date ?? Carbon::now()->format('Y-m-d');
        $dateUTC = Carbon::parse($date, $timezone)
            ->setTimezone('UTC')
            ->format('Y-m-d');
        return [
            'paramDate' => $date,
            'dateUTC' => $dateUTC
        ];
    }

    private function getSales(?Seller $seller, string $startDate, string $endDate): Collection
    {
        return Sale::bySellerAndDateRange($seller, $startDate, $endDate)->get();
    }


    public function getTotals(Collection $sales)
    {
        return [
            'amount' => $sales->sum('amount'),
            'sellerCommission' => $sales->sum('seller_commission_amount'),
            'count' => $sales->count(),
        ];
    }
}
