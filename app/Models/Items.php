<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    protected $connection = 'mysql_acc';
    protected $tables = "l7w_items";
    protected $fillable = [
        'company_id', 'name', 'sku', 'description', 'sale_price', 'purchase_price', 'quantity', 'enabled'
    ];
}