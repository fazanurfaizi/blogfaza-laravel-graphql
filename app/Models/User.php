<?php

namespace App\Models;

use App\Presenters\UserPresenter;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Gravatar;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, PresentableTrait;

    protected $presenter = UserPresenter::class;

    protected $fillable = [
        'name', 'email', 'password', 'role_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier(){
        return $this->getKey();
    }

    public function getJWTCustomClaims(){
        return [];
    }

    public function getAvatarAttribute(){
        return $this->avatar();
    }

    public function avatar($size = 80){
        return Gravatar::src($this->email, $size);
    }

    public function posts(){
        return $this->hasMany(Post::class);
    }    

    public function role(){
        return $this->belongsTo(Role::class);
    }
}
