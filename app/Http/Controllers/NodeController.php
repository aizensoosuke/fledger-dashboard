<?php

namespace App\Http\Controllers;

use App\Data\FloPageData;
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

        $experiment = $node->experiment;

        $node->pages = $data->pages_stored ?? $node->pages;
        $node->status = $data->node_status ?? $node->status;

        $data->pages_stored->each(function (FloPageData $page) use ($node) {
            $node->floPages()->firstOrCreate(
                ['flo_id' => $page->id],
                ['name' => $page->name, 'experiment_id' => $node->experiment_id],
            );
        });

        $node->save();
        $experiment->save();

        $dataPoints = collect($data->timed_metrics)
            ->map(function ($metric) use (&$node) {
                return $node->dataPoints()->create([
                    'name' => $metric[0],
                    'value' => $metric[1],
                    'time' => now()->floorSeconds(10),
                ]);
            });

        $timelessDataPoints = collect($data->timeless_metrics)
            ->map(function ($metric) use (&$node) {
                return $node->timelessDataPoints()->updateOrCreate(
                    ['name' => $metric[0]],
                    ['value' => $metric[1]],
                );
            });

        $targetPageIds = $experiment->targetFloPages()->inRandomOrder()->take(2)->pluck('flo_id');

        $response = [
            'timed_data_points' => $dataPoints->pluck('id'),
            'timeless_data_points' => $timelessDataPoints->pluck('id'),
            'target_page_ids' => $targetPageIds,
        ];

        return response()->json($response, Response::HTTP_OK);
    }
}
