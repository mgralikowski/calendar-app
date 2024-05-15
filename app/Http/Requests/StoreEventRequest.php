<?php

namespace App\Http\Requests;

use App\Models\Location;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Exists;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'agenda' => ['required', 'string'],
            'start' => ['required', 'date'],
            'end' => ['required', 'date', 'after:start', 'before_or_equal:'. $this->date('start')->addHours(8)],
            'participants' => ['required', 'array'],
            'participants.*' => ['email', new Exists(User::class, 'email')],
            'location_id' => ['nullable', 'uuid', new Exists(Location::class, 'id')],
        ];
    }

    protected function prepareForValidation(): void
    {
        $timezone = $this->user()->timezone ?? config('app.timezone');

        $this->merge([
            'start' => $this->date('start', tz: $timezone)?->utc(),
            'end' => $this->date('end', tz: $timezone)?->utc(),
        ]);
    }

    public function messages(): array
    {
        return ['end.before_or_equal' => 'Meetings shouldn’t be longer than 8 hours'];
    }
}
