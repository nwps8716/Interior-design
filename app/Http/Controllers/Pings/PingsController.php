<?php

namespace App\Http\Controllers\Pings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Appraisal\Pings As PingsModle;
use App\Model\Appraisal\TotalBudget As TotalBudgetModle;
use App\Model\User\User As UserModle;
use Alert;

class PingsController extends Controller
{
    private $level = [
        1 => 'A',
        2 => 'B',
        3 => 'C',
        4 => 'D',
        5 => 'E',
        6 => 'F'
    ];

    /**
     * 坪數估價主頁面
     *
     * @return array
     */
    public function index(
        Request $_oRequest,
        PingsModle $_oPingsModle,
        TotalBudgetModle $_oTotalBudgetModle,
        UserModle $_oUserModle
    )
    {
        $aLevelPings = $aTotalBudget = [];
        $iTotalBudget = 100000;
        $iEngineeringBudget = $iSystemBudget = 50;
        $fSystemDiscount = 0;

        ## 判斷使用者權限
        $sCheckSession = $this->checkSession($_oRequest, true);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
        }

        ## 使用者登入資訊
        $aUserInfo = $_oRequest->session()->get('login_user_info');

        ## 使用者坪數設定值
        $iUserPings = $_oUserModle
            ->where('user_name', $aUserInfo['user_name'])
            ->value('pings');

        ## 判斷使用者沒有設定坪數，預設就為20坪
        $iPings = (!empty($iUserPings)) ? $iUserPings : 20;

        ## 取得每隔級距的坪數設定
        $aLevelPings = $this->getLevelPingsSet($_oPingsModle, $iPings);

        ## 取得使用者特殊總預算相關設定
        $aTotalBudget = $_oTotalBudgetModle
            ->where('user_name', $aUserInfo['user_name'])
            ->get()
            ->toArray();

        if (!empty($aTotalBudget)) {
            $iTotalBudget = $aTotalBudget[0]['total_budget'];
            $iEngineeringBudget = $aTotalBudget[0]['engineering_budget'];
            $iSystemBudget = $aTotalBudget[0]['system_budget'];
            $fSystemDiscount = $aTotalBudget[0]['system_discount'];
        }

        ## 整理使用者特殊相關設定
        $aSpecialTableData = $this->getSpecialTableSet(
            $iTotalBudget,
            $iEngineeringBudget,
            $iSystemBudget,
            $fSystemDiscount,
            $aLevelPings['system_discount_switch']
        );

