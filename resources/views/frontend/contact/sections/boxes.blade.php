<section class="contact-boxes-area">
    <div class="container">
        <div class="row row-gap-5 gx-5">
            @if (
                widget('contact','boxes','box_1_title') 
                || widget('contact','boxes','box_1_subtitle') 
                || widget('contact','boxes','box_1_text') 
                || widget('contact','boxes','box_1_icon')
            )
                <div class="col-lg">
                    <div class="contact-box card h-100">
                        <div class="card-body d-flex flex-lg-column flex-xl-row gap-3">
                            @if (widget('contact','boxes','box_1_icon'))
                                <div>
                                    <div class="contact-box-icon position-relative d-flex">
                                        {!! classList(widget('contact','boxes','box_1_icon'), ['fa-fw','fa-2x','m-auto']) !!}
                                    </div>
                                </div>
                            @endif
                            @if (widget('contact','boxes','box_1_title') || widget('contact','boxes','box_1_subtitle') || widget('contact','boxes','box_1_text'))
                                <div class="contact-box-inner d-flex flex-column">
                                    @if (widget('contact','boxes','box_1_title'))
                                        <h2 class="contact-box-title">{{ widget('contact','boxes','box_1_title') }}</h2>
                                    @endif
                                    @if (widget('contact','boxes','box_1_subtitle'))
                                        <p class="contact-box-subtitle mb-2">{{ widget('contact','boxes','box_1_subtitle') }}</p>
                                    @endif
                                    @if (widget('contact','boxes','box_1_text'))
                                        <p class="contact-box-text mb-0">{{ widget('contact','boxes','box_1_text') }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            @if (
                widget('contact','boxes','box_2_title') 
                || widget('contact','boxes','box_2_subtitle') 
                || widget('contact','boxes','box_2_text') 
                || widget('contact','boxes','box_2_icon')
            )
                <div class="col-lg">
                    <div class="contact-box card h-100">
                        <div class="card-body d-flex flex-lg-column flex-xl-row gap-3">
                            @if (widget('contact','boxes','box_2_icon'))
                                <div>
                                    <div class="contact-box-icon position-relative d-flex">
                                        {!! classList(widget('contact','boxes','box_2_icon'), ['fa-fw','fa-2x','m-auto']) !!}
                                    </div>
                                </div>
                            @endif
                            @if (widget('contact','boxes','box_2_title') || widget('contact','boxes','box_2_subtitle') || widget('contact','boxes','box_2_text'))
                                <div class="contact-box-inner d-flex flex-column">
                                    @if (widget('contact','boxes','box_2_title'))
                                        <h2 class="contact-box-title">{{ widget('contact','boxes','box_2_title') }}</h2>
                                    @endif
                                    @if (widget('contact','boxes','box_2_subtitle'))
                                        <p class="contact-box-subtitle mb-2">{{ widget('contact','boxes','box_2_subtitle') }}</p>
                                    @endif
                                    @if (widget('contact','boxes','box_2_text'))
                                        <p class="contact-box-text mb-0">{{ widget('contact','boxes','box_2_text') }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
            @if (
                widget('contact','boxes','box_3_title') 
                || widget('contact','boxes','box_3_subtitle') 
                || widget('contact','boxes','box_3_text') 
                || widget('contact','boxes','box_3_icon')
            )
                <div class="col-lg">
                    <div class="contact-box card h-100">
                        <div class="card-body d-flex flex-lg-column flex-xl-row gap-3">
                            @if (widget('contact','boxes','box_3_icon'))
                                <div>
                                    <div class="contact-box-icon position-relative d-flex">
                                        {!! classList(widget('contact','boxes','box_3_icon'), ['fa-fw','fa-2x','m-auto']) !!}
                                    </div>
                                </div>
                            @endif
                            @if (widget('contact','boxes','box_3_title') || widget('contact','boxes','box_3_subtitle') || widget('contact','boxes','box_3_text'))
                                <div class="contact-box-inner d-flex flex-column">
                                    @if (widget('contact','boxes','box_3_title'))
                                        <h2 class="contact-box-title">{{ widget('contact','boxes','box_3_title') }}</h2>
                                    @endif
                                    @if (widget('contact','boxes','box_3_subtitle'))
                                        <p class="contact-box-subtitle mb-2">{{ widget('contact','boxes','box_3_subtitle') }}</p>
                                    @endif
                                    @if (widget('contact','boxes','box_3_text'))
                                        <p class="contact-box-text mb-0">{{ widget('contact','boxes','box_3_text') }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>