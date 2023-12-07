<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function add_group(Request $req){

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

    public function getGroup(){
        $groups= Group::all();
        return view('groups.show_group',['groups'=>$groups]);
    }

    

}
