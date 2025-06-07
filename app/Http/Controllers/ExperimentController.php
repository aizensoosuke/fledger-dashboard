<?php

namespace App\Http\Controllers;

use App\Data\ExperimentData;
use App\Data\PostExperimentData;
use App\Models\Experiment;
use Gate;
use Illuminate\Http\Request;
use function Filament\authorize;

class ExperimentController extends Controller
{
    public function store(Request $request)
    {
        Gate::authorize('create experiments');

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'pages_amount' => 'nullable|integer|min:0',
        ]);

        $experiment = Experiment::create($data);

        return response()->json(['id' => $experiment->id], 201);
    }
}
