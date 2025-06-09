<?php

namespace App\Http\Controllers;

use App\Models\Experiment;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

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
                    'name' => $instanceName,
                ]);
            }
        }

        return response()->json(['id' => $experiment->id], 201);
    }

    public function update(Request $request, Experiment $experiment)
    {
        Gate::authorize('update experiments');

        $data = $request->validate([
            'target_page_id' => 'string|max:255',
        ]);

        if (isset($data['target_page_id'])) {
            $experiment->target_page_id = $data['target_page_id'];
        }

        $experiment->save();

        return response('success', Response::HTTP_OK);

    }

    public function targetPageId(Request $request, Experiment $experiment)
    {
        Gate::authorize('view experiments');

        if (! $experiment->target_page_id) {
            return response()->json(['error' => 'Target page ID not set'], 404);
        }

        return response()->json(['target_page_id' => $experiment->target_page_id]);
    }

    public function end(Request $request, Experiment $experiment)
    {
        Gate::authorize('end experiments');

        $experiment->ended_at = now();
        $experiment->save();

        return response('success', 200);
    }
}
