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
        $start = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $end = (clone $start)->addMonth();

        $spendings = Spending::with('user')->with('category')
                              ->whereDate('date', '>=', $start)
                              ->whereDate('date', '<', $end)
                              ->orderBy('date', 'desc')
                              ->get();

        $result = [
            'spendings' => $spendings,
            'total' => round($spendings->sum('amount'), 2)
        ];

    	return response()->json($result);
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
