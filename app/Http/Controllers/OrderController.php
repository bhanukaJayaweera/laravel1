<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Promotion;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\OrderImport;
use Illuminate\Support\Facades\Log; // Import Log facade
use PDF;
use App\Models\OrderDeletionRequest;

class OrderController extends Controller
{
    public function index(){
        // if (!Auth::user()->hasRole('admin')) {
        //     abort(403, 'Unauthorized');
        // }
        if (auth()->user()->can('handle orders')) {
            $orders = Order::all();
            return view('Order.index',compact('orders'));
        }

    }
   
    public function create()
    {
        $customers = Customer::all(); // Fetch all customers
        $products = Product::all(); // Fetch all customers
        return view('Order.create', compact('customers','products'));
        
    }
   
    public function store(Request $request){
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            //'product_id' => 'required',
            'date'=> 'required|date',
            'payment_type'=> 'required',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        $newOrder = Order::create($data);
        return redirect(route('order.index'));
    } 

    

    public function generatepdfSelect(Request $request){
        $orderIds = $request->input('order_ids');
        if (!$orderIds) {
            return back()->with('error', 'No products selected!');
        }
    
        $orders = Order::whereIn('id', $orderIds)->get();
        // Load view with selected products
        $pdf = PDF::loadView('Order.pdf_template', compact('orders'));
        return $pdf->stream("order_list.pdf"); // 👈 This opens in browser
    // Download PDF
        //return $pdf->download('generated.pdf');
    }

   

    public function view(Order $order){
        #dd($product); #used to check the data sent 
        return view('Order.view',compact('order'));
        // return view('Order.index',compact('order'));
    }
    public function edit(Order $order){ 
        $selectedCustomerId = $order->customer_id;
        $selectedProductId = $order->product_id;
        $selectedPaymentType= $order->payment_type;
        $customers = Customer::all(); // Fetch all customers
        $products = Product::all(); // Fetch all customers
        return view('Order.edit',compact('customers','products','order','selectedCustomerId','selectedProductId','selectedPaymentType'));
    }
   
    public function update(Order $order, Request $request){
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'product_id' => 'required|exists:products,id',
            'date'=> 'required|date',
            'payment_type'=> 'required',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        $order -> update($data);
        return redirect(route('order.index'))->with('success','Order updated successfully');
    }

    //AJAX
    
            // $order = Order::findOrFail($orderId); // fetch single order
            // $order->products()->detach(); // Remove pivot table entries
            // $order->delete();
            //return response()->json(['message' => 'Order deleted successfully']);
        public function destroy($orderId){
            $order = Order::findOrFail($orderId);

            // Check if a pending deletion request already exists
            if (OrderDeletionRequest::where('order_id', $orderId)->where('status', 'Deleted')->exists()) {
                return response()->json(['message' => 'A deletion request already exists for this order.'], 409);
            }
            OrderDeletionRequest::create([
                'order_id' => $order->id,
                'requested_by' => auth()->id(),
                'status' => 'Deleted',
            ]);
            return response()->json(['message' => 'Orders delete submitted for approval']); 
               
        }

    public function showApprovalRequests()
        {
            $requests = OrderDeletionRequest::with('order', 'user')
            ->whereIn('status', ['Deleted', 'Updated'])
            ->get();
            return view('Order.approvals', compact('requests'));
        }

        public function approveDelete($id)
        {           
            Log::info('Request Id:', ['id' => $id]); // ✅ correct
            $request = OrderDeletionRequest::findOrFail($id);
            $order = $request->order;
            if ($order) {
                $order->products()->detach();
                $order->delete();
            }
           
            $request->status = 'delete_approved';
            $request->save();    
            return response()->json(['message' => 'Order deletion approved and order removed.']); 
        }

        public function rejectDelete($id)
        {
            Log::info('Request Id:', ['id' => $id]); // ✅ correct
            $request = OrderDeletionRequest::findOrFail($id);
            $request->status = 'delete_rejected';
            $request->save();

            //return back()->with('info', 'Order deletion request rejected.');
            return response()->json(['message' => 'Order deletion request rejected.']); 
        }


