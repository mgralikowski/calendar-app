<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocationRequest;
use App\Models\Location;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Gate;

class LocationController extends Controller
{
    public function index(): Collection
    {
        return Location::all(); // @todo use ApiResource
    }

    public function store(StoreLocationRequest $request): Location
    {
        // @todo use Action or Service
        $location = new Location($request->validated());
        $location->manager()->associate($request->user());
        $location->save();

        return $location; // @todo use ApiResource
    }

    public function show(Location $location): Location
    {
        Gate::authorize('view', $location);

        return $location; // @todo use ApiResource
    }
}
