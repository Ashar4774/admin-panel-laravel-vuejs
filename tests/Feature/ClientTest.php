<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ClientTest extends TestCase
{
//    use RefreshDatabase;
    /**
     * List of clients test.
     */
    public function test_list_clients(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        Client::factory()->hasInvoices(2)->count(5)->create();

        $response = $this->getJson('/api/clients');

        $response->assertStatus(200)
            ->assertOk()
            ->assertJsonStructure(['data']);
    }

    /**
     * adding client test.
     */
    public function test_store_client(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $refNo = 'REF' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $data = ['ref_no' => $refNo, 'client_name' => 'Test Client1'];

        $response = $this->postJson("/api/clients", $data);
        $response->assertStatus(201)
            ->assertJson(['message' => "Client Added Successfully"]);
        $this->assertDatabaseHas("clients", [
            'ref_no' => $refNo,
            'name' => 'Test Client1'
        ]);
    }

    /**
     * showing client test.
     */
    public function test_show_a_client(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $client = Client::factory()->hasInvoices(2)->create();

        $response = $this->getJson("/api/clients/{$client->id}");

        $response->assertStatus(201)
            ->assertJsonFragment(['client' => ['id' => $client->id]]);
    }

    /**
     * updating client test.
     */
    public function test_update_a_client(): void
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
    }

    /**
     * deleting client test.
     */
    public function test_delete_a_client(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $client = Client::factory()->hasInvoices(2)->create();
        $response = $this->deleteJson('/api/clients/{$client->id}');
        $response->assertStatus(201)
            ->assertJsonFragment(['message' => 'Client deleted successfully']);
    }

    /**
     * importing clients test.
     */
    public function test_import_clients_from_excel(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $file = UploadedFile::fake()->create('clients.xlsx');

        $response = $this->postJson('/api/clients/import', [
            'client_file' => $file
        ]);

        $response->assertOk()
            ->assertJsonFragment(['message' => 'Client data imported successfully']);
    }

    /**
     * State of account of client test.
     */
    public function test_return_state_of_account(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        $client = Client::factory()->hasInvoices(2)->create();

        $response = $this->getJson('/api/clients/state_of_account/{$client->id}');
        $response->assertOk()
            ->assertJsonFragment(['client' => ['id' => $client->id]]);
    }
}
