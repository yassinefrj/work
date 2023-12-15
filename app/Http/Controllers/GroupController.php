<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

/**
 * The GroupController class extends Laravel's base Controller class.
 * It is responsible for handling group-related operations such as adding a new group and retrieving existing groups.
 */
class GroupController extends Controller
{
    /**
     * The add_group method is used for adding a new group to the system.
     * It validates the incoming request, creates a new Group instance, saves it to the database,
     * and then redirects to the index of groups.
     */
    public function add_group(Request $req)
    {

        $req->validate([
            'name' => 'required|unique:groups',
            'description' => 'required',
        ]);

        $group = new Group;
        $group->name = $req->input('name');
        $group->description = $req->input('description');
        $group->save();
        return redirect(route('groups.index'));
    }

    /**
     * The getGroup method retrieves all existing groups from the database and renders a view to display them.
     */
    public function getGroup()
    {
        $groups = Group::all();

        return view('groups.show_group', ['groups' => $groups]);
    }

    public function getGroupApi()
    {
        $groups = Group::all();
        return response()->json($groups);
    }
}
