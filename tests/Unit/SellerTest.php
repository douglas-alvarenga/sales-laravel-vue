<?php

namespace Tests\Unit;

use Mockery;
use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Seller;
use Illuminate\Support\Facades\Mail;
use App\Services\DailySalesReportService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SellerTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldSuccessfullyCreateSeller()
    {
        $seller = Seller::factory()->make();
        $seller = $seller->toArray();

        $response = $this->postJson(
            '/api/seller',
            $seller,
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getTokenLogin(),
            ]
        );

        $response->assertSuccessful()
            ->assertJson(['success' => true]);
        $this->assertDatabaseHas('sellers', ['id' => $response->json('data.seller.id')]);
    }

    public function testShouldSuccessfullyUpdateSeller()
    {
        $seller = Seller::factory()->create();
        $seller = $seller->toArray();
        $seller['name'] = 'teste update';

        $response = $this->putJson(
            '/api/seller/' . $seller['id'],
            $seller,
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getTokenLogin(),
            ]
        );

        $response->assertSuccessful()
            ->assertJson(['success' => true]);
        $this->assertDatabaseHas('sellers', [
            'id' => $seller['id'],
            'name' => $seller['name'],
        ]);
    }

    public function testShouldSuccessfullyRestoreSeller()
    {
        $seller = Seller::factory()->trashed()->create();
        $this->assertSoftDeleted($seller);

        $response = $this->putJson(
            '/api/seller/' . $seller->id . '/restore',
            [],
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getTokenLogin(),
            ]
        );

        $response->assertSuccessful()
            ->assertJson(['success' => true]);
        $this->assertNotSoftDeleted($seller);
    }

    public function testShouldSuccessfullyShowAllSellers()
    {
        $count = 10;
        Seller::truncate();
        Seller::factory($count)->create();

        $response = $this->getJson(
            '/api/seller',
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getTokenLogin(),
            ]
        );

        $response->assertSuccessful()
            ->assertJson(['success' => true])
            ->assertJsonCount($count, 'data.sellers');
    }

    public function testShouldSuccessfullyShowSellerById()
    {
        $seller = Seller::factory()->create();

        $response = $this->getJson(
            '/api/seller/' . $seller->id,
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getTokenLogin(),
            ]
        );

        $response->assertJsonFragment($seller->toArray());
    }

    public function testShouldSuccessfullyDeleteSeller()
    {
        $seller = Seller::factory()->create();

        $response = $this->deleteJson(
            '/api/seller/' . $seller->id,
            [],
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getTokenLogin(),
            ]
        );

        $response->assertSuccessful()
            ->assertJson(['success' => true]);
        $this->assertSoftDeleted($seller);
    }

    public function testShouldFailureRestoreSeller()
    {
        Seller::truncate();

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getTokenLogin(),
        ];

        $this->put("/api/seller/" . rand(5, 10) . "/restore", $headers)->assertUnprocessable();
    }

    public function testShouldSuccessfullySendDailySalesReportToSellerManually()
    {
        $seller = Seller::factory()->create();
        $date = Carbon::now()->format('Y-m-d');

        $mock = Mockery::mock(DailySalesReportService::class);
        $mock->shouldReceive('sendMailToSeller')
            ->once()
            ->with(\Mockery::type(Seller::class), $date)
            ->andReturn(true);
        $this->app->instance(\App\Services\DailySalesReportService::class, $mock);

        $response = $this->postJson(
            '/api/seller/' . $seller->id . '/send-daily-sales-report',
            [
                'date' => $date,
            ],
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getTokenLogin(),
            ]
        );
        $response->assertSuccessful();
    }

    protected function setup(): void
    {
        parent::setUp();
    }
}
