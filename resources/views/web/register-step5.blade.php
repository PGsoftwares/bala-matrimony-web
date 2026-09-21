@extends('web.layouts.layout')
@section('title', $metaTags->title ?? 'Family Details')
@section('description', $metaTags->description ?? '')
@section('keywords', $metaTags->keywords ?? '')

@section('content')
    @include('web.includes.header')

    <section class="container-fluid">

        <div class="card border-0 p-4 mt-3 mb-5 shadow rounded-4 gradient-bg-primary">
            <div class="row">

                <div class="col-md-4 d-flex flex-column justify-content-between text-white">
                    <div>
                        <h5 class="fw-bold">Family Details</h5>
                        <p>Please fill with your family details</p>
                    </div>
                    <div class="d-flex justify-content-center text-center">
                        <img src="{{ asset('asset/img/registration.png') }}" alt="registration" class="img-fluid" style="max-height: 400px;">
                    </div>
                </div>

                <div class="col-md-8 d-flex flex-column" >
                    <div class="card shadow border-0 p-0 d-flex flex-column" >

                        <div class="p-3">
                            <h5 class="fw-semibold mb-0">Family Details</h5>
                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif
                        </div>

                        <div class="form-body px-3 overflow-auto" style="flex: 1 1 auto;">
                            <form id="family-form" method="POST" action="{{ route('storeRegisterStep5') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $user_id }}">
                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label for="father_name" class="form-label">Father Name <span class="text-danger">*</span></label>
                                        <input type="text" id="father_name" name="father_name" class="form-control rounded-pill shadow-none @error('father_name') is-invalid @enderror" placeholder="Enter Father Name" value="{{ old('father_name', $userAndUserDetails->father_name ?? '') }}" required>
                                        @error('father_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="father_profession" class="form-label">Father Status</label>
                                        <select id="father_profession" name="father_profession" class="form-select rounded-pill shadow-none">
                                            <option value="">Select Father Status</option>
                                            <option value="Employed" {{ old('father_profession', $userAndUserDetails->father_profession ?? '') == 'Employed' ? 'selected' : '' }}>Employed</option>
                                            <option value="Not Working" {{ old('father_profession', $userAndUserDetails->father_profession ?? '') == 'Not Working' ? 'selected' : '' }}>Not Working</option>
                                            <option value="Passed Away" {{ old('father_profession', $userAndUserDetails->father_profession ?? '') == 'Passed Away' ? 'selected' : '' }}>Passed Away</option>
                                            @if(!empty($userAndUserDetails->father_profession) && !in_array(strtolower(trim($userAndUserDetails->father_profession)), ['employed', 'not working', 'passed away', 'passedaway']))
                                                <option value="{{ $userAndUserDetails->father_profession }}" selected>{{ $userAndUserDetails->father_profession }}</option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="mother_name" class="form-label">Mother Name <span class="text-danger">*</span></label>
                                        <input type="text" id="mother_name" name="mother_name" class="form-control rounded-pill shadow-none @error('mother_name') is-invalid @enderror" placeholder="Enter Mother Name" value="{{ old('mother_name', $userAndUserDetails->mother_name ?? '') }}" required>
                                        @error('mother_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="col-md-6">
                                        <label for="mother_profession" class="form-label">Mother Status</label>
                                        <select id="mother_profession" name="mother_profession" class="form-select rounded-pill shadow-none">
                                            <option value="">Select Mother Status</option>
                                            <option value="Employed" {{ old('mother_profession', $userAndUserDetails->mother_profession ?? '') == 'Employed' ? 'selected' : '' }}>Employed</option>
                                            <option value="Not Working" {{ old('mother_profession', $userAndUserDetails->mother_profession ?? '') == 'Not Working' ? 'selected' : '' }}>Not Working</option>
                                            <option value="Passed Away" {{ old('mother_profession', $userAndUserDetails->mother_profession ?? '') == 'Passed Away' ? 'selected' : '' }}>Passed Away</option>
                                            @if(!empty($userAndUserDetails->mother_profession) && !in_array(strtolower(trim($userAndUserDetails->mother_profession)), ['employed', 'not working', 'passed away', 'passedaway']))
                                                <option value="{{ $userAndUserDetails->mother_profession }}" selected>{{ $userAndUserDetails->mother_profession }}</option>
                                            @endif
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="family_type" class="form-label">Family Type</label>
                                        <select id="family_type" name="family_type" class="form-select rounded-pill shadow-none">
                                            <option value="">Select Family Type</option>
                                            <option value="Joint" {{ old('family_type') == 'Joint' ? 'selected' : '' }}>Joint</option>
                                            <option value="Nuclear" {{ old('family_type') == 'Nuclear' ? 'selected' : '' }}>Nuclear</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="family_status" class="form-label">Family Status</label>
                                        <select id="family_status" name="family_status" class="form-select rounded-pill shadow-none">
                                            <option value="">Select Family Status</option>
                                            <option value="Middle Class" {{ old('family_status') == 'Middle Class' ? 'selected' : '' }}>Middle Class</option>
                                            <option value="Upper Middle Class" {{ old('family_status') == 'Upper Middle Class' ? 'selected' : '' }}>Upper Middle Class</option>
                                            <option value="Rich" {{ old('family_status') == 'Rich' ? 'selected' : '' }}>Rich</option>
                                            <option value="Affluent" {{ old('family_status') == 'Affluent' ? 'selected' : '' }}>Affluent</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="family_values" class="form-label">Family Values</label>
                                        <select id="family_values" name="family_values" class="form-select rounded-pill shadow-none">
                                            <option value="">Select Family Values</option>
                                            <option value="Orthodox" {{ old('family_values') == 'Orthodox' ? 'selected' : '' }}>Orthodox</option>
                                            <option value="Traditional" {{ old('family_values') == 'Traditional' ? 'selected' : '' }}>Traditional</option>
                                            <option value="Moderate" {{ old('family_values') == 'Moderate' ? 'selected' : '' }}>Moderate</option>
                                            <option value="Liberal" {{ old('family_values') == 'Liberal' ? 'selected' : '' }}>Liberal</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="elder_brother" class="form-label">Elder Brothers</label>
                                        <select id="elder_brother" name="elder_brother" class="form-select rounded-pill shadow-none">
                                            <option value="">Select Elder Brothers</option>
                                            <option value="None" {{ old('elder_brother') == 'None' ? 'selected' : '' }}>None</option>
                                            <option value="1" {{ old('elder_brother') == '1' ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ old('elder_brother') == '2' ? 'selected' : '' }}>2</option>
                                            <option value="3" {{ old('elder_brother') == '3' ? 'selected' : '' }}>3</option>
                                            <option value="4" {{ old('elder_brother') == '4' ? 'selected' : '' }}>4</option>
                                            <option value="5" {{ old('elder_brother') == '5' ? 'selected' : '' }}>5</option>
                                            <option value="6" {{ old('elder_brother') == '6' ? 'selected' : '' }}>6</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="younger_brother" class="form-label">Younger Brothers</label>
                                        <select id="younger_brother" name="younger_brother" class="form-select rounded-pill shadow-none">
                                            <option value="">Select Younger Brothers</option>
                                            <option value="None" {{ old('younger_brother') == 'None' ? 'selected' : '' }}>None</option>
                                            <option value="1" {{ old('younger_brother') == '1' ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ old('younger_brother') == '2' ? 'selected' : '' }}>2</option>
                                            <option value="3" {{ old('younger_brother') == '3' ? 'selected' : '' }}>3</option>
                                            <option value="4" {{ old('younger_brother') == '4' ? 'selected' : '' }}>4</option>
                                            <option value="5" {{ old('younger_brother') == '5' ? 'selected' : '' }}>5</option>
                                            <option value="6" {{ old('younger_brother') == '6' ? 'selected' : '' }}>6</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="elder_married_brother" class="form-label">Elder Married Brothers</label>
                                        <select id="elder_married_brother" name="elder_married_brother" class="form-select rounded-pill shadow-none">
                                            <option value="">Select Elder Married Brothers</option>
                                            <option value="None" {{ old('elder_married_brother', $userAndUserDetails->elder_married_brother ?? '') == 'None' ? 'selected' : '' }}>None</option>
                                            <option value="1" {{ old('elder_married_brother', $userAndUserDetails->elder_married_brother ?? '') == '1' ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ old('elder_married_brother', $userAndUserDetails->elder_married_brother ?? '') == '2' ? 'selected' : '' }}>2</option>
                                            <option value="3" {{ old('elder_married_brother', $userAndUserDetails->elder_married_brother ?? '') == '3' ? 'selected' : '' }}>3</option>
                                            <option value="4" {{ old('elder_married_brother', $userAndUserDetails->elder_married_brother ?? '') == '4' ? 'selected' : '' }}>4</option>
                                            <option value="5" {{ old('elder_married_brother', $userAndUserDetails->elder_married_brother ?? '') == '5' ? 'selected' : '' }}>5</option>
                                            <option value="6" {{ old('elder_married_brother', $userAndUserDetails->elder_married_brother ?? '') == '6' ? 'selected' : '' }}>6</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="younger_married_brother" class="form-label">Younger Married Brothers</label>
                                        <select id="younger_married_brother" name="younger_married_brother" class="form-select rounded-pill shadow-none">
                                            <option value="">Select Younger Married Brothers</option>
                                            <option value="None" {{ old('younger_married_brother', $userAndUserDetails->younger_married_brother ?? '') == 'None' ? 'selected' : '' }}>None</option>
                                            <option value="1" {{ old('younger_married_brother', $userAndUserDetails->younger_married_brother ?? '') == '1' ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ old('younger_married_brother', $userAndUserDetails->younger_married_brother ?? '') == '2' ? 'selected' : '' }}>2</option>
                                            <option value="3" {{ old('younger_married_brother', $userAndUserDetails->younger_married_brother ?? '') == '3' ? 'selected' : '' }}>3</option>
                                            <option value="4" {{ old('younger_married_brother', $userAndUserDetails->younger_married_brother ?? '') == '4' ? 'selected' : '' }}>4</option>
                                            <option value="5" {{ old('younger_married_brother', $userAndUserDetails->younger_married_brother ?? '') == '5' ? 'selected' : '' }}>5</option>
                                            <option value="6" {{ old('younger_married_brother', $userAndUserDetails->younger_married_brother ?? '') == '6' ? 'selected' : '' }}>6</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="elder_sister" class="form-label">Elder Sisters</label>
                                        <select id="elder_sister" name="elder_sister" class="form-select rounded-pill shadow-none">
                                            <option value="">Select Elder Sisters</option>
                                            <option value="None" {{ old('elder_sister', $userAndUserDetails->elder_sister ?? '') == 'None' ? 'selected' : '' }}>None</option>
                                            <option value="1" {{ old('elder_sister', $userAndUserDetails->elder_sister ?? '') == '1' ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ old('elder_sister', $userAndUserDetails->elder_sister ?? '') == '2' ? 'selected' : '' }}>2</option>
                                            <option value="3" {{ old('elder_sister', $userAndUserDetails->elder_sister ?? '') == '3' ? 'selected' : '' }}>3</option>
                                            <option value="4" {{ old('elder_sister', $userAndUserDetails->elder_sister ?? '') == '4' ? 'selected' : '' }}>4</option>
                                            <option value="5" {{ old('elder_sister', $userAndUserDetails->elder_sister ?? '') == '5' ? 'selected' : '' }}>5</option>
                                            <option value="6" {{ old('elder_sister', $userAndUserDetails->elder_sister ?? '') == '6' ? 'selected' : '' }}>6</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="younger_sister" class="form-label">Younger Sisters</label>
                                        <select id="younger_sister" name="younger_sister" class="form-select rounded-pill shadow-none">
                                            <option value="">Select Younger Sisters</option>
                                            <option value="None" {{ old('younger_sister', $userAndUserDetails->younger_sister ?? '') == 'None' ? 'selected' : '' }}>None</option>
                                            <option value="1" {{ old('younger_sister', $userAndUserDetails->younger_sister ?? '') == '1' ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ old('younger_sister', $userAndUserDetails->younger_sister ?? '') == '2' ? 'selected' : '' }}>2</option>
                                            <option value="3" {{ old('younger_sister', $userAndUserDetails->younger_sister ?? '') == '3' ? 'selected' : '' }}>3</option>
                                            <option value="4" {{ old('younger_sister', $userAndUserDetails->younger_sister ?? '') == '4' ? 'selected' : '' }}>4</option>
                                            <option value="5" {{ old('younger_sister', $userAndUserDetails->younger_sister ?? '') == '5' ? 'selected' : '' }}>5</option>
                                            <option value="6" {{ old('younger_sister', $userAndUserDetails->younger_sister ?? '') == '6' ? 'selected' : '' }}>6</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="elder_married_sister" class="form-label">Elder Married Sisters</label>
                                        <select id="elder_married_sister" name="elder_married_sister" class="form-select rounded-pill shadow-none">
                                            <option value="">Select Elder Married Sisters</option>
                                            <option value="None" {{ old('elder_married_sister', $userAndUserDetails->elder_married_sister ?? '') == 'None' ? 'selected' : '' }}>None</option>
                                            <option value="1" {{ old('elder_married_sister', $userAndUserDetails->elder_married_sister ?? '') == '1' ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ old('elder_married_sister', $userAndUserDetails->elder_married_sister ?? '') == '2' ? 'selected' : '' }}>2</option>
                                            <option value="3" {{ old('elder_married_sister', $userAndUserDetails->elder_married_sister ?? '') == '3' ? 'selected' : '' }}>3</option>
                                            <option value="4" {{ old('elder_married_sister', $userAndUserDetails->elder_married_sister ?? '') == '4' ? 'selected' : '' }}>4</option>
                                            <option value="5" {{ old('elder_married_sister', $userAndUserDetails->elder_married_sister ?? '') == '5' ? 'selected' : '' }}>5</option>
                                            <option value="6" {{ old('elder_married_sister', $userAndUserDetails->elder_married_sister ?? '') == '6' ? 'selected' : '' }}>6</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="younger_married_sister" class="form-label">Younger Married Sisters</label>
                                        <select id="younger_married_sister" name="younger_married_sister" class="form-select rounded-pill shadow-none">
                                            <option value="">Select Younger Married Sisters</option>
                                            <option value="None" {{ old('younger_married_sister', $userAndUserDetails->younger_married_sister ?? '') == 'None' ? 'selected' : '' }}>None</option>
                                            <option value="1" {{ old('younger_married_sister', $userAndUserDetails->younger_married_sister ?? '') == '1' ? 'selected' : '' }}>1</option>
                                            <option value="2" {{ old('younger_married_sister', $userAndUserDetails->younger_married_sister ?? '') == '2' ? 'selected' : '' }}>2</option>
                                            <option value="3" {{ old('younger_married_sister', $userAndUserDetails->younger_married_sister ?? '') == '3' ? 'selected' : '' }}>3</option>
                                            <option value="4" {{ old('younger_married_sister', $userAndUserDetails->younger_married_sister ?? '') == '4' ? 'selected' : '' }}>4</option>
                                            <option value="5" {{ old('younger_married_sister', $userAndUserDetails->younger_married_sister ?? '') == '5' ? 'selected' : '' }}>5</option>
                                            <option value="6" {{ old('younger_married_sister', $userAndUserDetails->younger_married_sister ?? '') == '6' ? 'selected' : '' }}>6</option>
                                        </select>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label fw-medium">Property Details <span class="small text-muted fw-normal">(Enter number of properties e.g. 1, 2, 3)</span></label>
                                        @php
                                            $rawPropertyDetails = old('property_details', $userAndUserDetails->property_details ?? '');
                                            $selectedProperties = \App\Http\Controllers\Helpers\DataController::parsePropertyDetails($rawPropertyDetails);
                                        @endphp
                                        <div class="row g-2">
                                            @foreach($db['propertyDetails'] as $propertyDetail)
                                                <div class="col-lg-3 col-md-4 col-sm-6">
                                                    <div class="border rounded-3 p-2 bg-light bg-opacity-50 h-100">
                                                        <label for="prop_{{ $loop->index }}" class="form-label small fw-semibold text-truncate d-block mb-1" title="{{ $propertyDetail->name }}">{{ $propertyDetail->name }}</label>
                                                        <input type="number" min="0" step="1" name="property_details[{{ $propertyDetail->name }}]" id="prop_{{ $loop->index }}"
                                                               value="{{ old('property_details.' . $propertyDetail->name, $selectedProperties[$propertyDetail->name] ?? '') }}"
                                                               class="form-control form-control-sm rounded-pill shadow-none"
                                                               placeholder="e.g. 1, 2">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <label for="property_info" class="form-label">Additional Property Details</label>
                                        <textarea name="property_info" id="property_info" rows="3" class="form-control rounded-4 shadow-none" placeholder="Enter additional property details...">{{ old('property_info', $userAndUserDetails->property_info ?? '') }}</textarea>
                                    </div>

                                </div>

                            </form>
                        </div>

                        <div class="bg-white border-0 p-3 d-flex justify-content-between align-items-center shadow-top sticky-bottom">
                            <form action="{{ route('logout') }}" method="POST" class="m-0">
                                @csrf
                                <button type="submit" class="btn button2 rounded-pill">Logout</button>
                            </form>
                            <div class="d-flex gap-2">
{{--                                <a href="{{ url('educationJob-details') }}" class="btn button1 rounded-pill">Back</a>--}}
                                <button form="family-form" type="submit" class="btn button2 rounded-pill">Save & Next</button>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>
    @include('web.includes.footer')
@endsection
