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
    private $aSpacing = ['A', 'B', 'C', 'D', 'E', 'F'];

    /**
     * 取得裝潢工程預算表
     *
     * @return boolean
     */
    public function getEngineering(Request $_oRequest)
    {
        ## 判斷使用者權限
        if ($this->checkSession($_oRequest, false) !== 'success') {
            return redirect($this->checkSession($_oRequest, false));
        }

        $iBudget = (int) $_oRequest->budget;

        // echo "<pre>";
        // print_r($iBudget);
        // exit;
        return view('budget/engineering', [
            'spacing' => $this->aSpacing
        ]);
    }

    // /**
    //  * 取得裝潢工程預算表
    //  *
    //  * @return boolean
    //  */
    // public function getEngineering(Request $_oRequest)
    // {
    //     ## 判斷使用者權限
    //     if ($this->checkSession($_oRequest, false) !== 'success') {
    //         return redirect($this->checkSession($_oRequest, false));
    //     }

    //     $iBudget = (int) $_oRequest->budget;

    //     // echo "<pre>";
    //     // print_r($iBudget);
    //     // exit;
    //     return view('budget/engineering', [
    //         'spacing' => $this->aSpacing
    //     ]);
    // }

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