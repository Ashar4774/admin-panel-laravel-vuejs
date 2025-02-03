<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(){
        try {
            $clients = Client::with('invoices')->get();
            $totalAmounts = 0;
            $totalArrears = 0;
            $totalBad_debts = 0;
            $totalPayments = 0;
            foreach($clients as $client){
                if($client->invoices) {
                    $totalArrears += ($client->calculateArrears() - $client->calculateBadDebts());
                    $totalBad_debts += $client->calculateBadDebts();
                    $totalAmounts += $client->calculateAmounts();
                    $totalPayments += $client->calculatePayments();
                }
            }

            return response()->json([
                'totalArrears' => $totalArrears,
                'totalBad_debts' => $totalBad_debts,
                'totalAmounts' => $totalAmounts,
                'totalPayments' => $totalPayments,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
               'message' => 'Something went wrong regarding getting information',
               'error' => $e->getMessage()
            ]);
        }

    }

    public function user_profile(){
        try {
            $auth = Auth::user();
            return response()->json([
               'auth' => $auth,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'There is error while fetching logged in user, please check if user is logged in or not.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
