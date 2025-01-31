<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

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
        }

    }
}
