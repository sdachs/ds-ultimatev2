<?php
/**
 * Created by IntelliJ IDEA.
 * User: crams
 * Date: 18.08.2019
 * Time: 16:05
 */

namespace App\Tool\AttackPlanner;


use Illuminate\Contracts\Session\Session;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class AttackList extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'world_id',
        'user_id',
    ];

    protected $hidden = [
        'edit_key',
        'show_key',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function world()
    {
        return $this->belongsTo('App\World');
    }

    public function items()
    {
        return $this->hasMany('App\Tool\AttackPlanner\AttackListItem' )->orderBy('send_time');
    }

    public function nextAttack(){
        $item = $this->items->where('send_time', '>', Carbon::createFromTimestamp(time()))->first();
        if (!isset($item->send_time)){
            return '-';
        }
        $date = $item->send_time->locale(\App::getLocale());
        return $date->isoFormat('L LT');
    }

    public function outdatedCount(){
        return $this->items->where('send_time', '<', Carbon::createFromTimestamp(time()))->count();
    }

    public function attackCount(){
        return $this->items->where('send_time', '>', Carbon::createFromTimestamp(time()))->count();
    }

}
