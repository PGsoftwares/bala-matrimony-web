<div class="sidebar sticky-sidebar sidebar-modern col-sm-4 col-lg-3">
    <div class="row d-flex justify-content-center">
        <div class="col-md-11">
            <form method="GET" action="{{ route('aiSearch') }}">

                <div class="card ">
                    <div class="mb-4 theme-bg shadow p-10  text-light d-flex justify-content-between">
                        <h4>AI Search</h4>
                        <button class="btn btn-md btn-success" type="submit">Filter</button>
                    </div>

                    <div class="card">
                        <div class="card-body p-2">
                            <select id="language" class="form-control" name="mother_tongue">
                                <option value="">Select Mother Tongue</option>
                                @foreach($db['languages'] as $language)
                                    <option value="{{ $language->language }}">{{ $language->language }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-2">
                            <select id="age" class="form-control" name="age">
                                <option value="">Select Age</option>
                                <option value="18 to 20">18 to 20</option>
                                <option value="21 to 25">21 to 25</option>
                                <option value="26 to 30">26 to 30</option>
                                <option value="31 to 35">31 to 35</option>
                                <option value="36 to 40">36 to 40</option>
                                <option value="41 to 45">41 to 45</option>
                                <option value="46 to 50">46 to 50</option>
                                <option value="51 to 55">51 to 55</option>
                                <option value="56 to 60">56 to 60</option>
                                <option value="61 to 65">61 to 65</option>
                                <option value="66 to 70">66 to 70</option>
                            </select>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-2">
                            <select id="marital_status" class="form-control" name="marital_status">
                                <option value="">Select Marital Status</option>
                                @foreach($db['maritalStatuses'] as $maritalStatus)
                                    <option value="{{ $maritalStatus->name }}">{{ $maritalStatus->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-2">
                            <select id="skin_tone" class="form-control" name="skin_tone">
                                <option value="">Select Skin Tone</option>
                                @foreach($db['skinTones'] as $skinTone)
                                    <option value="{{ $skinTone->name }}">{{ $skinTone->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-2">
                            <select id="height" class="form-control" name="height">
                                <option value="">Select Height</option>
                                @foreach($db['heights'] as $height)
                                    <option value="{{ $height->name }}">{{ $height->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-2">
                            <select id="body_type" class="form-control" name="body_type">
                                <option value="">Select Body Type</option>
                                @foreach($db['bodyTypes'] as $bodyType)
                                    <option value="{{ $bodyType->name }}">{{ $bodyType->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-2">
                            <select id="drinking_habit" class="form-control" name="drinking_habit">
                                <option value="">Select Drinking Habit</option>
                                @foreach($db['drinkingHabits'] as $drinkingHabit)
                                    <option value="{{ $drinkingHabit->name }}">{{ $drinkingHabit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-2">
                            <select id="smoking_habit" class="form-control" name="smoking_habit">
                                <option value="">Select Smoking Habit</option>
                                @foreach($db['smokingHabits'] as $smokingHabit)
                                    <option value="{{ $smokingHabit->name }}">{{ $smokingHabit->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-2">
                            <select id="religion" class="form-control" name="religion">
                                <option value="">Select Religion</option>
                                @foreach($db['religions'] as $religion)
                                    <option value="{{ $religion->name }}">{{ $religion->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-2">
                            <select id="caste" class="form-control" name="caste">
                                <option value="">Select Caste</option>
                                @foreach($db['castes'] as $caste)
                                    <option value="{{ $caste }}">{{ $caste }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!--<div class="card">-->
                    <!--    <div class="card-body p-2">-->
                    <!--        <select id="sub-caste" class="form-control" name="sub_caste">-->
                    <!--            <option value="">Select Sub Caste</option>-->
                    <!--            <option value=""></option>-->
                    <!--        </select>-->
                    <!--    </div>-->
                    <!--</div>-->

                    <div class="card">
                        <div class="card-body p-2">
                            <select id="education-level" class="form-control" name="qualification">
                                <option value="">Select Education type</option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-body p-2">
                            <select id="study" class="form-control" name="education">
                                <option value="">Select Education</option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-body p-2">
                            <select id="occupation-type" class="form-control" name="occupation_type">
                                <option value="">Select Occupation Type</option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-body p-2">
                            <select id="occupation" class="form-control" name="occupation">
                                <option value="">Select Occupation</option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-2">
                            <select id="rashi" class="form-control" name="rashi">
                                <option value="">Select Rashi</option>
                                @foreach($db['rashies'] as $rashi)
                                    <option value="{{ $rashi->name }}">{{ $rashi->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-2">
                            <select id="nakshatra" class="form-control" name="nakshatra">
                                <option value="">Select Nakshatra</option>
                                @foreach($db['nakshatras'] as $nakshatra)
                                    <option value="{{ $nakshatra->name }}">{{ $nakshatra->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-2">
                            <select id="lagnam" class="form-control" name="lagnam">
                                <option value="">Select Lagnam</option>
                                @foreach($db['lagnams'] as $lagnam)
                                    <option value="{{ $lagnam->name }}">{{ $lagnam->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-2">
                            <select id="padam" class="form-control" name="padam">
                                <option value="">Select Padam</option>
                                @foreach($db['padams'] as $padam)
                                    <option value="{{ $padam->name }}">{{ $padam->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-2">
                            <select id="kulam" class="form-control" name="kulam">
                                <option value="">Select Kulam</option>
                                @foreach($db['kulams'] as $kulam)
                                    <option value="{{ $kulam->name }}">{{ $kulam->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-body p-2">
                            <select id="gothram" class="form-control" name="gothram">
                                <option value="">Select Gothram</option>
                                @foreach($db['gothrams'] as $gothram)
                                    <option value="{{ $gothram->name }}">{{ $gothram->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="card">
                        <div class="card-body p-2">
                            <select id="dosham" class="form-control" name="dosham">
                                <option value="">Select Dosham</option>
                                @foreach($db['dosham'] as $dosham)
                                    <option value="{{ $dosham->name }}">{{ $dosham->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-2">
                            <select id="country" class="form-control" name="country">
                                <option value="">Select Country</option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-2">
                            <select id="state" class="form-control" name="state">
                                <option value="">Select State</option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body p-2">
                            <select id="city" class="form-control" name="city">
                                <option value="">Select City</option>
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <div class="card-header bg-warning d-flex justify-content-between  theme-bg shadow p-15">
                        <button class="btn btn-md btn-success" type="reset">Reset</button>
                        <button class="btn btn-md btn-success" type="submit">Filter</button>
                    </div>

                </div>
            </form>
        </div>
    </div>


    {{--Dropdown js--}}
    <script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" />
    <script src="{{ asset('assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('web/assets/js/dropdown.js') }}"></script>

</div>

<!-- Include library -->
<link href="{{ asset('assets/css/choices.min.css') }}" rel="stylesheet">
<script src="{{ asset('assets/js/choices.min.js') }}"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const selectIds = [
            'language', 'age', 'marital_status', 'skin_tone', 'height', 'body_type',
            'drinking_habit', 'smoking_habit', 'religion', 'rashi', 'nakshatra', 'lagnam', 'padam',
            'kulam', 'gothram', 'dosham',
        ];
        selectIds.forEach(function(selectId) {
            const selectElement = document.getElementById(selectId);
            if (selectElement) {
                new Choices(selectElement, {
                    searchEnabled: true,
                    removeItemButton: true,
                    shouldSort: false,
                });
            }
        });
    });
</script>
