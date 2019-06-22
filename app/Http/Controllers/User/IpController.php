<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Model\User\WhiteIP As WhiteIPModle;
use Alert;

class IpController extends Controller
{
    /**
     * 取得白名單IP列表
     *
     * @return boolean
     */
    public function getWhiteIps(Request $_oRequest, WhiteIPModle $_oWhiteIPModle)
    {
        ## 判斷使用者權限
        $sCheckSession = $this->checkSession($_oRequest, false);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
        }

        $aWhiteIps = $_oWhiteIPModle
            ->get()
            ->pluck('ip')
            ->toArray();

        return view('white_ip', [
            'ip_list' => $aWhiteIps
        ]);
    }

    /**
     * 新增白名單IP
     *
     * @return boolean
     */
    public function postWhiteIp(Request $_oRequest, WhiteIPModle $_oWhiteIPModle)
    {
        ## 判斷使用者權限
        $sCheckSession = $this->checkSession($_oRequest, false);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
        }

        $sWhiteIP = $_oRequest->input('white_ip');

        ## 驗證IP格式
        if (!filter_var($sWhiteIP, FILTER_VALIDATE_IP) || empty($sWhiteIP)) {
            return response()->json(['result' => false, 'errormsg' => '此為無效IP，請重新輸入']);
        }

        ## 檢查IP是否存在
        $bCheckIp = (bool) $_oWhiteIPModle
            ->where('ip', $sWhiteIP)
            ->count();
        if ($bCheckIp) {
            return response()->json(['result' => false, 'errormsg' => 'IP已存在，請重新輸入']);
        }
        
        try {
            $bResult = $_oWhiteIPModle->insert(
                [
                    'ip' => $sWhiteIP
                ]
            );
        } catch (\Exception $e) {
            return response()->json(['result' => false, 'errormsg' => '新增失敗！！']);
        }

        return response()->json(['result' => true]);
    }

    /**
     * 刪除白名單IP
     *
     * @return boolean
     */
    public function deleteWhiteIp(Request $_oRequest, WhiteIPModle $_oWhiteIPModle)
    {
        ## 判斷使用者權限
        $sCheckSession = $this->checkSession($_oRequest, false);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
        }

        $sWhiteIP = $_oRequest->input('white_ip');

        ## 刪除IP
        $bResult = $_oWhiteIPModle
            ->where('ip', $sWhiteIP)
            ->delete();

        return response()->json(['result' => $bResult]);
    }
}