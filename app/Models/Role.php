<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Role extends Model
{
    use Notifiable;

    protected $fillable = ['name'];

    public function user()
    {
        return $this->hasOne(User::class);
    }
}
