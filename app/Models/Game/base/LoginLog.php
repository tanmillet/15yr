<?php
/**
 * Created by PhpStorm.
 * User: qc
 * Date: 2018/10/9
 * Time: 10:09
 */

namespace App\Models\Game\base;


use App\Models\Model;

class LoginLog extends Model
{
    protected $table = 'login_log';
    protected $connection = 'mysql_three';
}