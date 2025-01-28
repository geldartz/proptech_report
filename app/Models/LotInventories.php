<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LotInventories extends Model
{
    use HasFactory;

    protected $connection = 'mysql_main';
    protected $table = 'lot_inventories';

    public function account_number(){
        return $this->hasOne(UserLotInventories::class, 'lot_inventory_id')->selectRaw('id, lot_inventory_id, account_number');
    }
    public function customer_name()
    {
        return $this->hasOne(UserLotInventories::class, 'lot_inventory_id')
                    ->select('id', 'lot_inventory_id', 'user_id')
                    ->selectRaw("(SELECT CONCAT(first_name, ' ', last_name) FROM users WHERE id = user_id) as customer_name");
    }
}
