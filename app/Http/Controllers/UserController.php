<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user.index');
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find($id);
        $user->assignRole("MANAGER");

        return 200;
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
        $user = User::find($id);
        $user->syncRoles();
        $user->delete();

        return 200;
    }

    public function datatables(Request $request)
    {
        $draw = (int)$request->get('draw');
        $start = (int)$request->get("start");
        $rowperpage = (int)$request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnIndex = $columnIndex_arr[0]['column']; // Column index
        $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue = $search_arr['value']; // Search value
        $searchRole = $columnName_arr[4]['search']['value'];

        // Total records
        $totalRecords = User::select('count(*) as allcount')->count();
        $totalRecordswithFilter = User::select('count(*) as allcount')->where('username', 'like', '%' . $searchValue . '%')->count();

        // Fetch records
        if ($searchRole == null) {
            $records = User::orderBy($columnName, $columnSortOrder)
                ->where('users.username', 'like', '%' . $searchValue . '%')
                ->select('users.*', 'roles.name as role')
                ->leftjoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->leftjoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->skip($start)
                ->take($rowperpage)
                ->get();
        } else {

            $searchRoleValue = substr($searchRole, 1, strlen($searchRole) - 2);
            $records = User::orderBy($columnName, $columnSortOrder)
                ->where('users.username', 'like', '%' . $searchValue . '%')
                ->where('model_has_roles.role_id', 'like', $searchRoleValue)
                ->select('users.*', 'roles.name as role')
                ->leftjoin('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
                ->leftjoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
                ->skip($start)
                ->take($rowperpage)
                ->get();
        }
        // dd($records);
        $data_arr = array();

        foreach ($records as $record) {
            $id = $record->id;
            $username = $record->username;
            $created_at = $record->created_at;
            $updated_at = $record->updated_at;
            $role = $record->role;

            $data_arr[] = array(
                "id" => $id,
                "username" => $username,
                "created_at" => $created_at,
                "updated_at" => $updated_at,
                "role" => $role,
                "action" => $id,
            );
        }

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $data_arr
        );

        return json_encode($response);
    }

    public function role()
    {
        $roles = Role::all();

        return $roles;
    }
}
