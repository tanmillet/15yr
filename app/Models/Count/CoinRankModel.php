<?php

namespace App\Models\Count;

use App\Models\Game\base\userGame;
use App\Models\Game\base\userGameCopy;
use App\Models\Game\base\userInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Models\Count\base\CoinRank;


class CoinRankModel extends Model
{
    /**
     * 定时统计金币排行榜
     * @return string
     */
    public function arstianCount()
    {
        $coinrank = new CoinRank();
        $res = $coinrank->where('date',date('Y-m-d'))->first();  //查询今日是否已经统计数据
        if($res)
            return;
        $datas = $this->coinrank()->toArray();
        if($datas == false)  // 检查是否查询到数据/查询是否错误
            return;
        $coinrank->insert($datas);
    }

    /**
     * 金币排行榜
     * @param int $jishi
     * @param int $limit
     * @return mixed
     */
    public function coinrank($jishi = 0,$limit = 50)
    {
        $date = $jishi ? 'now()' : 'curdate()';
        $usergame = new userGame();
        $userinfo = new userInfo();
        $ltable = $usergame->getTable();
        $rtable = $userinfo->getTable();
        $robot = $userinfo->aiUserId;
        $datas = $usergame
            ->leftJoin($rtable,$ltable.'.uid',$rtable.'.uid')
            ->select($ltable.'.uid','uname as nickname',
                'uchip as coin',
                DB::raw($date.' as date,
                    case when '.$ltable.'.uid>' . $robot . ' then 0 else 1 end as robot'))
            ->orderBy($ltable.'.uchip', 'desc')
            ->limit($limit)
            ->get();
        return $datas;
    }

    /**
     * 金币变化排行榜
     * @param int $jishi
     * @param int $limit
     * @return mixed
     */
    public function coinchange($jishi = 0,$limit = 50)
    {
        $date = $jishi ? 'now()' : 'curdate()';
        $before = new userGameCopy();
        $rtable = $before->getTable(); // 往日日期表
        $now = new userGame();
        $ltable = $now->getTable(); // 当前日期表
        $userinfo = new userInfo();
        $utable = $userinfo->getTable(); // 用户信息表
        $datas = $now
            ->leftJoin($rtable, $ltable . '.uid', $rtable . '.uid')
            ->leftJoin($utable, $ltable . '.uid', $utable . '.uid')
            ->select($ltable . '.uid', $utable . '.uname as nickname',
                $rtable . '.uchip as beforecoin',
                $ltable . '.uchip as aftercoin',
                DB::raw('abs(' . $ltable . '.uchip - ' . $rtable . '.uchip) as changecoin,
                case when ' . $ltable . '.uchip > ' . $rtable . '.uchip then 1 else 0 end as win,
                '.$date.' as date,
                0 as robot'))
            ->where( $ltable . '.uid',">=",$userinfo->aiUserId )
            ->orderBy('changecoin', 'desc')
            ->limit($limit)
            ->get();
        return $datas;
    }
}
