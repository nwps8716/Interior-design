<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Alert;

class HomeController extends Controller
{
    /**
     * 主頁面
     *
     * @return boolean
     */
    public function index(Request $_oRequest)
    {
        ## 判斷使用者權限
        $sCheckSession = $this->checkSession($_oRequest, true);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
        }
        return view('home');
    }
}