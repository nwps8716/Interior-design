<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Model\User\User As UserModle;
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
        if (!$_oRequest->session()->has('login_user_info')){
            toast('使用者已被登出！！','error','top-right');
            return redirect('login');
        }
        return view('home');
    }
}