<?php

namespace App\Models\Count\base;

use Illuminate\Database\Eloquent\Model;

class SeasonCount extends Model
{
    public $table = "season_count";
    public $group = ["1"=>"青铜","2"=>"白银","3"=>"黄金","4"=>"铂金","5"=>"钻石","6"=>"大师","7"=>"王者"];
}
