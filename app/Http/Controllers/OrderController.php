<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;


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
            'product_id' => 'required|exists:products,id',
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
        $order->delete();
        return redirect(route('order.index'))->with('success','Order deleted successfully');
    }
}
