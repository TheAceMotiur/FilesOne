<section class="affiliate-table-area">
    <div class="container">
        <div class="row align-items-center justify-content-xl-center gx-5">
            @if (widget('affiliate','table','upper_title') || widget('affiliate','table','title') || widget('affiliate','table','text'))
                <div class="col-lg-6 d-flex flex-column mb-5 mb-lg-0">
                    <div class="section-heading">
                        @if (widget('affiliate','table','upper_title'))
                            <p class="section-heading-upper animate animate__fadeIn">
                                {{ widget('affiliate','table','upper_title') }}
                            </p>
                        @endif
                        @if (widget('affiliate','table','title'))
                            <h2 class="position-relative pb-5 mb-3 animate animate__fadeIn" data-anm-delay="400ms">
                                {{ widget('affiliate','table','title') }}
                            </h2>
                        @endif
                        @if (widget('affiliate','table','text'))
                            <p class="section-heading-text animate animate__fadeIn" data-anm-delay="800ms">
                                {{ widget('affiliate','table','text') }}
                            </p>
                        @endif
                        @if (Auth::check())
                            <a 
                                href="{{ LaravelLocalization::localizeUrl('/') }}" 
                                class="btn btn-color-1">
                                {{ __('lang.become_affiliate') }}
                            </a>
                        @else
                            <a 
                                href="{{ LaravelLocalization::localizeUrl(pageSlug('login', true)) }}" 
                                class="btn btn-color-1">
                                {{ __('lang.become_affiliate') }}
                            </a>
                        @endif
                    </div>
                </div>
            @endif
            <div class="col-lg-6">
                <div class="country-rates-card card">
                    <div class="card-body table-responsive">
                        <table class="table mb-0{{ count($rates) > 7 ? ' table-scroll' : '' }}">
                            <thead>
                                <tr>
                                    <th class="col-2">#</th>
                                    <th class="col">{{ __('lang.country') }}</th>
                                    <th class="col">{{ __('lang.payout_rate') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rates as $key => $rate)
                                    <tr>
                                        <th class="col-2">{{ $key + 1 }}</th>
                                        <td class="col">{{ $rate->country_name }}</td>
                                        <td class="col">{{ $currency . $rate->rate }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    @if (affiliateSetting('type') == 1)
                                        <td class="col">*{{ __('lang.affiliate_table_info_1') }}</td>
                                    @elseif(affiliateSetting('type') == 2)
                                        <td class="col">*{{ __('lang.affiliate_table_info_2') }}</td>
                                    @endif
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>