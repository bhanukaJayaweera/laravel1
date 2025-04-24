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
            if (OrderDeletionRequest::where('order_id', $orderId)->where('status', 'pending')->exists()) {
                return response()->json(['message' => 'A deletion request already exists for this order.'], 409);
            }
            OrderDeletionRequest::create([
                'order_id' => $order->id,
                'requested_by' => auth()->id(),
            ]);
            return response()->json(['message' => 'Orders delete submitted for approval']); 
               
        }

    public function showApprovalRequests()
        {
            $requests = OrderDeletionRequest::with('order', 'user')->where('status', 'pending')->get();
            return view('Order.approvals', compact('requests'));
        }

        public function approveDelete($id)
        {
            $request = OrderDeletionRequest::findOrFail($id);
           
            $order = $request->order;
            if ($order) {
                $order->products()->detach();
                $order->delete();
            }
           
            $request->status = 'approved';
            $request->save();

            // Delete the actual order
            // $request->order->delete();
            // $request->order->products()->detach(); 

            return back()->with('success', 'Order deletion approved and order removed.');
        }

        public function rejectDelete($id)
        {
            $request = OrderDeletionRequest::findOrFail($id);
            $request->status = 'rejected';
            $request->save();

            return back()->with('info', 'Order deletion request rejected.');
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
                        'message' => "Sufficient inventory for {$productModel->name}."
                    ]);
                }
            }
    }
    // Load data for editing
    public function orderedit($id)
    {
        $customers = Customer::all(); // Fetch all customers
        $products = Product::all(); // Fetch all customers
        //$order = Order::findOrFail($id);
        $order = Order::with(['products' => function($q) {
            $q->withPivot('quantity');
        }])->find($id);
        return response()->json([
            'order' => $order,
            'customers' => $customers,
            'products' => $products]);

    }

    public function newfetch()
    {
        $customers = Customer::all(); // Fetch all customers
        $products = Product::all(); // Fetch all customers
       
        return response()->json([
            'customers' => $customers,
            'products' => $products]);

    }

    //save edit
    public function editOrder(Request $request) {
        Log::info('Request Data:', $request->all()); // Log the request data
        $products = json_decode($request->products, true);  
        // if (!$products) {
        //     return back()->with('error', 'No products selected!');
        // }  
        $data = $request->validate(
            [
            'customer_id' => 'required|exists:customers,id',
            //'product_id' => 'required',
            'date'=> 'required|date',
            'payment_type'=> 'required',
            'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'status'=> 'required',
            ]
        );
        Log::info('Validated Order Data:', $data);
        $order = Order::findOrFail($request->id); // find the existing order
        $order->update($data);
        Log::info('Order Edited:', ['id' => $order->id]);

        // First, remove existing pivot records
        $order->products()->detach();

        // Attach products to order (Pivot Table)
        //$order->products()->attach($productIds);
        foreach ($products as $product) {
            Log::info('Attaching Product:', $product);
            $order->products()->attach($product['product_id'], ['quantity' => $product['quantity']]);
        }
        return response()->json(['message' => 'Order updated successfully!']);
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
