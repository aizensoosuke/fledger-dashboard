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
            'nodes_amount' => 'nullable|integer|min:0',
            'instances_per_node' => 'nullable|integer|min:0',
        ]);

        $experiment = Experiment::create($data);

        $nodesAmount = $data['nodes_amount'] ?? 0;
        $instancesPerNode = $data['instances_per_node'] ?? 0;

        for ($i = 0; $i < $nodesAmount; $i++) {
            for ($j = 0; $j < $instancesPerNode; $j++) {
                $instanceName = $i <= 9
                    ? "fledger-n0$i-$j"
                    : "fledger-n$i-$j";
                $experiment->nodes()->create([
                    'name' => $instanceName
                ]);
            }
        }

        return response()->json(['id' => $experiment->id], 201);
    }
}
