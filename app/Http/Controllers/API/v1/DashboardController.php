<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\v1\Auth\UpdatePasswordRequest;
use App\Http\Requests\API\v1\Auth\UserProfileRequest;
use App\Http\Requests\API\v1\Auth\UserProfileImageRequest;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            $auth = Auth::user()->load('roles.permissions');
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

    public function update_profile_image(UserProfileImageRequest $request){
        try {

            if ($request->hasFile('image')) {
                $file = $request->file('image');

                // Get the original file name and extension
                $originalName = $file->getClientOriginalName();
                $destinationPath = public_path('assets/img/profile_images');
                $file->move($destinationPath, $originalName);

                // Save the image path to the user's profile
                $attributes['image'] = 'assets/img/profile_images/' . $originalName;
            }
            User::where('id',Auth::user()->id)
                ->update([
                    'image'    => $attributes['image'] ?? Auth::user()->image,
                ]);

            return response()->json([
               'message' => 'Image profile updated successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'There is something wrong while updating image, please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update_profile(UserProfileRequest $request){
        try {

            /*if ($request->hasFile('image')) {
                $file = $request->file('image');

                // Get the original file name and extension
                $originalName = $file->getClientOriginalName();
                $destinationPath = public_path('assets/img/profile_images');
                $file->move($destinationPath, $originalName);

                // Save the image path to the user's profile
                $attributes['image'] = 'assets/img/profile_images/' . $originalName;
            }*/
            User::where('id',Auth::user()->id)
                ->update([
//                    'image'    => $attributes['image'] ?? Auth::user()->image,
                    'name'    => $request['name'],
                    'email' => $request['email'],
                    'phone'     => $request['phone'],
                    'location' => $request['location'],
                    'about_me'    => $request["about_me"],
                ]);

            return response()->json([
               'message' => 'Profile updated successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'There is something wrong while updating profile, please try again.',
                'error' => $e->getMessage()
            ]);
        }
    }

    public function update_password(UpdatePasswordRequest $request){
        try {
            $user = Auth::user();

            // Check if current password matches
            if (!Hash::check($request['password'], $user->password)) {
                return response()->json([
                    'message' => 'The current password is incorrect.',
                ], 422);
            }

            // Update the user's password
            $user->update([
                'password' => Hash::make($request->new_password),
            ]);

            return response()->json([
                'message' => 'Password changed successfully'
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'There is something wrong while updating profile, please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
