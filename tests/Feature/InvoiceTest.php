<?php

namespace Tests\Feature;

use App\Imports\InvoiceDetailImport;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use RefreshDatabase;
    // Index function
    public function test_logged_in_user_can_view_list_of_invoices(){
        $this->setUpLogin();
        // create client
        $client = Client::factory()->create([
            'ref_no' => 'REFTEST001',
            'name' => 'Client Test1'
        ]);
        // create invoice
        $invoice = $this->postJson(route('invoices.store'), [
            'clients_id' => $client->id,
            'amount' => 5000,
            'due_date' => '2025-06-15',
            'rcd_due_date' => '2025-06-20',
            'invoice_year' => '25-26',
            'rcd_amount' => 4500,
            'bad_debt_amount' => 0,
            'payment_type' => 'cash',
            'status' => 'good',
            'notes' => 'Paid partially',
        ]);
        $invoice->assertStatus(201);

        // view invoices
        $invoiceData = Invoice::first();
        $this->assertDatabaseHas('invoices', [
           'id' => $invoiceData->id,
           'clients_id' => $client->id
        ]);

        $this->assertEquals(1, Invoice::count());

    }

    // Store function
    public function test_logged_in_user_can_create_invoice(){
        $this->setUpLogin();

        // Client Create
        $client = Client::factory()->create();

        $invoice = $this->postJson(route('invoices.store'),[
            'clients_id' => $client->id,
            'amount' => 5000,
            'due_date' => '2025-06-15',
            'rcd_due_date' => '2025-06-20',
            'invoice_year' => '25-26',
            'rcd_amount' => 4500,
            'bad_debt_amount' => 0,
            'payment_type' => 'cash',
            'status' => 'good',
            'notes' => 'Paid partially',
        ]);
        $invoice->assertStatus(201);

        $invoiceData = Invoice::first();
        $this->assertDatabaseHas('invoices', [
           'id' => $invoiceData->id,
           'clients_id' => $client->id
        ]);
    }

    // Show specific invoice function
    public function test_logged_in_user_can_view_invoice_with_specific_id(){
        $this->setUpLogin();

        $client = Client::factory()->create([
            'ref_no' => 'REFTEST001',
            'name' => 'Client Test1'
        ]);

        $invoice = $this->postJson(route('invoices.store'),[
            'clients_id' => $client->id,
            'amount' => 5000,
            'due_date' => '2025-06-15',
            'rcd_due_date' => '2025-06-20',
            'invoice_year' => '25-26',
            'rcd_amount' => 4500,
            'bad_debt_amount' => 0,
            'payment_type' => 'cash',
            'status' => 'good',
            'notes' => 'Paid partially',
        ]);
        $invoice->assertStatus(201);

        $invoiceData = Invoice::first();

        $invoiceById = $this->getJson(route('invoices.show', $invoiceData->id));
        $invoiceById->assertStatus(201);
    }
    // Update function
    public function test_logged_in_user_can_update_invoice(){
        $this->setUpLogin();

        $client = Client::factory()->create([
            'ref_no' => 'REFTEST001',
            'name' => 'Client Test1'
        ]);

        $invoice = $this->postJson(route('invoices.store'),[
            'clients_id' => $client->id,
            'amount' => 5000,
            'due_date' => '2025-06-15',
            'rcd_due_date' => '2025-06-20',
            'invoice_year' => '25-26',
            'rcd_amount' => 4500,
            'bad_debt_amount' => 0,
            'payment_type' => 'cash',
            'status' => 'good',
            'notes' => 'Paid partially',
        ]);
        $invoice->assertStatus(201);

        $invoiceData = Invoice::first();

        $invoiceUpdate = $this->putJson(route('invoices.update', $invoiceData->id), [
            'clients_id' => $client->id,
            'amount' => 5000,
            'due_date' => '2025-06-15',
            'rcd_due_date' => '2025-06-20',
            'invoice_year' => '25-26',
            'rcd_amount' => 4500,
            'bad_debt_amount' => 0,
            'payment_type' => 'bank',
            'status' => 'good',
            'notes' => 'Updated Invoice',
        ]);
        $invoiceUpdate->assertStatus(200);
        $this->assertDatabaseHas('invoices', [
            'notes' => 'Updated Invoice'
        ]);
    }

    // Delete function
    public function test_logged_in_user_can_delete_invoice(){
        $this->setUpLogin();

        $client = Client::factory()->create([
            'ref_no' => 'REFTEST001',
            'name' => 'Client Test1'
        ]);

        $invoice = $this->postJson(route('invoices.store'),[
            'clients_id' => $client->id,
            'amount' => 5000,
            'due_date' => '2025-06-15',
            'rcd_due_date' => '2025-06-20',
            'invoice_year' => '25-26',
            'rcd_amount' => 4500,
            'bad_debt_amount' => 0,
            'payment_type' => 'cash',
            'status' => 'good',
            'notes' => 'Paid partially',
        ]);
        $invoice->assertStatus(201);

        $invoiceData = Invoice::first();

        $invoiceDelete = $this->deleteJson(route('invoices.destroy', $invoiceData->id));
        $invoiceDelete->assertStatus(201);
        $this->assertEquals(0, Invoice::count());
    }

    // Import function
    public function test_logged_in_user_can_import_invoices(){
        $this->setUpLogin();

        Client::factory()->create([
            'ref_no' => 'REFTEST001',
            'name' => 'Client Test1'
        ]);

        Excel::fake();

        $file = UploadedFile::fake()->create('invoices.xlsx');
        $invoiceUpdated = $this->postJson(route('invoices.import'), [
            'invoice_file' => $file
        ]);
        $invoiceUpdated->assertStatus(201);
        $invoiceUpdated->assertJson([
            'message' => 'Invoice records has been imported successfully'
        ]);

        // Ensure import was triggered
        Excel::assertImported('invoices.xlsx', function ($import) {
            return $import instanceof InvoiceDetailImport;
        });
    }

    // Filter Invoice function
    public function test_logged_in_user_can_filter_invoice_from_list(){
        // login user
        $this->setUpLogin();

        // create client
        $client = Client::factory()->create([
            'ref_no' => 'REFTEST001',
            'name' => 'Client Test1'
        ]);

        // create invoice
        $invoice = $this->postJson(route('invoices.store'),[
           'clients_id' => $client->id,
            'amount' => 5000,
            'due_date' => '2025-06-15',
            'rcd_due_date' => '2025-06-20',
            'invoice_year' => '25-26',
            'rcd_amount' => 4500,
            'bad_debt_amount' => 0,
            'payment_type' => 'cash',
            'status' => 'good',
            'notes' => 'Paid partially',
        ]);
        $invoice->assertStatus(201);

        // filter invoice
        $invoiceFilter = $this->getJson(route('getInvoices',[
            'rcd_amount' => 4500,
            'start' => 0,
            'length' => 10,
            'draw' => 1
        ]));
        $invoiceFilter->assertStatus(200);
    }
}
