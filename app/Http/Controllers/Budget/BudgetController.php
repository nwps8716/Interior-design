<?php

namespace App\Http\Controllers\Budget;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Appraisal\Pings As PingsModle;
use App\Model\User\User As UserModle;
use App\Model\UnitPrice\Engineering As EngineeringModle;
use App\Model\UnitPrice\SubEngineering As SubEngineeringModle;
use App\Model\UnitPrice\System As SystemModle;
use App\Model\UnitPrice\SubSystem As SubSystemModle;
use App\Model\User\UserBudget As UserBudgetModle;
use App\Model\Appraisal\TotalBudget As TotalBudgetModle;
use App\Model\UnitPrice\SubSystemSort As SubSystemSortModle;
use App\Model\UnitPrice\GeneralSort As GeneralSortModle;
use Alert;
use Response;

class BudgetController extends Controller
{
    ## 級距
    private $aSpacing = [
        1 => 'A級',
        2 => 'B級',
        3 => 'C級',
        4 => 'D級',
        5 => 'E級',
        6 => 'F級',
        7 => 'Special1'
    ];
    ## 預設級距價格
    private $aDefaultSpacePrice = [
        '工程預算' => 45,
        '系統預算' => 55,
        'A級工程' => 50,
        'B級工程' => 50,
        'C級工程' => 50,
        'D級工程' => 50,
        'E級工程' => 50,
        'F級工程' => 50,
    ];
    ## 特殊工程表
    private $aSpecialLevelID = [7];

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
        UserModle $_oUserModle,
        TotalBudgetModle $_oTotalBudgetModle
    )
    {
        $aResult = $aEngineeringList = $aUserBudget = [];
        $iSubTotal = $iAmount = 0;

        $iLevel = (int) $_oRequest->input('level_id', 1);

        ## 判斷使用者權限
        $sCheckSession = $this->checkSession($_oRequest, true);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
        }

        ## 使用者登入資訊
        $aUserInfo = $_oRequest->session()->get('login_user_info');

        if (in_array($iLevel, $this->aSpecialLevelID)) {
            ## 取得使用者特殊總預算資料
            $aSpecialTotalAmount = $this->getUserSpecialTotalBudget(
                $_oTotalBudgetModle,
                $aUserInfo['user_name']
            );
            $iAmount = $aSpecialTotalAmount['engineering_total_budget'];
        } else {
            ## 取得使用者級距
            $iAmount = $this->getUserLevelPingsAmount(
                $_oPingsModle,
                $_oUserModle,
                $aUserInfo['user_name'],
                $iLevel,
                '工程預算'
            );
        }

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
            ->where('level_id', $iLevel)
            ->where('category_id', 1)
            ->get()
            ->keyBy('sub_project_id')
            ->toArray();

        ## 整理資料
        foreach ($aSubEngineering as $iKey => $aValue) {
            ## 子項目數量
            $iSubProjectNum = (isset($aUserBudget[$aValue['sub_project_id']])) ?
                $aUserBudget[$aValue['sub_project_id']]['sub_project_number'] : 0;

            $aResult[$aValue['project_id']][$aValue['sort']] = [
                'sub_project_id' => $aValue['sub_project_id'],
                'sub_project_name' => $aValue['sub_project_name'],
                'unit_price' => $aValue['unit_price'],
                'unit' => $aValue['unit'],
                'number' => $iSubProjectNum,
                'remark' => $aValue['remark']
            ];
            ksort($aResult[$aValue['project_id']]);

            ## 總小記
            $iSubTotal += ($iSubProjectNum * $aValue['unit_price']);
        }

        ## 總預算資料
        $aTotalData = [
            'total' => $iAmount,
            'sub_total' => $iSubTotal,
            'remaining_money' => ($iAmount - $iSubTotal),
        ];

        return view('engineering_budget', [
            'spacing' => $this->aSpacing,
            'level_id' => $iLevel,
            'total_info' => $aTotalData,
            'engineering' => $aEngineering,
            'list' => $aResult
        ]);
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
        SubSystemModle $_oSubSystemModle,
        UserBudgetModle $_oUserBudgetModle,
        TotalBudgetModle $_oTotalBudgetModle
    )
    {
        $iSubTotal = $iTotal = 0;
        $aFreeGiftList = $aFreeGift = $aSystem = $aResult = [];
        $iLevel = (int) $_oRequest->input('level_id', 1);

        ## 判斷使用者權限
        $sCheckSession = $this->checkSession($_oRequest, true);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
        }

        ## 使用者登入資訊
        $aUserInfo = $_oRequest->session()->get('login_user_info');

        if (in_array($iLevel, $this->aSpecialLevelID)) {
            ## 取得使用者特殊總預算資料
            $aSpecialTotalAmount = $this->getUserSpecialTotalBudget(
                $_oTotalBudgetModle,
                $aUserInfo['user_name']
            );
            $iTotal = $aSpecialTotalAmount['system_total_budget'];
        } else {
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
        }

        ## 取得好禮贈送的SystemID
        $aFreeGift = $_oSystemModle
            ->where('system_name', '好禮贈送')
            ->get()
            ->sortBy('sort')
            ->toArray();

        if (!empty($aFreeGift)) {
            $aSystem[$aFreeGift[0]['system_id']] = $aFreeGift[0]['system_name'];
            ## 取得好禮贈送子項目
            $aFreeGiftList = $_oSubSystemModle
                ->where('system_id', $aFreeGift[0]['system_id'])
                ->get()
                ->toArray();
        }


        ## 取得使用者設定的裝潢工程級距詳細資料
        $aUserBudget = $_oUserBudgetModle
            ->select('sub_project_id', 'sub_project_number', 'remark')
            ->where('user_name', $aUserInfo['user_name'])
            ->where('level_id', $iLevel)
            ->where('category_id', 3)
            ->get()
            ->keyBy('sub_project_id')
            ->toArray();

        ## 整理資料
        foreach ($aFreeGiftList as $iKey => $aValue) {
            ## 子項目數量
            $iSubProjectNum = (isset($aUserBudget[$aValue['sub_system_id']])) ?
                $aUserBudget[$aValue['sub_system_id']]['sub_project_number'] : 0;

            $aResult[$aValue['system_id']][$aValue['general_name']][] = [
                'sub_system_id' => $aValue['sub_system_id'],
                'general_name' => $aValue['general_name'],
                'sub_system_name' => $aValue['sub_system_name'],
                'format' => $aValue['format'],
                'unit_price' => $aValue['unit_price'],
                'unit' => $aValue['unit'],
                'number' => $iSubProjectNum,
                'remark' => $aValue['remark']
            ];

            ## 總小記
            $iSubTotal += ($iSubProjectNum * $aValue['unit_price']);
        }

        ## 好禮贈送為總系統牌價3%
        $iTotal = round($iTotal * 0.03, 2);

        ## 總預算資料
        $aTotalData = [
            'total' => $iTotal,
            'sub_total' => $iSubTotal,
            'remaining_money' => $iTotal - $iSubTotal,
        ];

        return view('system_freegift', [
            'spacing' => $this->aSpacing,
            'level_id' => $iLevel,
            'total_info' => $aTotalData,
            'system' => $aSystem,
            'sub_system' => $aResult
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
        SubSystemModle $_oSubSystemModle,
        UserBudgetModle $_oUserBudgetModle,
        TotalBudgetModle $_oTotalBudgetModle,
        SubSystemSortModle $_oSubSystemSortModle,
        GeneralSortModle $_oGeneralSortModle
    )
    {
        $iTotal = $iSubTotal = 0;
        $aTotalData = $aSystem = $aResult = [];

        ## 判斷使用者權限
        $sCheckSession = $this->checkSession($_oRequest, true);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
        }

        $iLevel = (int) $_oRequest->input('level_id', 1);

        ## 使用者登入資訊
        $aUserInfo = $_oRequest->session()->get('login_user_info');

        if (in_array($iLevel, $this->aSpecialLevelID)) {
            ## 取得使用者特殊總預算資料
            $aSpecialTotalAmount = $this->getUserSpecialTotalBudget(
                $_oTotalBudgetModle,
                $aUserInfo['user_name']
            );
            $iTotal = $aSpecialTotalAmount['system_total_budget'];
        } else {
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
        }

        ## 取得系統主項目列表
        $aSystem = $_oSystemModle
            ->where('system_name', '!=', '好禮贈送')
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

        ## 取得使用者設定的裝潢工程級距詳細資料
        $aUserBudget = $_oUserBudgetModle
            ->select('sub_project_id', 'sub_project_number', 'remark')
            ->where('user_name', $aUserInfo['user_name'])
            ->where('level_id', $iLevel)
            ->where('category_id', 2)
            ->get()
            ->keyBy('sub_project_id')
            ->toArray();

        ## 取得系統工程子項目排序
        $aSubSystemSort = $_oSubSystemSortModle
            ->get()
            ->toArray();
        ## 整理桶稱的排序
        foreach ($aSubSystemSort as $aData) {
            $aSortResult[$aData['system_id']][$aData['sort']] = $aData['general_name'];
            ksort($aSortResult[$aData['system_id']]);
        }

        ## 取得系統工程 - 統稱細項排序
        $aGeneralSort = $_oGeneralSortModle
            ->get()
            ->pluck('sort', 'sub_system_id')
            ->toArray();

        ## 整理資料
        foreach ($aSubSystem as $iKey => $aValue) {
            ## 子項目數量
            $iSubProjectNum = (isset($aUserBudget[$aValue['sub_system_id']])) ?
                $aUserBudget[$aValue['sub_system_id']]['sub_project_number'] : 0;

            if (!empty($aSortResult[$aValue['system_id']]) && empty($aResult[$aValue['system_id']]) ) {
                foreach ($aSortResult[$aValue['system_id']] as $gname) {
                    $aResult[$aValue['system_id']][$gname] = [];
                }
            }

            ## 判斷有排序資料
            $iGeneralDetailSort = (isset($aGeneralSort[$aValue['sub_system_id']])) ?
                $aGeneralSort[$aValue['sub_system_id']] - 1 : $aValue['sub_system_id'];

            $aResult[$aValue['system_id']][$aValue['general_name']][$iGeneralDetailSort] = [
                'sub_system_id' => $aValue['sub_system_id'],
                'general_name' => $aValue['general_name'],
                'sub_system_name' => $aValue['sub_system_name'],
                'format' => $aValue['format'],
                'unit_price' => $aValue['unit_price'],
                'unit' => $aValue['unit'],
                'number' => $iSubProjectNum,
                'remark' => $aValue['remark']
            ];
            ksort($aResult[$aValue['system_id']][$aValue['general_name']]);

            ## 總小記
            $iSubTotal += ($iSubProjectNum * $aValue['unit_price']);
        }

        ## 總預算資料
        $aTotalData = [
            'total' => $iTotal,
            'sub_total' => $iSubTotal,
            'remaining_money' => $iTotal - $iSubTotal,
        ];
        
        return view('system_budget', [
            'spacing' => $this->aSpacing,
            'level_id' => $iLevel,
            'total_info' => $aTotalData,
            'system' => $aSystem,
            'sub_system' => $aResult
        ]);
    }

    ## ========================= 共用Function ========================= ##
    /**
     * 修改使用者工程級距預算 - 子項目詳細設定
     *
     * @return boolean
     */
    public function putUserBudget(
        Request $_oRequest,
        UserBudgetModle $_oUserBudgetModle,
        $_iLevelID
    )
    {
        ## 判斷使用者權限
        $sCheckSession = $this->checkSession($_oRequest, true);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
        }

        ## 分類ID(工程：1、系統：2、好禮贈送：3)
        $iCategoryID = (int) $_oRequest->input('category_id', 0);
        $iSubProjectID = (int) $_oRequest->input('sub_project_id');
        $iSubProjectNumber = (int) $_oRequest->input('sub_project_number');

        ## 使用者登入資訊
        $aUserInfo = $_oRequest->session()->get('login_user_info');

        ## 更新使用者級距的子細目數量
        $bResult = $_oUserBudgetModle->updateData(
            $aUserInfo['user_name'],
            $_iLevelID,
            $iSubProjectID,
            $iSubProjectNumber,
            $iCategoryID
        );

        return response()->json(['result' => $bResult]);
    }

    /**
     * 刪除使用者裝潢工程級距預算的數量設定
     *
     * @return boolean
     */
    public function deleteUserBudget(
        Request $_oRequest,
        UserBudgetModle $_oUserBudgetModle,
        $_iLevelID
    )
    {
        ## 判斷使用者權限
        $sCheckSession = $this->checkSession($_oRequest, true);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
        }

        ## 分類ID(工程：1、系統：2、好禮贈送：3)
        $iCategoryID = (int) $_oRequest->input('category_id', 0);

        ## 使用者登入資訊
        $aUserInfo = $_oRequest->session()->get('login_user_info');

        $oResult = $_oUserBudgetModle
            ->where('user_name', $aUserInfo['user_name'])
            ->where('level_id', $_iLevelID)
            ->where('category_id', $iCategoryID)
            ->delete();

        return response()->json(['result' => true]);
    }

    ## ========================= 共用Function ========================= ##
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

        if (empty($aPings)) {
            $aPings = $this->aDefaultSpacePrice;
        }

        $sLevelName = $this->aSpacing[$_iLevel] . '工程';
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

    /**
     * 取得使用者特殊總預算資料
     * @param string $_sUserName  使用者登入名稱
     * @return array $aResult
     */
    private function getUserSpecialTotalBudget(
        TotalBudgetModle $_oTotalBudgetModle,
        $_sUserName
    )
    {
        $aTotalBudget = [];

        ## 預設值
        $aResult = [
            'engineering_total_budget' => 50000,
            'system_total_budget' => 50000
        ];

        $aTotalBudget = $_oTotalBudgetModle
            ->where('user_name', $_sUserName)
            ->get()
            ->toArray();

        if (!empty($aTotalBudget)) {
            $iTotal = $aTotalBudget[0]['total_budget'];
            $fSystemDiscount = ($aTotalBudget[0]['system_discount'] == 0) ? 1 : $aTotalBudget[0]['system_discount'];

            $iEngineeringTotalBudget = $iTotal * ($aTotalBudget[0]['engineering_budget'] / 100);
            $iSystemTotalBudget = round($iTotal * ($aTotalBudget[0]['system_budget'] / 100) / $fSystemDiscount, 2);

            $aResult = [
                'engineering_total_budget' => $iEngineeringTotalBudget,
                'system_total_budget' => $iSystemTotalBudget,
            ];
        }

        return $aResult;
    }
}