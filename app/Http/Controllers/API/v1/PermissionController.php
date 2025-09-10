<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function getPermissions()
    {
        try {
            $permissions = Permission::get();

            return response()->json([
                'permissions' => $permissions
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'There is an error while showing permissions, please try again',
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
    public function store(Request $request)
    {
        try {
            $permission = Permission::create([
                'name' => $request['name']
            ]);

            return response()->json([
                'message' => 'Permission added successfully',
                'permission' => $permission
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'There is an error while adding permission, please try again',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $permission = Permission::whereId($id)->first();

        return response()->json([
            'permission' => $permission
        ]);
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
        try {
            $permission = Permission::findOrFail($id);
            $permission->update([
                'name' => $request['name']
            ]);
            return response()->json([
                'message' => 'Permission updated successfully',
                'permission' => $permission
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'There is an error while updating permission details. Please try again',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
