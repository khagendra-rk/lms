<?php

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * It returns a JSON response of all the permissions in the database.
     * 
     * @return A collection of all the permissions in the database.
     */
    public function index()
    {
        // $this->authorize('view-permissions', Permission::class);
        $permissons = Permission::all();
        return response()->json($permissons);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', Permission::class);
        $data = $request->validate([
            'name' => ['required'],
            'slug' => ['required'],
            'for' => ['required'],
        ]);
        $permission = Permission::create($data);
        return response()->json($permission, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function show(Permission $permission)
    {
        $this->authorize('view', $permission);
        return response()->json($permission);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $this->authorize('update', $permission);
        $data = $request->validate([
            'name' => ['required'],
        ]);
        $permission->update($data);
        $permission->fresh();
        return response()->json($permission);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Permission  $permission
     * @return \Illuminate\Http\Response
     */
    public function destroy(Permission $permission)
    {
        $this->authorize('delete', $permission);
        $permission->delete();
        return response()->noContent();
    }
}
