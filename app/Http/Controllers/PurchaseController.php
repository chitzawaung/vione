<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class PurchaseController extends Controller
{
    /**
     * Show purchase form for a product
     */
    public function create(Product $product)
    {
        return Inertia::render('Products/Purchase', [
            'product' => $product,
        ]);
    }

    /**
     * Process the purchase
     */
    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1|max:' . $product->quantity_available,
        ], [
            'quantity.max' => 'Only ' . $product->quantity_available . ' items available in stock.',
        ]);

        try {
            DB::beginTransaction();

            // Calculate total price
            $totalPrice = $product->price * $validated['quantity'];

            // Create transaction record
            Transaction::create([
                'user_id' => auth()->id(),
                'product_id' => $product->id,
                'quantity' => $validated['quantity'],
                'total_amount' => $totalPrice,
            ]);

            // Update product stock
            $product->decrement('quantity_available', $validated['quantity']);

            DB::commit();

            return redirect()->route('products.index')
                ->with('success', "Successfully purchased {$validated['quantity']} {$product->name}(s) for \${$totalPrice}!");

        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()->with('error', 'Purchase failed. Please try again.');
        }
    }
}
