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

    public function update(Request $request, Experiment $experiment, Node $node)
    {
        Gate::authorize('update nodes');

        $data = $request->validate([
            'pages' => 'string|max:16384|nullable',
            'amount_flo_value_sent' => 'integer|min:0|nullable',
            'amount_request_flo_metas_received' => 'integer|min:0|nullable',
            'status' => 'string|in:active,success,timeout|nullable',
        ]);

        if (isset($data['pages'])) {
            $node->pages = str($data['pages'])->split('/,/');
        }

        if (isset($data['amount_request_flo_metas_received'])) {
            $node->amount_request_flo_metas_received = $data['amount_request_flo_metas_received'];
        }

        if (isset($data['amount_flo_value_sent'])) {
            $node->amount_flo_value_sent = $data['amount_flo_value_sent'];
        }

        if (isset($data['status'])) {
            $node->status = $data['status'];
        }

        $node->save();

        return response('success', Response::HTTP_OK);

    }
}
