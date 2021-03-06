<?php

namespace Tests\Feature;

use App\Packeges\PdfGenerator\Adapters\MpdfAdaptor;
use App\Packeges\PdfGenerator\PdfGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\View;
use Nette\Utils\Json;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    public $token;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $response = ($this->post("/api/en/login-test", ["user_id" => 2])->send()->getData());
        if($response){
            $this->token = "Bearer " . $response->data->token;
        }
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCreateInvoice()
    {
        $response = $this->get('/api/en/technician/invoices/create', ["Authorization" => $this->token]);
        $response->assertStatus(200);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStoreInvoiceValidationError()
    {
        $dataRequest = [
            "services" => [
                [
                "id" => 5,
                "count" => 3,
                "price" => 11
                ],
            ],
            "order_id" => 1,
            "extra_services" => [
                [
                    "name" => "add monitor",
                    "count" => 1,
                    "price" => 15
                ]
            ],
            "parts" => [
                [
                    "name" => "monitor",
                    "count" => 1,
                    "price" => 5
                ]
            ]
        ];

        $response = $this->post('/api/en/technician/invoices/store', $dataRequest, ["Authorization" => $this->token]);
        $response->assertStatus(200);
        $response->assertJson(["status_number" => "S400"]);
//        dd($response->getData());
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStoreInvoiceSuccess()
    {
        $dataRequest = [
            "services" => [
                [
                    "id" => 5,
                    "count" => 3,
                    "price" => 11
                ],
            ],
            "order_id" => 2,
            "extra_services" => [
                [
                    "name" => "add monitor",
                    "count" => 1,
                    "price" => 15
                ]
            ],
            "parts" => [
                [
                    "name" => "monitor",
                    "count" => 1,
                    "price" => 15
                ]
            ]
        ];

        $response = $this->post('/api/en/technician/invoices/store', $dataRequest, ["Authorization" => $this->token]);

        dd($response->getData());

        $response->assertStatus(200);
        $response->assertJson(["status_number" => "S201"]);

    }


    /**
     * @throws \Mpdf\MpdfException
     */
    public function testGetInvoice(){

        $response = $this->get('/api/en/orders/2/invoice/show', ["Authorization" => $this->token]);

        $response->assertStatus(200);
       // $response->assertJson(["status_number" => "S201"]);

        //$pdf->make();
    }
}
