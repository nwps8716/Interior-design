<?php

namespace App\Http\Controllers\Budget;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Pings\Pings As PingsModle;
use App\Model\User\User As UserModle;
use App\Model\UnitPrice\Engineering As EngineeringModle;
use App\Model\UnitPrice\SubEngineering As SubEngineeringModle;
use App\Model\UnitPrice\System As SystemModle;
use App\Model\UnitPrice\SubSystem As SubSystemModle;
use App\Model\User\UserBudget As UserBudgetModle;
use Alert;
use Response;

class BudgetController extends Controller
{
    ## 級距
    private $aSpacing = [
        1 => 'A',
        2 => 'B',
        3 => 'C',
        4 => 'D',
        5 => 'E',
        6 => 'F'
    ];

    /**
     * 取得裝潢工程預算表
     *
     * @return boolean
     */
    public function getEngineering(
        Request $_oRequest,
        EngineeringModle $_oEngineeringModle,
        SubEngineeringModle $_oSubEngineeringModle,
        UserBudgetModle $_oUserBudgetModle,
        PingsModle $_oPingsModle,
        UserModle $_oUserModle
    )
    {
        $aResult = $aEngineeringList = $aUserBudget = [];
        $iSubTotal = $iAmount = 0;

        $iLevel = (int) $_oRequest->input('level_id', 1);

        ## 判斷使用者權限
        if ($this->checkSession($_oRequest, false) !== 'success') {
            return redirect($this->checkSession($_oRequest, false));
        }

        ## 使用者登入資訊
        $aUserInfo = $_oRequest->session()->get('login_user_info');

        ## 取得使用者級距
        $iAmount = $this->getUserLevelPingsAmount(
            $_oPingsModle,
            $_oUserModle,
            $aUserInfo['user_name'],
            $iLevel,
            '工程預算'
        );

        ## 取得工程主項目列表
        $aEngineering = $_oEngineeringModle
            ->get()
            ->sortBy('sort')
            ->pluck('project_name', 'project_id')
            ->toArray();

        ## 給預設Key值
        foreach ($aEngineering as $key => $value) {
            $aResult[$key] = [];
        }

        ## 取得工程子項目列表
        $aSubEngineering = $_oSubEngineeringModle
            ->get()
            ->toArray();

        ## 取得使用者設定的裝潢工程級距詳細資料
        $aUserBudget = $_oUserBudgetModle
            ->select('sub_project_id', 'sub_project_number', 'remark')
            ->where('user_name', $aUserInfo['user_name'])
            ->where('budget_id', $iLevel)
            ->get()
            ->keyBy('sub_project_id')
            ->toArray();

        ## 整理資料
        foreach ($aSubEngineering as $iKey => $aValue) {
            ## 子項目數量
            $iSubProjectNum = (isset($aUserBudget[$aValue['sub_project_id']])) ?
                $aUserBudget[$aValue['sub_project_id']]['sub_project_number'] : 0;

            $aResult[$aValue['project_id']][] = [
                'sub_project_id' => $aValue['sub_project_id'],
                'sub_project_name' => $aValue['sub_project_name'],
                'unit_price' => $aValue['unit_price'],
                'unit' => $aValue['unit'],
                'number' => $iSubProjectNum,
                'remark' => $aValue['remark']
            ];

            ## 總小記
            $iSubTotal += ($iSubProjectNum * $aValue['unit_price']);
        }

        ## 總預算資料
        $aTotalData = [
            'total' => $iAmount,
            'sub_total' => $iSubTotal,
            'remaining_money' => ($iAmount - $iSubTotal),
        ];

        return view('budget/engineering', [
            'spacing' => $this->aSpacing,
            'level_id' => $iLevel,
            'total_info' => $aTotalData,
            'engineering' => $aEngineering,
            'list' => $aResult
        ]);
    }

