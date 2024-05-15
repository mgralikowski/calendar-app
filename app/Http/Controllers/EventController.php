<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // @todo use Repositories
        return Event::with('location')
            ->where(function (Builder $query) use ($user) {
                $query
                    ->where('owner_id', $user->getKey())
                    ->orWhereHas('participants', fn (Builder $query) => $query->where('user_id', $user->getKey()))
                    ->orWhereHas('location', fn (Builder $query) => $query->where('manager_id', $user->getKey()));
            })
            ->when($request->get('day'), static fn (Builder $query, string $date) => $query->whereDate('start', $date))
            ->when($request->get('location_id'), static fn (Builder $query, string $locationUuid) => $query->whereRelation('location', $locationUuid))
            ->when($request->get('query'), static fn (Builder $query, string $search) => $query->where('name', 'LIKE', "%{$search}%")->orWhere('agenda', 'LIKE', "%{$search}%"))
            ->get();

        // @todo use ApiResource
    }

    public function store(StoreEventRequest $request): Event
    {
        // @todo use Action or Service
        $event = new Event($request->validated());
        $event->owner()->associate($request->user());
        $event->location()->associate($request->get('location_id'));
        $event->save();
        $event->participants()->attach(User::whereIn('email', $request->get('participants', []))->get());

        return $event; // @todo use ApiResource
    }

    public function show(Event $event): Event
    {
        Gate::authorize('view', $event);

        return $event->load('location'); // @todo use ApiResource
    }
}
