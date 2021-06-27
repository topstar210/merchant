<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AgentsController extends Controller
{
    //
    public function __construct()
    {

    }

    public function all(Request $request)
    {
        return view('app.agents.all');
    }

    public function add(Request $request)
    {
        return view('app.agents.add');
    }

    public function view(Request $request, User $user)
    {
        return view('app.agents.view', ['agent' => $user]);
    }


}
