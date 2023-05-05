<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    //




  public function indexRoles(){


        $roles = DB::table('roles')->select('*')->get();

       return view('roles/roles_index',compact('roles'))->with('user', auth()->user());


    }





    function editRoles(){




    }





}
