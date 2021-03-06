<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Model\User\WhiteIP As WhiteIPModle;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * 檢查使用者權限
     *
     * @param  boolean $_bShowToVisitor  是否可以給訪客查看頁面
     * @return boolean
     */
    public function checkSession(Request $_oRequest, $_bShowToVisitor)
    {
        $aLoginUser = $_oRequest->session()->get('login_user_info');

        ## 工程師維護網站專用
        if ($aLoginUser['user_name'] !== 'admin' && $aLoginUser['level'] > 1) {
            // $_oRequest->session()->flush();
            // return 'maintenance';
        }

        ## 取得白名單
        $aWhiteIP = WhiteIPModle::get()->pluck('ip')->toArray();

        ## 判斷IP不在白名單內
        if ((int) $aLoginUser['level'] === 3 && !in_array($_SERVER['REMOTE_ADDR'], $aWhiteIP)) {
            $_oRequest->session()->flush();
            return 'noservice';

        ## 判斷使用者已被登出
        } else if (empty($aLoginUser)) {
            toast('使用者已被登出！！','error','top-right');
            return 'login';

        ## 判斷使用者層級有無權限查看此頁面
        } elseif ((int) $aLoginUser['level'] === 3 && $_bShowToVisitor === false) {
            toast('使用者無權限！！','error','top-right');
            return 'home';
        }

        return 'success';
    }

}
