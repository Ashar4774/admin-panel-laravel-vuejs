<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\v1\Invoice\InvoiceRequest;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $invoices = Invoice::with('clients')->orderBy('updated_at','desc')->paginate(10);
        return response()->json($invoices);
    }

    public function fetchClients()
    {
        $clients = Client::get();
        return response()->json($clients);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(InvoiceRequest $request)
    {
        try{
            $invoice = Invoice::create([
                'clients_id' => $request['clients_id'],
                'amount' => $request['amount'],
                'due_date' => $request['due_date'],
                'rcd_amount' => $request['rcd_amount'],
                'rcd_due_date' => $request['rcd_due_date'],
                'invoice_year' => $request['invoice_year'],
                'notes' => $request['notes'],
                'status' => $request['status'],
                'payment_type' => $request['payment_type'],
            ]);

            return response()->json([
                'message' => 'Invoice added successfully',
                'invoice' => $invoice
            ], 201);
        } catch(\Exception $e){
            return response()->json([
                'message' => 'There is problem while creating invoice, please try again.',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
