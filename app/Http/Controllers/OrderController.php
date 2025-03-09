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
}
