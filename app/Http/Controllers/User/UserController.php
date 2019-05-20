<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
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
            ->get()
            ->toArray()[0];

        ## 判斷如果無使用者
        if (empty($aUserList) ||
            !(Hash::check($sPassword, $aUserList['password']))) {
            toast('帳號or密碼錯誤，請重新登入','error','top-right');
            return redirect()->back();
        }

        toast('登入成功','success','top-right');
        return redirect('home');
    }

    /**
     * 新增使用者
     *
     * @return boolean
     */
    public function createUser(
        Request $_oRequest,
        UserModle $_oUserModel
    )
    {
        $aUserList = [];

        $sUserName = $_oRequest->input('user_name');
        $sPassword = $_oRequest->input('password');
        $sRePassword = $_oRequest->input('re_password');

        ## 簡易判斷
        if (empty($sUserName)) {
            toast('帳號不能為空白','error','top-right');
            return redirect()->back();
        } elseif (($sPassword !== $sRePassword) || empty($sPassword) || empty($sRePassword)) {
            toast('密碼or確認密碼錯誤,請重新輸入','error','top-right');
            return redirect()->back();
        }

        ## 密碼簡易加密
        $sHashPassword = Hash::make($sPassword, [
            'memory' => 1024,
            'time' => 2,
            'threads' => 2,
        ]);

        try {
            $bResult = $_oUserModel->insert(
                [
                    'user_name' => $sUserName,
                    'password' => $sHashPassword,
                    'level' => 3
                ]
            );
        } catch (\Exception $e) {
            if ($e->getCode() === '23000') {
                toast('使用者已存在','error','top-right');
                return redirect()->back();
            }
            toast('新增失敗！！','error','top-right');
            return redirect()->back();
        }

        toast('新增帳號成功，請重新登入!!','success','top-right');
        return redirect('login');
    }
}
