<?php

namespace App\Http\Controllers\Budget;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UnitPrice\Engineering As EngineeringModle;
use App\Model\UnitPrice\SubEngineering As SubEngineeringModle;
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
        UserBudgetModle $_oUserBudgetModle
    )
    {
        $aResult = $aEngineeringList = $aUserBudget = [];
        $iSubTotal = 0;
        ## 測試數量
        $iTestTotal = 100000;

        $iBudget = (int) $_oRequest->input('budget', 1);

        ## 判斷使用者權限
        if ($this->checkSession($_oRequest, false) !== 'success') {
            return redirect($this->checkSession($_oRequest, false));
        }

        ## 使用者登入資訊
        $aUserInfo = $_oRequest->session()->get('login_user_info');

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
            ->where('budget_id', $iBudget)
            ->get()
            ->keyBy('sub_project_id')
            ->toArray();

        ## 整理資料
        foreach ($aSubEngineering as $iKey => $aValue) {
            ## 子項目數量
            $iSubProjectNum = (isset($aUserBudget[$aValue['sub_project_id']])) ?
                $aUserBudget[$aValue['sub_project_id']]['sub_project_number'] : 0;

            ## 備註
            $sRemark = (isset($aUserBudget[$aValue['sub_project_id']])) ?
                $aUserBudget[$aValue['sub_project_id']]['remark'] : '';

            $aResult[$aValue['project_id']][] = [
                'sub_project_id' => $aValue['sub_project_id'],
                'sub_project_name' => $aValue['sub_project_name'],
                'unti_price' => $aValue['unti_price'],
                'unti' => $aValue['unti'],
                'number' => $iSubProjectNum,
                'remark' => $sRemark
            ];

            ## 總小記
            $iSubTotal += ($iSubProjectNum * $aValue['unti_price']);
        }

        ## 總預算資料
        $aTotalData = [
            'total' => $iTestTotal,
            'sub_total' => $iSubTotal,
            'remaining_money' => ($iTestTotal - $iSubTotal),
        ];

        return view('budget/engineering', [
            'spacing' => $this->aSpacing,
            'budget_id' => $iBudget,
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
        $_iBudgetID
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
            $_iBudgetID,
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
        $_iBudgetID
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
            ->where('budget_id', $_iBudgetID)
            ->delete();

        return response()->json(['result' => true]);
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