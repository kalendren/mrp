<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'company_address',
        'company_contact',
        'company_email',
        'pcr_footer',
        'coc_statement',
        'logo_path',
        'Prefix',
    ];
}
