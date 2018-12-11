<?php
/**
 * Created by PhpStorm.
 * User: qc
 * Date: 2018/10/9
 * Time: 11:15
 */

namespace App\Console\Commands;

use App\Models\Count\MatchRankCountModel;
use Illuminate\Console\Command;

class ArtisanMatchRankCount extends Command
{
    protected $name = 'sync:matchRankCount';
    public function handle()
    {
        (new MatchRankCountModel())->arstianCount();
    }
}