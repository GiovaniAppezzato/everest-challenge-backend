<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MovieResource;
use App\Services\MovieService;

class MovieController extends Controller
{
    public function __construct(
        protected MovieService $movieService
    ) {}

    public function index(Request $request)
    {
        $movies = $this->movieService->getMovies($request->all());

        return MovieResource::collection($movies);
    }
}
