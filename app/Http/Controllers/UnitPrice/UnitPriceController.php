<?php

namespace App\Http\Controllers\UnitPrice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UnitPrice\Engineering As EngineeringModle;
use App\Model\UnitPrice\SubEngineering As SubEngineeringModle;
use App\Model\UnitPrice\System As SystemModle;
use App\Model\UnitPrice\SubSystem As SubSystemModle;
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
                'unit_price' => $aValue['unit_price'],
                'unit' => $aValue['unit'],
                'remark' => $aValue['remark']
            ];
        }

        return view('unitprice/engineering', [
            'engineering' => $aEngineering,
            'sub_engineering' => $aResult
        ]);
    }

    /**
     * 新增子項目內容
     *
     * @return array
     */
    public function createSubEngineering(
        Request $_oRequest,
        SubEngineeringModle $_oSubEngineeringModle
    )
    {
        ## 判斷使用者權限
        if ($this->checkSession($_oRequest, false) !== 'success') {
            return redirect($this->checkSession($_oRequest, false));
        }

        $iProjectID = (int) $_oRequest->input('project_id');
        $sSubProjectName = $_oRequest->input('sub_project_name');
        $iUnitPrice = (int) $_oRequest->input('unit_price');
        $sUnit = $_oRequest->input('unit');
        $sRemark = $_oRequest->input('remark');

        $bResult = $_oSubEngineeringModle->insert(
            [
                'sub_project_name' => $sSubProjectName,
                'project_id' => $iProjectID,
                'unit_price' => $iUnitPrice,
                'unit' => $sUnit,
                'remark' => $sRemark
            ]
        );

        return response()->json(['result' => true]);
    }

    /**
     * 更新子項目內容
     *
     * @return array
     */
    public function putSubEngineering(
        Request $_oRequest,
        SubEngineeringModle $_oSubEngineeringModle
    )
    {
        ## 判斷使用者權限
        if ($this->checkSession($_oRequest, false) !== 'success') {
            return redirect($this->checkSession($_oRequest, false));
        }

        $iSubProjectID = (int) $_oRequest->input('id');
        $sSubProjectName = $_oRequest->input('name');
        $iUnitPrice = (int) $_oRequest->input('unit_price');
        $sUnit = $_oRequest->input('unit');
        $sRemark = $_oRequest->input('remark');

        ## 更新工程子項目
        $bResult = $_oSubEngineeringModle
            ->where('sub_project_id', $iSubProjectID)
            ->update(
                [
                    'sub_project_name' => $sSubProjectName,
                    'unit_price' => $iUnitPrice,
                    'unit' => $sUnit,
                    'remark' => $sRemark,
                ]
            );

        return response()->json(['result' => true]);
    }

    /**
     * 刪除子項目內容
     *
     * @return array
     */
    public function deleteSubEngineering(
        Request $_oRequest,
        SubEngineeringModle $_oSubEngineeringModle
    )
    {
        ## 判斷使用者權限
        if ($this->checkSession($_oRequest, false) !== 'success') {
            return redirect($this->checkSession($_oRequest, false));
        }

        $iSubProjectID = (int) $_oRequest->input('id');

        ## 刪除工程子項目
        $bResult = $_oSubEngineeringModle
            ->where('sub_project_id', $iSubProjectID)
            ->delete();

        return response()->json(['result' => true]);
    }

    /**
     * 新增工程項目分類
     *
     * @return array
     */
    public function createEngineering(
        Request $_oRequest,
        EngineeringModle $_oEngineeringModle
    )
    {
        ## 判斷使用者權限
        if ($this->checkSession($_oRequest, false) !== 'success') {
            return redirect($this->checkSession($_oRequest, false));
        }

        $sProjectName = $_oRequest->input('project_name');

        $iLastSort = $_oEngineeringModle
            ->max('sort');

        $bResult = $_oEngineeringModle->insert(
            [
                'project_name' => $sProjectName,
                'sort' => ($iLastSort + 1)
            ]
        );

        return response()->json(['result' => true]);
    }

    /**
     * 刪除工程分類項目
     *
     * @return array
     */
    public function deleteEngineering(
        Request $_oRequest,
        EngineeringModle $_oEngineeringModle,
        SubEngineeringModle $_oSubEngineeringModle
    )
    {
        ## 判斷使用者權限
        if ($this->checkSession($_oRequest, false) !== 'success') {
            return redirect($this->checkSession($_oRequest, false));
        }

        $iProjectID = (int) $_oRequest->input('id');

        ## 取得工程大類底下是否還有子項目
        $iSubProjectCount = $_oSubEngineeringModle
            ->where('project_id', $iProjectID)
            ->count();

        if ($iSubProjectCount > 0) {
            return response()->json(['result' => false]);
        }

        ## 刪除工程分類項目
        $bResult = $_oEngineeringModle
            ->where('project_id', $iProjectID)
            ->delete();

        return response()->json(['result' => true]);
    }

    /**
     * 修改工程分類項目
     *
     * @return array
     */
    public function putEngineering(
        Request $_oRequest,
        EngineeringModle $_oEngineeringModle
    )
    {
        ## 判斷使用者權限
        if ($this->checkSession($_oRequest, false) !== 'success') {
            return redirect($this->checkSession($_oRequest, false));
        }

        $iProjectID = (int) $_oRequest->input('id');
        $sProjectName = $_oRequest->input('name');

        ## 更新工程分類項目名稱
        $bResult = $_oEngineeringModle
            ->where('project_id', $iProjectID)
            ->update(
                [
                    'project_name' => $sProjectName,
                ]
            );

        return response()->json(['result' => true]);
    }

    /**
     * 取得系統單價列表
     *
     * @return array
     */
    public function getSystemList(
        Request $_oRequest,
        SystemModle $_oSystemModle,
        SubSystemModle $_oSubSystemModle
    )
    {
        $aResult = [];
        ## 判斷使用者權限
        if ($this->checkSession($_oRequest, false) !== 'success') {
            return redirect($this->checkSession($_oRequest, false));
        }

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
                'remark' => $aValue['remark']
            ];
        }
        
        return view('unitprice/system', [
            'system' => $aSystem,
            'sub_system' => $aResult
        ]);
    }

    /**
     * 新增系統單價項目分類
     *
     * @return array
     */
    public function createSystem(
        Request $_oRequest,
        SystemModle $_oSystemModle
    )
    {
        ## 判斷使用者權限
        if ($this->checkSession($_oRequest, false) !== 'success') {
            return redirect($this->checkSession($_oRequest, false));
        }

        $sSystemName = $_oRequest->input('system_name');

        $iLastSort = $_oSystemModle
            ->max('sort');

        $bResult = $_oSystemModle->insert(
            [
                'system_name' => $sSystemName,
                'sort' => ($iLastSort + 1)
            ]
        );

        return response()->json(['result' => true]);
    }

    /**
     * 新增系統單價子項目內容
     *
     * @return array
     */
    public function createSubSystem(
        Request $_oRequest,
        SubSystemModle $_oSubSystemModle
    )
    {
        ## 判斷使用者權限
        if ($this->checkSession($_oRequest, false) !== 'success') {
            return redirect($this->checkSession($_oRequest, false));
        }

        $iSystemID = (int) $_oRequest->input('system_id');
        $sGeneralName = $_oRequest->input('general_name');
        $sSubSystemName = $_oRequest->input('sub_system_name');
        $sFormat = $_oRequest->input('format');
        $iUnitPrice = (int) $_oRequest->input('unit_price');
        $sUnit = $_oRequest->input('unit');
        $sRemark = $_oRequest->input('remark');

        $bResult = $_oSubSystemModle->insert(
            [
                'general_name' => $sGeneralName,
                'sub_system_name' => $sSubSystemName,
                'format' => $sFormat,
                'unit_price' => $iUnitPrice,
                'unit' => $sUnit,
                'system_id' => $iSystemID,
                'remark' => $sRemark
            ]
        );

        return response()->json(['result' => true]);
    }

    /**
     * 刪除-系統單價子項目內容
     *
     * @return array
     */
    public function deleteSubSystem(
        Request $_oRequest,
        SubSystemModle $_oSubSystemModle
    )
    {
        ## 判斷使用者權限
        if ($this->checkSession($_oRequest, false) !== 'success') {
            return redirect($this->checkSession($_oRequest, false));
        }

        $iSubSystemID = (int) $_oRequest->input('id');

        ## 刪除系統子項目
        $bResult = $_oSubSystemModle
            ->where('sub_system_id', $iSubSystemID)
            ->delete();

        return response()->json(['result' => true]);
    }

    /**
     * 更新-系統單價子項目內容
     *
     * @return array
     */
    public function putSubSystem(
        Request $_oRequest,
        SubSystemModle $_oSubSystemModle
    )
    {
        ## 判斷使用者權限
        if ($this->checkSession($_oRequest, false) !== 'success') {
            return redirect($this->checkSession($_oRequest, false));
        }
        
        $iSubSystemID = (int) $_oRequest->input('id');
        $sGeneralName = $_oRequest->input('general_name');
        $sSubSystemName = $_oRequest->input('name');
        $sFormat = $_oRequest->input('format');
        $iUnitPrice = (int) $_oRequest->input('unit_price');
        $sUnit = $_oRequest->input('unit');
        $sRemark = $_oRequest->input('remark');

        ## 更新系統子項目
        $bResult = $_oSubSystemModle
            ->where('sub_system_id', $iSubSystemID)
            ->update(
                [
                    'general_name' => $sGeneralName,
                    'sub_system_name' => $sSubSystemName,
                    'format' => $sFormat,
                    'unit_price' => $iUnitPrice,
                    'unit' => $sUnit,
                    'remark' => $sRemark,
                ]
            );

        return response()->json(['result' => true]);
    }

    /**
     * 修改-系統分類項目
     *
     * @return array
     */
    public function putSystem(
        Request $_oRequest,
        SystemModle $_oSystemModle
    )
    {
        ## 判斷使用者權限
        if ($this->checkSession($_oRequest, false) !== 'success') {
            return redirect($this->checkSession($_oRequest, false));
        }

        $iSystemID = (int) $_oRequest->input('id');
        $sSystemName = $_oRequest->input('name');

        ## 更新系統分類項目名稱
        $bResult = $_oSystemModle
            ->where('system_id', $iSystemID)
            ->update(
                [
                    'system_name' => $sSystemName,
                ]
            );

        return response()->json(['result' => true]);
    }

    /**
     * 刪除-系統單價分類項目
     *
     * @return array
     */
    public function deleteSystem(
        Request $_oRequest,
        SystemModle $_oSystemModle,
        SubSystemModle $_oSubSystemModle
    )
    {
        ## 判斷使用者權限
        if ($this->checkSession($_oRequest, false) !== 'success') {
            return redirect($this->checkSession($_oRequest, false));
        }

        $iSystemID = (int) $_oRequest->input('id');

        ## 取得系統分類底下是否還有子項目
        $iSubSystemCount = $_oSubSystemModle
            ->where('system_id', $iSystemID)
            ->count();

        if ($iSubSystemCount > 0) {
            return response()->json(['result' => false]);
        }

        ## 刪除系統分類項目
        $bResult = $_oSystemModle
            ->where('system_id', $iSystemID)
            ->delete();

        return response()->json(['result' => true]);
    }
}
