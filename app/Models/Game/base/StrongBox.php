<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Models\Game\base;
use App\Models\Model;
/**
 * Description of StrongBox
 *
 * @author 七彩P1
 */
class StrongBox extends Model{
    //put your code here
    public  $table ="strong_box";
	public  $connection="mysql_two";
    protected $fillable  = [//创建用户时候可以修改添加的字段
        "uid",
        //"uchip",
        "uchip",
        "question" ,
        "answer" ,
        "question_two" ,
        "answer_two" ,
        "password" ,
        'created_at',
        'updated_at'
        //"utime",
    ];
    
    public  $answer_type  = [//
        "1"=>"你中学老师是谁？",
        "2"=>"你最有影响的名字？",
        "3"=>"你大学班主任的名字？",
        "4"=>"你的家乡在哪？",
    ];
}
