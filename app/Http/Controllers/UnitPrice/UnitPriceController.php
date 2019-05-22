<?php

namespace App\Http\Controllers\UnitPrice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UnitPrice\Engineering As EngineeringModle;
use App\Model\UnitPrice\SubEngineering As SubEngineeringModle;
use Alert;
use Response;

class UnitPriceController extends Controller
{
    /**
     * 取得工程單價列表
     *
     * @return array
     */
    public function getEngineeringList(
        Request $_oRequest,
        EngineeringModle $_oEngineeringModle,
        SubEngineeringModle $_oSubEngineeringModle
    )
    {
        $aResult = [];

        ## 取得工程主項目列表
        $aEngineering = $_oEngineeringModle
            ->get()
            ->sortBy('sort')
            ->pluck('project_name', 'project_id')
            ->toArray();

        ## 取得工程子項目列表
        $aSubEngineering = $_oSubEngineeringModle
            ->get()
            ->toArray();

        ## 整理資料
        foreach ($aSubEngineering as $iKey => $aValue) {
            $aResult[$aValue['project_id']][] = [
                'sub_project_name' => $aValue['sub_project_name'],
                'unti_price' => $aValue['unti_price'],
                'unti' => $aValue['unti']
            ];
        }

        return view('engineering', [
            'engineering' => $aEngineering,
            'sub_engineering' => $aResult
        ]);
    }
}
