<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\v1\Client\ClientRequest;
use App\Models\Client;
use http\Env\Response;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $clients = Client::orderBy('updated_at', 'desc')->paginate(10);
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
            $client = Client::findOrFail($id);
            return response()->json([
                'message' => 'Client found',
                'client' => $client
            ], 201);
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
