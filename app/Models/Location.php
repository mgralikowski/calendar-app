<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasUuids;

    protected $fillable = ['name', 'address'];

    /**
     * Owner.
     *
     * @return BelongsTo
     */
    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
