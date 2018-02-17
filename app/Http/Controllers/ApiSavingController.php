<?php

namespace App\Http\Controllers;

use App\Http\Requests\SavingRequest;
use App\Http\Requests\ParticipationRequest;
use App\Models\Saving;

class ApiSavingController extends Controller
{
    public function index()
    {
        $savings = Saving::all();
        $savings->map(function($saving) {
            $saving->current_amount = $saving->current_amount;
        });

    	return response()->json($savings);
    }

    public function store(SavingRequest $request)
    {
        $saving = Saving::create($request->json()->all());

        $saving->current_amount = $saving->current_amount;
        return response()->json($saving);
    }

    public function update($id, SavingRequest $request)
    {
        $saving = Saving::find($id);
        $saving->update($request->json()->all());

        $saving->current_amount = $saving->current_amount;
        return response()->json($saving);
    }

    public function addParticipation($id, ParticipationRequest $request)
    {
        $user = auth()->user();

        if (! $saving = $user->savings()->find($id)) {
            $saving = Saving::find($id);
            $user->savings()->attach($id);
        }

        $saving->pivot->amount += $request->input('amount');
        $saving->pivot->save();
    }

    public function destroy($id)
    {
        Saving::where('id', $id)->delete();
    }
}
