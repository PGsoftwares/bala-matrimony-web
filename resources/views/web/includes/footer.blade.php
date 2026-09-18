<style>
    .social-links {
        display: flex;
        gap: 15px;
    }

    .social-links a {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        background: #c8102e;
        color: #ffffff;
        border-radius: 50%;
        text-decoration: none;
        transition: all 0.3s ease;
    }

    .social-links a:hover {
        background: #0d6b38;
        color: #ffffff;
        transform: translateY(-2px);
    }
</style>
<div class="container p-5">
    <div class="row">
        <div class="col-lg-12" style="max-width: 800px; margin: 0 auto;">
            <h4 class="text-center fw-bold" style="color: #c8102e;">Matrimony Purpose Only</h4>
            <p class="text-dark text-center">Bala Matrimony Bureau is exclusively a matrimonial platform created to help individuals and families find suitable marriage partners. This website is not a dating or casual relationship platform and is intended solely for genuine matrimonial purposes.</p>
        </div>
    </div>
</div>

<div class="container-fluid p-4 footer" style="background-color: #094d27;">
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="footer-section p-3">
                <img src="{{ asset('asset/img/logo/pg.webp') }}" alt="Bala Matrimony Bureau" width="150px" >
                <br><br>
                <p  class="text-white">Connecting hearts across India with traditional values and modern approach.</p>
                
                <div class="social-links p-2">
                    <a href="https://www.facebook.com/share/1ApkrBb6qe/" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                    <a href="https://www.instagram.com/balamatrimonybureau?utm_source=qr&igsi=MTk4OWFsZWk1czIxeQ==" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                    <a href="https://youtube.com/@balathidumal?si=ZNOgk2eakjqPUC_s" target="_blank" rel="noopener" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                </div>
                
                <div class="p-2 pt-1 pb-3">
                    <a href="#" target="_blank" rel="noopener">
                        <img src="{{ asset('asset/img/icon/google-play.webp') }}" alt="Get it on Google Play" style="height: 50px; width: auto;" class="img-fluid rounded">
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="footer-section p-3">
                <h4 class="text-white">Quick Links</h4>
                <ul class="list-unstyled">
                    <li><a class="text-white" href="{{ url('payment-plans') }}">Payment Plans</a></li>
                    <li><a class="text-white" href="{{ url('about-us') }}">About Us</a></li>
                    <li><a class="text-white" href="{{ url('contact-us') }}">Contact Us</a></li>
                    <li><a class="text-white" href="{{ url('privacy-policy') }}">Privacy Policy</a></li>
                    <li><a class="text-white" href="{{ url('terms-and-condition') }}">Terms & Conditions</a></li>
                    <li><a class="text-white" href="{{ url('refund-policy') }}">Refund Policy</a></li>
                    <li><a class="text-white" href="{{ url('help') }}">Help</a></li>
                </ul>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="footer-section p-3">
                <h4 class="text-white">Cities</h4>
                <ul class="list-unstyled">
                    <li><a class="text-white" href="{{ url('home-search?gender=&age=&religion=&caste=&city=Coimbatore') }}">Coimbatore</a></li>
                    <li><a class="text-white" href="{{ url('home-search?gender=&age=&religion=&caste=&city=Chennai') }}">Chennai</a></li>
                    <li><a class="text-white" href="{{ url('home-search?gender=&age=&religion=&caste=&city=Madurai') }}">Madurai</a></li>
                    <li><a class="text-white" href="{{ url('home-search?gender=&age=&religion=&caste=&city=Tirunelveli') }}">Tirunelveli</a></li>
                    <li><a class="text-white" href="{{ url('home-search?gender=&age=&religion=&caste=&city=Thiruchirapalli') }}">Thiruchirapalli</a></li>
                    <li><a class="text-white" href="{{ url('home-search?gender=&age=&religion=&caste=&city=Erode') }}">Erode</a></li>
                    <li><a class="text-white" href="{{ url('home-search?gender=&age=&religion=&caste=&city=Salem') }}">Salem</a></li>
                    <li><a class="text-white" href="{{ url('home-search?gender=&age=&religion=&caste=&city=Namakkal') }}">Namakkal</a></li>
                    <li><a class="text-white" href="{{ url('home-search?gender=&age=&religion=&caste=&city=') }}">All Over Tamilnadu</a></li>
                </ul>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="footer-section p-3">
                <h4 class="text-white">Contact Info</h4>
                <div class="contact-info">
                    <p><i class="bi bi-telephone-fill text-white me-2"></i>
                        <a class="text-white" href="tel:{{ $db['contactInfo']->mobile_1 ?? '' }}">{{ $db['contactInfo']->mobile_1 ?? '' }}</a>
                        @if($db['contactInfo']->mobile_2 ?? '')
                            , <a class="text-white" href="tel:{{ $db['contactInfo']->mobile_2 }}">{{ $db['contactInfo']->mobile_2 }}</a>
                        @endif
                    </p>
                    <p><i class="bi bi-envelope-fill text-white me-2"></i> <a class="text-white" href="mailto:{{ $db['contactInfo']->email ?? '' }}">{{ $db['contactInfo']->email ?? '' }}</a></p>
                    <p class="text-white"><i class="bi bi-geo-alt-fill text-white me-2"></i> {{ $db['contactInfo']->address ?? '' }}</p>
                    <div class="text-white mt-3">
                        <p class="mb-1"><i class="bi bi-clock-fill text-white me-2"></i><strong>Office Time:</strong></p>
                        <p class="mb-1 small" style="padding-left: 24px;">Mon - Sat: 9.00am to 7.00pm</p>
                        <p class="mb-0 small" style="padding-left: 24px;">Sun: 10.00am to 5.00pm</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="d-flex justify-content-center">
        <img class="" src="{{ asset('asset/img/footer-img.png') }}" alt="" style="max-width: 100%">
    </div>

    <hr>
    <div class="row-cols-md-2 d-md-flex justify-content-md-between">
        <p class="text-white">&copy; <script>document.write(new Date().getFullYear().toString())</script> Bala Matrimony Bureau. All rights reserved.</p>
        <p class="text-white text-end">Designed by <a style="color: #FFFFFF" href="https://www.pgsoftwares.com/" target="_blank" rel="noopener">PG Softwares</a></p>
    </div>
</div>
