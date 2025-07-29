<?php

namespace App\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Movie;

class MovieRepository
{
    public function getMovies(array $params): LengthAwarePaginator
    {
        return Movie::query()
            ->with('directors')
            ->when(! empty($params['title']), function ($query) use ($params) {
                $query->where('title', 'like', "%{$params['title']}%");
            })
            ->when(! empty($params['year']), function ($query) use ($params) {
                $query->whereDate('year', $params['year']);
            })
            ->when(! empty($params['director']), function ($query) use ($params) {
                $query->whereHas('directors', function ($query) use ($params) {
                    $query->where('name', 'like', "%{$params['director']}%");
                });
            })
            ->paginate();
    }
}
