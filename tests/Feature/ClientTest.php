<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use RefreshDatabase;
    /**
     * adding clients test.
     */
    public function test_logged_in_user_can_create_a_client(): void
    {
        $this->setUpLogin();

        // Client Create
        $client = $this->postJson(route('clients.store'),[
           'ref_no' => 'REFTEST001',
           'client_name' => 'Client Test1'
        ]);
        $client->assertStatus(201);
        $this->assertEquals(1, Client::count());
        $this->assertJson($client->getContent());
    }

    /**
     * show clients list test.
     */
    public function test_logged_in_user_can_view_clients_list(){
        $this->setUpLogin();

        // create client
        Client::factory()->hasInvoices(2)->create([
            'ref_no' => 'REFTEST001',
            'name' => 'Client Test1'
        ]);

        // view clients
        $response = $this->getJson(route('clients.index'));
        $response->assertStatus(200);
        $this->assertEquals(1, Client::count());
    }

    /**
     * showing client test.
     */
    public function test_logged_in_user_can_show_the_client_with_specific_id(){
        $this->setUpLogin();

        // create client
        Client::factory()->hasInvoices(2)->create([
            'ref_no' => 'REFTEST001',
            'name' => 'Client Test1'
        ]);

        // show client with id
        $client = Client::with('invoices')->first();
        $showClient = $this->getJson(route('clients.show', $client->id));
        $this->assertEquals($client->ref_no, 'REFTEST001');
        $this->assertEquals($client->name, 'Client Test1');
        $showClient->assertStatus(201);
    }


    /**
     * updating client test.
     */
    public function test_logged_in_user_can_update_the_client(){
        $this->setUpLogin();
        // create client
        Client::factory()->hasInvoices(2)->create([
            'ref_no' => 'REFTEST001',
            'name' => 'Client Test1'
        ]);
        // update client
        $client = Client::first();
        $response = $this->putJson(route('clients.update', $client->id), [
            'ref_no' => 'REFUPTEST001',
            'client_name' => 'Client Updated Test1'
        ]);

        $updatedClient = Client::first();
        //check updated client
        $this->assertEquals('REFUPTEST001', $updatedClient->ref_no);
        $this->assertEquals('Client Updated Test1', $updatedClient->name);
        $response->assertStatus(201);

    }

    /**
     * deleting client.
     */
    public function test_logged_in_user_can_delete_the_client(){
        $this->setUpLogin();
        // create client
        Client::factory()->hasInvoices(2)->create([
            'ref_no' => 'REFTEST001',
            'name' => 'Client Test1'
        ]);

        // delete client data
        $client = Client::first();

        $response = $this->deleteJson(route('clients.destroy', $client->id));
        $response->assertStatus(201);
    }

    /**
     * importing clients test.
     */
    public function test_logged_in_user_can_import_clients_from_excel(): void
    {
        $this->setUpLogin();
        $file = UploadedFile::fake()->create('clients.xlsx');

        $response = $this->postJson(route('clients.import'), [
            'client_file' => $file
        ]);

        $response->assertStatus(200);
    }

    /**
     * State of account of client test.
     */
    public function test_logged_in_user_can_view_state_of_account_of_user(){
        $this->setUpLogin();

        // create Client
        Client::factory()->hasInvoices(2)->create([
            'ref_no' => 'REFTEST001',
            'name' => 'Client Test1'
        ]);

        $clientData = Client::first();

        // show invoice by hitting route clients/stateOfAccount/id
        $stateResponse = $this->getJson(route('clients.state_of_account', $clientData->id));
        $stateResponse->assertStatus(200);
        $stateResponse->assertJsonStructure([
            'client'
        ]);

    }

}
