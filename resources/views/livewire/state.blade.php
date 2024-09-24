<div>

    <select wire:model.live="selectedState" class="form-select" name="state">
        @foreach (App\Models\CityState::select('state')->distinct()->get() as $state)
            <option value="{{ $state->state }}">{{ $state->state }}</option>
        @endforeach
    </select>

   
        {{-- <!-- Cities dependent select menu... -->
        <select wire:model.live="selectedCity" wire:key="{{ $selectedState }}">
            @foreach (City::whereStateId($selectedState->id)->get() as $city)
                <option value="{{ $city->id }}">{{ $city->label }}</option>
            @endforeach
        </select> --}}


</div>
