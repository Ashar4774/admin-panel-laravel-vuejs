<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class StateOfAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($ref_no)
    {
        try{
            $this->authorize('view', StateOfAccount::class);
            $client = Client::with('invoices')->where('ref_no', 'LIKE', "%{$ref_no}%")->first();
            $html = '';
            if ($client) {
                $client->arrears = $client->calculateArrears();
                $client->bad_debt = $client->calculateBadDebts();
//                $html = view('client.partials.invoice', compact('client'))->render();

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
     * Show the form for editing the specified resource.
     */
    public function edit($ref_no)
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
