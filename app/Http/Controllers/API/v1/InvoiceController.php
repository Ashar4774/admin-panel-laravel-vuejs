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
//        $perPage = $request->get('per_page', 10);
        $invoices = Invoice::with('clients')->orderBy('updated_at','desc')->get();
        return response()->json($invoices);
    }

    public function getInvoices(Request $request)
    {
        $totalRecords = Invoice::count();

        $start = $request->input('start'); // Offset
        $length = $request->input('length'); // Page size
        $draw = $request->input('draw'); // Draw counter for DataTables
        $searchValue = $request->input('search.value'); // Search value if applicable

        $query = Invoice::with('clients');

        // Apply filters from the form
        if ($request->filled('clients_id')) {
            $query->where('clients_id', $request->input('clients_id'));
        }

        if ($request->filled('inv_client_name')) {
            $query->whereHas('clients', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->input('inv_client_name')}%");
            });
        }

        if ($request->filled('amount')) {
            $query->where('amount', $request->input('amount'));
        }

        if ($request->filled('due_date')) {
            $query->whereDate('due_date', $request->input('due_date'));
        }

        if ($request->filled('rcd_amount')) {
            $query->where('rcd_amount', $request->input('rcd_amount'));
        }

        if ($request->filled('rcd_due_date')) {
            $query->whereDate('rcd_due_date', $request->input('rcd_due_date'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->input('payment_type'));
        }

        if ($request->filled('bad_debt_amount')) {
            $query->where('bad_debt_amount', $request->input('bad_debt_amount'));
        }

        // Handle Nullable fields
        if ($request->has('rcd_amount_nullable') && $request->input('rcd_amount_nullable') == 1) {
            $query->whereNull('rcd_amount');
        }

        if ($request->has('bad_debt_amount_nullable') && $request->input('bad_debt_amount_nullable') == 1) {
            $query->whereNull('bad_debt_amount');
        }

        if ($request->has('invoice_status')) {
            if($request->input('invoice_status') == 'paid'){
                $query->whereNotNulL('rcd_amount')
                    ->orWhereNotNull('bad_debt_amount');
            } elseif($request->input('invoice_status') == 'unpaid') {
                $query->whereNull('rcd_amount')->whereNull('bad_debt_amount');
            }
        }

        // Apply search if there's a search value
        if (!empty($searchValue)) {
            $query->whereHas('clients', function ($q) use ($searchValue) {
                $q->where('name', 'like', "%$searchValue%")
                    ->orWhere('ref_no', 'like', "%$searchValue%");
            });
        }

        // Get the total filtered records count
        $filteredRecords = $query->count();

        // Apply pagination
        $invoices = $query->offset($start)
            ->limit($length)
            ->get();

//        dd($invoices);

        $invoices = $invoices->map(function ($invoice) {
            $rowClass = "text-center text-capitalize " . ($invoice->rcd_amount == null
                    ? ($invoice->status == "bad_debts" ? 'bg-gradient-danger text-white' : 'bg-gradient-secondary text-white')
                    : ($invoice->status == "bad_debts" ? 'bg-gradient-danger text-white' : 'bg-gradient-success text-white'));

            return [
                'id' => $invoice->id,
                'ref_no' => $invoice->clients->ref_no,
                'client_name' => $invoice->clients->name ?? '-',
                'amount' => $invoice->amount,
                'due_date' => $invoice->getFormattedDueDateAttribute(),
                'invoice_year' => $invoice->invoice_year,
                'rcd_amount' => $invoice->rcd_amount ?? 0,
                'rcd_due_date' => $invoice->rcd_due_date ? $invoice->getFormattedRcdDueDateAttribute() : '-',
                'time_gap' => $invoice->rcd_due_date ? round($invoice->time_gap()) : '-',
                'bad_debt_amount' => $invoice->bad_debt_amount ?? 0,
                'status' => $invoice->status ?? '-',
                'payment_type' => $invoice->payment_type ?? '-',
//                'notes' => $invoice->notes ?? '-',
                'invoice_status' => ($invoice->rcd_amount != null || $invoice->bad_debt_amount != null) ? 'Paid' : 'Unpaid',
                'row_class' => $rowClass,
//                'actions' => view('invoice.partials.actions', compact('invoice'))->render(),
            ];
        });
        return response()->json([
            'draw' => request('draw'),  // The draw counter from DataTables
            'recordsTotal' => $totalRecords,  // Total records in the database
            'recordsFiltered' => $filteredRecords,  // Total filtered records (adjust if you're filtering)
            'data' => $invoices  // Data for the current page
        ]);
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
