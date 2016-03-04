<?php

namespace Jclappiway\LaravelSips\Models;

use Illuminate\Database\Eloquent\Model;
use Jclappiway\LaravelSips\Models\Country;

class Customer extends Model
{

    public function orders()
    {
        return $this->belongsToMany('\Jclappiway\LaravelSips\Models\Order');
    }

    public function getFullAddress()
    {
        $country = Country::where('iso_3166_2', $this->country)->first();
        return $this->address . ', ' . $this->zipcode . ' ' . $this->city . ' ' . $country->name;
    }

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'address',
        'city',
        'zipcode',
        'country',
        'phone',
        'ip_address',
        'cgu',
        'newsletter',
    ];
}
