<?php

namespace Jclappiway\LaravelSips\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'address',
        'city',
        'zipcode',
        'country',
        'phone',
    ];
}
