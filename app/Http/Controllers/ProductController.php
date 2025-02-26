<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Product;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductsImport;

class ProductController extends Controller
{
    public function index(){
        $products = Product::all();
        return view('Products.index',compact('products'));

    } 
    public function create(){
        return view('Products.create');
    } 
    public function store(Request $request){
        $data = $request->validate([
            'name' => 'required',
            'quantity' => 'required|integer',
            'price' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);

        $newProduct = Product::create($data);
        return redirect(route('product.index'));
    } 

    public function edit(Product $product){
        #dd($product); #used to check the data sent 
        return view('Products.edit',['product' => $product]);
    }

    public function view(Product $product){
        #dd($product); #used to check the data sent 
        return view('Products.view',['product' => $product]);
    }

    // public function generatepdf(Request $request){
    //     $data = $request->all();
    //     //$data = $request->all(); // Get form data
    // // Load view and pass form data
    //     $pdf = Pdf::loadView('Products.pdf_template', compact('data'));
    // // Download PDF
    //     return $pdf->download('generated.pdf');
    // }

    public function generatepdfSelect(Request $request){
        $productIds = $request->input('product_ids');
        if (!$productIds) {
            return back()->with('error', 'No products selected!');
        }
    
        $products = Product::whereIn('id', $productIds)->get();
        // Load view with selected products
        $pdf = PDF::loadView('Products.pdf_template', compact('products'));
    // Download PDF
        return $pdf->download('generated.pdf');
    }

    public function update(Product $product, Request $request){
        $data = $request->validate([
            'name' => 'required',
            'quantity' => 'required|integer',
            'price' => 'required|regex:/^\d+(\.\d{1,2})?$/'
        ]);
        $product -> update($data);
        return redirect(route('product.index'))->with('success','Product updated successfully');
    }

    public function destroy(Product $product){
        $product->delete();
        return redirect(route('product.index'))->with('success','Product deleted successfully');
    }

    public function showUploadForm()
    {
        return view('Products.upload');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new ProductsImport, $request->file('file'));

        return back()->with('success', 'Excel data imported successfully.');
    }

    

}
