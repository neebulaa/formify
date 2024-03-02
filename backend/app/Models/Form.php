<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public $timestamps = false;

    public function allowed_domains(){
        return $this->hasMany(AllowedDomain::class);
    }

    public function questions(){
        return $this->hasMany(Question::class);
    }

    public function responses(){
        return $this->hasMany(Response::class);
    }
}
