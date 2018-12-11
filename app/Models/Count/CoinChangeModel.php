<?php
/**
 * Created by PhpStorm.
 * User: qc
 * Date: 2018/10/9
 * Time: 11:43
 */

namespace App\Models\Count;

use App\Models\Count\base\CoinChange;

class CoinChangeModel
{
    /**
     * 定时统计金币变化排行
     * @return string
     */
    public function arstianCount()
    {
        $coinchange = new CoinChange();
        $res = $coinchange->where('date',date('Y-m-d'))->first(); //查询今日是否已经统计数据
        if($res)
            return;
        $datas = (new \App\Models\Count\CoinRankModel())->coinchange()->toArray();
        if($datas == false) // 检查是否查询到数据/查询是否错误
            return;
        $coinchange->insert($datas);
    }
}