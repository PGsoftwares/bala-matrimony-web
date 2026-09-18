<section class="col-md-4 mt-md-4 mb-5">

    {{--Search By ID--}}
    <div class="card border-0 shadow-sm p-3 rounded-4 mb-3">
        <h5 class="fw-bold">Search by Profile ID</h5>
        <p class="small text-muted mb-3">Enter the Profile ID to quickly find a specific match.</p>

        <form method="GET" action="{{ route('horoscopeSearch') }}" class="d-flex align-items-center gap-2">
            <div class="input-group light-gray rounded-pill overflow-hidden">
                <span class="input-group-text bg-white border-0 px-3 fw-semibold primary_color">BMB</span>
                <input type="text" name="search_id" class="form-control border-0" placeholder="Enter ID" oninput="this.value = this.value.replace(/[^0-9]/g, '');" />
            </div>
            <button type="submit" class="btn button1 rounded-pill px-4 py-2">
                Search
            </button>
        </form>
    </div>

    {{--Search By Profile--}}
    <div class="card border-0 shadow-sm p-3 rounded-4">
        <h5 class="fw-bold">Search Your Partner</h5>
        <p class="small text-muted mb-3">Find your ideal match with your preferred filters.</p>

        <form method="GET" action="{{ route('horoscopeSearch') }}">

            <div class="mb-3">
                <label class="form-label fw-medium small">Rashi</label>
                <select name="rashi[]" class="form-select rounded-pill select2" multiple>
                    <option value="">Select</option>
                    @foreach($db['rashies'] as $rashi)
                        <option value="{{ $rashi->name }}">{{ $rashi->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium small">Gothram</label>
                <select name="gothram[]" class="form-select rounded-pill select2" multiple>
                    <option value="">Select</option>
                    @foreach($db['gothrams'] as $gothram)
                        <option value="{{ $gothram->name }}">{{ $gothram->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium small">Dosham</label>
                <select name="dosham[]" class="form-select rounded-pill select2" multiple>
                    <option value="">Select</option>
                    @foreach($db['dosham'] as $dosham)
                        <option value="{{ $dosham->name }}">{{ $dosham->name }}</option>
                    @endforeach
                </select>
            </div>

            {{--Actions--}}
            <div class="row g-2">
                <div class="col-6">
                    <button type="reset" class="btn button1 w-100 px-4 py-2">Reset</button>
                </div>
                <div class="col-6">
                    <button type="submit" class="btn button2 w-100 px-4 py-2">Search</button>
                </div>
            </div>

        </form>
    </div>


    <link href="{{ asset('asset/css/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('asset/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: 'Any',
                allowClear: true
            });
        });
    </script>
</section>
