<?php

namespace Tests\Unit;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Sale;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailySalesReportToAdminMail;
use App\Services\DailySalesReportService;
use App\Mail\DailySalesReportToSellerMail;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DailySalesReportServiceTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldSuccessfullySendMailToSeller()
    {
        $seller = Seller::factory()->create();
        $date = Carbon::now()->format('Y-m-d');
        $sale = Sale::factory()->create([
            'seller_id' => $seller->id,
            'date' => $date . ' 12:00:00',
        ]);
        Sale::factory(10)->create([
            'seller_id' => $seller->id,
            'date' => Carbon::now()->subDays(10)->format('Y-m-d H:i:s'),
        ]);

        Mail::fake();

        $service = new DailySalesReportService();
        $service->sendMailToSeller($seller, $date);

        Mail::assertQueued(DailySalesReportToSellerMail::class, function ($mail) use ($seller, $sale, $date) {
            return $mail->hasTo($seller->email) &&
                $mail->totalAmount === $sale->amount &&
                $mail->salesCount === 1 &&
                $mail->date === $date;
        });
    }

    public function testShouldSuccessfullySendMailToAdmin()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $date = Carbon::now()->format('Y-m-d');
        $sales = Sale::factory(5)->create([
            'date' => $date . ' 12:00:00',
        ]);
        Sale::factory(10)->create([
            'date' => Carbon::now()->subDays(10)->format('Y-m-d H:i:s'),
        ]);

        Mail::fake();

        $service = new DailySalesReportService();
        $service->sendMailToAdmin($admin, $date);

        Mail::assertQueued(DailySalesReportToAdminMail::class, function ($mail) use ($admin, $sales, $date) {
            return $mail->hasTo($admin->email) &&
                $mail->totalAmount === $sales->sum('amount') &&
                $mail->salesCount === $sales->count() &&
                $mail->date === $date;
        });
    }
}
