<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Sale extends Model
{
    use HasFactory;
    use Cachable;
    use SoftDeletes;

    protected $with = [
        'seller',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'amount',
        'seller_id',
        'date',
        'seller_commission_amount',
        'seller_commission_percentage',
    ];

    protected static function booted()
    {
        static::saving(
            function ($sale) {
                $sale->seller_commission_percentage = $sale->seller_commission_percentage ?? env('SELLER_COMMISSION_PERCENTAGE', 8.5);
                $sale->seller_commission_amount = $sale->amount * ($sale->seller_commission_percentage / 100);
            }
        );
    }

    public function seller()
    {
        return $this->belongsTo(Seller::class)->withTrashed();
    }

    public function scopeBySellerAndDateRange($query, ?Seller $seller = null, string $dateStart, string $dateEnd)
    {
        $timezone = 'America/Sao_Paulo';
        $start = Carbon::parse($dateStart, $timezone)->startOfDay()->setTimezone('UTC');
        $end = Carbon::parse($dateEnd, $timezone)->endOfDay()->setTimezone('UTC');

        if (!is_null($seller)) {
            $query = $query->where('seller_id', $seller->id);
        }
        return $query->whereBetween('date', [$start, $end]);
    }
}