        return view('pings', [
            'default_pings' => $iPings,
            'main_data' => $aLevelPings['main_data'],
            'engineering_budget' => $aLevelPings['engineering_budget'],
            'system_budget' => $aLevelPings['system_budget'],
            'level_data' => $aLevelPings['level_data'],
            'special_data' => $aSpecialTableData,
            'system_discount_switch' => $aLevelPings['system_discount_switch']

        ]);
    }

    /**
     * 試算每個級距坪數金額
     *
     * @return array
     */
    public function getTrialAmount(
        Request $_oRequest,
        TotalBudgetModle $_oTotalBudgetModle,
        PingsModle $_oPingsModle,
        $_iPings
    )
    {
        $aLevelPings = $aSpecialTableData = [];

        ## 判斷使用者權限
        $sCheckSession = $this->checkSession($_oRequest, true);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
        }

        $iTotalBudget = (int) $_oRequest->input('total_budget');
        $iEngineeringBudget = (int) $_oRequest->input('e_budget');
        $iSystemBudget = (int) $_oRequest->input('s_budget');
        $fSystemDiscount = (float) $_oRequest->input('system_discount');

        ## 使用者登入資訊
        $aUserInfo = $_oRequest->session()->get('login_user_info');

        ## 取得每隔級距的坪數設定
        $aLevelPings = $this->getLevelPingsSet($_oPingsModle, $_iPings);

        ## 整理使用者特殊相關設定
        $aSpecialTableData = $this->getSpecialTableSet(
            ($iTotalBudget * 10000),
            $iEngineeringBudget,
            $iSystemBudget,
            $fSystemDiscount,
            $aLevelPings['system_discount_switch']
        );

        return view('pings', [
            'default_pings' => $_iPings,
            'main_data' => $aLevelPings['main_data'],
            'engineering_budget' => $aLevelPings['engineering_budget'],
            'system_budget' => $aLevelPings['system_budget'],
            'level_data' => $aLevelPings['level_data'],
            'special_data' => $aSpecialTableData,
            'system_discount_switch' => $aLevelPings['system_discount_switch']
        ]);
    }

    /**
     * 修改系統預算、工程預算和級距坪數價格
     *
     * @return boolean
     */
    public function editPercent(Request $_oRequest, PingsModle $_oPingsModle)
    {
        ## 判斷使用者權限
        $sCheckSession = $this->checkSession($_oRequest, false);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
        }

        $iEngineering_budget = (int) $_oRequest->input('engineering_budget');
        $iSystem_budget = (int) $_oRequest->input('system_budget');
        $aLevelPrice = (array) $_oRequest->input('level_price');
        $iSystemDiscountSwitch = (int) $_oRequest->input('system_discount_switch');

        ## 修改系統折數開關
        $_oPingsModle->updateOrCreate(
            ['name' => '系統折數開關'],
            ['name' => '系統折數開關', 'numerical_value' => $iSystemDiscountSwitch]
        );

        ## 修改工程預算
        $_oPingsModle->updateOrCreate(
            ['name' => '工程預算'], 
            ['name' => '工程預算', 'numerical_value' => $iEngineering_budget]
        );

        ## 修改系統預算
        $_oPingsModle->updateOrCreate(
            ['name' => '系統預算'], 
            ['name' => '系統預算', 'numerical_value' => $iSystem_budget]
        );

        ## 修改級距坪數價格
        unset($aLevelPrice[0]);
        foreach ($aLevelPrice as $key => $iPrice) {
            $sLevelName = $this->level[$key] . '級工程';

            $_oPingsModle->updateOrCreate(
                ['name' => $sLevelName],
                ['numerical_value' => $iPrice]
            );
        }
        
        return response()->json(['result' => true]);
    }

    /**
     * 修改使用者設定坪數
     *
     * @return boolean
     */
    public function editUserPings(Request $_oRequest, UserModle $_oUserModle)
    {
        ## 判斷使用者權限
        $sCheckSession = $this->checkSession($_oRequest, true);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
        }

        $iPings = (int) $_oRequest->input('pings');

        ## 使用者登入資訊
        $aUserInfo = $_oRequest->session()->get('login_user_info');
        
        ## 清除並重新設定使用者資訊
        $_oRequest->session()->flush();
        $_oRequest->session()->put(
            'login_user_info',
            [
                'user_name' => $aUserInfo['user_name'],
                'level' => $aUserInfo['level'],
                'pings' => $iPings
            ]
        );

        ## 修改使用者坪數設定
        $_oUserModle->updateOrCreate(
            ['user_name' => $aUserInfo['user_name']],
            ['pings' => $iPings]
        );

        return response()->json(['result' => true]);
    }

    /**
     * 修改使用者設定坪數
     *
     * @return boolean
     */
    public function editUserTotalBudget(Request $_oRequest, TotalBudgetModle $_oTotalBudgetModle)
    {
        ## 判斷使用者權限
        $sCheckSession = $this->checkSession($_oRequest, true);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
        }

        $iTotalBudget = (int) $_oRequest->input('total_budget');
        $iEngineeringBudget = (int) $_oRequest->input('e_budget');
        $iSystemBudget = (int) $_oRequest->input('s_budget');
        $fSystemDiscount = (float) $_oRequest->input('system_discount');

        ## 使用者登入資訊
        $aUserInfo = $_oRequest->session()->get('login_user_info');

        ## 修改工程預算
        $_oTotalBudgetModle->updateOrCreate(
            ['user_name' => $aUserInfo['user_name']],
            [
                'user_name' => $aUserInfo['user_name'],
                'total_budget' => $iTotalBudget * 10000,
                'engineering_budget' => $iEngineeringBudget,
                'system_budget' => $iSystemBudget,
                'system_discount' => $fSystemDiscount
            ]
        );

        return response()->json(['result' => true]);
    }

    ## ========================= 共用Function ========================= ##
    /**
     * 取得每隔級距的坪數設定
     *
     * @return array
     */
    private function getLevelPingsSet(PingsModle $_oPingsModle, $_iPings)
    {
        ## 取得坪數相關設定
        $aPings = $_oPingsModle
            ->get()
            ->pluck('numerical_value', 'name')
            ->toArray();

        $iEngineering_budget = (empty($aPings['工程預算'])) ? 45 : (int) $aPings['工程預算'];
        $iSystem_budget = (empty($aPings['系統預算'])) ? 55 : (int) $aPings['系統預算'];
        $iSystemDiscountSwitch = (empty($aPings['系統折數開關'])) ? (int) 0 : (int) $aPings['系統折數開關'];

        foreach ($this->level as $key => $level_name) {
            $sLevelName = $level_name . '級工程';
            ## 預設級距每坪數多少錢
            $iLevelPingsPrice = (isset($aPings[$sLevelName])) ? $aPings[$sLevelName] : 50;
            $aData[$key]['pings'] = $_iPings;
            $aData[$key]['level'] = $level_name;
            $aData[$key]['price_of_level'] = $iLevelPingsPrice;
            $aData[$key]['price'] = $_iPings * $aData[$key]['price_of_level'];
            $aData[$key]['engineering_budget'] = $iEngineering_budget . '%';
            $aData[$key]['engineering_budget_total'] = $aData[$key]['price'] * ($iEngineering_budget/100);
            $aData[$key]['system_budget'] = $iSystem_budget . '%';
            $iSystemPrice = $aData[$key]['price'] * ($iSystem_budget/100);
            $aTmp = $this->countSystemCardAndDiscount($iSystemPrice);
            $aData[$key]['system_card_price'] = $aTmp['card_price'];
            $aData[$key]['system_discount'] = $aTmp['discount'];
            $aData[$key]['system_price'] = $iSystemPrice;

            ## 級距資料
            $aLevelData[$key] = [
                'level_name' => $sLevelName,
                'pings_price' => $iLevelPingsPrice,
            ];
        }

        return [
            'main_data' => $aData,
            'engineering_budget' => $iEngineering_budget,
            'system_budget' => $iSystem_budget,
            'level_data' => $aLevelData,
            'system_discount_switch' => $iSystemDiscountSwitch
        ];
    }

    /**
     * 使用系統售價來計算系統牌價、系統折數
     *
     * @return array
     */
    private function countSystemCardAndDiscount($iSystemPrice)
    {
        $iCardPrice = 0;
        $iDiscount = 0;
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
        ## 系統折數
        switch (true) {
            case ($iCardPrice < 200000):
                $iDiscount = 0;
                break;
            case ($iCardPrice >= 500000);
                $iDiscount = 0.65;
                break;
            case ($iCardPrice >= 400000);
                $iDiscount = 0.75;
                break;
            case ($iCardPrice >= 300000);
                $iDiscount = 0.85;
                break;
            case ($iCardPrice >= 200000);
                $iDiscount = 0.95;
                break;
            default:
                $iDiscount = 0;
                break;
        }

        return [
            'card_price' => round($iCardPrice, 2),
            'discount' => $iDiscount,
        ];
    }

    /**
     * 取得使用者特殊相關設定
     *
     * @param  int    $_iTotslBudget          使用者總預算
     * @param  int    $_iEngineeringBudget    使用者工程預算％數
     * @param  int    $_iSystemBudget         使用者系統預算％數
     * @param  string $_fSystemDiscount       使用者系統折數
     * @param  int    $_iSystemDiscountSwitch 使用者系統折數開關
     * @return array
     */
    private function getSpecialTableSet(
        $_iTotalBudget,
        $_iEngineeringBudget,
        $_iSystemBudget,
        $_fSystemDiscount,
        $_iSystemDiscountSwitch
    )
    {
        $aSystemDiscount = [];

        ## 系統售價
        $iSystemPrice = $_iTotalBudget * ($_iSystemBudget / 100);

        ## 判斷是否可以修改折數
        if ($_iSystemDiscountSwitch === 0) {
            $aSystemDiscount = $this->countSystemCardAndDiscount($iSystemPrice);
        } else {
            $aSystemDiscount['card_price'] = ($_fSystemDiscount == 0) ?
                $iSystemPrice : round($iSystemPrice / $_fSystemDiscount, 2);
            $aSystemDiscount['discount'] = $_fSystemDiscount;
        }

        $aResult = [
            'total_budget' => $_iTotalBudget / 10000,
            'engineering_budget' => $_iEngineeringBudget,
            'engineering_total_budget' => $_iTotalBudget * ($_iEngineeringBudget / 100),
            'system_budget' => $_iSystemBudget,
            'system_card_price' => $aSystemDiscount['card_price'],
            'system_discount' => $aSystemDiscount['discount'],
            'system_price' => $iSystemPrice,
        ];

        return $aResult;
    }
}