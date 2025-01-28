<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\v1\Invoice\InvoiceImportRequest;
use App\Http\Requests\API\v1\Invoice\InvoiceRequest;
use App\Imports\InvoiceDetailImport;
use App\Models\Client;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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

    public function import(InvoiceImportRequest $request){
        try {
            Excel::import(new InvoiceDetailImport, $request->file('invoice_file'));

            return response()->json([
                'message' => 'Invoice records has been imported successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'There is something wrong while importing invoice file, please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
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
    public function show($id)
    {
        try{
            $invoice = Invoice::findOrFail($id);
            return response()->json([
               'message' => 'Invoice found',
               'invoice' => $invoice
            ], 201);
        } catch(\Exception $e){
            return response()->json([
                'message' => 'There is something wrong while fetching Invoice result. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }

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
    public function update(InvoiceRequest $request, $id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            $invoice->update([
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
                'message' => 'Invoice updated successfully',
                'invoice' => $invoice
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'There is an error while updating invoice. Please try again.',
                'error' => $e->getMessage()
            ]);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $invoice = Invoice::findOrFail($id);
            $invoice->delete();
            return response()->json([
                'message' => 'Invoice has been deleted'
            ], 201);
        } catch(\Exception $e){
            return response()->json([
                'error' => 'There is an error while deleting Invoice, please try again'
            ], 500);
        }
    }
}
