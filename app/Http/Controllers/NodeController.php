<?php

namespace App\Http\Controllers;

use App\Data\SimulationSnapshotData;
use App\Models\Experiment;
use App\Models\Node;
use Gate;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NodeController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Experiment $experiment)
    {
        Gate::authorize('create nodes');

        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $node = $experiment->nodes()->create($data);

        return response()->json(['id' => $node->id], Response::HTTP_CREATED);
    }

    public function update(Request $request, Node $node)
    {
        Gate::authorize('update nodes');

        $data = SimulationSnapshotData::validateAndCreate($request);

        $node->load(['dataPoints', 'timelessDataPoints', 'experiment']);
        $experiment = $node->experiment;

        $node->pages_stored = $data->pages_stored ?? $node->pages_stored;
        $node->status = $data->node_status ?? $node->status;

        $dataPoints = collect($data->timed_metrics)
            ->map(function ($metric) use (&$node) {
                return $node->dataPoints()->make([
                    'name' => $metric[0],
                    'value' => $metric[1],
                    'time' => now()->floorSeconds(10),
                ]);
            })
            ->all();
        $node->dataPoints()->saveMany($dataPoints);

        $timelessDataPoints = collect($data->timeless_metrics)
            ->map(function ($metric) use (&$node) {
                $tdp = $node->timelessDataPoints()->where('name', $metric[0])->first();
                if ($tdp) {
                    $tdp->value = $metric[1];

                    return $tdp;
                }

                return $node->timelessDataPoints()->make([
                    'name' => $metric[0],
                    'value' => $metric[1],
                ]);
            })
            ->all();
        $node->timelessDataPoints()->saveMany($timelessDataPoints);

        $node->save();
        $experiment->save();

        $targetPageIds = collect($node->experiment->target_pages)->pluck('id')->toArray();

        $response = [
            'timed_data_points' => collect($dataPoints)->pluck('id'),
            'timeless_data_points' => collect($timelessDataPoints)->pluck('id'),
            'target_page_ids' => $targetPageIds,
        ];

        return response()->json($response, Response::HTTP_OK);
    }
}
