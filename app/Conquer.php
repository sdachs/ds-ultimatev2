<?php

namespace App;

use App\Ally;
use App\Util\BasicFunctions;
use Illuminate\Database\Eloquent\Model;

class Conquer extends Model
{
    protected $table = 'conquer';

    public static function playerConquerCounts($server, $world, $playerID){
        $conquerModel = new Conquer();
        $conquerModel->setTable(BasicFunctions::getDatabaseName($server, $world).'.conquer');

        $conquer = collect();
        $conquer->put('old', $conquerModel->where('old_owner', $playerID)->count());
        $conquer->put('new', $conquerModel->where('new_owner', $playerID)->count());
        $conquer->put('total', $conquer->get('old')+$conquer->get('new'));

        return $conquer;
    }
    
    public static function allyConquerCounts($server, $world, $allyID){
        $conquerModel = new Conquer();
        $conquerModel->setTable(BasicFunctions::getDatabaseName($server, $world).'.conquer');
        
        $playerModel = new Player();
        $playerModel->setTable(BasicFunctions::getDatabaseName($server, $world).'.player_latest');

        $allyPlayers = array();
        foreach ($playerModel->newQuery()->where('ally_id', $allyID)->get() as $player) {
            $allyPlayers[] = $player->playerID;
        }
        
        $conquer = collect();
        $conquer->put('old', $conquerModel->whereIn('old_owner', $allyPlayers)->count());
        $conquer->put('new', $conquerModel->whereIn('new_owner', $allyPlayers)->count());
        $conquer->put('total', $conquer->get('old')+$conquer->get('new'));

        return $conquer;
    }
    
    public static function villageConquerCounts($server, $world, $villageID){
        $conquerModel = new Conquer();
        $conquerModel->setTable(BasicFunctions::getDatabaseName($server, $world).'.conquer');

        $conquer = collect();
        $conquer->put('total', $conquerModel->where('villageID', $villageID)->count());

        return $conquer;
    }
}
