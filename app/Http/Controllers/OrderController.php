<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\OrderImport;
use Illuminate\Support\Facades\Log; // Import Log facade

class OrderController extends Controller
{
    public function index(){
        $orders = Order::all();
        return view('Order.index',compact('orders'));

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
    // Download PDF
        return $pdf->download('generated.pdf');
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

    public function destroy(Order $order){
        $order->products()->detach(); // Remove pivot table entries
        $order->delete();
        return redirect(route('order.index'))->with('success','Order deleted successfully');
    }

    public function deleteMultiple(Request $request)
    {
        $orderIds = $request->order_ids;

        if ($orderIds) {
            Order::whereIn('id', $orderIds)->delete();
            return redirect()->back()->with('success', 'Selected orders deleted successfully.');
        }
        return redirect()->back()->with('error', 'No orders selected.');
    }

    public function showUploadForm()
    {
        return view('Order.upload');
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

     //AJAX

     //orderproduct new 
    public function storeOrder(Request $request) {
        Log::info('Request Data:', $request->all()); // Log the request data
        $products = json_decode($request->products, true);  
        if (!$products) {
            return back()->with('error', 'No products selected!');
        }  
        $data = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            //'product_id' => 'required',
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
        }
        return back()->with('success', 'Order placed successfully!');
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
        return back()->with('success', 'Order edited successfully!');
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
