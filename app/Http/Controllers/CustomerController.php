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

    public function view(Customer $customer){
        #dd($product); #used to check the data sent 
        return view('Customer.view',['customer' => $customer]);
    }

    public function edit(Customer $customer){
        #dd($product); #used to check the data sent 
        return view('Customer.edit',['customer' => $customer]);
    }

    public function update(Customer $customer, Request $request){
        $data = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required|integer',
            'email'=> 'required|email',
        ]);
        $customer -> update($data);
        return redirect(route('customer.index'))->with('success','Customer updated successfully');
    }

    public function destroy(Customer $customer){
        $customer->delete();
        return redirect(route('customer.index'))->with('success','Customer deleted successfully');
    }

    public function create(){
        return view('Customer.create');
    } 
    public function store(Request $request){
        $data = $request->validate([
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required|integer',
            'email' => 'required|email',
        ]);

        $newCustomer = Customer::create($data);
        return redirect(route('customer.index'));
    } 

    //AJAX
    // Load customer data for editing
    public function ajaxedit($id)
    {
        $customer = Customer::findOrFail($id);
        return response()->json($customer);
    }

    // Store or update customer
    public function ajaxstore(Request $request)
    {
        $customer = Customer::updateOrCreate(
            ['id' => $request->id], // If ID exists, update; otherwise, create new
            [
                'name' => $request->name,
                'address' => $request->address,
                'email' => $request->email,
                'phone' => $request->phone
            ]
        );

        return response()->json(['message' => 'Customer updated successfully!']);
    }

    public function ajaxstorenew(Request $request)
    {
        $customer = Customer::updateOrCreate(
            [
                'name' => $request->name,
                'address' => $request->address,
                'email' => $request->email,
                'phone' => $request->phone
            ]
        );

        return response()->json(['message' => 'Customer saved successfully!']);
    }



}
