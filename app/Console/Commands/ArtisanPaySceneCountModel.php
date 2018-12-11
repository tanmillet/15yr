<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Console\Commands;
use Illuminate\Console\Command;
use App\Models\Count\PaySceneCountModel;
/**
 * Description of ArtisanPaySceneCountModel
 *
 * @author 七彩P1
 */
class ArtisanPaySceneCountModel extends Command{
    protected $name = '';
    protected $signature = 'sync:payscencount {--count_type=}';
    public function handle()
    {
        $count_type = $this->option('count_type');
        (new PaySceneCountModel)->arstianCount($count_type);
    }
}
