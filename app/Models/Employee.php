<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'gender', 'title', 'first_name', 'last_name',
        'address', 'city', 'country', 'postcode', 'email'
    ];

    /**
     * Employee's experiences (one-to-many
     * 
     * @return \Illuminate\Database\Eloquent\Relations
     */
    public function experience() {
        return $this->hasMany('App\Models\Experience');
    }
}
