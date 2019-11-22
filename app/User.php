<?php

namespace App;

use App\Http\Controllers\User\SettingsController;
use Auth;
use Carbon\Carbon;
use Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    use SoftDeletes, Notifiable;

    public $table = 'users';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
        'email_verified_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'created_at',
        'updated_at',
        'deleted_at',
        'remember_token',
        'email_verified_at',
    ];

    /**
     * @param $value
     * @return string|null
     */
    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    /**
     * @param $value
     */
    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    /**
     * @param $input
     */
    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    /**
     * @param string $token
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('\App\Role');
    }

    /**
     * Route notifications for the mail channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForMail($notification)
    {
        return $this->email;
    }

    public function BugreportComments()
    {
        return $this->hasMany('App\BugreportComment');
    }

    public static function boot()
    {
        parent::boot();

        static::created(function ($user){
            $profile = new Profile();
            $profile->user_id = $user->id;
            $profile->save();
        });
    }

    public function followAttackPlanner()
    {
        return $this->morphedByMany('App\Tool\AttackPlanner\AttackList', 'followable', 'follows');
    }

    public function followMap()
    {
        return $this->morphedByMany('App\Tool\Map\Map', 'followable', 'follows');
    }

    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    public function avatarPath(){
        SettingsController::existProfile();
        $avatar = $this->profile->avatar;
        if (Storage::disk('local')->exists($avatar)){
            return Storage::url('app/'.$avatar);
        }else{
            return asset('images/default/user.png');
        }
    }

    public function dsConnection()
    {
        return $this->hasMany('App\DsConnection');
    }

}
