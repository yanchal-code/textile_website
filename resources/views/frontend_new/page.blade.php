@extends('frontend_new.layouts.main')

@section('content')
<main class="main__content_wrapper">

    <!-- Breadcrumb Section -->
    <!-- Breadcrumb Section -->
     <section class="breadcrumb__section breadcrumb__bg">
     <div class="container">
          <div class="row">
               <div class="col text-center">
                    <h1 class="breadcrumb__content--title text-white mb-25">
                         @if($page->slug == 'contact-us') Contact Us @else {{ $page->name }} @endif
                    </h1>
                    <ul class="breadcrumb__content--menu d-flex justify-content-center">
                         <li><a class="text-white" href="/">Home/</a></li>
                         <li class="breadcrumb-item text-white">pages/</li>
                         <li><span class="text-white">
                         @if($page->slug == 'contact-us') contact-us @else {{ $page->slug }} @endif
                         </span></li>
                    </ul>
               </div>
          </div>
     </div>
     </section>
    @if($page->slug == 'contact-us')
    <!-- Contact Page Section -->
    <section class="contact__section section--padding">
        <div class="container">
            <div class="section__heading text-center mb-40">
                <h2 class="section__heading--maintitle">Get In Touch</h2>
            </div>
            <div class="main__contact--area position__relative d-flex flex-wrap gap-5">
                <!-- Contact Form -->
                <div class="contact__form flex-grow-1">
                    <h3 class="contact__form--title mb-40">Contact Me</h3>
                    <form id="contactFormFront" class="contact__form--inner needs-validation" novalidate>
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 mb-20">
                                <label class="contact__form--label" for="input1">Name <span>*</span></label>
                                <input class="contact__form--input" name="name" id="input1" type="text" placeholder="Your Name" required>
                            </div>
                            <div class="col-lg-6 mb-20">
                                <label class="contact__form--label" for="input2">Email <span>*</span></label>
                                <input class="contact__form--input" name="email" id="input2" type="email" placeholder="Your Email" required>
                            </div>
                            <div class="col-lg-6 mb-20">
                                <label class="contact__form--label" for="input3">Subject <span>*</span></label>
                                <input class="contact__form--input" name="subject" id="input3" type="text" placeholder="Subject" required>
                            </div>
                            <div class="col-12 mb-20">
                                <label class="contact__form--label" for="input4">Message <span>*</span></label>
                                <textarea class="contact__form--textarea" name="message" id="input4" rows="6" placeholder="Write Your Message" required></textarea>
                            </div>
                            <div class="col-12 text-center">
                                <div id="contactFormSpinner"></div>
                                <button type="submit" class="contact__form--btn primary__btn">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Contact Info Boxes -->
                <div class="contact__info border-radius-5 flex-grow-1">
                    <div class="contact__info--items mb-20">
                        <h3 class="contact__info--content__title text-white mb-15">Contact Us</h3>
                        <div class="contact__info--items__inner d-flex">
                            <div class="contact__info--icon"><i class="bi bi-telephone"></i></div>
                            <div class="contact__info--content">
                                <p class="text-white">Call us at <a href="tel:+{{ config('settings.phone') }}">{{ config('settings.phone') }}</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="contact__info--items mb-20">
                        <h3 class="contact__info--content__title text-white mb-15">Email Address</h3>
                        <div class="contact__info--items__inner d-flex">
                            <div class="contact__info--icon"><i class="bi bi-envelope"></i></div>
                            <div class="contact__info--content">
                                <p class="text-white"><a href="mailto:{{ config('settings.email') }}">{{ config('settings.email') }}</a></p>
                            </div>
                        </div>
                    </div>
                    <div class="contact__info--items mb-20">
                        <h3 class="contact__info--content__title text-white mb-15">Office Location</h3>
                        <div class="contact__info--items__inner d-flex">
                            <div class="contact__info--icon"><i class="bi bi-geo-alt"></i></div>
                            <div class="contact__info--content">
                                <p class="text-white">{{ config('settings.address') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Map Section -->
    <div class="col-lg-12" data-aos="fade-up" data-aos-delay="300">
                         <iframe
                              src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d48389.78314118045!2d-74.006138!3d40.710059!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c25a22a3bda30d%3A0xb89d1fe6bc499443!2sDowntown%20Conference%20Center!5e0!3m2!1sen!2sus!4v1676961268712!5m2!1sen!2sus"
                              frameborder="0" style="border:0; width: 100%; height: 400px;" allowfullscreen="" loading="lazy"
                              referrerpolicy="no-referrer-when-downgrade">
                         </iframe>
     </div>

    @else
    
    <!-- Normal Page Section -->
    <section class="flat-spacing-25">
        <div class="container mb-5 mt-5">
            <div class="tf-main-area-page">
                {!! $page->content !!}
            </div>
        </div>
    </section>
    @endif

</main>
@endsection

@section('scripts')
<script>
$('#contactFormFront').submit(function(e){
    e.preventDefault();
    var form = $(this);
    if(form[0].checkValidity() === true){
        var formData = new FormData(this);
        $.ajax({
            type:"POST",
            url:"{{ route('front.sendContactEmail') }}",
            data:formData,
            headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            processData:false,
            contentType:false,
            dataType:"json",
            beforeSend:function(){ $('#contactFormSpinner').html('<span class="loader"></span> Loading...'); },
            success:function(response){
                $('#contactFormSpinner').html('');
                if(response.status){
                    form[0].reset();
                    showNotification('Message sent successfully','success');
                }else{
                    var errorsHtml = '';
                    $.each(response.errors, function(k, v){ errorsHtml += '<p>'+v[0]+'</p>'; });
                    showNotification(errorsHtml,'danger','html');
                }
            },
            error:function(){ $('#contactFormSpinner').html(''); showNotification('Something went wrong!','danger'); }
        });
    }else{ form.addClass('was-validated'); }
});
</script>
@endsection
