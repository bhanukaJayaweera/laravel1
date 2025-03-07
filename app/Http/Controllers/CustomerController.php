<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Customer;
use Maatwebsite\Excel\Facades\Excel;
// use App\Imports\ProductsImport;
use Illuminate\Http\Request;


class CustomerController extends Controller
{
    public function index(){
        $customers = Customer::all();
        return view('Customer.index',compact('customers'));

    } 

    public function generatepdfSelect(Request $request){
        $customerIds = $request->input('customer_ids');
        if (!$customerIds) {
            return back()->with('error', 'No products selected!');
        }
    
        $customers = Customer::whereIn('id', $customerIds)->get();
        // Load view with selected products
        $pdf = PDF::loadView('Customer.pdf_template', compact('customers'));
    // Download PDF
        return $pdf->download('generated.pdf');
    }
}
