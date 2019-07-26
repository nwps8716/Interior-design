<?php

namespace App\Http\Controllers\UnitPrice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UnitPrice\Engineering As EngineeringModle;
use App\Model\UnitPrice\SubEngineering As SubEngineeringModle;
use App\Model\UnitPrice\System As SystemModle;
use App\Model\UnitPrice\SubSystem As SubSystemModle;
use App\Model\UnitPrice\SubSystemSort As SubSystemSortModle;
use App\Model\UnitPrice\GeneralSort As GeneralSortModle;
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
        $sCheckSession = $this->checkSession($_oRequest, false);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
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
            $aResult[$aValue['project_id']][$aValue['sort']] = [
                'sub_project_id' => $aValue['sub_project_id'],
                'sub_project_name' => $aValue['sub_project_name'],
                'unit_price' => $aValue['unit_price'],
                'unit' => $aValue['unit'],
                'remark' => $aValue['remark']
            ];
            ksort($aResult[$aValue['project_id']]);
        }

        return view('engineering_unitprice', [
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
        $sCheckSession = $this->checkSession($_oRequest, false);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
        }

        $iProjectID = (int) $_oRequest->input('project_id');
        $sSubProjectName = $_oRequest->input('sub_project_name');
        $iUnitPrice = (int) $_oRequest->input('unit_price');
        $sUnit = $_oRequest->input('unit');
        $sRemark = $_oRequest->input('remark');

        $iLastSort = $_oSubEngineeringModle
            ->where('project_id', $iProjectID)
            ->max('sort');

        $bResult = $_oSubEngineeringModle->insert(
            [
                'sub_project_name' => $sSubProjectName,
                'project_id' => $iProjectID,
                'unit_price' => $iUnitPrice,
                'unit' => $sUnit,
                'remark' => $sRemark,
                'sort' => ($iLastSort + 1),
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
        $sCheckSession = $this->checkSession($_oRequest, false);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
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
        $sCheckSession = $this->checkSession($_oRequest, false);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
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
        $sCheckSession = $this->checkSession($_oRequest, false);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
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
        $sCheckSession = $this->checkSession($_oRequest, false);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
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
        $sCheckSession = $this->checkSession($_oRequest, false);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
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
        SubSystemModle $_oSubSystemModle,
        SubSystemSortModle $_oSubSystemSortModle,
        GeneralSortModle $_oGeneralSortModle
    )
    {
        $aResult = [];

        ## 判斷使用者權限
        $sCheckSession = $this->checkSession($_oRequest, false);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
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
                'remark' => $aValue['remark']
            ];
            ksort($aResult[$aValue['system_id']][$aValue['general_name']]);
        }
        
        return view('system_unitprice', [
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
        $sCheckSession = $this->checkSession($_oRequest, false);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
        }

        $sSystemName = $_oRequest->input('system_name');

        ## 取得最後一個排序，但不能為好禮贈送999
        $iLastSort = $_oSystemModle
            ->where('sort', '!=', '999')
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
        SubSystemModle $_oSubSystemModle,
        SubSystemSortModle $_oSubSystemSortModle,
        GeneralSortModle $_oGeneralSortModle
    )
    {
        ## 判斷使用者權限
        $sCheckSession = $this->checkSession($_oRequest, false);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
        }

        $iSystemID = (int) $_oRequest->input('system_id');
        $sGeneralName = $_oRequest->input('general_name');
        $sSubSystemName = $_oRequest->input('sub_system_name');
        $sFormat = $_oRequest->input('format');
        $iUnitPrice = (int) $_oRequest->input('unit_price');
        $sUnit = $_oRequest->input('unit');
        $sRemark = $_oRequest->input('remark');

        ## 取得該統稱細項的總數
        $aGeneralDetail = $_oSubSystemModle
            ->where('system_id', $iSystemID)
            ->where('general_name', $sGeneralName)
            ->get()
            ->pluck('sub_system_id', 'sub_system_id')
            ->toArray();

        ## 取得系統工程 - 統稱細項排序
        $aGeneralSort = $_oGeneralSortModle
            ->get()
            ->pluck('sort', 'sub_system_id')
            ->toArray();

        foreach ($aGeneralDetail as $key => $value) {
            $aGeneralSortList[$aGeneralSort[$value]] = $value;
            ksort($aGeneralSortList);
        }
        $iGeneralDetailCount = (!empty($aGeneralSortList)) ? array_key_last($aGeneralSortList) : 0;

        $iID = $_oSubSystemModle->insertGetId(
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

        ## 新增該子項目排序
        $_oGeneralSortModle->insert(
            [
                'sub_system_id' => $iID,
                'sort' => $iGeneralDetailCount + 1,
            ]
        );

        $oSubSystemSort = $_oSubSystemSortModle
            ->where('system_id', $iSystemID)
            ->get();

        if (!$oSubSystemSort->isEmpty() &&
            $oSubSystemSort->where('general_name', $sGeneralName)->isEmpty()) {
            $iLastSort = $oSubSystemSort->max('sort');
            $_oSubSystemSortModle->insert(
                [
                    'system_id' => $iSystemID,
                    'general_name' => $sGeneralName,
                    'sort' => ($iLastSort + 1)
                ]
            );
        } else if ($oSubSystemSort->isEmpty() &&
            $oSubSystemSort->where('general_name', $sGeneralName)->isEmpty()) {
                $_oSubSystemSortModle->insert(
                    [
                        'system_id' => $iSystemID,
                        'general_name' => $sGeneralName,
                        'sort' => 1
                    ]
                );
        }

        return response()->json(['result' => true]);
    }

    /**
     * 刪除-系統單價子項目內容
     *
     * @return array
     */
    public function deleteSubSystem(
        Request $_oRequest,
        SubSystemModle $_oSubSystemModle,
        SubSystemSortModle $_oSubSystemSortModle,
        GeneralSortModle $_oGeneralSortModle
    )
    {
        ## 判斷使用者權限
        $sCheckSession = $this->checkSession($_oRequest, false);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
        }

        $iSubSystemID = (int) $_oRequest->input('id');
        $iSystemID = (int) $_oRequest->input('sid');
        $sGeneralName = (string) $_oRequest->input('general_name');

        $oSubSystem = $_oSubSystemModle
            ->where('system_id', $iSystemID)
            ->where('general_name', $sGeneralName);

        if ($oSubSystem->count() === 1) {
            ## 刪除系統子項目統稱排序
            $_oSubSystemSortModle
                ->where('system_id', $iSystemID)
                ->where('general_name', $sGeneralName)
                ->delete();
        }

        ## 刪除系統子項目
        $oSubSystem->where('sub_system_id', $iSubSystemID)->delete();

        ## 刪除系統子項目統稱細項排序
        $_oGeneralSortModle->where('sub_system_id', $iSubSystemID)->delete();

        return response()->json(['result' => true]);
    }

    /**
     * 更新-系統單價子項目內容
     *
     * @return array
     */
    public function putSubSystem(
        Request $_oRequest,
        SubSystemModle $_oSubSystemModle,
        SubSystemSortModle $_oSubSystemSortModle,
        GeneralSortModle $_oGeneralSortModle
    )
    {
        ## 判斷使用者權限
        $sCheckSession = $this->checkSession($_oRequest, false);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
        }
        
        $iSubSystemID = (int) $_oRequest->input('id');
        $iSystemID = (int) $_oRequest->input('sid');
        $sGeneralName = $_oRequest->input('general_name');
        $sOriginGeneralName = $_oRequest->input('origin_general_name');
        $sSubSystemName = $_oRequest->input('name');
        $sFormat = $_oRequest->input('format');
        $iUnitPrice = (int) $_oRequest->input('unit_price');
        $sUnit = $_oRequest->input('unit');
        $sRemark = $_oRequest->input('remark');

        $oSubSystem = $_oSubSystemModle
            ->where('system_id', $iSystemID)
            ->where('general_name', $sOriginGeneralName);

        $oPreSubSystemSort = $_oSubSystemSortModle
            ->where('system_id', $iSystemID);

        ## 取得欲修改系統工程底下最後一個排序
        $oMaxSort = $oPreSubSystemSort;
        $iMaxSort = $oMaxSort->get()->max('sort');

        ## 預修改統稱名稱是否存在
        $iPreCount = $oPreSubSystemSort
            ->where('general_name', $sGeneralName)
            ->count();

        if ($oSubSystem->count() === 1 && $iPreCount === 0) {
            ## 修改系統子項目統稱名稱
            $_oSubSystemSortModle
                ->where('system_id', $iSystemID)
                ->where('general_name', $sOriginGeneralName)
                ->update(
                    [
                        'general_name' => $sGeneralName,
                        'system_id' => $iSystemID
                    ]
                );

        ## 判斷原本統稱底下無其他項目 && 預修改統稱已存在 && 原本統稱和預修改統稱名稱不一樣，刪除原本的統稱
        } else if ($oSubSystem->count() === 1 && $iPreCount === 1 && $sOriginGeneralName !== $sGeneralName) {
            $_oSubSystemSortModle
                ->where('system_id', $iSystemID)
                ->where('general_name', $sOriginGeneralName)
                ->delete();

        ## 判斷原本統稱底下有其他項目 && 預修改統稱不存在，新增一筆新的統稱排序
        } else if ($oSubSystem->count() > 1 && $iPreCount === 0) {
            $_oSubSystemSortModle->insert(
                [
                    'system_id' => $iSystemID,
                    'general_name' => $sGeneralName,
                    'sort' => ($iMaxSort + 1)
                ]
            );
        }

        ## 取得該統稱細項的總數
        $aGeneralDetail = $_oSubSystemModle
            ->where('system_id', $iSystemID)
            ->where('general_name', $sGeneralName)
            ->get()
            ->pluck('sub_system_id', 'sub_system_id')
            ->toArray();

        ## 取得系統工程 - 統稱細項排序
        $aGeneralSort = $_oGeneralSortModle
            ->get()
            ->pluck('sort', 'sub_system_id')
            ->toArray();

        foreach ($aGeneralDetail as $key => $value) {
            $aGeneralSortList[$aGeneralSort[$value]] = $value;
            ksort($aGeneralSortList);
        }
        $iGeneralDetailCount = (!empty($aGeneralSortList)) ? array_key_last($aGeneralSortList) : 0;

        $_oGeneralSortModle
            ->where('sub_system_id', $iSubSystemID)
            ->update(
                [
                    'sort' => $iGeneralDetailCount + 1,
                ]
            );

        ## 更新系統子項目
        $bResult = $oSubSystem
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
        $sCheckSession = $this->checkSession($_oRequest, false);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
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
        $sCheckSession = $this->checkSession($_oRequest, false);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
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

    /**
     * 取得工程排序設定列表
     *
     * @return array
     */
    public function getSortList(
        Request $_oRequest,
        EngineeringModle $_oEngineeringModle,
        SubEngineeringModle $_oSubEngineeringModle,
        SystemModle $_oSystemModle,
        SubSystemModle $_oSubSystemModle,
        SubSystemSortModle $_oSubSystemSortModle,
        GeneralSortModle $_oGeneralSortModle
    )
    {
        $aProject = $aSubProject = $aProjectSort = [];
        $aSystem = $aSubSystem = $aSystemSort = $aSubSystemSort = [];
        $aSystemDetailSort = $aGeneralSort = [];
        $aProjectList[0] = '裝潢工程大項';
        $aSystemList[0] = '系統工程大項';

        ## 判斷使用者權限
        $sCheckSession = $this->checkSession($_oRequest, false);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
        }

        ## 取得裝潢工程主項目列表
        $aProject = $_oEngineeringModle
            ->get()
            ->sortBy('sort')
            ->keyBy('sort')
            ->toArray();

        ## 取得裝潢大項目排序
        $aProjectListTmp = array_pluck($aProject, 'project_name', 'project_id');
        $aProjectList = $aProjectList + $aProjectListTmp;

        ## 取得裝潢工程子項目
        $aSubProject = $_oSubEngineeringModle
            ->get()
            ->toArray();

        ## 組裝潢工程子項目列表
        $aProjectSort[0] =  $aProject;
        foreach ($aSubProject as $value) {
            if (!empty($aProjectList[$value['project_id']])) {
                $aProjectSort[$value['project_id']][$value['sort']] = [
                    'sub_project_id' => $value['sub_project_id'],
                    'sub_project_name' => $value['sub_project_name']
                ];
                ksort($aProjectSort[$value['project_id']]);
            }
        }

        ## 取得系統工程主項目列表
        $aSystem = $_oSystemModle
            ->where('system_name', '!=' , '好禮贈送')
            ->get()
            ->sortBy('sort')
            ->keyBy('sort')
            ->toArray();

        ## 取得系統大項目排序
        $aSystemListTmp = array_pluck($aSystem, 'system_name', 'system_id');
        $aSystemList = $aSystemList + $aSystemListTmp;

        ## 取得系統工程子項目排序
        $aSubSystemSort = $_oSubSystemSortModle
            ->get()
            ->toArray();

        ## 組系統工程子項目列表
        $aSystemSort[0] =  $aSystem;
        foreach ($aSubSystemSort as $value) {
            if (!empty($aSystemList[$value['system_id']])) {
                $aSystemSort[$value['system_id']][$value['sort']] = [
                    'sgn_id' => $value['sgn_id'],
                    'general_name' => $value['general_name']
                ];
                ksort($aSystemSort[$value['system_id']]);
                ## 有子項目系統工程ID
                $aSystemID[$value['system_id']] = $value['system_id'];
            }
        }

        ## 判斷哪些系統工程有子項目
        foreach ($aSystemList as $id => $name) {
            if ($id > 0 && !in_array($id, $aSystemID)) {
                unset($aSystemList[$id]);
            }
        }

        ## 取得系統工程子項目
        $aSubSystem = $_oSubSystemModle
            ->where('system_id', '!=', 6)
            ->get()
            ->toArray();

        ## 取得系統工程 - 統稱細項排序
        $aGeneralSort = $_oGeneralSortModle
            ->get()
            ->pluck('sort', 'sub_system_id')
            ->toArray();

        ## 判斷哪些系統工程有子項目
        foreach ($aSubSystem as $value) {
            $aSystemDetailSort[$value['system_id']][$value['general_name']][$value['sub_system_id']] = [
                'sub_system_name' => $value['sub_system_name'],
                'format' => $value['format'],
                'remark' => $value['remark']
            ];

            ## 取得該大項有哪些統稱
            $aGeneral[$value['system_id']][] = $value['general_name'];
            $aGeneral[$value['system_id']] = array_unique($aGeneral[$value['system_id']]);
        }

        return view('engineering_sort', [
            'project_list' => $aProjectList,
            'system_list' => $aSystemList,
            'project_sort' => $aProjectSort,
            'system_sort' => $aSystemSort,
            'system_detail_sort' => $aSystemDetailSort,
            'system_general' => $aGeneral,
            'general_sort' => $aGeneralSort,
        ]);
    }

    /**
     * 修改工程大項目or子項目排序
     *
     * @return array
     */
    public function putEngineeringSort(
        Request $_oRequest,
        EngineeringModle $_oEngineeringModle,
        SubEngineeringModle $_oSubEngineeringModle,
        SystemModle $_oSystemModle,
        SubSystemModle $_oSubSystemModle,
        SubSystemSortModle $_oSubSystemSortModle,
        GeneralSortModle $_oGeneralSortModle,
        $_iCategoryID
    )
    {
        $aResult = [];

        ## 判斷使用者權限
        $sCheckSession = $this->checkSession($_oRequest, false);
        if ($sCheckSession !== 'success') {
            return redirect($sCheckSession)->with(['ip' => $_SERVER['REMOTE_ADDR']]);
        }

        $bSubStatus = (boolean) $_oRequest->input('sub_status');
        $aSortData = (array) $_oRequest->input('sort_data');

        ## 裝潢工程 - 大項目or細項
        if ((int) $_iCategoryID === 1) {
            $oSortModel = ($bSubStatus) ? $_oSubEngineeringModle : $_oEngineeringModle;
            $sIDName = ($bSubStatus) ? 'sub_project_id' : 'project_id';

        ## 系統工程 - 大項目or統稱
        } elseif ((int) $_iCategoryID === 2) {
            $oSortModel = ($bSubStatus) ? $_oSubSystemSortModle : $_oSystemModle;
            $sIDName = ($bSubStatus) ? 'sgn_id' : 'system_id';

        ## 系統工程 - 統稱細項
        } elseif ((int) $_iCategoryID === 3) {
            $oSortModel = $_oGeneralSortModle;
            $sIDName = 'sub_system_id';
        }

        foreach ($aSortData as $sort => $id) {
            $iSort = $sort + 1;
            ## 更新工程排序
            $bResult = $oSortModel
                ->where($sIDName, $id)
                ->update(
                    [
                        'sort' => $iSort,
                    ]
                );
        }

        return response()->json(['result' => true]);
    }
}