    public function deleteMultiple(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id'
        ]);
        $orderIds = $request->input('order_ids');  
        Log::info('Deleting orders:', ['order_ids' => $orderIds]);
        try {
            $orders = Order::whereIn('id', $orderIds)->get();          
            foreach ($orders as $order) {
                $order->products()->detach();
                $order->delete();
            }         
            return response()->json([
                'success' => true,
                'message' => 'Orders deleted successfully'
            ]);        
        } catch (\Exception $e) {
            Log::error('Error deleting orders:', ['error' => $e->getMessage()]);         
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete orders'
            ], 500);
        }
    }

    public function showUploadForm()
    {
        if (auth()->user()->can('handle orders')) {
            return view('Order.upload');
        }
    }

public function importorder(Request $request)
{
    // Custom validation messages
    $messages = [
        'file.required' => 'Please select a file to upload.',
        'file.mimes' => 'Only Excel files (.xlsx, .xls) are allowed.',
        'file.max' => 'File size should not exceed 5MB.' // Added file size validation
    ];

    try {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:5120', // 5MB in KB
        ], $messages);

        // Get the original file name
        $fileName = $request->file('file')->getClientOriginalName();
        
        // Store the file temporarily (optional)
        $filePath = $request->file('file')->store('temp');

        // Import with potential customizations
        $import = new OrderImport();
        Excel::import($import, $request->file('file'));

        // You can access import statistics if your OrderImport class implements WithProgressBar
        $importedRows = $import->getRowCount(); // Assuming you have this method
        
        return back()->with([
            'success' => 'Orders imported successfully!',
            'imported_rows' => $importedRows,
            'file_name' => $fileName
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        // Specifically handle validation exceptions
        return back()->withErrors($e->validator)->withInput();
        
    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        // Handle Excel validation errors (if using WithValidation in OrderImport)
        $failures = $e->failures();
        
        return back()->with([
            'error' => 'There were errors in your Excel file.',
            'failures' => $failures
        ]);
        
    } catch (\Exception $e) {
        // General exception handling
        \Log::error('Order Import Error: ' . $e->getMessage());
        return back()->with('error', 'Import failed: ' . $e->getMessage());
    }
}
     //orderproduct new 
    public function storeOrder(Request $request) {
    if (auth()->user()->can('handle orders')) {
        Log::info('Request Data:', $request->all()); // Log the request data
        $products = json_decode($request->products, true);  
        if (!$products) {
            return back()->with('error', 'No products selected!');
        }  
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'date'=> 'required|date',
            'payment_type'=> 'required',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        //check the delivery date is in the past
        $deliveryDate = $request->input('date');
        if (strtotime($deliveryDate) < strtotime(today()->toDateString())) {
             return response()->json([
                'success' => false,
                'message' => 'Delivery date cannot be in the past'
            ]);
        }
        $data['cashier_name'] = auth()->user()->name; // Force-set the cashier name
        Log::info('Validated Order Data:', $data);
        $order = Order::create($data);
        Log::info('Order Created:', ['id' => $order->id]);
        // Attach products to order (Pivot Table)
        //$order->products()->attach($productIds);
        foreach ($products as $product) {
            Log::info('Attaching Product:', $product);
            $order->products()->attach($product['product_id'], ['quantity' => $product['quantity']]);
            // Update inventory
            $productModel = Product::find($product['product_id']);
            if ($productModel) {
                $newInventory = $productModel->quantity - $product['quantity'];
                $productModel->quantity = $newInventory;
                $productModel->save();
            }
        }
        $totalDiscount = 0;
        foreach ($products as $product) {
            $totalDiscount += (float)($product['discount']);
        }
        
        // Generate the PDF
        $pdf = PDF::loadView('Order.invoice', [
            'order' => $order,
            'products' => $products,
            'totaldiscount' => $totalDiscount,
        ]);

        // Optionally save to storage
        $fileName = 'invoice_'.$order->id.'.pdf';
        $pdf->save(storage_path('app/public/invoices/' . $fileName));
        $url = asset('storage/invoices/' . $fileName);//create url to open in new tab
        return response()->json([
            'status' => 'success',
            'message' => 'Order saved successfully!',
            'invoice_url' => $url
        ], 200);
    }
    }

    public function checkInvent(Request $request){
        $productId = $request->input('productId');
        $quantity = $request->input('quantity');
        // $promotions = Promotion::where('product_id', $productId)
        // ->whereDate('start_date', '<=', now())
        // ->whereDate('end_date', '>=', now())
        // ->get();
        $productModel = Product::find($productId);
            if ($productModel) {
                $newInventory = $productModel->quantity - $quantity;
                if ($newInventory < 0) {
                    Log::warning('Inventory would go negative. Skipping product: ' . $productModel->name);
                    return response()->json([
                        'status' => 'error',
                        'message' => "Not enough inventory for {$productModel->name}. Available: {$productModel->quantity}, Requested: $quantity."
                    ], 422);            
                }
                else{
                    return response()->json([
                        'status' => 'success',
                        'message' => "Sufficient inventory for {$productModel->name}.",
                        // 'promotions' => $promotions,
                    ]);
                }
            }
    }


    // public function getPromotions(Request $request){
    //     if (!$request->isJson()) {
    //         return response()->json(['error' => 'Invalid data format'], 400);
    //     }
    
    //     // Get the JSON content
    //     $data = $request->json()->all();
    //     // Access the products array
    //     $selectedProducts = $data['products'] ?? [];
       
    //     // Validate the data structure
    //     if (empty($selectedProducts)) {
    //         return response()->json(['error' => 'No products selected'], 400);
    //     }
    //     $productPromotions = []; // Array to store promotions for all products
    //     // Process each product
    //     foreach ($selectedProducts as $product) {
    //         \Log::info("products: ", [
    //             'product' => $product,              
    //         ]);
    //         $productId = $product['product_id'] ?? null;
    //         $promotions = Promotion::where('product_id', $productId)
    //         ->where('is_active', 'yes') 
    //         ->whereDate('start_date', '<=', now())
    //         ->whereDate('end_date', '>=', now())
    //         ->get();

    //           // Store promotions for this product
    //         $productPromotions[] = $promotions->toArray();
    //     }
    //     \Log::info("Found promotions for product $productId", [
    //         'promotions_count' => $promotions->count(),
    //         'promotions' => $promotions->toArray()
    //     ]);
    //     // Return the promotions or whatever data you need
    //     return response()->json([
    //         'promotions' => $productPromotions,
    //     ]);
    // }

    public function getPromotions(Request $request)
    {
        if (!$request->isJson()) {
            return response()->json(['error' => 'Invalid data format'], 400);
        }

        $data = $request->json()->all();
        $selectedProducts = $data['products'] ?? [];

        if (empty($selectedProducts)) {
            return response()->json(['error' => 'No products selected'], 400);
        }

        $productPromotions = []; // This will store promotions keyed by product ID

        foreach ($selectedProducts as $product) {
            $productId = $product['product_id'] ?? null;
            if (!$productId) continue;

            // Get active promotions for this product
            $promotions = Promotion::where('product_id', $productId)
                ->where('is_active', 'yes')
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->get();

            // Store promotions with product ID as key
            $productPromotions[$productId] = $promotions->toArray();

            \Log::info("Promotions for product {$productId}", [
                'count' => $promotions->count(),
                'promotions' => $promotions->toArray()
            ]);
        }

        return response()->json([
            'success' => true,
            'promotions' => $productPromotions,
            'message' => 'Promotions retrieved successfully'
        ]);
    }

    // Load data for editing
    public function orderedit($id,Request $request)
    {
        $requestId = $request->input('requestId'); 
        $customers = Customer::all(); // Fetch all customers
        //$products = Product::all(); // Fetch all customers
        $request = OrderDeletionRequest::find($requestId);
        //$order = Order::findOrFail($id);
        // $order = Order::with(['products' => function($q) {
        //     $q->withPivot('quantity');
        // }])->find($id);
        //load into dropdown
       $products = Product::with(['marketPrice' => function($query) {
            $query->where('market_id', 1)
                ->latest('price_date')
                ->limit(1);
        }])->get()->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
            // 'quantity' => $product->quantity,
                'price' => $product->marketPrice->first()->price ?? null,
            ];
        });
        // Load order with products AND their latest market price
        $order = Order::with(['products' => function($query) {
            $query->withPivot('quantity')
                ->with(['currentMarketPrice' => function($subQuery) {
                    $subQuery->where('market_id', 1)
                            ->latest('price_date')
                            ->limit(1);
                }]);
        }])->find($id);
        return response()->json([
            'order' => $order,
            'customers' => $customers,
            'products' => $products,
            'request' => $request,
           ]);

    }
    
    public function orderdeleteload($id,Request $request)
    {
        $requestId = $request->input('requestId'); 
        $customers = Customer::all(); // Fetch all customers
        $products = Product::all(); // Fetch all customers
        $request = OrderDeletionRequest::find($requestId);
        //$order = Order::findOrFail($id);
        $order = Order::with(['products' => function($q) {
            $q->withPivot('quantity');
        }])->find($id);
        return response()->json([
            'order' => $order,
            'customers' => $customers,
            'products' => $products,
            'request' => $request,
           ]);

    }
    public function orderapproveload($id,Request $request)
    {
        $requestId = $request->input('requestId'); 
        $customers = Customer::all(); // Fetch all customers
        $products = Product::all(); // Fetch all customers
        $request = OrderDeletionRequest::find($requestId);
        //$order = Order::findOrFail($id);
        $order = Order::with(['products' => function($q) {
            $q->withPivot('quantity');
        }])->find($id);
        return response()->json([
            'order' => $order,
            'customers' => $customers,
            'products' => $products,
            'requested_changes' => $request ? json_decode($request->requested_changes, true) : null,]);

    }

    public function newfetch()
    {
        $customers = Customer::all(); // Fetch all customers
       //$products = Product::all(); // Fetch all customers
      // Get products with their latest market price (market_id = 1)
        $products = Product::with(['marketPrice' => function($query) {
            $query->where('market_id', 1)
                ->latest('price_date');
                
        }])->get()->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
            // 'quantity' => $product->quantity,
                'price' => $product->marketPrice->first()->price ?? null,
            ];
        });
        return response()->json([
            'customers' => $customers,
            'products' => $products]);

    }

    
        // $validated = $request->validate([
        //     'id' => 'required|exists:orders,id',
        //     'customer_id' => 'required|exists:customers,id',
        //     'products' => 'required|array',
        //     'date'=> 'required|date',
        //     'payment_type'=> 'required',
        //     'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        //     'status'=> 'required',
        // ]);

    //save edit

        public function editOrder(Request $request) 
        {
            \Log::info('2. Raw editData from request:', ['editData' => $request->editData]);
        
            try {
                \DB::beginTransaction();
                \Log::info('3. Transaction started');  
                // Find the order
                // \Log::info('4. Finding order with ID: ' . $request->id);
                $order = Order::with('products')->find($request->id);
                
                if (!$order) {
                    return response()->json(['message' => 'Order not found.']);
                }
        
                // Check for existing update request
                // \Log::info('6. Checking for existing update requests');
                // if (OrderDeletionRequest::where('order_id', $order->id)
                //                       ->where('status', 'Updated')
                //                       ->exists()) {
                //     \Log::notice('Duplicate update request', ['order_id' => $order->id]);
                //     return response()->json([
                //         'message' => 'An update request already exists for this order.'
                //     ]);
                // }
        
                // Decode and validate products
                \Log::info('7. Decoding product data');
                $editedProducts = json_decode($request->editData, true);
                
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return response()->json([
                        'message' => 'Invalid product data format',
                        'error' => json_last_error_msg()
                    ]);
                }
                
                //check the delivery date is in the past
                $deliveryDate = $request->date;
                if (strtotime($deliveryDate) < strtotime(today()->toDateString())) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Delivery date cannot be in the past'
                    ]);
                }
        
                // Prepare products
                \Log::info('8. Processing products', ['count' => count($editedProducts)]);
                $products = [];
                foreach ($editedProducts as $index => $product) {
                    if (!isset($product['product_id'], $product['quantity'], $product['price'])) {
                        \Log::error('Missing product fields at index: ' . $index, ['product' => $product]);
                        return response()->json([
                            'message' => "Product at index $index is missing required fields"
                        ]);
                    }
                    
                    $products[] = [
                        'id' => $product['product_id'],
                        'quantity' => $product['quantity'],
                        'price' => $product['price'],
                        'name'=> $product['name'],
                    ];
                }
        
                // Prepare changes
                \Log::info('9. Preparing requested changes');
                $requestedChanges = [
                    'customer_id' => $request->customer_id,
                    'payment_type' => $request->payment_type,
                    'products' => $products,
                    'date' => $request->date,
                    'amount' => $request->amount,
                    'status' => $request->status,
                ];
        
                // \Log::info('10. Creating OrderDeletionRequest', [
                //     'order_id' => $order->id,
                //     'changes' => $requestedChanges
                // ]);
        
                $deletionRequest = OrderDeletionRequest::create([
                    'order_id' => $order->id,
                    'requested_by' => auth()->id(),
                    'status' => 'Updated',
                    'requested_changes' => json_encode($requestedChanges),
                ]);
        
                \DB::commit();
                // \Log::info('11. Transaction committed');
                // \Log::info('12. Request created successfully', [
                //     'request_id' => $deletionRequest->id
                // ]);
    
                return response()->json([
                    'message' => 'Order update submitted for approval',
                    'request_id' => $deletionRequest->id
                ]);
        
            } catch (\Exception $e) {
                \DB::rollBack();
                // \Log::error('13. Error in editOrder: ' . $e->getMessage(), [
                //     'exception' => $e,
                //     'trace' => $e->getTraceAsString()
                // ]);
                return response()->json([
                    'message' => 'Failed to submit update request',
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
      
    }
        

    public function approveUpdate($id) {
        try {
            // 1. Find the request
            $request = OrderDeletionRequest::findOrFail($id);
            \Log::info("[REQUEST FOUND]", $request->toArray());

            // 2. Get associated order
            $order = $request->order;
            if (!$order) {
                \Log::error("[ORDER MISSING] No order associated with request", ['request_id' => $id]);
                throw new \Exception("No order found for this request");
            }

            // // 4. Detach existing products
            // $order->products()->detach();

            // 5. Process requested changes
            if (!$request->requested_changes) {
                throw new \Exception("No changes data found in request");
            }

            $changes = json_decode($request->requested_changes, true);

            // 6. Update order fields
            $order->update([
                'customer_id' => $changes['customer_id'] ?? $order->customer_id,
                'payment_type' => $changes['payment_type'] ?? $order->payment_type,
                'amount' => $changes['amount'] ?? $order->amount,
                'status' => $changes['status'] ?? $order->status,
                'date' => $changes['date'] ?? $order->date,
            ]);
            \Log::info("[ORDER UPDATED]", $order->fresh()->toArray());

            // 7. Attach new products
            if (empty($changes['products'])) {
                \Log::error("[NO PRODUCTS] No products in changes payload");
                throw new \Exception("No products data in changes");
            }

    
        // Get current products with quantities
        $currentProducts = $order->products->pluck('pivot.quantity', 'id')->toArray();

        // First, detach all current products (we'll reattach the updated ones)
        $order->products()->detach();

        foreach ($changes['products'] as $product) {
            $productId = $product['id'];
            $newQuantity = $product['quantity'];

            // Attach product with new quantity
            $order->products()->attach($productId, ['quantity' => $newQuantity]);

            // Update inventory
            $productModel = Product::find($productId);
            
            // If product was in the order before, calculate the difference
            if (isset($currentProducts[$productId])) {
                $quantityDifference = $currentProducts[$productId] - $newQuantity;
                $productModel->quantity += $quantityDifference;
            } else {
                // Product is new to the order, just subtract the new quantity
                $productModel->quantity -= $newQuantity;
            }
            
            $productModel->save();
        }

            // 8. Update request status
            $request->status = 'update_approved';
            $request->save();
            \Log::info("[REQUEST APPROVED]");

            // 9. Final verification
            // \Log::info("[FINAL CHECK]", [
            //     'attached_products' => $order->products()->count(),
            //     'new_amount' => $order->amount,
            //     'request_status' => $request->status
            // ]);

            return response()->json([
                'success' => true,
                'message' => 'Order update approved successfully',
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            // \Log::error("[APPROVAL FAILED]", [
            //     'error' => $e->getMessage(),
            //     'trace' => $e->getTraceAsString(),
            //     'request_id' => $id ?? 'unknown'
            // ]);

            return response()->json([
                'success' => false,
                'message' => 'Approval failed: ' . $e->getMessage()
            ]);
        }
      

    }
     // $request = OrderDeletionRequest::findOrFail($id);

        // $order = $request->order;

        // // First, remove existing pivot records
        // $order->products()->detach();
        // if ($request->requested_changes) {
        //     $changes = json_decode($request->requested_changes, true);
    
        //     $order->update([
        //         'customer_id' => $changes['customer_id'],
        //         'payment_type' => $changes['payment_type'],
        //         'amount' =>$changes['amount'],
        //         'status' =>$changes['status'],
        //         'date' =>$changes['date'],
        //     ]);
        // }

        // foreach ($changes['products'] as $product) {
        //     Log::info('Attaching Product:', $product);
        //     $order->products()->attach($product['id'], ['quantity' => $product['quantity']]);
        // }
        // $request->status = 'update_approved';
        // $request->save();    
        // return response()->json(['message' => 'Order update approved.']); 


    public function rejectUpdate($id) {
        Log::info('Request Id:', ['id' => $id]); // ✅ correct
        $request = OrderDeletionRequest::findOrFail($id);
        $request->status = 'update_rejected';
        $request->save();

        //return back()->with('info', 'Order deletion request rejected.');
        return response()->json(['message' => 'Order Update request rejected.']); 
    }

     //productsearch page
    public function showSearch(){
        // $orders = Order::all();
    if (auth()->user()->can('handle orders')) {
         $customers = Customer::all(); // Fetch all customers
         $products = Product::all(); // Fetch all customers
         return view('Order.productsearch', compact('customers','products'));
    }
    } 
 
     public function search(Request $request)
     {
        // if (auth()->user()->can('handle orders')) {
         $products = Product::all();
         $orders = [];
 
         if ($request->filled('product_id') || $request->filled('date')) {
               $orders = Order::where(function($query) use ($request) {
                    if ($request->filled('product_id')) {
                        $query->whereHas('products', function ($q) use ($request) {
                            $q->where('product_id', $request->product_id);
                        });
                    }
                    if ($request->filled('date')) {
                        $query->where('date', $request->date);
                    }               
                })
                ->with('customer')
                ->get();
         }
         $selectedProductId = $request->input('product_id');
         return view('Order.productsearch', compact('products', 'orders','selectedProductId'));
        // }
     }

        public function getCustomer(Request $request)
        {
            try {
                $customer = Customer::findOrFail($request->customer_id);
                return response()->json([
                    'customer' => $customer,
                    'status' => 'success'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'message' => 'Customer not found',
                    'status' => 'error'
                ], 404);
            }
        }
    //  public function getCustomer($customerId){
    //     $customer = Customer::findOrFail($customerId);
    //     return view('Order.index', compact('customer'));
    //  }
        


    // Store or update 
    // public function orderstore(Request $request)
    // {
    //     $order = Order::updateOrCreate(
    //         ['id' => $request->id], // If ID exists, update; otherwise, create new
    //         [
    //             'customer_id' => $request->customer_id,
    //             'product_id' => $request->product_id,
    //             'date' => $request->date,
    //             'payment_type' => $request->payment_type,
    //             'amount' => $request->amount
    //         ]
    //     );

    //     return response()->json(['message' => 'Order updated successfully!']);
    // }

    // public function ordernew(Request $request)
    // {
    //     $order = Order::updateOrCreate(
    //         [
    //             'customer_id' => $request->customer_id,
    //             'product_id' => $request->product_id,
    //             'date' => $request->date,
    //             'payment_type' => $request->payment_type,
    //             'amount' => $request->amount
    //         ]
    //     );

    //     return response()->json(['message' => 'Order saved successfully!']);
    // }
}
