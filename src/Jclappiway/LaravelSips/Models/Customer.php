<?php

namespace Jclappiway\LaravelSips\Models;

use Illuminate\Database\Eloquent\Model;
use Jclappiway\LaravelSips\Models\Country;
use Validator;

class Customer extends Model
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
        'ip_address',
        'cgu',
        'newsletter',
    ];

    protected $rules = [
        'firstname' => 'required|max:255',
        'lastname'  => 'required|max:255',
        'email'     => 'required|email|max:255',
        'address'   => 'required|max:255',
        'city'      => 'required|max:255',
        'zipcode'   => 'required|max:255',
        'country'   => 'required|max:255',
        'amount'    => 'required',
        'recurring' => 'required',
        'cgu'       => 'accepted',
    ];

    public function orders()
    {
        return $this->belongsToMany('\Jclappiway\LaravelSips\Models\Order');
    }

    public function getFullAddress()
    {
        $country = Country::where('iso_3166_2', $this->country)->first();
        return $this->address . ', ' . $this->zipcode . ' ' . $this->city . ' ' . $country->name;
    }

    public function makeValidator($data)
    {
        $this->validator = Validator::make($data, $this->rules);
    }

    public function validate($data)
    {
        $this->makeValidator($data);
        return $this->validator->passes();
    }

}
