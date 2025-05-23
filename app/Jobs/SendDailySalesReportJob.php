<?php

namespace App\Jobs;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Seller;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use App\Services\DailySalesReportService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendDailySalesReportJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public string $sendTo, public ?string $paramDate = null)
    {
        $this->paramDate = $this->paramDate ?? Carbon::now()->format('Y-m-d');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $service = new DailySalesReportService();
        match ($this->sendTo) {
            'seller' => Seller::all()->each(fn($seller) => $service->sendMailToSeller($seller, $this->paramDate)),
            'admin' => User::admin()->get()->each(fn($user) => $service->sendMailToAdmin($user, $this->paramDate)),
        };
    }
}
