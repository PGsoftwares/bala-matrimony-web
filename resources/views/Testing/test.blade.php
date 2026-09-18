<div class="container">
    <h1>Search</h1>
    <form  method="POST">
        @csrf
        @foreach($searchFields as $field)
            <div class="mb-3">
                <label for="{{ $field->field_name }}" class="form-label">{{ $field->display_name }}</label>
                @if ($field->field_type == 'select')
                    <select class="form-control" id="{{ $field->field_name }}" name="{{ $field->field_name }}">
                        @foreach(explode(',', $field->options) as $option)
                            <option value="{{ trim($option) }}">{{ trim($option) }}</option>
                        @endforeach
                    </select>
                @elseif ($field->field_type == 'radio')
                    @foreach(explode(',', $field->options) as $option)
                        <div class="form-check">
                            <input class="form-check-input" type="radio" id="{{ $field->field_name }}_{{ trim($option) }}" name="{{ $field->field_name }}" value="{{ trim($option) }}">
                            <label class="form-check-label" for="{{ $field->field_name }}_{{ trim($option) }}">
                                {{ trim($option) }}
                            </label>
                        </div>
                    @endforeach
                @else
                    <input type="{{ $field->field_type }}" class="form-control" id="{{ $field->field_name }}" name="{{ $field->field_name }}">
                @endif
            </div>
        @endforeach
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>


