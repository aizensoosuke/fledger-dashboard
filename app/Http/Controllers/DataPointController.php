<?php

namespace App\Http\Controllers;

use App\Models\DataPoint;
use App\Models\Experiment;
use App\Models\Node;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class DataPointController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Experiment $_experiment, Node $node)
    {
        Gate::authorize('create data points');

        $time = now()->floorSeconds(10);

        $ids = [];

        foreach ($request->all() as $key => $value) {
            if (str($key)->length() > 255) {
                return response()->json(['error' => "Key '$key' exceeds maximum length of 255 characters."], Response::HTTP_BAD_REQUEST);
            }
            if (str($value)->length() > 255) {
                return response()->json(['error' => "Value for key '$key' exceeds maximum length of 255 characters."], Response::HTTP_BAD_REQUEST);
            }

            if (!is_numeric($value)) {
                return response()->json(['error' => "Value for key '$key' must be numeric."], Response::HTTP_BAD_REQUEST);
            }

            $dataPoint = $node->dataPoints()->create([
                'name' => $key,
                'value' => $value,
                'time' => $time,
            ]);

            $ids[] = $dataPoint->id;
        }

        return response()->json(['ids' => $ids], Response::HTTP_CREATED);
    }
}
