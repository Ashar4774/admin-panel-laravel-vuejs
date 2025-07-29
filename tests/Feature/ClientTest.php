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
        // create user
        $user = User::factory()->create();
        // login
        $this->actingAs($user, 'sanctum');

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
        // create user
        $user = User::factory()->create();
        // login user with sanctum
        $this->actingAs($user, 'sanctum');
        // create client
        $client = Client::factory()->hasInvoices(2)->create([
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
        // create user
        $user = User::factory()->create();
        // login user with sanctum
        $this->actingAs($user, 'sanctum');
        // create client
        $storeClient = Client::factory()->hasInvoices(2)->create([
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
        // create user
        $user = User::factory()->create();
        // login user with sanctum
        $this->actingAs($user, 'sanctum');
        // create client
        $storeClient = Client::factory()->hasInvoices(2)->create([
            'ref_no' => 'REFTEST001',
            'name' => 'Client Test1'
        ]);
        $storeClient->assertStatus(201);
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
    /*public function test_update_a_client(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $client = Client::factory()->hasInvoices(2)->create();
        $response = $this->putJson('/api/clients/{$client->id}', [
            'ref_no' => 'REFNEW',
            'name' => 'Updated Test Name'
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['message' => 'Client Updated Successfully']);
    }*/

    /**
     * deleting client.
     */
    public function test_logged_in_user_can_delete_the_client(){
        // create user
        $user = User::factory()->create();
        // login user with sanctum
        $this->actingAs($user, 'sanctum');
        // create client
        $storeClient = Client::factory()->hasInvoices(2)->create([
            'ref_no' => 'REFTEST001',
            'name' => 'Client Test1'
        ]);
        $storeClient->assertStatus(201);

        // delete client data
        $client = Client::first();

        $response = $this->deleteJson(route('clients.destroy', $client->id));
        $response->assertStatus(201);
    }

    /**
     * importing clients test.
     */
    /*public function test_import_clients_from_excel(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $file = UploadedFile::fake()->create('clients.xlsx');

        $response = $this->postJson('/api/clients/import', [
            'client_file' => $file
        ]);

        $response->assertOk()
            ->assertJsonFragment(['message' => 'Client data imported successfully']);
    }*/

    /**
     * State of account of client test.
     */
    public function test_logged_in_user_can_view_state_of_account_of_user(){
        // create user
        $user = User::factory()->create();

        // logged in user
        $this->actingAs($user, 'sanctum');

        // create Client
        $client = Client::factory()->hasInvoices(2)->create([
            'ref_no' => 'REFTEST001',
            'name' => 'Client Test1'
        ]);
        $client->assertStatus(201);

        $clientData = Client::first();

        // create invoices against that client
        /*$invoice = Invoice::factory()->count(3)->create([
           'clients_id' => $clientData->id
        ]);*/
//        $invoice->assertStatus(201);

        // show invoice by hitting route clients/stateOfAccount/id
        $stateResponse = $this->getJson(route('clients.state_of_account', $clientData->id));
        $stateResponse->assertStatus(200);
        $stateResponse->assertJsonStructure([
            'client'
        ]);

    }
    /*public function test_return_state_of_account(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $client = Client::factory()->hasInvoices(2)->create();

        $response = $this->getJson('/api/clients/state_of_account/{$client->id}');
        $response->assertOk()
            ->assertJsonFragment(['client' => ['id' => $client->id]]);
    }*/
}
