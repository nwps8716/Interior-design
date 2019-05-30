<?php

namespace App\Http\Controllers\Pings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Pings\Pings As PingsModle;
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
    public function index(Request $_oRequest, PingsModle $_oPingsModle, UserModle $_oUserModle)
    {
        $aLevelPings =[];

        if (!$_oRequest->session()->has('login_user_info')){
            toast('使用者已被登出！！','error','top-right');
            return redirect('login');
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

        return view('pings', [
            'default_pings' => $iPings,
            'main_data' => $aLevelPings['main_data'],
            'engineering_budget' => $aLevelPings['engineering_budget'],
            'system_budget' => $aLevelPings['system_budget'],
            'level_data' => $aLevelPings['level_data']
        ]);
    }

    /**
     * 試算每個級距坪數金額
     *
     * @return array
     */
    public function getTrialAmount(Request $_oRequest, PingsModle $_oPingsModle, $_iPings)
    {
        $aLevelPings = [];

        if (!$_oRequest->session()->has('login_user_info')){
            toast('使用者已被登出！！','error','top-right');
            return redirect('login');
        }

        ## 取得每隔級距的坪數設定
        $aLevelPings = $this->getLevelPingsSet($_oPingsModle, $_iPings);

        return view('pings', [
            'default_pings' => $_iPings,
            'main_data' => $aLevelPings['main_data'],
            'engineering_budget' => $aLevelPings['engineering_budget'],
            'system_budget' => $aLevelPings['system_budget'],
            'level_data' => $aLevelPings['level_data']
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
        if ($this->checkSession($_oRequest, false) !== 'success') {
            return redirect($this->checkSession($_oRequest, false));
        }

        $iEngineering_budget = (int) $_oRequest->input('engineering_budget');
        $iSystem_budget = (int) $_oRequest->input('system_budget');
        $aLevelPrice = (array) $_oRequest->input('level_price');

        ## 修改工程預算
        $_oPingsModle->updateOrCreate(
            ['name' => '工程預算'], 
            ['id' => 1, 'name' => '工程預算', 'numerical_value' => $iEngineering_budget]
        );

        ## 修改系統預算
        $_oPingsModle->updateOrCreate(
            ['name' => '系統預算'], 
            ['id' => 2, 'name' => '系統預算', 'numerical_value' => $iSystem_budget]
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
        if (!$_oRequest->session()->has('login_user_info')){
            toast('使用者已被登出！！','error','top-right');
            return redirect('login');
        }

        $iPings = (int) $_oRequest->input('pings');

        ## 使用者登入資訊
        $aUserInfo = $_oRequest->session()->get('login_user_info');

        ## 修改使用者坪數設定
        $_oUserModle->updateOrCreate(
            ['user_name' => $aUserInfo['user_name']],
            ['pings' => $iPings]
        );

        return response()->json(['result' => true]);
    }

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
            'level_data' => $aLevelData
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
            case ($iCardPrice > 500000);
                $iDiscount = 0.65;
                break;
            case ($iCardPrice > 400000);
                $iDiscount = 0.75;
                break;
            case ($iCardPrice > 300000);
                $iDiscount = 0.85;
                break;
            case ($iCardPrice > 200000);
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
}