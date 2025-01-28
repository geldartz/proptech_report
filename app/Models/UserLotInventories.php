<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLotInventories extends Model
{
    use HasFactory;

    protected $connection = 'mysql_main';
    protected $table = 'user_lot_inventories';
}
