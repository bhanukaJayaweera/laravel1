<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
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
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        try {
            Excel::import(new OrderImport, $request->file('file'));
            return back()->with('success', 'Orders imported successfully!');
        } catch (\Exception $e) {
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
        // Generate the PDF
        $pdf = PDF::loadView('Order.invoice', compact('order'));

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
                        //'promotions' => $promotions,
                    ]);
                }
            }
    }
    // Load data for editing
    public function orderedit($id,Request $request)
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
        $products = Product::all(); // Fetch all customers
       
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
    // public function editOrder(Request $request) {

        public function editOrder(Request $request) 
        {
            \Log::info('2. Raw editData from request:', ['editData' => $request->editData]);
        
            try {
                \DB::beginTransaction();
                \Log::info('3. Transaction started');  
                // Find the order
                \Log::info('4. Finding order with ID: ' . $request->id);
                $order = Order::with('products')->find($request->id);
                
                \Log::info('5. Order found?', ['exists' => !is_null($order)]);
                if (!$order) {
                    \Log::warning('Order not found', ['id' => $request->id]);
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
                    \Log::error('JSON decode failed', [
                        'error' => json_last_error_msg(),
                        'input' => $request->editData
                    ]);
                    return response()->json([
                        'message' => 'Invalid product data format',
                        'error' => json_last_error_msg()
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
        
                \Log::info('10. Creating OrderDeletionRequest', [
                    'order_id' => $order->id,
                    'changes' => $requestedChanges
                ]);
        
                $deletionRequest = OrderDeletionRequest::create([
                    'order_id' => $order->id,
                    'requested_by' => auth()->id(),
                    'status' => 'Updated',
                    'requested_changes' => json_encode($requestedChanges),
                ]);
        
                \DB::commit();
                \Log::info('11. Transaction committed');
                \Log::info('12. Request created successfully', [
                    'request_id' => $deletionRequest->id
                ]);
    
                return response()->json([
                    'message' => 'Order update submitted for approval',
                    'request_id' => $deletionRequest->id
                ]);
        
            } catch (\Exception $e) {
                \DB::rollBack();
                \Log::error('13. Error in editOrder: ' . $e->getMessage(), [
                    'exception' => $e,
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json([
                    'message' => 'Failed to submit update request',
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }
      
    }
        

    public function approveUpdate($id) {
        //Log::info('Request Data:', $request->all()); // Log the request data
        
        $request = OrderDeletionRequest::findOrFail($id);
        $order = $request->order;
        
        // if (!$products) {
        //     return back()->with('error', 'No products selected!');
        // }  
        
        // First, remove existing pivot records
        $order->products()->detach();
        if ($request->requested_changes) {
            $changes = json_decode($request->requested_changes, true);
    
            $order->update([
                'customer_id' => $changes['customer_id'],
                'payment_type' => $changes['payment_type'],
                'amount' =>$changes['amount'],
                'status' =>$changes['status'],
                'date' =>$changes['date'],
            ]);
        }

        foreach ($changes['products'] as $product) {
            Log::info('Attaching Product:', $product);
            $order->products()->attach($product['id'], ['quantity' => $product['quantity']]);
        }
        $request->status = 'update_approved';
        $request->save();    
        return response()->json(['message' => 'Order update approved.']); 

        // $data = $order->validate(
        //     [
        //     'customer_id' => 'required|exists:customers,id',
        //     //'product_id' => 'required',
        //     'date'=> 'required|date',
        //     'payment_type'=> 'required',
        //     'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        //     'status'=> 'required',
        //     ]
        // );
       // Log::info('Validated Order Data:', $data);
        //$order = Order::findOrFail($request->id); // find the existing order
        //$order->update($data);
        //Log::info('Order Edited:', ['id' => $order->id]);

        // First, remove existing pivot records
        // $order->products()->detach();

        // Attach products to order (Pivot Table)
        //$order->products()->attach($productIds);
        //$products = json_decode($order->products, true);  
       

    }
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
 
         if ($request->filled('product_id')) {
             $orders = Order::whereHas('products', function ($query) use ($request) {
                 $query->where('product_id', $request->product_id);
             })->with('customer')->get();
         }
         $selectedProductId = $request->input('product_id');
         return view('Order.productsearch', compact('products', 'orders','selectedProductId'));
        // }
     }

        


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
