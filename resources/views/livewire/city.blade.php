<div>
    <select name="city" class="js-example-basic-single">
        <option value="">Select City</option>
        @foreach ($city as $city)
            <option value="{{ $state->city }}">{{ $state->city }}</option>
        @endforeach
    </select>
</div>
