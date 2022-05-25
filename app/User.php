<?php

namespace App;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function isAdmin()
    {
        return $this->email === 'admin@example.com';
    }

    public function gravitar($s = 80, $d = 'identicon', $r = 'g')
    {
        return 'https://www.gravatar.com/avatar/' . md5(strtolower(trim($this->email))) . "?s=$s&d=$d&r=$r";
    }
}
