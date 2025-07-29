<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
    // Index function
    public function test_logged_in_user_can_view_list_of_invoices(){
        // create user

        // login user

        // create invoice with client

        // view invoices
    }

    // Store function
    public function test_logged_in_user_can_create_invoice(){
        //
    }

    // Show specific invoice function
    public function test_logged_in_user_can_view_invoice_with_specific_id(){
        //
    }
    // Update function
    public function test_logged_in_user_can_update_invoice(){
        //
    }

    // Delete function
    public function test_logged_in_user_can_delete_invoice(){
        //
    }

    // Import function
    public function test_logged_in_user_can_import_invoices(){
        //
    }

    // Filter Invoice function
    public function test_logged_in_user_can_filter_invoice_from_list(){
        //
    }

    //
}