    /**
     * 修改使用者裝潢工程級距預算 - 子項目詳細設定
     *
     * @return boolean
     */
    public function putUserEngineering(
        Request $_oRequest,
        UserBudgetModle $_oUserBudgetModle,
        $_iLevelID
    )
    {
        ## 判斷使用者權限
        if ($this->checkSession($_oRequest, false) !== 'success') {
            return redirect($this->checkSession($_oRequest, false));
        }

        $iSubProjectID = (int) $_oRequest->input('sub_project_id');
        $iSubProjectNumber = (int) $_oRequest->input('sub_project_number');

        ## 使用者登入資訊
        $aUserInfo = $_oRequest->session()->get('login_user_info');

        ## 更新使用者裝潢工程級距的子細目數量
        $bResult = $_oUserBudgetModle->updateData(
            $aUserInfo['user_name'],
            $_iLevelID,
            $iSubProjectID,
            $iSubProjectNumber
        );

        return response()->json(['result' => $bResult]);
    }

    /**
     * 刪除使用者裝潢工程級距預算的數量設定
     *
     * @return boolean
     */
    public function deleteUserEngineering(
        Request $_oRequest,
        UserBudgetModle $_oUserBudgetModle,
        $_iLevelID
    )
    {
        ## 判斷使用者權限
        if ($this->checkSession($_oRequest, false) !== 'success') {
            return redirect($this->checkSession($_oRequest, false));
        }

        ## 使用者登入資訊
        $aUserInfo = $_oRequest->session()->get('login_user_info');

        $oResult = $_oUserBudgetModle
            ->where('user_name', $aUserInfo['user_name'])
            ->where('budget_id', $_iLevelID)
            ->delete();

        return response()->json(['result' => true]);
    }

    /**
     * 取得好禮贈送列表
     *
     * @return boolean
     */
    public function getFreeGift(
        Request $_oRequest,
        PingsModle $_oPingsModle,
        UserModle $_oUserModle,
        SystemModle $_oSystemModle,
        SubSystemModle $_oSubSystemModle
    )
    {
        $iSubTotal = $iTotal = 0;
        $iLevel = (int) $_oRequest->input('level_id', 1);

        ## 判斷使用者權限
        if ($this->checkSession($_oRequest, false) !== 'success') {
            return redirect($this->checkSession($_oRequest, false));
        }

        ## 使用者登入資訊
        $aUserInfo = $_oRequest->session()->get('login_user_info');

        ## 取得使用者級距總預算
        $iSystemPrice = $this->getUserLevelPingsAmount(
            $_oPingsModle,
            $_oUserModle,
            $aUserInfo['user_name'],
            $iLevel,
            '系統預算'
        );

        ## 取得系統牌價
        $iTotal = $this->countSystemCardAndDiscount($iSystemPrice);

        ## 總預算資料
        $aTotalData = [
            'total' => $iTotal,
            'sub_total' => $iSubTotal,
            'remaining_money' => $iTotal - $iSubTotal,
        ];

        ## 好禮贈送為總系統牌價3%
        $iTotal = round($iTotal * 0.03, 2);

        ## 總預算資料
        $aTotalData = [
            'total' => $iTotal,
            'sub_total' => $iSubTotal,
            'remaining_money' => $iTotal - $iSubTotal,
        ];

        return view('budget/system_freegift', [
            'spacing' => $this->aSpacing,
            'level_id' => $iLevel,
            'total_info' => $aTotalData
        ]);
    }

