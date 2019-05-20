<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\User\User As UserModle;
use Alert;

class UserController extends Controller
{
    /**
     * 使用者登入
     *
     * @return boolean
     */
    public function postLogin(
        Request $_oRequest,
        UserModle $_oUserModel
    )
    {
        $aUserList = [];

        $sUserName = $_oRequest->input('user_name');
        $sPassword = $_oRequest->input('password');

        $aUserList = $_oUserModel
            ->where('user_name', $sUserName)
            ->where('password', $sPassword)
            ->get()
            ->toArray();

        ## 判斷如果無使用者
        if (empty($aUserList)) {
            Alert::error('錯誤', '帳號or密碼錯誤,請重新登入');
            return redirect()->back();
        }

        return redirect('home')->with('success', 'Login Successfully!');
    }
}
