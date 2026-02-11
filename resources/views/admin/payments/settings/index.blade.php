@extends("admin.layouts.dashboard")
@section("content")
<div class="row">
    <div class="col-md-4 col-lg-3 mb-4 mb-md-0">
        <div class="card">
            <div class="card-body">
                <div class="setting-tabs nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a href="?tab=currency" class="nav-link active" id="v-pills-currency-tab" data-bs-toggle="pill" 
                        data-bs-target="#v-pills-currency" role="tab" aria-controls="v-pills-currency" aria-selected="true">{{ __('lang.currency') }}</a>
                    <a href="?tab=gateways" class="nav-link" id="v-pills-gateways-tab" data-bs-toggle="pill" 
                        data-bs-target="#v-pills-gateways" role="tab" aria-controls="v-pills-gateways" aria-selected="false">{{ __('lang.gateway') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8 col-lg-9">
        @if ($errors->any())
            <div class="alert alert-1 alert-dismissible fade show" role="alert">
                @foreach ($errors->all() as $error)
                    <p class="m-0">{{ $error }}</p>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-2 alert-dismissible fade show" role="alert">
                <p class="m-0">{{ session('success') }}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-1 alert-dismissible fade show" role="alert">
                <p class="m-0">{{ session('error') }}</p>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="tab-content" id="v-pills-tabContent">
            <div class="tab-pane fade show active" id="v-pills-currency" role="tabpanel" aria-labelledby="v-pills-currency-tab" tabindex="0">
                <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/payments/settings') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.currency_settings') }}</div>
                            <div class="row">
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="currency-name" class="form-label">{{ __('lang.currency') }}</label>
                                    <select class="form-select" name="currency-name" id="currency-name" aria-label="Currency">
                                        <option value="USD"{{ paymentSetting('currency_name') == 'USD' ? ' selected' : '' }}>USD</option>
                                        <option value="EUR"{{ paymentSetting('currency_name') == 'EUR' ? ' selected' : '' }}>EUR</option>
                                        <option value="AED"{{ paymentSetting('currency_name') == 'AED' ? ' selected' : '' }}>AED</option>
                                        <option value="AFN"{{ paymentSetting('currency_name') == 'AFN' ? ' selected' : '' }}>AFN*</option>
                                        <option value="ALL"{{ paymentSetting('currency_name') == 'ALL' ? ' selected' : '' }}>ALL</option>
                                        <option value="AMD"{{ paymentSetting('currency_name') == 'AMD' ? ' selected' : '' }}>AMD</option>
                                        <option value="ANG"{{ paymentSetting('currency_name') == 'ANG' ? ' selected' : '' }}>ANG</option>
                                        <option value="AOA"{{ paymentSetting('currency_name') == 'AOA' ? ' selected' : '' }}>AOA*</option>
                                        <option value="ARS"{{ paymentSetting('currency_name') == 'ARS' ? ' selected' : '' }}>ARS*</option>
                                        <option value="AUD"{{ paymentSetting('currency_name') == 'AUD' ? ' selected' : '' }}>AUD</option>
                                        <option value="AWG"{{ paymentSetting('currency_name') == 'AWG' ? ' selected' : '' }}>AWG</option>
                                        <option value="AZN"{{ paymentSetting('currency_name') == 'AZN' ? ' selected' : '' }}>AZN</option>
                                        <option value="BAM"{{ paymentSetting('currency_name') == 'BAM' ? ' selected' : '' }}>BAM</option>
                                        <option value="BBD"{{ paymentSetting('currency_name') == 'BBD' ? ' selected' : '' }}>BBD</option>
                                        <option value="BDT"{{ paymentSetting('currency_name') == 'BDT' ? ' selected' : '' }}>BDT</option>
                                        <option value="BGN"{{ paymentSetting('currency_name') == 'BGN' ? ' selected' : '' }}>BGN</option>
                                        <option value="BIF"{{ paymentSetting('currency_name') == 'BIF' ? ' selected' : '' }}>BIF</option>
                                        <option value="BMD"{{ paymentSetting('currency_name') == 'BMD' ? ' selected' : '' }}>BMD</option>
                                        <option value="BND"{{ paymentSetting('currency_name') == 'BND' ? ' selected' : '' }}>BND</option>
                                        <option value="BOB"{{ paymentSetting('currency_name') == 'BOB' ? ' selected' : '' }}>BOB*</option>
                                        <option value="BRL"{{ paymentSetting('currency_name') == 'BRL' ? ' selected' : '' }}>BRL*</option>
                                        <option value="BSD"{{ paymentSetting('currency_name') == 'BSD' ? ' selected' : '' }}>BSD</option>
                                        <option value="BWP"{{ paymentSetting('currency_name') == 'BWP' ? ' selected' : '' }}>BWP</option>
                                        <option value="BYN"{{ paymentSetting('currency_name') == 'BYN' ? ' selected' : '' }}>BYN</option>
                                        <option value="BZD"{{ paymentSetting('currency_name') == 'BZD' ? ' selected' : '' }}>BZD</option>
                                        <option value="CAD"{{ paymentSetting('currency_name') == 'CAD' ? ' selected' : '' }}>CAD</option>
                                        <option value="CDF"{{ paymentSetting('currency_name') == 'CDF' ? ' selected' : '' }}>CDF</option>
                                        <option value="CHF"{{ paymentSetting('currency_name') == 'CHF' ? ' selected' : '' }}>CHF</option>
                                        <option value="CLP"{{ paymentSetting('currency_name') == 'CLP' ? ' selected' : '' }}>CLP*</option>
                                        <option value="CNY"{{ paymentSetting('currency_name') == 'CNY' ? ' selected' : '' }}>CNY</option>
                                        <option value="COP"{{ paymentSetting('currency_name') == 'COP' ? ' selected' : '' }}>COP*</option>
                                        <option value="CRC"{{ paymentSetting('currency_name') == 'CRC' ? ' selected' : '' }}>CRC*</option>
                                        <option value="CVE"{{ paymentSetting('currency_name') == 'CVE' ? ' selected' : '' }}>CVE*</option>
                                        <option value="CZK"{{ paymentSetting('currency_name') == 'CZK' ? ' selected' : '' }}>CZK</option>
                                        <option value="DJF"{{ paymentSetting('currency_name') == 'DJF' ? ' selected' : '' }}>DJF*</option>
                                        <option value="DKK"{{ paymentSetting('currency_name') == 'DKK' ? ' selected' : '' }}>DKK</option>
                                        <option value="DOP"{{ paymentSetting('currency_name') == 'DOP' ? ' selected' : '' }}>DOP</option>
                                        <option value="DZD"{{ paymentSetting('currency_name') == 'DZD' ? ' selected' : '' }}>DZD</option>
                                        <option value="EGP"{{ paymentSetting('currency_name') == 'EGP' ? ' selected' : '' }}>EGP</option>
                                        <option value="ETB"{{ paymentSetting('currency_name') == 'ETB' ? ' selected' : '' }}>ETB</option>
                                        <option value="FJD"{{ paymentSetting('currency_name') == 'FJD' ? ' selected' : '' }}>FJD</option>
                                        <option value="FKP"{{ paymentSetting('currency_name') == 'FKP' ? ' selected' : '' }}>FKP*</option>
                                        <option value="GBP"{{ paymentSetting('currency_name') == 'GBP' ? ' selected' : '' }}>GBP</option>
                                        <option value="GEL"{{ paymentSetting('currency_name') == 'GEL' ? ' selected' : '' }}>GEL</option>
                                        <option value="GIP"{{ paymentSetting('currency_name') == 'GIP' ? ' selected' : '' }}>GIP</option>
                                        <option value="GMD"{{ paymentSetting('currency_name') == 'GMD' ? ' selected' : '' }}>GMD</option>
                                        <option value="GNF"{{ paymentSetting('currency_name') == 'GNF' ? ' selected' : '' }}>GNF*</option>
                                        <option value="GTQ"{{ paymentSetting('currency_name') == 'GTQ' ? ' selected' : '' }}>GTQ*</option>
                                        <option value="GYD"{{ paymentSetting('currency_name') == 'GYD' ? ' selected' : '' }}>GYD</option>
                                        <option value="HKD"{{ paymentSetting('currency_name') == 'HKD' ? ' selected' : '' }}>HKD</option>
                                        <option value="HNL"{{ paymentSetting('currency_name') == 'HNL' ? ' selected' : '' }}>HNL*</option>
                                        <option value="HTG"{{ paymentSetting('currency_name') == 'HTG' ? ' selected' : '' }}>HTG</option>
                                        <option value="HUF"{{ paymentSetting('currency_name') == 'HUF' ? ' selected' : '' }}>HUF</option>
                                        <option value="IDR"{{ paymentSetting('currency_name') == 'IDR' ? ' selected' : '' }}>IDR</option>
                                        <option value="ILS"{{ paymentSetting('currency_name') == 'ILS' ? ' selected' : '' }}>ILS</option>
                                        <option value="INR"{{ paymentSetting('currency_name') == 'INR' ? ' selected' : '' }}>INR</option>
                                        <option value="ISK"{{ paymentSetting('currency_name') == 'ISK' ? ' selected' : '' }}>ISK</option>
                                        <option value="JMD"{{ paymentSetting('currency_name') == 'JMD' ? ' selected' : '' }}>JMD</option>
                                        <option value="JPY"{{ paymentSetting('currency_name') == 'JPY' ? ' selected' : '' }}>JPY</option>
                                        <option value="KES"{{ paymentSetting('currency_name') == 'KES' ? ' selected' : '' }}>KES</option>
                                        <option value="KGS"{{ paymentSetting('currency_name') == 'KGS' ? ' selected' : '' }}>KGS</option>
                                        <option value="KHR"{{ paymentSetting('currency_name') == 'KHR' ? ' selected' : '' }}>KHR</option>
                                        <option value="KMF"{{ paymentSetting('currency_name') == 'KMF' ? ' selected' : '' }}>KMF</option>
                                        <option value="KRW"{{ paymentSetting('currency_name') == 'KRW' ? ' selected' : '' }}>KRW</option>
                                        <option value="KYD"{{ paymentSetting('currency_name') == 'KYD' ? ' selected' : '' }}>KYD</option>
                                        <option value="KZT"{{ paymentSetting('currency_name') == 'KZT' ? ' selected' : '' }}>KZT</option>
                                        <option value="LAK"{{ paymentSetting('currency_name') == 'LAK' ? ' selected' : '' }}>LAK*</option>
                                        <option value="LBP"{{ paymentSetting('currency_name') == 'LBP' ? ' selected' : '' }}>LBP</option>
                                        <option value="LKR"{{ paymentSetting('currency_name') == 'LKR' ? ' selected' : '' }}>LKR</option>
                                        <option value="LRD"{{ paymentSetting('currency_name') == 'LRD' ? ' selected' : '' }}>LRD</option>
                                        <option value="LSL"{{ paymentSetting('currency_name') == 'LSL' ? ' selected' : '' }}>LSL</option>
                                        <option value="MAD"{{ paymentSetting('currency_name') == 'MAD' ? ' selected' : '' }}>MAD</option>
                                        <option value="MDL"{{ paymentSetting('currency_name') == 'MDL' ? ' selected' : '' }}>MDL</option>
                                        <option value="MGA"{{ paymentSetting('currency_name') == 'MGA' ? ' selected' : '' }}>MGA</option>
                                        <option value="MKD"{{ paymentSetting('currency_name') == 'MKD' ? ' selected' : '' }}>MKD</option>
                                        <option value="MMK"{{ paymentSetting('currency_name') == 'MMK' ? ' selected' : '' }}>MMK</option>
                                        <option value="MNT"{{ paymentSetting('currency_name') == 'MNT' ? ' selected' : '' }}>MNT</option>
                                        <option value="MOP"{{ paymentSetting('currency_name') == 'MOP' ? ' selected' : '' }}>MOP</option>
                                        <option value="MUR"{{ paymentSetting('currency_name') == 'MUR' ? ' selected' : '' }}>MUR*</option>
                                        <option value="MVR"{{ paymentSetting('currency_name') == 'MVR' ? ' selected' : '' }}>MVR</option>
                                        <option value="MWK"{{ paymentSetting('currency_name') == 'MWK' ? ' selected' : '' }}>MWK</option>
                                        <option value="MXN"{{ paymentSetting('currency_name') == 'MXN' ? ' selected' : '' }}>MXN</option>
                                        <option value="MYR"{{ paymentSetting('currency_name') == 'MYR' ? ' selected' : '' }}>MYR</option>
                                        <option value="MZN"{{ paymentSetting('currency_name') == 'MZN' ? ' selected' : '' }}>MZN</option>
                                        <option value="NAD"{{ paymentSetting('currency_name') == 'NAD' ? ' selected' : '' }}>NAD</option>
                                        <option value="NGN"{{ paymentSetting('currency_name') == 'NGN' ? ' selected' : '' }}>NGN</option>
                                        <option value="NIO"{{ paymentSetting('currency_name') == 'NIO' ? ' selected' : '' }}>NIO*</option>
                                        <option value="NOK"{{ paymentSetting('currency_name') == 'NOK' ? ' selected' : '' }}>NOK</option>
                                        <option value="NPR"{{ paymentSetting('currency_name') == 'NPR' ? ' selected' : '' }}>NPR</option>
                                        <option value="NZD"{{ paymentSetting('currency_name') == 'NZD' ? ' selected' : '' }}>NZD</option>
                                        <option value="PAB"{{ paymentSetting('currency_name') == 'PAB' ? ' selected' : '' }}>PAB*</option>
                                        <option value="PEN"{{ paymentSetting('currency_name') == 'PEN' ? ' selected' : '' }}>PEN*</option>
                                        <option value="PGK"{{ paymentSetting('currency_name') == 'PGK' ? ' selected' : '' }}>PGK</option>
                                        <option value="PHP"{{ paymentSetting('currency_name') == 'PHP' ? ' selected' : '' }}>PHP</option>
                                        <option value="PKR"{{ paymentSetting('currency_name') == 'PKR' ? ' selected' : '' }}>PKR</option>
                                        <option value="PLN"{{ paymentSetting('currency_name') == 'PLN' ? ' selected' : '' }}>PLN</option>
                                        <option value="PYG"{{ paymentSetting('currency_name') == 'PYG' ? ' selected' : '' }}>PYG*</option>
                                        <option value="QAR"{{ paymentSetting('currency_name') == 'QAR' ? ' selected' : '' }}>QAR</option>
                                        <option value="RON"{{ paymentSetting('currency_name') == 'RON' ? ' selected' : '' }}>RON</option>
                                        <option value="RSD"{{ paymentSetting('currency_name') == 'RSD' ? ' selected' : '' }}>RSD</option>
                                        <option value="RUB"{{ paymentSetting('currency_name') == 'RUB' ? ' selected' : '' }}>RUB</option>
                                        <option value="RWF"{{ paymentSetting('currency_name') == 'RWF' ? ' selected' : '' }}>RWF</option>
                                        <option value="SAR"{{ paymentSetting('currency_name') == 'SAR' ? ' selected' : '' }}>SAR</option>
                                        <option value="SBD"{{ paymentSetting('currency_name') == 'SBD' ? ' selected' : '' }}>SBD</option>
                                        <option value="SCR"{{ paymentSetting('currency_name') == 'SCR' ? ' selected' : '' }}>SCR</option>
                                        <option value="SEK"{{ paymentSetting('currency_name') == 'SEK' ? ' selected' : '' }}>SEK</option>
                                        <option value="SGD"{{ paymentSetting('currency_name') == 'SGD' ? ' selected' : '' }}>SGD</option>
                                        <option value="SHP"{{ paymentSetting('currency_name') == 'SHP' ? ' selected' : '' }}>SHP*</option>
                                        <option value="SLE"{{ paymentSetting('currency_name') == 'SLE' ? ' selected' : '' }}>SLE</option>
                                        <option value="SOS"{{ paymentSetting('currency_name') == 'SOS' ? ' selected' : '' }}>SOS</option>
                                        <option value="SRD"{{ paymentSetting('currency_name') == 'SRD' ? ' selected' : '' }}>SRD*</option>
                                        <option value="STD"{{ paymentSetting('currency_name') == 'STD' ? ' selected' : '' }}>STD*</option>
                                        <option value="SZL"{{ paymentSetting('currency_name') == 'SZL' ? ' selected' : '' }}>SZL</option>
                                        <option value="THB"{{ paymentSetting('currency_name') == 'THB' ? ' selected' : '' }}>THB</option>
                                        <option value="TJS"{{ paymentSetting('currency_name') == 'TJS' ? ' selected' : '' }}>TJS</option>
                                        <option value="TOP"{{ paymentSetting('currency_name') == 'TOP' ? ' selected' : '' }}>TOP</option>
                                        <option value="TRY"{{ paymentSetting('currency_name') == 'TRY' ? ' selected' : '' }}>TRY</option>
                                        <option value="TTD"{{ paymentSetting('currency_name') == 'TTD' ? ' selected' : '' }}>TTD</option>
                                        <option value="TWD"{{ paymentSetting('currency_name') == 'TWD' ? ' selected' : '' }}>TWD</option>
                                        <option value="TZS"{{ paymentSetting('currency_name') == 'TZS' ? ' selected' : '' }}>TZS</option>
                                        <option value="UAH"{{ paymentSetting('currency_name') == 'UAH' ? ' selected' : '' }}>UAH</option>
                                        <option value="UGX"{{ paymentSetting('currency_name') == 'UGX' ? ' selected' : '' }}>UGX</option>
                                        <option value="UYU"{{ paymentSetting('currency_name') == 'UYU' ? ' selected' : '' }}>UYU*</option>
                                        <option value="UZS"{{ paymentSetting('currency_name') == 'UZS' ? ' selected' : '' }}>UZS</option>
                                        <option value="VND"{{ paymentSetting('currency_name') == 'VND' ? ' selected' : '' }}>VND</option>
                                        <option value="VUV"{{ paymentSetting('currency_name') == 'VUV' ? ' selected' : '' }}>VUV</option>
                                        <option value="WST"{{ paymentSetting('currency_name') == 'WST' ? ' selected' : '' }}>WST</option>
                                        <option value="XAF"{{ paymentSetting('currency_name') == 'XAF' ? ' selected' : '' }}>XAF</option>
                                        <option value="XCD"{{ paymentSetting('currency_name') == 'XCD' ? ' selected' : '' }}>XCD</option>
                                        <option value="XOF"{{ paymentSetting('currency_name') == 'XOF' ? ' selected' : '' }}>XOF*</option>
                                        <option value="XPF"{{ paymentSetting('currency_name') == 'XPF' ? ' selected' : '' }}>XPF*</option>
                                        <option value="YER"{{ paymentSetting('currency_name') == 'YER' ? ' selected' : '' }}>YER</option>
                                        <option value="ZAR"{{ paymentSetting('currency_name') == 'ZAR' ? ' selected' : '' }}>ZAR</option>
                                        <option value="ZMW"{{ paymentSetting('currency_name') == 'ZMW' ? ' selected' : '' }}>ZMW</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="currency-icon" class="form-label">{{ __('lang.currency_icon') }}</label>
                                    <input type="text" class="form-control" name="currency-icon" id="currency-icon" value="{{ paymentSetting('currency_icon') }}" autocomplete="off">
                                </div>
                                <div class="text-center mb-4">
                                    <button type="submit" class="btn btn-color-1">
                                        <i class="spinner fa-solid fa-check me-1"></i>
                                        {{ __('lang.save') }}
                                    </button>
                                </div>
                                <p class="text-center m-0">
                                    {{ __('lang.currency_info') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @csrf
                </form>
            </div>
            <div class="tab-pane fade" id="v-pills-gateways" role="tabpanel" aria-labelledby="v-pills-gateways-tab" tabindex="0">
                <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/payments/settings/bank') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">{{ __('lang.bank') }}</div>
                            <div class="row">
                                <div class="col-sm-6 col-md-12 col-lg-6 mb-4">
                                    <label for="bank-status" class="form-label">{{ __('lang.status') }}</label>
                                    <select class="form-select" name="bank-status" id="bank-status" aria-label="Bank Status">
                                        <option value="1"{{ $bank->status == 1 ? ' selected' : '' }}>{{ __('lang.enable') }}</option>
                                        <option value="0"{{ $bank->status == 0 ? ' selected' : '' }}>{{ __('lang.disable') }}</option>
                                    </select>
                                </div>
                                <div class="mb-4">
                                    <label for="bank-info" class="form-label">{{ __('lang.info') }}</label>
                                    <textarea class="form-control" name="bank-info" id="bank-info" rows="2" autocomplete="off">{{ $bank->info }}</textarea>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-color-1">
                                        <i class="spinner fa-solid fa-check me-1"></i>
                                        {{ __('lang.save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @csrf
                </form>
                <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/payments/settings/stripe') }}">
                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">Stripe</div>
                            <div class="row">
                                <div class="col-sm-6 col-md-12 col-lg-6 col-xxl-2 mb-4">
                                    <label for="stripe-status" class="form-label">{{ __('lang.status') }}</label>
                                    <select class="form-select" name="stripe-status" id="stripe-status" aria-label="Stripe Status">
                                        <option value="1"{{ $stripe->status == 1 ? ' selected' : '' }}>{{ __('lang.enable') }}</option>
                                        <option value="0"{{ $stripe->status == 0 ? ' selected' : '' }}>{{ __('lang.disable') }}</option>
                                    </select>
                                </div>
                                <div class="col-xxl-5 mb-4">
                                    <label for="stripe_publishable" class="form-label">{{ __('lang.publishable_key') }}</label>
                                    <input type="text" class="form-control" name="stripe-public" id="stripe_publishable" value="{{ $stripe->public }}" autocomplete="off">
                                </div>
                                <div class="col-xxl-5 mb-4">
                                    <label for="stripe-secret" class="form-label">{{ __('lang.secret_key') }}</label>
                                    <input type="text" class="form-control" name="stripe-secret" id="stripe-secret" value="{{ $stripe->secret }}" autocomplete="off">
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-color-1">
                                        <i class="spinner fa-solid fa-check me-1"></i>
                                        {{ __('lang.save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @csrf
                </form>
                <form method="POST" action="{{ LaravelLocalization::localizeUrl('/admin/payments/settings/razorpay') }}">
                    <div class="card mt-4">
                        <div class="card-body">
                            <div class="card-heading pb-3 mb-3">Razorpay</div>
                            <div class="row">
                                <div class="col-sm-6 col-md-12 col-lg-6 col-xxl-2 mb-4">
                                    <label for="razorpay-status" class="form-label">{{ __('lang.status') }}</label>
                                    <select class="form-select" name="razorpay-status" id="razorpay-status" aria-label="Razorpay Status">
                                        <option value="1"{{ $razorpay->status == 1 ? ' selected' : '' }}>{{ __('lang.enable') }}</option>
                                        <option value="0"{{ $razorpay->status == 0 ? ' selected' : '' }}>{{ __('lang.disable') }}</option>
                                    </select>
                                </div>
                                <div class="col-xxl-5 mb-4">
                                    <label for="razorpay_publishable" class="form-label">{{ __('lang.publishable_key') }}</label>
                                    <input type="text" class="form-control" name="razorpay-public" id="razorpay_publishable" value="{{ $razorpay->public }}" autocomplete="off">
                                </div>
                                <div class="col-xxl-5 mb-4">
                                    <label for="razorpay-secret" class="form-label">{{ __('lang.secret_key') }}</label>
                                    <input type="text" class="form-control" name="razorpay-secret" id="razorpay-secret" value="{{ $razorpay->secret }}" autocomplete="off">
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-color-1">
                                        <i class="spinner fa-solid fa-check me-1"></i>
                                        {{ __('lang.save') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
@stop