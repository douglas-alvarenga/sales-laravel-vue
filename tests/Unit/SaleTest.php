<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Sale;
use App\Models\Seller;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SaleTest extends TestCase
{
    use RefreshDatabase;

    public function testShouldSuccessfullyCreateSale()
    {
        $sale = Sale::factory()->make();
        $sale = $sale->toArray();

        $response = $this->postJson(
            '/api/sale',
            $sale,
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getTokenLogin(),
            ]
        );

        $response->assertSuccessful()
            ->assertJson(['success' => true]);
        $this->assertDatabaseHas('sales', [
            'id' => $response->json('data.sale.id'),
            'seller_commission_amount' => ($sale['seller_commission_percentage'] / 100) * $sale['amount'],
        ]);
    }

    public function testShouldSuccessfullyUpdateSale()
    {
        $sale = Sale::factory()->create();
        $sale = $sale->toArray();
        $sale['amount'] += 50;

        $response = $this->putJson(
            '/api/sale/' . $sale['id'],
            $sale,
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getTokenLogin(),
            ]
        );

        $response->assertSuccessful()
            ->assertJson(['success' => true]);
        $this->assertDatabaseHas('sales', [
            'id' => $sale['id'],
            'amount' => $sale['amount'],
        ]);
    }

    public function testShouldSuccessfullyRestoreSale()
    {
        $sale = Sale::factory()->trashed()->create();
        $this->assertSoftDeleted($sale);

        $response = $this->putJson(
            '/api/sale/' . $sale->id . '/restore',
            [],
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getTokenLogin(),
            ]
        );

        $response->assertSuccessful()
            ->assertJson(['success' => true]);
        $this->assertNotSoftDeleted($sale);
    }

    public function testShouldSuccessfullyShowAllSales()
    {
        $count = 10;
        Sale::truncate();
        Sale::factory($count)->create();

        $response = $this->getJson(
            '/api/sale',
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getTokenLogin(),
            ]
        );

        $response->assertSuccessful()
            ->assertJson(['success' => true])
            ->assertJsonCount($count, 'data.sales');
    }

    public function testShouldSuccessfullyShowSaleById()
    {
        $sale = Sale::factory()->create();

        $response = $this->getJson(
            '/api/sale/' . $sale->id,
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getTokenLogin(),
            ]
        );

        $responseData = $response->json('data.sale');
        $this->assertEquals($sale->id, $responseData['id']);
        $this->assertEqualsWithDelta($sale->amount, $responseData['amount'], 0.001);
        $this->assertEqualsWithDelta($sale->seller_commission_amount, $responseData['seller_commission_amount'], 0.001);
        $this->assertEqualsWithDelta($sale->seller_commission_percentage, $responseData['seller_commission_percentage'], 0.001);
        $this->assertEquals($sale->seller_id, $responseData['seller_id']);

        $response->assertJson(['success' => true]);
    }

    public function testShouldSuccessfullyShowSaleBySeller()
    {
        $count = 10;
        $seller = Seller::factory()->create();
        $sale = Sale::factory($count)->create(['seller_id' => $seller->id]);
        $sale = Sale::factory(20)->create();

        $response = $this->getJson(
            '/api/sale?seller=' . $seller->id,
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getTokenLogin(),
            ]
        );

        $response->assertSuccessful()
            ->assertJson(['success' => true])
            ->assertJsonCount($count, 'data.sales');
    }

    public function testShouldSuccessfullyDeleteSale()
    {
        $sale = Sale::factory()->create();

        $response = $this->deleteJson(
            '/api/sale/' . $sale->id,
            [],
            [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getTokenLogin(),
            ]
        );

        $response->assertSuccessful()
            ->assertJson(['success' => true]);
        $this->assertSoftDeleted($sale);
    }

    public function testShouldFailureRestoreSale()
    {
        Sale::truncate();

        $headers = [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getTokenLogin(),
        ];

        $this->put("/api/sale/" . rand(5, 10) . "/restore", $headers)->assertUnprocessable();
    }

    protected function setup(): void
    {
        parent::setUp();
    }
}
