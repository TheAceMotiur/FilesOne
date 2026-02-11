<section class="pricing-faqs-area">
    <div class="small-container container">
        @if (widget('pricing','faqs','upper_title') || widget('pricing','faqs','title') || widget('pricing','faqs','text'))
            <div class="section-heading text-center mb-4">
                @if (widget('pricing','faqs','upper_title'))
                    <p class="section-heading-upper animate animate__fadeIn" data-anm-delay="1200ms">
                        {{ widget('pricing','faqs','upper_title') }}
                    </p>
                @endif
                @if (widget('pricing','faqs','title'))
                    <h2 class="position-relative pb-5 mb-4 animate animate__fadeIn" data-anm-delay="1600ms">
                        {{ widget('pricing','faqs','title') }}
                    </h2>
                @endif
                @if (widget('pricing','faqs','text'))
                    <p class="section-heading-text mb-0 animate animate__fadeIn" data-anm-delay="2000ms">
                        {{ widget('pricing','faqs','text') }}
                    </p>
                @endif
            </div>
        @endif
        <div class="accordion accordion-flush" id="faqs">
            @if (widget('pricing','faqs','question_1') && widget('pricing','faqs','answer_1'))
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-1" aria-expanded="false" aria-controls="flush-1">
                            {{ widget('pricing','faqs','question_1') }}
                        </button>
                    </h2>
                    <div id="flush-1" class="accordion-collapse collapse" data-bs-parent="#faqs">
                        <div class="accordion-body">{{ widget('pricing','faqs','answer_1') }}</div>
                    </div>
                </div>
            @endif
            @if (widget('pricing','faqs','question_2') && widget('pricing','faqs','answer_2'))
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-2" aria-expanded="false" aria-controls="flush-2">
                            {{ widget('pricing','faqs','question_2') }}
                        </button>
                    </h2>
                    <div id="flush-2" class="accordion-collapse collapse" data-bs-parent="#faqs">
                        <div class="accordion-body">{{ widget('pricing','faqs','answer_2') }}</div>
                    </div>
                </div>
            @endif
            @if (widget('pricing','faqs','question_3') && widget('pricing','faqs','answer_3'))
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-3" aria-expanded="false" aria-controls="flush-3">
                            {{ widget('pricing','faqs','question_3') }}
                        </button>
                    </h2>
                    <div id="flush-3" class="accordion-collapse collapse" data-bs-parent="#faqs">
                        <div class="accordion-body">{{ widget('pricing','faqs','answer_3') }}</div>
                    </div>
                </div>
            @endif
            @if (widget('pricing','faqs','question_4') && widget('pricing','faqs','answer_4'))
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-4" aria-expanded="false" aria-controls="flush-4">
                            {{ widget('pricing','faqs','question_4') }}
                        </button>
                    </h2>
                    <div id="flush-4" class="accordion-collapse collapse" data-bs-parent="#faqs">
                        <div class="accordion-body">{{ widget('pricing','faqs','answer_4') }}</div>
                    </div>
                </div>
            @endif
            @if (widget('pricing','faqs','question_5') && widget('pricing','faqs','answer_5'))
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-5" aria-expanded="false" aria-controls="flush-5">
                            {{ widget('pricing','faqs','question_5') }}
                        </button>
                    </h2>
                    <div id="flush-5" class="accordion-collapse collapse" data-bs-parent="#faqs">
                        <div class="accordion-body">{{ widget('pricing','faqs','answer_5') }}</div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>