<?php
/**
 * Created by PhpStorm.
 * User: qc
 * Date: 2018/10/9
 * Time: 11:15
 */

namespace App\Console\Commands;

use App\Models\Count\CoinChangeModel;
use App\Models\Count\CoinRankModel;
use Illuminate\Console\Command;

class ArtisanCoinRankCount extends Command
{
    protected $name = 'sync:coinRankCount';
    public function handle()
    {
        (new CoinRankModel())->arstianCount();
        (new CoinChangeModel())->arstianCount();
    }
}