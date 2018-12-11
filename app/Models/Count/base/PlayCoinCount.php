<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Count\base;
use App\Models\Model;
/**
 * Description of PlayCoinCount
 *
 * @author 七彩P1
 */
class PlayCoinCount extends Model{
    //put your code here
    public $table = "playcoincount";
    public $timestamps =FALSE;
    
    public $count_type_arr =[
        "1"=>"人数","2"=>"局数","3"=>"玩家累计输金币","4"=>"玩家累计赢金币","5"=>"机器人陪打赢","6"=>"服务费","7"=>"合桌次数"
    ];
    public $room_type_arr =[
        "1"=>"新手场","2"=>"初级场","3"=>"中级场","4"=>"高级场","5"=>"精英场","6"=>"大师场"
    ];
    
    
    public $showgametype = [//这个跟gameid关联
        //1.经典 2.百人 斗地主1经典 2癞子 3比赛 4排位赛
        "1" => [//斗牛
            //key的对应值 gameid_gametype请参照 config下的game.php的gameid 跟 gametype  跟 gametype  room_id 下表是房间类型    值是房间id
            "1"=>["key"=>"1-1","value"=>"经典","room_id"=>["1"=>1,"2"=>2,"3"=>3,"4"=>4,"5"=>5,"6"=>6]],
        ],
        "2" => [//斗地主
            "1"=>["key"=>"2-1","value"=>"经典","room_id"=>["1"=>1,"2"=>2,"3"=>3,"4"=>4,"5"=>5,"6"=>6]],
            "2"=>["key"=>"2-2","value"=>"癞子","room_id"=>["1"=>1,"2"=>2,"3"=>3,"4"=>4,"5"=>5,"6"=>6]],
            "3"=>["key"=>"2-4","value"=>"排位赛","room_id"=>["1"=>1,"2"=>2,"3"=>3,"4"=>4,"5"=>5,"6"=>6]],
        ],

        "4" => [//跑得快
            "1"=>["key"=>"4-1","value"=>"十五张","room_id"=>["1"=>1401,"2"=>1402,"3"=>1403,"4"=>1404]],
            "2"=>["key"=>"4-2","value"=>"十六张","room_id"=>["1"=>1405,"2"=>1406,"3"=>1407,"4"=>1408]],
        ],
    ];
}
