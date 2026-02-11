<section class="affiliate-boxes-area">
    <div class="container">
        <div class="row row-gap-5 gx-5">
            <div class="col-lg-4">
                <div class="affiliate-box card h-100">
                    <div class="card-body text-center">
                        @if (widget('affiliate','boxes','box_1_icon'))
                            <div class="affiliate-box-icon d-flex align-items-center justify-content-center mx-auto mb-3">
                                {!! classList(widget('affiliate','boxes','box_1_icon'), ['fa-fw','fa-2x','m-auto']) !!}
                            </div>
                        @endif
                        @if (widget('affiliate','boxes','box_1_title'))
                            <h2 class="affiliate-box-title mb-3">{{ widget('affiliate','boxes','box_1_title') }}</h2>
                        @endif
                        @if (widget('affiliate','boxes','box_1_text'))
                            <p class="affiliate-box-text text-md mb-0">{{ widget('affiliate','boxes','box_1_text') }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="affiliate-box card h-100">
                    <div class="card-body text-center">
                        @if (widget('affiliate','boxes','box_2_icon'))
                            <div class="affiliate-box-icon d-flex align-items-center justify-content-center mx-auto mb-3">
                                {!! classList(widget('affiliate','boxes','box_2_icon'), ['fa-fw','fa-2x','m-auto']) !!}
                            </div>
                        @endif
                        @if (widget('affiliate','boxes','box_2_title'))
                            <h2 class="affiliate-box-title mb-3">{{ widget('affiliate','boxes','box_2_title') }}</h2>
                        @endif
                        @if (widget('affiliate','boxes','box_2_text'))
                            <p class="affiliate-box-text text-md mb-0">{{ widget('affiliate','boxes','box_2_text') }}</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="affiliate-box card h-100">
                    <div class="card-body text-center">
                        @if (widget('affiliate','boxes','box_3_icon'))
                            <div class="affiliate-box-icon d-flex align-items-center justify-content-center mx-auto mb-3">
                                {!! classList(widget('affiliate','boxes','box_3_icon'), ['fa-fw','fa-2x','m-auto']) !!}
                            </div>
                        @endif
                        @if (widget('affiliate','boxes','box_3_title'))
                            <h2 class="affiliate-box-title mb-3">{{ widget('affiliate','boxes','box_3_title') }}</h2>
                        @endif
                        @if (widget('affiliate','boxes','box_3_text'))
                            <p class="affiliate-box-text text-md mb-0">{{ widget('affiliate','boxes','box_3_text') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>