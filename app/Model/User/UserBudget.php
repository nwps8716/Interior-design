<?php

namespace App\Model\User;

use Illuminate\Database\Eloquent\Model;
use DB;
use Exception;

class UserBudget extends Model
{
    protected $fillable = [
        'id',
        'user_name',
        'category_id',
        'level_id',
        'sub_project_id',
        'sub_project_number',
        'remark',
    ];

    protected $table = 'user_budget';
    protected $primaryKey = 'id';
    public $timestamps = false;

    /**
     * 更新使用者裝潢工程級距的子細目數量
     *
     * @param  string  $_sUserName         使用者登入名稱
     * @param  int     $_iBudgetID         級距ID
     * @param  int     $_iSubProjectID     工程子項目ID
     * @param  int     $_iSubProjectNumber 工程子項目數量
     * @param  int     $_iCategoryID       分類ID(工程：1、系統：2、好禮贈送：3)
     *
     * @return boolean
     */
    public function updateData(
        $_sUserName,
        $_iBudgetID,
        $_iSubProjectID,
        $_iSubProjectNumber,
        $_iCategoryID
    )
    {
        try {
            DB::beginTransaction();
            $oResult = $this->updateOrCreate(
               [
                    'user_name' => $_sUserName,
                    'level_id' => $_iBudgetID,
                    'sub_project_id' => $_iSubProjectID,
                    'category_id' => $_iCategoryID
                ],
                [
                    'sub_project_number' => $_iSubProjectNumber,
                    'remark' => '',
                    'category_id' => $_iCategoryID
                ]
            );
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }

        return true;
    }
}
