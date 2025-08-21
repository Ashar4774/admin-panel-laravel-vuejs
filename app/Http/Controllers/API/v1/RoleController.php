<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\v1\RoleRequest;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return response()->json($roles);
    }

    public function getRoles()
    {
        $roles = Role::get();
        return [
            'roles' => $roles
        ];
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
    public function store(RoleRequest $request)
    {
        try{
            $role = Role::create([
                'name' => $request['name']
            ]);

            return response()->json([
                'message' => 'Role added successfully',
                'role' => $role
            ]);
        } catch(\Exception $e){
            return response()->json([
                'message' => 'There is an error while adding role, please try again',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $role = Role::whereId($id)->first();

        return response()->json([
            'role' => $role
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
    public function update(RoleRequest $request, $id)
    {
        try {
            $role= Role::findOrFail($id);
            $role->update([
                'name' => $request['name']
            ]);
            return response()->json([
                'message' => 'Role updated successfully',
                'role' => $role
            ], 201);
        } catch(\Exception $e){
            return response()->json([
                'message' => 'There is an error while updating role details. Please try again',
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
            $role = Role::findOrFail($id);
            $role->delete();

            return response()->json([
                'message' => 'Role has been deleted successfully'
            ], 201);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'There is an error while deleting role. Please try again',
                'error' =>  $e->getMessage()
            ], 500);
        }
    }
}
