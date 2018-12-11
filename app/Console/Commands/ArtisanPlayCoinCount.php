<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Count\PlayCoinCountModel;
/**
 * Description of ArtisanPlayCoinCount
 *
 * @author 七彩P1
 */
class ArtisanPlayCoinCount extends Command{
    protected $name = '';
    protected $signature = 'sync:playcoincount';
    public function handle()
    {
        (new PlayCoinCountModel)->arstianCount();
    }
}

