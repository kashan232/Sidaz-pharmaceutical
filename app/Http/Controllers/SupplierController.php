<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller
{
    /**
     * Display a listing of the suppliers.
     */
    public function index()
    {
        $suppliers = Supplier::orderBy('id', 'desc')->get();
        return view('admin_panel.supplier.index', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage or update an existing one.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'required|string|max:50',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'ntn_number' => 'nullable|string|max:100',
            'payment_terms' => 'nullable|string|max:255',
            'credit_limit' => 'nullable|numeric|min:0',
            'opening_balance' => 'nullable|numeric',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        if ($request->filled('edit_id')) {
            $supplier = Supplier::findOrFail($request->edit_id);
            $msg = [
                'success' => 'Supplier Updated Successfully',
                'reload' => true
            ];
        } else {
            $supplier = new Supplier();
            $msg = [
                'success' => 'Supplier Created Successfully',
                'redirect' => route('suppliers.index')
            ];
        }

        $supplier->name = $request->name;
        $supplier->company_name = $request->company_name;
        $supplier->contact_person = $request->contact_person;
        $supplier->phone = $request->phone;
        $supplier->email = $request->email;
        $supplier->address = $request->address;
        $supplier->ntn_number = $request->ntn_number;
        $supplier->payment_terms = $request->payment_terms;
        $supplier->credit_limit = $request->credit_limit ?? 0;
        $supplier->opening_balance = $request->opening_balance ?? 0;
        $supplier->status = $request->status;
        $supplier->save();

        return response()->json($msg);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        $supplier = Supplier::find($id);
        if ($supplier) {
            $supplier->delete();
            return response()->json([
                'success' => 'Supplier Deleted Successfully',
                'reload' => route('suppliers.index'),
            ]);
        } else {
            return response()->json(['error' => 'Supplier Not Found']);
        }
    }
}
