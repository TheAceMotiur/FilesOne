<section class="affiliate-payments-area">
    <div class="small-container container">
        @if (widget('affiliate','payments','upper_title') || widget('affiliate','payments','title') || widget('affiliate','payments','text'))
            <div class="section-heading text-center mb-5">
                @if (widget('affiliate','payments','upper_title'))
                    <p class="section-heading-upper animate animate__fadeIn" data-anm-delay="1200ms">
                        {{ widget('affiliate','payments','upper_title') }}
                    </p>
                @endif
                @if (widget('affiliate','payments','title'))
                    <h2 class="position-relative pb-5 mb-4 animate animate__fadeIn" data-anm-delay="1600ms">
                        {{ widget('affiliate','payments','title') }}
                    </h2>
                @endif
                @if (widget('affiliate','payments','text'))
                    <p class="section-heading-text mb-0 animate animate__fadeIn" data-anm-delay="2000ms">
                        {{ widget('affiliate','payments','text') }}
                    </p>
                @endif
            </div>
        @endif
        @if ($latestPayments)
            @foreach ($latestPayments as $key => $value)
                <div class="payments-card{{ $key == 0 ? '' : ' mt-3'}}">
                    <div class="d-flex flex-wrap justify-content-between p-3">
                        <p class="m-0">{{ $value['date'] }}</p>
                        <p class="m-0">{{ $value['email'] }}</p>
                        <p class="m-0">{{ $value['amount'] }}</p>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-md text-center m-0">
                {{ __('lang.no_data') }}
            </p>
        @endif
    </div>
</section>