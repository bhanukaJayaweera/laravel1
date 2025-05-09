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
use App\Models\PromotionApproveRequest;


class PromotionController extends Controller
{
    public function index(){
        // if (!Auth::user()->hasRole('admin')) {
        //     abort(403, 'Unauthorized');
        // }
        if (auth()->user()->can('handle promotions')) {
            $promotions = Promotion::all();
            return view('Promotion.index',compact('promotions'));
        }

    }

    public function generatepdfSelect(Request $request){
        $promotionIds = $request->input('promotion_ids');
        if (!$promotionIds) {
            return back()->with('error', 'No products selected!');
        }
    
        $promotions = Promotion::whereIn('id', $promotionIds)->get();
        // Load view with selected products
        $pdf = PDF::loadView('Promotion.pdf_template', compact('promotions'));
        return $pdf->stream("Promotion_list.pdf"); // 👈 This opens in browser
    // Download PDF
        //return $pdf->download('generated.pdf');
    }

        public function destroy($promotionId){
            $promotion = Promotion::findOrFail($promotionId);

            // Check if a pending deletion request already exists
            if (PromotionApproveRequest::where('promotion_id', $promotionId)->where('status', 'Deleted')->exists()) {
                return response()->json(['message' => 'A deletion request already exists for this Record.'], 409);
            }
            PromotionApproveRequest::create([
                'promotion_id' => $promotion->id,
                'requested_by' => auth()->id(),
                'status' => 'Deleted',
            ]);
            return response()->json(['message' => 'Promotion delete submitted for approval']); 
               
        }

        public function showApprovalRequests()
        {
            $requests = PromotionApproveRequest::with('promotion', 'user')
            ->whereIn('status', ['Deleted', 'Updated'])
            ->get();
            return view('Promotion.approvals', compact('requests'));
        }

        public function approveDelete($id)
        {           
            Log::info('Request Id:', ['id' => $id]); // ✅ correct
            $request = PromotionApproveRequest::findOrFail($id);
            $promotion = $request->promotion;
            if ($promotion) {
                $promotion->delete();
            }
           
            $request->status = 'delete_approved';
            $request->save();    
            return response()->json(['message' => 'Promotion deletion approved and order removed.']); 
        }

        public function rejectDelete($id)
        {
            Log::info('Request Id:', ['id' => $id]); // ✅ correct
            $request = PromotionApproveRequest::findOrFail($id);
            $request->status = 'delete_rejected';
            $request->save();

            //return back()->with('info', 'Order deletion request rejected.');
            return response()->json(['message' => 'Promotion deletion request rejected.']); 
        }


