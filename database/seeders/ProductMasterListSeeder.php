<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductMasterList;

class ProductMasterListSeeder extends Seeder
{
    public function run()
    {
        $products = [
            ['product_id' => '4450', 'type' => 'Smartphone', 'brand' => 'Apple', 'model' => 'iPhone SE', 'capacity' => '2GB/16GB', 'quantity' => 13],
            ['product_id' => '4768', 'type' => 'Smartphone', 'brand' => 'Apple', 'model' => 'iPhone SE', 'capacity' => '2GB/32GB', 'quantity' => 30],
            ['product_id' => '4451', 'type' => 'Smartphone', 'brand' => 'Apple', 'model' => 'iPhone SE', 'capacity' => '2GB/64GB', 'quantity' => 20],
            ['product_id' => '4574', 'type' => 'Smartphone', 'brand' => 'Apple', 'model' => 'iPhone SE', 'capacity' => '2GB/128GB', 'quantity' => 16],
            ['product_id' => '6039', 'type' => 'Smartphone', 'brand' => 'Apple', 'model' => 'iPhone SE (2020)', 'capacity' => '3GB/64GB', 'quantity' => 18],
            ['product_id' => '6040', 'type' => 'Smartphone', 'brand' => 'Samsung', 'model' => 'Galaxy S10', 'capacity' => '4GB/64GB', 'quantity' => 25],
            ['product_id' => '6041', 'type' => 'Smartphone', 'brand' => 'Samsung', 'model' => 'Galaxy S10+', 'capacity' => '6GB/128GB', 'quantity' => 15],
            ['product_id' => '6042', 'type' => 'Smartphone', 'brand' => 'Samsung', 'model' => 'Galaxy Note 10', 'capacity' => '8GB/256GB', 'quantity' => 12],
            ['product_id' => '6043', 'type' => 'Smartphone', 'brand' => 'Google', 'model' => 'Pixel 4', 'capacity' => '6GB/64GB', 'quantity' => 20],
            ['product_id' => '6044', 'type' => 'Smartphone', 'brand' => 'Google', 'model' => 'Pixel 4 XL', 'capacity' => '6GB/128GB', 'quantity' => 18],
            ['product_id' => '6045', 'type' => 'Laptop', 'brand' => 'Dell', 'model' => 'XPS 13', 'capacity' => '8GB/256GB', 'quantity' => 10],
            ['product_id' => '6046', 'type' => 'Laptop', 'brand' => 'Dell', 'model' => 'Inspiron 15', 'capacity' => '4GB/512GB', 'quantity' => 15],
            ['product_id' => '6047', 'type' => 'Laptop', 'brand' => 'HP', 'model' => 'Spectre x360', 'capacity' => '16GB/1TB', 'quantity' => 8],
            ['product_id' => '6048', 'type' => 'Laptop', 'brand' => 'Lenovo', 'model' => 'ThinkPad X1', 'capacity' => '16GB/512GB', 'quantity' => 12],
            ['product_id' => '6049', 'type' => 'Tablet', 'brand' => 'Apple', 'model' => 'iPad Air', 'capacity' => '4GB/64GB', 'quantity' => 22],
            ['product_id' => '6050', 'type' => 'Tablet', 'brand' => 'Samsung', 'model' => 'Galaxy Tab S6', 'capacity' => '6GB/128GB', 'quantity' => 19],
            ['product_id' => '6051', 'type' => 'Tablet', 'brand' => 'Amazon', 'model' => 'Fire HD 10', 'capacity' => '2GB/32GB', 'quantity' => 30],
            ['product_id' => '6052', 'type' => 'Headphones', 'brand' => 'Sony', 'model' => 'WH-1000XM4', 'capacity' => 'N/A', 'quantity' => 25],
            ['product_id' => '6053', 'type' => 'Headphones', 'brand' => 'Bose', 'model' => 'QuietComfort 35', 'capacity' => 'N/A', 'quantity' => 20],
            ['product_id' => '6054', 'type' => 'Smartwatch', 'brand' => 'Apple', 'model' => 'Watch Series 5', 'capacity' => '32GB', 'quantity' => 15],
            ['product_id' => '6055', 'type' => 'Smartwatch', 'brand' => 'Samsung', 'model' => 'Galaxy Watch 3', 'capacity' => '8GB', 'quantity' => 18],
            ['product_id' => '6056', 'type' => 'Camera', 'brand' => 'Canon', 'model' => 'EOS R', 'capacity' => 'N/A', 'quantity' => 10],
            ['product_id' => '6057', 'type' => 'Camera', 'brand' => 'Nikon', 'model' => 'Z6', 'capacity' => 'N/A', 'quantity' => 12],
            ['product_id' => '6058', 'type' => 'Monitor', 'brand' => 'LG', 'model' => 'UltraFine 4K', 'capacity' => 'N/A', 'quantity' => 15],
            ['product_id' => '6059', 'type' => 'Monitor', 'brand' => 'Dell', 'model' => 'S2721QS', 'capacity' => 'N/A', 'quantity' => 20],
            ['product_id' => '6060', 'type' => 'Keyboard', 'brand' => 'Logitech', 'model' => 'MX Keys', 'capacity' => 'N/A', 'quantity' => 25],
            ['product_id' => '6061', 'type' => 'Keyboard', 'brand' => 'Razer', 'model' => 'BlackWidow', 'capacity' => 'N/A', 'quantity' => 18],
            ['product_id' => '6062', 'type' => 'Mouse', 'brand' => 'Logitech', 'model' => 'MX Master 3', 'capacity' => 'N/A', 'quantity' => 22],
            ['product_id' => '6063', 'type' => 'Mouse', 'brand' => 'Razer', 'model' => 'DeathAdder', 'capacity' => 'N/A', 'quantity' => 20],
            ['product_id' => '6064', 'type' => 'Speaker', 'brand' => 'JBL', 'model' => 'Charge 5', 'capacity' => 'N/A', 'quantity' => 15],
            ['product_id' => '6065', 'type' => 'Speaker', 'brand' => 'Bose', 'model' => 'SoundLink Revolve', 'capacity' => 'N/A', 'quantity' => 17],
            ['product_id' => '6066', 'type' => 'Gaming Console', 'brand' => 'Sony', 'model' => 'PlayStation 5', 'capacity' => '825GB', 'quantity' => 10],
            ['product_id' => '6067', 'type' => 'Gaming Console', 'brand' => 'Microsoft', 'model' => 'Xbox Series X', 'capacity' => '1TB', 'quantity' => 12],
        ];

        foreach ($products as $product) {
            ProductMasterList::create($product);
        }
    }
}