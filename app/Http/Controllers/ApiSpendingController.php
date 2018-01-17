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
        $year = $request->input('year', Carbon::now()->year);
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $endDate = Carbon::createFromDate($year, $month, 31)->startOfDay();
        $spendings = Spending::with('user')->with('category')
                              ->whereDate('date', '>=', $startDate)
                              ->whereDate('date', '<', $endDate)
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
