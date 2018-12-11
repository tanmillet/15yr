<?php
/**
 * Created by PhpStorm.
 * User: qc
 * Date: 2018/9/29
 * Time: 11:48
 */

namespace App\Http\Controllers\Game;

use App\Http\Controllers\BaseController;
use App\Models\Count\base\CoinChange;
use App\Models\Count\base\CoinRank;
use App\Models\Count\CoinRankModel;
use Illuminate\Http\Request;

class CoinRankController extends BaseController
{
    /**
     * 展示金币排行榜信息
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $date_type = "yyyy-mm-dd"; // 设置日期格式
        $coinrank = new CoinRank();
        if($request->get('date')){ // 如果用户选择了日期
            $datas = $coinrank->where('date',$request->get('date'))->where('robot',0)->get();
        }else if($request->get('jishi')) {
            $coinrankm = new CoinRankModel();
            $datas = $coinrankm->coinrank(1);
        }else {
            $datas = $coinrank->where('date',date('Y-m-d',strtotime("-1 day")))->get();
        }
        return view('admin.game.moneycount.rank',['datas'=>$datas,'date_type'=>$date_type]);
    }

    /**
     * 展示金币变化排行榜信息
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function change(Request $request)
    {
        $date_type = "yyyy-mm-dd"; // 设置日期格式
        $coinchange = new CoinChange();
        if($request->get('date')){ // 有date,获取相应日期的数据
            $datas = $coinchange->where('date',$request->get('date'))->get();
        }else if($request->get('jishi')){ // 有即时,获取当前时间的数据
            $coinrank = new CoinRankModel();
            $datas = $coinrank->coinchange(1);
        }else { // 无date,无jishi,获取往日数据
            $datas = $coinchange->where('date',date('Y-m-d',strtotime("-1 day")))->get();
        }
        return view('admin.game.moneycount.change',['datas'=>$datas,'date_type'=>$date_type]);
    }
}