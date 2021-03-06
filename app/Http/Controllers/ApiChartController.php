<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;

class ApiChartController extends Controller
{
    public function spendings(Request $request)
    {
        $data = [
          'data' => [],
          'categories' => []
        ];
        $start = Carbon::now()->startOfMonth()->startOfDay()->subMonths(11);
        for($i = 1; $i <= 12; $i ++) {
            $start->addMonth();
            $data['categories'][] = config()->get('data.months')[$start->month];
            $end = (clone $start)->addMonth();
            $data['data'][] = round(DB::table('spendings')
                                ->whereNull('deleted_at')
                                ->whereDate('date', '>=', $start)
                                ->whereDate('date', '<', $end)
                                ->sum('amount'), 2);
        }

    	return response()->json($data);
    }

    public function dividing(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $start = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $end = (clone $start)->addMonth();
        $data = DB::table('spendings')
                  ->whereNull('spendings.deleted_at')
                  ->whereDate('date', '>=', $start)
                  ->whereDate('date', '<', $end)
                  ->leftJoin('categories', 'category_id', '=', 'categories.id')
                  ->select(DB::raw('ROUND(SUM(spendings.amount), 2) as total'), 'categories.title', 'categories.color')
                  ->groupBy('category_id')
                  ->orderBy('total', 'desc')
                  ->get();

        return response()->json($data);
    }

    public function situation(Request $request)
    {
        $month = $request->input('month', Carbon::now()->month);
        $year = $request->input('year', Carbon::now()->year);
        $start = Carbon::createFromDate($year, $month, 1)->startOfDay();
        $end = (clone $start)->addMonth();
        $data = DB::table('spendings')
                  ->whereNull('spendings.deleted_at')
                  ->whereDate('date', '>=', $start)
                  ->whereDate('date', '<', $end)
                  ->leftJoin('users', 'user_id', '=', 'users.id')
                  ->select(DB::raw('ROUND(SUM(spendings.amount), 2) as total'), 'users.name', 'users.color')
                  ->groupBy('user_id')
                  ->orderBy('total', 'desc')
                  ->get();

        return response()->json($data);
    }
}
