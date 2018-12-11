<?php
/**
 * Created by PhpStorm.
 * User: qc
 * Date: 2018/10/9
 * Time: 10:55
 */

namespace App\Models\Count;

use App\Models\Game\SeasonModel;
use App\Models\Count\base\SeasonCount;

class SeasonCountModel
{
    /**
     * 定时统计赛季数据
     * @return false|string
     */
    public function arstianCount()
    {
        //查询当前赛季数据
        $res = (new SeasonModel())->getData2();
        $re = (new SeasonModel())->getData()->toArray();
        $arr = array();
        foreach ($re as $k => $v) {
            $d = $res->where('game', $v['game'])->where('season', $v['season'])->where('group', $v['group'])->where('order', $v['order'])->toArray();
            foreach ($d as $key => $val) {
                $arr[$k][$key] = $val['uid'];
            }
            $re[$k]['ids'] = json_encode($arr[$k]);
        }
        //判断是否重复插入
        $lastdate = (new SeasonCount())->where('date', date('Y-m-d', strtotime("-1 day")))->orderBy('id', 'desc')->first();
        if ($lastdate) {
            return;
        }
        //插入数据
        (new SeasonCount())->insert($re);
    }
}