    public function deleteMultiple(Request $request)
    {
        $request->validate([
            'promotion_ids' => 'required|array',
            'promotion_ids.*' => 'exists:promotions,id'
        ]);
        $promotionIds = $request->input('promotion_ids');  
        Log::info('Deleting orders:', ['order_ids' => $promotionIds]);
        try {
            $promotions = Order::whereIn('id', $promotionIds)->get();          
            foreach ($promotions as $promotions) {
                $promotions->delete();
            }         
            return response()->json([
                'success' => true,
                'message' => 'Promotion deleted successfully'
            ]);        
        } catch (\Exception $e) {
            Log::error('Error deleting Promotion:', ['error' => $e->getMessage()]);         
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete Promotion'
            ], 500);
        }
    }

    // public function showUploadForm()
    // {
    //     if (auth()->user()->can('handle orders')) {
    //         return view('Order.upload');
    //     }
    // }

    // public function importorder(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|mimes:xlsx,xls',
    //     ]);

    //     try {
    //         Excel::import(new OrderImport, $request->file('file'));
    //         return back()->with('success', 'Orders imported successfully!');
    //     } catch (\Exception $e) {
    //         return back()->with('error', 'Import failed: ' . $e->getMessage());
    //     }
    // }


     //orderproduct new 
    public function storeOrder(Request $request) {
    if (auth()->user()->can('handle promotions')) {
        Log::info('Request Data:', $request->all()); // Log the request data
        // $products = json_decode($request->products, true);  
        // if (!$products) {
        //     return back()->with('error', 'No products selected!');
        // }  
        $data = $request->validate([
            'product_id' => 'required',      
            'description'=> 'required',
            'discount_percentage'=> 'required',
            'start_date'=> 'required|date',
            'end_date'=> 'required|date',
            'is_active'=> 'required',
            'usage_limit' => 'required|regex:/^\d+(\.\d{1,2})?$/',
        ]);
        Log::info('Validated Promotion Data:', $data);
        $promotion = Promotion::create($data);
        Log::info(' Created:', ['id' => $promotion->id]);
        
        }
        // Generate the PDF
        //$pdf = PDF::loadView('Order.invoice', compact('order'));
        // Optionally save to storage
        //$fileName = 'invoice_'.$order->id.'.pdf';
        //$pdf->save(storage_path('app/public/invoices/' . $fileName));
       // $url = asset('storage/invoices/' . $fileName);//create url to open in new tab
        return response()->json([
            'status' => 'success',
            'message' => 'Promotion saved successfully!',
        ]);
    }
    

    // Load data for editing
    public function orderedit($id)
    {
        //$requestId = $request->input('requestId'); 
        //$customers = Customer::all(); // Fetch all customers
        $products = Product::all(); // Fetch all customers
        $promotion = Promotion::find($id);
        //$order = Order::findOrFail($id);
        return response()->json([
            'products' => $products,
            'promotion' => $promotion,
  
           ]);

    }
    
    public function orderdeleteload($id,Request $request)
    {
        $requestId = $request->input('requestId'); 
        //$customers = Customer::all(); // Fetch all customers
        $products = Product::all(); // Fetch all customers
        $request = PromotionApproveRequest::find($requestId);
        //$order = Order::findOrFail($id);
        $promotion = Promotion::find($id);
        
        return response()->json([
            'promotion' => $promotion,
            //'customers' => $customers,
            'products' => $products,
            'request' => $request,
           ]);

    }
    public function orderapproveload($id,Request $request)
    {
        $requestId = $request->input('requestId'); 
        $products = Product::all(); // Fetch all customers
        $request = PromotionApproveRequest::find($requestId);
        $promotion = Promotion::find($id);
        return response()->json([
            'promotion' => $promotion,
            'products' => $products,
            'requested_changes' => $request ? json_decode($request->requested_changes, true) : null,]);

    }

    public function newfetch()
    {
        //$customers = Customer::all(); // Fetch all customers
        $products = Product::all(); // Fetch all customers
       
        return response()->json([
            'products' => $products]);

    }

        public function editOrder(Request $request) 
        {
            //\Log::info('2. Raw editData from request:', ['editData' => $request->editData]);
        
            try {
                \DB::beginTransaction();
                \Log::info('3. Transaction started');  
                // Find the order
                \Log::info('4. Finding promotion with ID: ' . $request->id);
                $promotion = Promotion::find($request->id);
                
                \Log::info('5. Promotion found?', ['exists' => !is_null($promotion)]);
                if (!$promotion) {
                    \Log::warning('Promotion not found', ['id' => $request->id]);
                    return response()->json(['message' => 'promotion not found.']);
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
                // \Log::info('7. Decoding product data');
                // //$editedProducts = json_decode($request->editData, true);
                
                // if (json_last_error() !== JSON_ERROR_NONE) {
                //     \Log::error('JSON decode failed', [
                //         'error' => json_last_error_msg(),
                //         'input' => $request->editData
                //     ]);
                //     return response()->json([
                //         'message' => 'Invalid product data format',
                //         'error' => json_last_error_msg()
                //     ]);
                // }
    
        
                // Prepare changes
                \Log::info('9. Preparing requested changes');
                $requestedChanges = [
                    'product_id' => $request->product_id,
                    'description' => $request->description,
                    'discount_percentage' => $request->discount_percentage,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date,
                    'is_active' => $request->is_active,
                    'usage_limit' => $request->usage_limit,
                ];

        
                \Log::info('10. Creating OrderDeletionRequest', [
                    'promotion_id' => $promotion->id,
                    'changes' => $requestedChanges
                ]);
        
                $updateRequest = PromotionApproveRequest::create([
                    'promotion_id' => $promotion->id,
                    'requested_by' => auth()->id(),
                    'status' => 'Updated',
                    'requested_changes' => json_encode($requestedChanges),
                ]);
        
                \DB::commit();
                \Log::info('11. Transaction committed');
                \Log::info('12. Request created successfully', [
                    'request_id' => $updateRequest->id
                ]);
    
                return response()->json([
                    'message' => 'Order update submitted for approval',
                    'request_id' => $updateRequest->id
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
        
        $request = PromotionApproveRequest::findOrFail($id);
        $promotion = $request->promotion;
        
        // if (!$products) {
        //     return back()->with('error', 'No products selected!');
        // }  
        
        if ($request->requested_changes) {
            $changes = json_decode($request->requested_changes, true);
    
            $promotion->update([
                'product_id' => $changes['product_id'],
                'description' => $changes['description'],
                'discount_percentage' =>$changes['discount_percentage'],
                'start_date' =>$changes['start_date'],
                'end_date' =>$changes['end_date'],
                'is_active' =>$changes['is_active'],
                'usage_limit' =>$changes['usage_limit'],
            ]);
        }

        $request->status = 'update_approved';
        $request->save();    
        return response()->json(['message' => 'Promotion update approved.']); 

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
        $request = PromotionApproveRequest::findOrFail($id);
        $request->status = 'update_rejected';
        $request->save();

        //return back()->with('info', 'Order deletion request rejected.');
        return response()->json(['message' => 'Promotion Update request rejected.']); 
    }

   
 
     

        


   
}
