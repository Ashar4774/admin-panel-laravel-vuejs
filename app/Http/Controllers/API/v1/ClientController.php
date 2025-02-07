<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\v1\Client\ClientImportRequest;
use App\Http\Requests\API\v1\Client\ClientRequest;
use App\Imports\CLientDetailImport;
use App\Models\Client;
use http\Env\Response;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clients = Client::with('invoices')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        // Map over the items in the paginated collection
        $clients->getCollection()->transform(function ($client) {
            $client->arrears = $client->calculateArrears();
            $client->bad_debts = $client->calculateBadDebts();
            return $client;
        });
        return response()->json($clients);
    }

    public function import(ClientImportRequest $request)
    {
        try {
            Excel::import(new CLientDetailImport, $request->file('client_file'));

            return response()->json([
                'message' => 'Client record imported successfully.'
            ], 200);
        } catch(\Exception $e){
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'There is an error while uploading file. Please try again later'
            ]);
        }

    }

    public function state_of_account($id)
    {
        try {
            $client = Client::with('invoices')->where('id', $id)->first();
            if ($client) {
                $client->arrears = $client->calculateArrears();
                $client->bad_debt = $client->calculateBadDebts();

                return response()->json([
                    'client' => $client
                ]);
            }

//            flash()->success('Client details are found.');

        } catch(Exception $e){
            flash()->error('Client details are not found.');

            return response()->json([
                'message' => 'Client details are not found.',
                'error' => $e->getMessage()
            ]);
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
    public function store(ClientRequest $request)
    {
        try {
            $client = Client::create([
                'ref_no' => $request['ref_no'],
                'name' => $request['client_name']
            ]);

            return response()->json([
                'message' => 'Client Added Successfully',
                'client' => $client
            ], 201);
        } catch(\Exception $e){
            return response()->json([
                'message' => 'There is an error while adding client details. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $client = Client::with('invoices')->findOrFail($id);
            if ($client) {
                $client->payment = $client->calculatePayments();
                $client->arrears = $client->calculateArrears();
                $client->bad_debt = $client->calculateBadDebts();

                return response()->json([
                    'message' => 'Client found',
                    'client' => $client
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Client not found'
                ], 404);
            }
        } catch(\Exception $e){
            return response()->json([
                'message' => 'There is an error while fetching client details. Please try again.',
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
    public function update(Request $request, $id)
    {
        try {
            $client= Client::findOrFail($id);
            $client->update([
                'ref_no' => $request['ref_no'],
                'name' => $request['client_name']
            ]);
            return response()->json([
                'message' => 'Client Updated successfully',
                'client' => $client
            ], 201);
        } catch(\Exception $e){
            return response()->json([
                'message' => 'There is an error while updating client details. Please try again',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->delete();

            return response()->json([
                'message' => 'User has been deleted success fully'
            ], 201);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'There is an error while deleting client. Please try again',
                'error' =>  $e->getMessage()
            ], 500);
        }
    }
}
