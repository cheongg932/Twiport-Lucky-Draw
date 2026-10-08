<?php

namespace App\Http\Controllers;

use App\Services\DrawService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PrizeController extends Controller
{
    public function __construct(private readonly DrawService $draws) {}

    public function index(): JsonResponse
    {
        return response()->json([
            'prizes' => $this->draws->catalog(),
            'wheel' => $this->draws->wheelOrder(),
        ]);
    }

    public function draw(Request $request, string $game): JsonResponse
    {
        $validated = $request->merge(['game' => $game])->validate([
            'game' => ['required', 'in:spin,scratch,slots'],
        ]);

        return response()->json($this->draws->draw($validated['game']));
    }
}
