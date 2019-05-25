<?php

namespace App\Http\Controllers\Budget;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UnitPrice\Engineering As EngineeringModle;
use App\Model\UnitPrice\SubEngineering As SubEngineeringModle;
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
        SubEngineeringModle $_oSubEngineeringModle
    )
    {
        $aResult = $aEngineeringList = [];
        $iSubTotal = 0;
        ## 測試數量
        $iTestNum = 10;
        $sTestRemark = 'GG';
        $iTestTotal = 100000;

        $iBudget = (int) $_oRequest->input('budget', 1);

        ## 判斷使用者權限
        if ($this->checkSession($_oRequest, false) !== 'success') {
            return redirect($this->checkSession($_oRequest, false));
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

        ## 整理資料
        foreach ($aSubEngineering as $iKey => $aValue) {
            $aResult[$aValue['project_id']][] = [
                'sub_project_id' => $aValue['sub_project_id'],
                'sub_project_name' => $aValue['sub_project_name'],
                'unti_price' => $aValue['unti_price'],
                'unti' => $aValue['unti'],
                'number' => $iTestNum,
                'remark' => $sTestRemark
            ];

            $iSubTotal += ($iTestNum * $aValue['unti_price']);
        }

        $aTotalData = [
            'total' => $iTestTotal,
            'sub_total' => $iSubTotal,
            'remaining_money' => ($iTestTotal - $iSubTotal),
        ];

        // echo "<pre> 取得裝潢工程預算表 getEngineering <pre>";
        // echo "<pre>";
        // print_r($aResult);
        // exit;
        return view('budget/engineering', [
            'spacing' => $this->aSpacing,
            'budget_id' => $iBudget,
            'total_info' => $aTotalData,
            'engineering' => $aEngineering,
            'list' => $aResult
        ]);
    }

    /**
     * 取得系統工程預算表
     *
     * @return boolean
     */
    public function getSystem(Request $_oRequest)
    {
        ## 判斷使用者權限
        if ($this->checkSession($_oRequest, false) !== 'success') {
            return redirect($this->checkSession($_oRequest, false));
        }
        return view('budget/system', [
            'spacing' => $this->aSpacing
        ]);
    }
}