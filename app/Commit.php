<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Commit extends Model
{
    public $table = 'commits';

    public $primaryKey = 'id';  
}
