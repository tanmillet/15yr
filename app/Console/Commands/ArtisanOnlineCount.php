<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
use App\Models\OnlineModel;
use App\Models\Count\OnlineCountModel;
/**
 * Description of ArtisanOffOline
 *下线
 * @author 七彩P1
 */
class ArtisanOnlineCount extends Command{
    protected $name = 'sync:onlineCount';
    public function handle()
    {
        (new OnlineCountModel)->arstianCount();
    }
}
