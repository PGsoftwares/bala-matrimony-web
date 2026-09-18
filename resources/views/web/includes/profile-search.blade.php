<section class="col-md-4 mt-md-4 mb-5">

    {{--Search By ID--}}
    <div class="card border-0 shadow-sm p-3 rounded-4 mb-3">
        <h5 class="fw-bold">Search by Profile ID</h5>
        <p class="small text-muted mb-3">Enter the Profile ID to quickly find a specific match.</p>

        <form method="GET" action="{{ route('all-profiles.index') }}" class="d-flex align-items-center gap-2">
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


        <form method="GET" action="{{ route('all-profiles.index') }}">

            {{--Basic details--}}
            <h5 class="fw-medium">Basic Details</h5>
            <label class="form-label fw-medium small">Age Range</label>
            <div class="mb-3">
                <select id="min_age" name="min_age" class="form-select rounded-pill mb-2">
                    <option value="">Minimum Age</option>
                </select>
                <select id="max_age" name="max_age" class="form-select rounded-pill">
                    <option value="">Maximum Age</option>
                </select>
            </div>

            <label class="form-label fw-medium small">Height Range</label>
            <div class="mb-3">
                <select id="min_height" name="height_from" class="form-select rounded-pill mb-2">
                    <option value="">Minimum Height</option>
                    @foreach($db['heights'] as $height)
                        <option value="{{ $height->name }}">{{ $height->name }}</option>
                    @endforeach
                </select>
                <select id="max_height" name="height_to" class="form-select rounded-pill">
                    <option value="">Maximum Height</option>
                    @foreach($db['heights'] as $height)
                        <option value="{{ $height->name }}">{{ $height->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium small">Marital Status</label>
                <select name="marital_status[]" class="form-select rounded-pill select2" multiple>
                    <option value="">Select</option>
                    @foreach($db['maritalStatuses'] as $maritalStatus)
                        <option value="{{ $maritalStatus->name }}">{{ $maritalStatus->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium small">Mother Tongue</label>
                <select name="mother_tongue[]" class="form-select rounded-pill select2" multiple>
                    <option value="">Select</option>
                    @foreach($db['languages'] as $language)
                        <option value="{{ $language->language }}">{{ $language->language }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium small">Physical Status</label>
                <select name="physical_status[]" class="form-select rounded-pill select2" multiple>
                    <option value="">Select</option>
                    <option value="Normal">Normal</option>
                    <option value="Physically Challenged">Physically Challenged</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium small">Religion</label>
                <select name="religion[]" class="form-select rounded-pill select2" multiple>
                    <option value="">Select</option>
                    @foreach($db['religions'] as $religion)
                        <option value="{{ $religion->name }}">{{ $religion->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium small">Caste</label>
                <select name="caste[]" class="form-select rounded-pill select2" multiple>
                    <option value="">Select</option>
                    @foreach($db['castes'] as $caste)
                        <option value="{{ $caste }}">{{ $caste }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium small">Ethnicity</label>
                <select name="ethnicity[]" class="form-select rounded-pill select2" multiple>
                    <option value="">Select</option>
                    @foreach($db['ethnicity'] as $ethnicity)
                        <option value="{{ $ethnicity->name }}">{{ $ethnicity->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium small">Nationality</label>
                <select name="nationality[]" class="form-select rounded-pill select2" multiple>
                    <option value="">Select</option>
                    @foreach($db['nationality'] as $nationality)
                        <option value="{{ $nationality->name }}">{{ $nationality->name }}</option>
                    @endforeach
                </select>
            </div>


            {{--Education details--}}
            <h5 class="fw-medium">Education & Career</h5>
            <div class="mb-3">
                <label class="form-label fw-medium small">Education</label>
                <select name="education[]" class="form-select rounded-pill select2" multiple>
                    <option value="">Select</option>
                    @foreach($db['combinedEducations'] as $level)
                        <optgroup label="{{ $level->level_name }}">
                            @foreach($level->educations as $education)
                                <option value="{{ $education->name }}">{{ $education->name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium small">Occupation</label>
                <select name="occupation[]" class="form-select rounded-pill select2" multiple>
                    <option value="">Select</option>
                    @foreach($db['combinedOccupations'] as $combinedOccupation)
                        <optgroup label="{{ $combinedOccupation->type_name }}">
                            @foreach($combinedOccupation->occupations as $occupation)
                                <option value="{{ $occupation->name }}">{{ $occupation->name }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="work_country" class="form-label fw-medium small">Work Country</label>
                <select id="work_country" name="work_country[]" class="form-select rounded-pill select2" multiple>
                    <option value="">Select</option>
                    @foreach($db['countries'] as $country)
                        <option value="{{ $country }}">{{ $country }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="visa_status" class="form-label fw-medium small">Visa Status</label>
                <select id="visa_status" name="visa_status[]" class="form-select rounded-pill select2" multiple>
                    <option value="">Select</option>
                    @foreach($db['visaStatus'] as $visaStatus)
                        <option value="{{ $visaStatus->name }}">{{ $visaStatus->name }}</option>
                    @endforeach
                </select>
            </div>

            {{--Lifestyle--}}
            <h5 class="fw-medium">Lifestyle</h5>
            <div class="mb-3">
                <label class="form-label fw-medium small">Eating Habit</label>
                <select name="eating_habit[]" class="form-select rounded-pill select2" multiple>
                    <option value="">Select</option>
                    @foreach($db['eatingHabits'] as $eatingHabit)
                        <option value="{{ $eatingHabit->name }}">{{ $eatingHabit->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium small">Drinking Habit</label>
                <select name="drinking_habit[]" class="form-select rounded-pill select2" multiple>
                    <option value="">Select</option>
                    @foreach($db['drinkingHabits'] as $drinkingHabit)
                        <option value="{{ $drinkingHabit->name }}">{{ $drinkingHabit->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium small">Smoking Habit</label>
                <select name="smoking_habit[]" class="form-select rounded-pill select2" multiple>
                    <option value="">Select</option>
                    @foreach($db['smokingHabits'] as $smokingHabit)
                        <option value="{{ $smokingHabit->name }}">{{ $smokingHabit->name }}</option>
                    @endforeach
                </select>
            </div>

            {{--Location--}}
            <h5 class="fw-medium">Location</h5>
            <div class="mb-3">
                <label class="form-label fw-medium small">Country</label>
                <select id="country" name="country" class="form-select rounded-pill select2">
                    <option value="">Select</option>
                    @foreach($db['countries'] as $country)
                        <option value="{{ $country }}">{{ $country }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium small">State</label>
                <select id="state" name="state" class="form-select rounded-pill select2">
                    <option value="">Select</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-medium small">City</label>
                <select id="city" name="city[]" class="form-select rounded-pill select2" multiple>
                    <option value="">Select</option>
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
    <script src="{{ asset('asset/js/script.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.select2').select2({
                placeholder: 'Any',
                allowClear: true
            });
        });
    </script>
</section>
