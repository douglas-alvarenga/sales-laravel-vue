<?php

namespace App\Mail;

use App\Models\Seller;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DailySalesReportToSellerMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(
        public Seller $seller,
        public float $totalAmount,
        public float $totalCommission,
        public int $salesCount,
        public string $date
    ) {
    }


    public function build()
    {
        return $this->subject('Seu resumo diário de vendas')
            ->view('emails.daily-sales-report-to-seller');
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
