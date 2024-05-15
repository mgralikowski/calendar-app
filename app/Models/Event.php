<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'agenda', 'start', 'end'];

    protected $casts = ['start' => 'datetime', 'end' => 'datetime'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        /** @var $date Carbon */
        return $date->timezone(auth()->user()->timezone)->toDateTimeString();
    }
}
