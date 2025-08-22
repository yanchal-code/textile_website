@php use Illuminate\Support\Str; @endphp
<div class="container">
    <div class="h5 text-center mb-4"><span class="px-3 border-bottom">Application Configurations</span></div>
    <ul class="nav nav-tabs" id="configTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="mail-tab" data-bs-toggle="tab" data-bs-target="#mail" type="button"
                role="tab" aria-controls="mail" aria-selected="true">Mail</button>
        </li>

        <li class="nav-item" role="presentation">
            <button class="nav-link" id="mailChimp-tab" data-bs-toggle="tab" data-bs-target="#mailChimp" type="button"
                role="tab" aria-controls="mailChimp" aria-selected="false">MailChimp</button>
        </li>


        <li class="nav-item" role="presentation">
            <button class="nav-link" id="paypal-tab" data-bs-toggle="tab" data-bs-target="#paypal" type="button"
                role="tab" aria-controls="paypal" aria-selected="false">PayPal</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="phonepe-tab" data-bs-toggle="tab" data-bs-target="#phonepe" type="button"
                role="tab" aria-controls="phonepe" aria-selected="false">PhonePe</button>
        </li>

        <!--<li class="nav-item" role="presentation">-->
        <!--    <button class="nav-link" id="vimeo-tab" data-bs-toggle="tab" data-bs-target="#vimeo" type="button"-->
        <!--        role="tab" aria-controls="vimeo" aria-selected="false">Vimeo</button>-->
        <!--</li>-->
         <li class="nav-item" role="presentation">
            <button class="nav-link" id="gemeni-tab" data-bs-toggle="tab" data-bs-target="#gemini" type="button"
                role="tab" aria-controls="gemini" aria-selected="false">Gemini</button>
        </li>
    </ul>
    <div class="tab-content mt-4" id="configTabsContent">
        <!-- Mail Config -->
        <div class="tab-pane fade show active" id="mail" role="tabpanel" aria-labelledby="mail-tab">
            <form class="custom-form" action="{{ route('env.update') }}" method="POST">
                @csrf
                <div class="row g-3">
                    @foreach ($envVariables as $key => $value)
                        @if (in_array($key, [
                                'MAIL_HOST',
                                'MAIL_PORT',
                                'MAIL_USERNAME',
                                'MAIL_PASSWORD',
                                'MAIL_ENCRYPTION',
                                'MAIL_FROM_ADDRESS',
                                'MAIL_FROM_NAME',
                            ]))
                            <div class="col-md-6">
                                <label for="{{ $key }}"
                                    class="form-label">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
                                <input type="text" id="{{ $key }}" name="{{ $key }}"
                                    class="form-control" value="{{ Str::of($value)->trim('"') }}">
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary">Save Mail Config</button>
                </div>
            </form>
        </div>

        <!-- MailChimp Config -->
        <div class="tab-pane fade" id="mailChimp" role="tabpanel" aria-labelledby="mailChimp-tab">
            <form class="custom-form" action="{{ route('env.update') }}" method="POST">
                @csrf
                <div class="row g-3">
                    @foreach ($envVariables as $key => $value)
                        @if (in_array($key, ['MAILCHIMP_API_KEY', 'MAILCHIMP_SERVER_PREFIX', 'MAILCHIMP_AUDIENCE_ID']))
                            <div class="col-md-6">
                                <label for="{{ $key }}"
                                    class="form-label">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
                                <input type="text" id="{{ $key }}" name="{{ $key }}"
                                    class="form-control" value="{{ Str::of($value)->trim('"') }}">
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary">Save MailChimp Config</button>
                </div>
            </form>
        </div>

        <!-- PayPal Config -->
        <div class="tab-pane fade" id="paypal" role="tabpanel" aria-labelledby="paypal-tab">
            <form class="custom-form" action="{{ route('env.update') }}" method="POST">
                @csrf
                <div class="row g-3">
                    @foreach ($envVariables as $key => $value)
                        @if (in_array($key, [
                                'PAYPAL_SANDBOX_CLIENT_ID',
                                'PAYPAL_SANDBOX_CLIENT_SECRET',
                                'PAYPAL_LIVE_CLIENT_ID',
                                'PAYPAL_LIVE_CLIENT_SECRET',
                            ]))
                            <div class="col-md-6">
                                <label for="{{ $key }}"
                                    class="form-label">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
                                <input type="text" id="{{ $key }}" name="{{ $key }}"
                                    class="form-control" value="{{ Str::of($value)->trim('"') }}">
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary">Save PayPal Config</button>
                </div>
            </form>
        </div>

        <!-- PhonePe Config -->
        <div class="tab-pane fade" id="phonepe" role="tabpanel" aria-labelledby="phonepe-tab">
            <form class="custom-form" action="{{ route('env.update') }}" method="POST">
                @csrf
                <div class="row g-3">
                    @foreach ($envVariables as $key => $value)
                      @if (in_array($key, ['PHONEPE_ENV','PHONEPE_MERCHANT_ID','PHONEPE_CLIENT_ID','PHONEPE_CLIENT_SECRET','PHONEPE_CLIENT_VERSION']))
                            <div class="col-md-6">
                                <label for="{{ $key }}"
                                    class="form-label">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
                                <input type="text" id="{{ $key }}" name="{{ $key }}"
                                    class="form-control" value="{{ Str::of($value)->trim('"') }}">
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary">Save PhonePe Config</button>
                </div>
            </form>
        </div>


        <!-- Vimeo Config -->
        <!--<div class="tab-pane fade" id="vimeo" role="tabpanel" aria-labelledby="vimeo-tab">-->
        <!--    <form class="custom-form" action="{{ route('env.update') }}" method="POST">-->
        <!--        @csrf-->
        <!--        <div class="row g-3">-->
        <!--            @foreach ($envVariables as $key => $value)-->
        <!--                @if (in_array($key, ['VIMEO_CLIENT_ID', 'VIMEO_CLIENT_SECRET', 'VIMEO_ACCESS_TOKEN']))-->
        <!--                    <div class="col-md-6">-->
        <!--                        <label for="{{ $key }}"-->
        <!--                            class="form-label">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>-->
        <!--                        <input type="text" id="{{ $key }}" name="{{ $key }}"-->
        <!--                            class="form-control" value="{{ Str::of($value)->trim('"') }}">-->
        <!--                    </div>-->
        <!--                @endif-->
        <!--            @endforeach-->
        <!--        </div>-->
        <!--        <div class="mt-4 text-center">-->
        <!--            <button type="submit" class="btn btn-primary">Save PhonePe Config</button>-->
        <!--        </div>-->
        <!--    </form>-->
        <!--</div>-->

        <!-- Gemini Config -->
        <div class="tab-pane fade" id="gemini" role="tabpanel" aria-labelledby="gemini-tab">
            <form class="custom-form" action="{{ route('env.update') }}" method="POST">
                @csrf
                <div class="row g-3">
                    @foreach ($envVariables as $key => $value)
                        @if (in_array($key, ['GEMINI_API_KEY']))
                            <div class="col-md-6">
                                <label for="{{ $key }}"
                                    class="form-label">{{ ucfirst(str_replace('_', ' ', $key)) }}</label>
                                <input type="text" id="{{ $key }}" name="{{ $key }}"
                                    class="form-control" value="{{ Str::of($value)->trim('"') }}">
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-primary">Save Gemini Config</button>
                </div>
            </form>
        </div>

    </div>
</div>
