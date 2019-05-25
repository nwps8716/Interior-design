<?php

namespace App\Http\Controllers\Budget;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Alert;

class BudgetController extends Controller
{
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
        return view('budget/engineering');
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
        return view('budget/system');
    }
}