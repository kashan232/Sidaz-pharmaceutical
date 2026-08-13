<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with('baseUnit')->orderBy('id', 'desc')->get();
        $baseUnits = Unit::where('status', 1)->get();
        return view("admin_panel.unit.index", compact('units', 'baseUnits'));
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'short_code' => 'required|string|max:50|unique:units,short_code,'.$request->edit_id,
            'unit_type' => 'required|string|in:Weight,Volume,Quantity',
            'base_unit' => 'nullable|exists:units,id',
            'conversion_factor' => 'nullable|numeric|min:0',
            'status' => 'required|boolean',
        ]);

        if ($validator->fails()) {
            return ['errors' => $validator->errors()];
        }

        if ($request->has('edit_id') && $request->edit_id != '' || $request->edit_id != null) {
            $unit = Unit::find($request->edit_id);
            $msg = [
                'success' => 'Unit Updated Successfully',
                'reload' => true
            ];
        } else {
            $unit = new Unit();
            $msg = [
                'success' => 'Unit Created Successfully',
                'redirect' => route('Unit.home')
            ];
        }

        $unit->name = $request->name;
        $unit->short_code = strtoupper($request->short_code);
        $unit->unit_type = $request->unit_type;
        $unit->base_unit = $request->base_unit;
        $unit->conversion_factor = $request->conversion_factor ?? 1;
        $unit->status = $request->status;
        $unit->save();

        return response()->json($msg);
    }

    public function delete($id)
    {

        $company = Unit::find($id);
        if ($company) {
            $company->delete();
            $msg = [
                'success' => 'Unit Deleted Successfully',
                'reload' =>  route('Unit.home'),
            ];
        } else {
            $msg = ['error' => 'Unit Not Found'];
        }
        return response()->json($msg);
    }
}
