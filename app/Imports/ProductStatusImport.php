<?php

namespace App\Imports;
use App\Models\ProductMasterList;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class ProductStatusImport
{
    public static function process(array $rows)
    {
        // Convert array to collection
        $rowsCollection = collect($rows);
        $productIds = $rowsCollection->pluck('product_id')->unique()->toArray();
        Log::info("Processing product IDs: " . implode(", ", $productIds));
        $products = ProductMasterList::whereIn('product_id', $productIds)
            ->get()
            ->keyBy('product_id');
        Log::info("Processing product: " . json_encode($products));

        foreach ($rows as $index => $row) {
            // Validate required keys
            if (!isset($row['product_id']) || !isset($row['status']) || !isset($row['quantity'])) {
                \Log::error("Invalid row at index {$index}: " . json_encode($row));
                continue;
            }

            $product = $products[$row['product_id']] ?? null;
            if ($product) {
                if ($row['status'] === 'Sold') {
                    $newQuantity = $product->quantity - $row['quantity'];
                    $product->quantity = $newQuantity; // Allow negative values
                } elseif ($row['status'] === 'Buy') {
                    $product->quantity += $row['quantity'];
                }
                $product->save();
            } else {
                \Log::warning("Product with ID {$row['product_id']} not found in ProductMasterList.");
            }
        }
    }
}