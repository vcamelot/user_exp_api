<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'company_name', 'job_title', 'experience',
        'month_from', 'year_from', 'month_to', 'year_to'
    ];
}
