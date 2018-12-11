<?php
/**
 * Created by PhpStorm.
 * User: qc
 * Date: 2018/9/27
 * Time: 18:56
 */

namespace App\Http\Controllers\Game;

use App\Http\Controllers\BaseController;
use App\Models\Count\base\CoinCount;
use App\Models\Count\base\CoinCountType;
use Illuminate\Http\Request;
use App\Models\Count\CoinCountModel;
class CoinCountController extends BaseController
{
    /**
     * 展示金币数据
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        (new CoinCountModel)->arstianCount();
        $date_type = "yyyy-mm-dd"; // 设置日期格式
        $where[0] = ['date','>=',date('Y-m-d',strtotime("-1 day"))];
        $where[1] = ['date','<=',date('Y-m-d',strtotime("-1 day"))];
        $where[2] = ['game',2];
        if($request->get('game')){
            $where[2] = ['game',$request->get('game')];
        }else{
            $request->offsetSet('game',2);
        }
        if($request->get('ldate')){
            $where[0] = ['date','>=',$request->get('ldate')];
        }
        if($request->get('rdate')){
            $where[1] = ['date','<=',$request->get('rdate')];
        }
        
        if($request->get('data_type')){
            $data_type = $request->get('data_type');
        }else{
            $data_type = "datas";
            $request->offsetSet('data_type', "datas");
        }
        $data_type_arr = array(
            "datas"=>"全部用户",
            "active_datas"=>"活跃用户",
        );
        
        $cointype = (new CoinCountType())->getTable();
        $coinc = (new CoinCount());
        $datas = $coinc->leftJoin($cointype,$cointype.'.id',$coinc->getTable().'.type')->where($where)->get()->toArray();
        $da = array();
        if(!empty($datas)){
            $types = json_decode($datas[0]['type']);
            foreach ($types as $k=>$v){
                $types[$k] = isset($types[$k+1]) ? $v.'-'.$types[$k+1] : $v.'-更多';
            }
            foreach ($datas as $ke=>$va){
                $da[$ke]['name'] = substr($va['date'],0,-9);
                if($va[$data_type]){
                    foreach (json_decode($va[$data_type]) as $k=>$v){
                        $da[$ke]['data'][$k] = (int)$v;
                    }
                }
                
            }
        }else{
            $types = [];
            $da = '';
        }
        return view('admin.game.moneycount.coin',['date_type'=>$date_type,'datas'=>$datas,'types'=>$types,'da'=>json_encode($da),"data_type_arr"=>$data_type_arr]);
    }

    /**
     * 添加统计类型
     * @return string
     */
    public function test()
    {
        $type = new CoinCountType();
        $data = [0,4000,10000,20000,50000,100000,500000,1000000,5000000,10000000,50000000,100000000];
        $type->where('active',1)->update(['active'=>0]); // 先把所有规则设置为不激活
        $res = $type->where('type',json_encode($data))->update(['active'=>1]); // 查询新规则是否在数据库中有重复
        if(!$res) // 如果没有重复
            $res = $type->insert(['type' => json_encode($data), 'active' => 1]); // 插入一条新数据
        if($res)
            return "OK";
        else
            return "FAILD";
    }
}