<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    public $appends = ['company_name'];

    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'company_id');
    }

    public function getCompanyNameAttribute()
    {
        return $this->company->name;
    }
}