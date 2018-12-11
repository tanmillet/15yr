<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Console\Commands;
use App\Models\Count\ActiveCountModel;
use Illuminate\Console\Command;
/**
 * Description of ArtisanActiveCount
 *
 * @author 七彩P1
 */
class ArtisanActiveCount  extends Command{
    //put your code here
    protected $name = 'sync:activecount';
    public function handle()
    {
        (new ActiveCountModel)->arstianCount();
    }
}
