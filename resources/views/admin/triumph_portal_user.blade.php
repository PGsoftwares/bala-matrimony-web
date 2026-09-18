<div class="container">
    <div class="d-flex justify-content-between">
        <h4>BMB000{{ $user->user_id }} - {{ $user->name }}</h4>
        <div>
            @if($user->status != 'deactivated')
                <span class="badge bg-success text-white" style="padding: 8px 15px">Active</span>
            @else
                <span class="badge bg-danger text-white" style="padding: 8px 15px">Deactivated</span>
            @endif
        </div>
    </div>


    <div class="table-container mt-3" style="max-height: 570px; overflow: auto; position: relative;">
        <table class="table table-bordered m-0" style="min-width: max-content;">
            <thead class="bg-light" style="position: sticky; top: 0; z-index: 1;">
            <tr>
                <th class="bg-white">Field</th>
                @foreach ($dates as $date)
                    <th class="bg-white">{{ $date }}</th>
                @endforeach
            </tr>
            </thead>
            <tbody>
            @php
                $fields = array_keys($history[$dates[0]] ?? []);
            @endphp
            @foreach ($fields as $field)
                <tr>
                    <td>{{ $field }}</td>
                    @php $prev = null; @endphp
                    @foreach ($dates as $date)
                        @php
                            $val = $history[$date][$field] ?? '-';
                            $isChanged = $prev !== null && $val !== $prev;
                            $prev = $val;
                        @endphp
                        <td style="color: {{ $isChanged ? 'red' : 'black' }}">{{ $val }}</td>
                    @endforeach
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
