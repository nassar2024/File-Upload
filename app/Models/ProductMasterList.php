<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductMasterList extends Model
{
    use HasFactory;

    protected $table = 'product_master_list';
    protected $primaryKey = 'product_id';
    protected $fillable = ['type', 'brand', 'model', 'capacity', 'quantity'];
}