<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SpendingRequest;
use App\Models\Spending;
use Carbon\Carbon;

class ApiSpendingController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $startDate = Carbon::createFromDate(null, $month, 1);
        $endDate = Carbon::createFromDate(null, $month, 31);
    	$spendings = Spending::with('user')->with('category')
                              ->whereDate('date', '>=', $startDate->startOfDay())
                              ->whereDate('date', '<', $endDate->startOfDay())
                              ->orderBy('date', 'desc')
                              ->get();

    	return response()->json($spendings);
    }

    public function update($id, SpendingRequest $request)
    {
        $inputs = $request->json()->all();
        
        foreach(['user_id', 'category_id'] as $nullable) {
            if ($inputs[$nullable] == 'null') {
                $inputs[$nullable] = null;
            }
        }

    	$spending = Spending::find($id);
    	$spending->update($inputs);
        $spending->load('user')->load('category');
    	
    	return response()->json($spending);
    }

    public function store(SpendingRequest $request)
    {
        $spending = Spending::create($request->json()->all());
        return response()->json($spending);
    }

    public function destroy($id)
    {
        Spending::where('id', $id)->delete();
    }
}