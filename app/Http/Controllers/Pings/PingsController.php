<?php

namespace App\Http\Controllers\Pings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

        $aData = [];
        $iDefault_pings = (int) $_oRequest->input('pings', 20); ## 坪數
        $iEngineering_budget = 45; ## 工程預算
        $iSystem_budget = 100 - $iEngineering_budget; ## 系統預算

        foreach ($this->level as $key => $level_name) {
            $aData[$key]['pings'] = $iDefault_pings;
            $aData[$key]['level'] = $level_name;
            $aData[$key]['price_of_level'] = $key * 100;
            $aData[$key]['price'] = $iDefault_pings * $aData[$key]['price_of_level'];
            $aData[$key]['engineering_budget'] = $iEngineering_budget . '%';
            $aData[$key]['engineering_budget_total'] = $aData[$key]['price'] * ($iEngineering_budget/100);
            $aData[$key]['system_budget'] = $iSystem_budget . '%';
            $aData[$key]['system_price'] = $aData[$key]['price'] * ($iSystem_budget/100);
            $aTmp = $this->countSystemCardAndDiscount($aData[$key]['system_price']);
            $aData[$key]['system_card_price'] = $aTmp['card_price'];
            $aData[$key]['system_discount'] = $aTmp['discount'];
        }
        
        return view('pings', [
            'default_pings' => $iDefault_pings,
            'main_data' => $aData,
        ]);
    }
    ## 使用系統售價來計算系統牌價、系統折數
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