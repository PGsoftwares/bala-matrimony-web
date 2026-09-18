<section class="col-md-4 mt-md-4 mb-5">

    <div class="row justify-content-center">
        {{-- Advertisement --}}
        @foreach($db['advertisements'] as $advertisement)
            @if($advertisement->add_3)
                <div class="mb-4 text-center">
                    <div class="card shadow-sm border-0">
                        <img src="{{ asset('Advertisement/' . $advertisement->add_3) }}"
                             alt="Advertisement"
                             class="img-fluid rounded w-100"
                        >
                    </div>
                </div>
            @endif

            @if($advertisement->add_4)
                <div class="mb-4 text-center">
                    <div class="card shadow-sm border-0">
                        <img src="{{ asset('Advertisement/' . $advertisement->add_4) }}"
                             alt="Advertisement"
                             class="img-fluid rounded w-100"
                        >
                    </div>
                </div>
            @endif
        @endforeach
        {{-- End: Advertisement --}}
    </div>

</section>
