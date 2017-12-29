<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class ApiChartController extends Controller
{
    public function spendings(Request $request)
    {
        $data = [];
        $year = $request->input('year', Carbon::now()->year);

        for($i = 1; $i <= 12; $i ++) {
            $start = Carbon::createFromDate($year, $i, 1)->startOfDay();
            $end = (clone $start)->addMonth();
            $data[] = DB::table('spendings')->whereDate('date', '>=', $start)
                                            ->whereDate('date', '<', $end)
                                            ->sum('amount');
        }

    	return response()->json($data);
    }

    public function dividing(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $start = Carbon::createFromDate(null, $month, 1)->startOfDay();
        $end = (clone $start)->addMonth();
        $data = DB::table('spendings')
                  ->whereDate('date', '>=', $start)
                  ->whereDate('date', '<', $end)
                  ->leftJoin('categories', 'category_id', '=', 'categories.id')
                  ->select(DB::raw('SUM(spendings.amount) as total'), 'categories.title', 'categories.color')
                  ->groupBy('category_id')
                  ->orderBy('total', 'desc')
                  ->get();

        return response()->json($data);
    }

    public function situation(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $start = Carbon::createFromDate(null, $month, 1)->startOfDay();
        $end = (clone $start)->addMonth();
        $data = DB::table('spendings')
                  ->whereDate('date', '>=', $start)
                  ->whereDate('date', '<', $end)
                  ->leftJoin('users', 'user_id', '=', 'users.id')
                  ->select(DB::raw('SUM(spendings.amount) as total'), 'users.name', 'users.color')
                  ->groupBy('user_id')
                  ->orderBy('total', 'desc')
                  ->get();

        return response()->json($data);
    }
}
