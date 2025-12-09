<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User; // For Customer and Assigned User
use App\Models\Store;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index()
    {
        $orders = Order::latest()
            ->with(['store', 'customer', 'assignedTo'])
            ->paginate(10);

        return view('backend.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order.
     */
    public function create()
    {
        // Fetch necessary data for dropdowns
        $customers = User::where('user_type', 'customer')->get(['id', 'name', 'phone']);
        $stores = Store::where('status', 1)->get(['id', 'name']);
        $products = Product::where('status', 1)->get(['id', 'name', 'sale_price', 'sku_prefix']);
        $staff = User::where('user_type', '!=', 'customer')->get(['id', 'name']);

        $paymentMethods = [0=>'Cash',1=>'Card',2=>'Mobile Banking',3=>'COD',4=>'Bank Transfer'];
        $paymentStatuses = [0=>'Pending',1=>'Partial',2=>'Paid',3=>'Cancelled'];
        $orderStatuses = [0=>'Pending',1=>'Confirmed',2=>'Hold',3=>'Cancelled',4=>'Delivered'];

        return view('backend.orders.create', compact(
            'customers', 'stores', 'products', 'staff', 
            'paymentMethods', 'paymentStatuses', 'orderStatuses'
        ));
    }

    /**
     * Store a newly created order in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate Order Data
        $orderValidated = $request->validate([
            'invoice_no' => 'required|string|max:50|unique:orders,invoice_no',
            'source' => 'nullable|string|max:255',
            'customer_id' => 'nullable|exists:users,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string|max:500',
            'store_id' => 'required|exists:stores,id',
            'assigned_to' => 'nullable|exists:users,id',
            
            // Financials (calculated on front-end, validated here)
            'sub_total' => 'required|numeric|min:0',
            'shipping_cost' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'paid' => 'required|numeric|min:0',
            'due' => 'required|numeric|min:0',
            
            'payment_method' => 'required|string|max:50',
            'payment_status' => 'required|string|max:50',
            'status' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);
        
        // 2. Validate Order Items Data
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            // 'items.*.sku' => 'required|string|max:50',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.purchase_price' => 'required|numeric|min:0',
            'items.*.sale_price' => 'required|numeric|min:0',
            // 'items.*.attributes' => 'nullable|array', // Laravel will handle this as JSON
        ]);

        try {
            DB::beginTransaction();

            // 3. Create the Order
            $order = Order::create($orderValidated);

            // 4. Create Order Items
            $orderItems = [];
            foreach ($request->input('items') as $item) {
                $orderItems[] = new OrderItem([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'sku' => $item['sku'],
                    'quantity' => $item['quantity'],
                    'purchase_price' => $item['purchase_price'],
                    'sale_price' => $item['sale_price'],
                    // Convert attributes array to JSON string for the DB (or let the Model cast handle it)
                    'attributes' => isset($item['attributes']) ? json_encode($item['attributes']) : null, 
                ]);
            }
            $order->items()->saveMany($orderItems);
            
            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Order placed successfully. Invoice No: ' . $order->invoice_no);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['An error occurred while placing the order: ' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit($order)
    {
        // Eager load items for the edit view
        $order = Order::with('items.product')->find($order);

        // Fetch necessary data for dropdowns
        $customers = User::where('user_type', 'customer')->get(['id', 'name', 'phone']);
        $stores = Store::where('status', 1)->get(['id', 'name']);
        $products = Product::where('status', 1)->get(['id', 'name', 'sale_price', 'sku_prefix']);
        $staff = User::where('user_type', '!=', 'customer')->get(['id', 'name']);

        $paymentMethods = ['Cash', 'Card', 'Mobile Banking', 'Bank Transfer'];
        $paymentStatuses = ['Pending', 'Paid', 'Partial'];
        $orderStatuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];

        return view('backend.orders.edit', compact(
            'order', 'customers', 'stores', 'products', 'staff', 
            'paymentMethods', 'paymentStatuses', 'orderStatuses'
        ));
    }

    /**
     * Update the specified order in storage.
     */
    public function update(Request $request, Order $order)
    {
        // 1. Validate Order Data
        $orderValidated = $request->validate([
            // Exclude the current order ID from the unique check
            'invoice_no' => 'required|string|max:50|unique:orders,invoice_no,' . $order->id,
            'source' => 'nullable|string|max:255',
            'customer_id' => 'nullable|exists:users,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'nullable|string|max:500',
            'store_id' => 'required|exists:stores,id',
            'assigned_to' => 'nullable|exists:users,id',
            
            // Financials
            'sub_total' => 'required|numeric|min:0',
            'shipping_cost' => 'required|numeric|min:0',
            'discount' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'paid' => 'required|numeric|min:0',
            'due' => 'required|numeric|min:0',
            
            'payment_method' => 'required|string|max:50',
            'payment_status' => 'required|string|max:50',
            'status' => 'required|string|max:50',
            'notes' => 'nullable|string',
        ]);
        
        // 2. Validate Order Items Data
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.id' => 'nullable|exists:order_items,id', // Exists for update, null for new item
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.sku' => 'required|string|max:50',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.purchase_price' => 'required|numeric|min:0',
            'items.*.sale_price' => 'required|numeric|min:0',
            // 'items.*.attributes' => 'nullable|array',
        ]);

        try {
            DB::beginTransaction();

            // 3. Update the Order
            $order->update($orderValidated);

            // 4. Sync Order Items
            $submittedItemIds = collect($request->input('items'))->pluck('id')->filter()->toArray();
            
            // Delete items not in the submission
            $order->items()->whereNotIn('id', $submittedItemIds)->delete();

            // Create/Update items
            foreach ($request->input('items') as $itemData) {
                $itemData['order_id'] = $order->id;
                // Encode attributes if present
                if (isset($itemData['attributes']) && is_array($itemData['attributes'])) {
                    $itemData['attributes'] = json_encode($itemData['attributes']);
                }

                if (isset($itemData['id'])) {
                    // Update existing item
                    OrderItem::where('id', $itemData['id'])->update($itemData);
                } else {
                    // Create new item
                    OrderItem::create($itemData);
                }
            }
            
            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Order updated successfully. Invoice No: ' . $order->invoice_no);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['An error occurred while updating the order: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified order from storage.
     */
    public function destroy(Order $order)
    {
        try {
            DB::beginTransaction();
            
            // Delete all associated order items first
            $order->items()->delete();
            
            // Then delete the order itself
            $order->delete();
            
            DB::commit();

            return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['An error occurred while deleting the order.']);
        }
    }
}