<?php

namespace App\Http\Controllers;

use App\Data\ExperimentData;
use App\Data\NodeData;
use App\Data\PostNodeData;
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

        $experiment->nodes()->create($data);

        return response('success', Response::HTTP_CREATED);
    }

    public function update(Request $request, Experiment $_experiment, Node $node)
    {
        Gate::authorize('update nodes');

        $data = $request->validate([
            'pages' => 'array',
            'pages.*' => 'string|max:255',
        ]);

        $node->update($data);

        return response('success', Response::HTTP_OK);

    }
}
