<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nucleares extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = ['nucl_tcodigo','nucl_tnombre','nucl_nestado'];
}
