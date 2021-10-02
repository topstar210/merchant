<?php

namespace App\Http\Controllers;

use App\Models\MerchantLien;
use App\Services\LienService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    //

    public function index(Request $request)
    {
        return view('app.account.index');
    }

    public function changeSecurity(Request $request, $change)
    {
        if (in_array($change, ['password', 'pin'])) {
            return view('app.account.security', ['action' => $change]);
        }

        return redirect('app')->with(['error' => true, 'error_message' => 'Invalid action initiated']);
    }

    public function releaseLienAPI(Request $request, MerchantLien $lien)
    {
        return LienService::releaseLien($lien);
    }

}