    /**
     * 取得系統工程預算表
     *
     * @return boolean
     */
    public function getSystem(
        Request $_oRequest,
        PingsModle $_oPingsModle,
        UserModle $_oUserModle,
        SystemModle $_oSystemModle,
        SubSystemModle $_oSubSystemModle
    )
    {
        $iSubTotal = 0;

        ## 判斷使用者權限
        if ($this->checkSession($_oRequest, false) !== 'success') {
            return redirect($this->checkSession($_oRequest, false));
        }

        $iLevel = (int) $_oRequest->input('level_id', 1);

        ## 使用者登入資訊
        $aUserInfo = $_oRequest->session()->get('login_user_info');

        ## 取得使用者級距總預算
        $iSystemPrice = $this->getUserLevelPingsAmount(
            $_oPingsModle,
            $_oUserModle,
            $aUserInfo['user_name'],
            $iLevel,
            '系統預算'
        );
        ## 取得系統牌價
        $iTotal = $this->countSystemCardAndDiscount($iSystemPrice);
        ## 總預算資料
        $aTotalData = [
            'total' => $iTotal,
            'sub_total' => $iSubTotal,
            'remaining_money' => $iTotal - $iSubTotal,
        ];

        ## 取得系統主項目列表
        $aSystem = $_oSystemModle
            ->get()
            ->sortBy('sort')
            ->pluck('system_name', 'system_id')
            ->toArray();

        ## 給預設Key值
        foreach ($aSystem as $key => $value) {
            $aResult[$key] = [];
        }

        ## 取得系統子項目列表
        $aSubSystem = $_oSubSystemModle
            ->get()
            ->toArray();

        ## 整理資料
        foreach ($aSubSystem as $iKey => $aValue) {
            $aResult[$aValue['system_id']][$aValue['general_name']][] = [
                'sub_system_id' => $aValue['sub_system_id'],
                'general_name' => $aValue['general_name'],
                'sub_system_name' => $aValue['sub_system_name'],
                'format' => $aValue['format'],
                'unit_price' => $aValue['unit_price'],
                'unit' => $aValue['unit'],
                'number' => 0,
            ];
        }

        return view('budget/system', [
            'spacing' => $this->aSpacing,
            'level_id' => $iLevel,
            'total_info' => $aTotalData,
            'system' => $aSystem,
            'sub_system' => $aResult
        ]);
    }

    ## ========================= 共用Function =========================##
    /**
     * 取得使用者級距坪數總預算
     * @param string $_sUserName  使用者登入名稱
     * @param int    $_iLevel     工程級距ID
     * @param string $_sPriceName 預算名稱(工程預算 or 系統預算)
     * @return int
     */
    private function getUserLevelPingsAmount(
        PingsModle $_oPingsModle,
        UserModle $_oUserModle,
        $_sUserName,
        $_iLevel,
        $_sPriceName
    )
    {
        $iAmount = $iPercent = $iLevelAmount = 0;

        $iUserPings = $_oUserModle
            ->where('user_name', $_sUserName)
            ->value('pings');

        ## 取得坪數相關設定
        $aPings = $_oPingsModle
            ->get()
            ->pluck('numerical_value', 'name')
            ->toArray();

        $sLevelName = $this->aSpacing[$_iLevel] . '級工程';
        ## 取得該坪數的每坪金額
        $iLevelAmount = $aPings[$sLevelName];

        ## 取得預算的％數
        $iPercent = $aPings[$_sPriceName]/ 100;

        ## 計算總預算
        $iAmount = ($iLevelAmount * $iUserPings) * $iPercent;

        return $iAmount;
    }

    /**
     * 計算系統牌價
     *
     * @return array
     */
    private function countSystemCardAndDiscount($iSystemPrice)
    {
        $iCardPrice = 0;

        ## 系統牌價
        switch (true) {
            case (($iSystemPrice / 0.95) < 200000):
                $iCardPrice = $iSystemPrice;
                break;
            case (($iSystemPrice / 0.65) >= 500000);
                $iCardPrice = $iSystemPrice / 0.65;
                break;
            case (($iSystemPrice / 0.75) >= 400000);
                $iCardPrice = $iSystemPrice / 0.75;
                break;
            case (($iSystemPrice / 0.85) >= 300000);
                $iCardPrice = $iSystemPrice / 0.85;
                break;
            case (($iSystemPrice / 0.95) >= 200000);
                $iCardPrice = $iSystemPrice / 0.95;
                break;
            default:
                $iCardPrice = $iSystemPrice;
                break;
        }

        return round($iCardPrice, 2);
    }
}