<section class="affiliate-statistics-area covered">
    <div class="container">
        <div class="row flex-column flex-md-row align-items-center justify-content-md-center gx-5">
            @if (widget('affiliate','stats','upper_title') || widget('affiliate','stats','title'))
                <div class="col-md-6 col-lg-4 mb-5 mb-md-0">
                    <div class="statistics-card-heading text-center text-md-start">
                        @if (widget('affiliate','stats','upper_title'))
                            <p class="statistics-card-upper animate animate__fadeIn">
                                {{ widget('affiliate','stats','upper_title') }}
                            </p>
                        @endif
                        @if (widget('affiliate','stats','title'))
                            <h2 class="animate animate__fadeIn" data-anm-delay="400ms">
                                {{ widget('affiliate','stats','title') }}
                            </h2>
                        @endif
                    </div>
                </div>
            @endif
            <div class="col-md-6 col-lg-8">
                <div class="row row-gap-3">
                    <div class="col-md">
                        <div class="stats-box d-flex align-items-center justify-content-center justify-content-md-start gap-4">
                            <div>
                                <div class="stats-box-icon d-flex">
                                    <i class="fa-solid fa-users fa-xl fa-fw m-auto"></i>
                                </div>
                            </div>
                            <div>
                                <p class="stats-box-text m-0">
                                    {{ widget('affiliate','stats','user_stats') ? widget('affiliate','stats','user_stats') : '-' }}
                                </p>
                                <p class="stats-box-subtext m-0">
                                    {{ __('lang.active_users') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="stats-box d-flex align-items-center justify-content-center justify-content-md-start gap-4">
                            <div>
                                <div class="stats-box-icon d-flex">
                                    <i class="fa-regular fa-folder-open fa-xl fa-fw m-auto"></i>
                                </div>
                            </div>
                            <div>
                                <p class="stats-box-text m-0">
                                    {{ widget('affiliate','stats','file_stats') ? widget('affiliate','stats','file_stats') : '-' }}
                                </p>
                                <p class="stats-box-subtext m-0">
                                    {{ __('lang.uploaded_files') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md">
                        <div class="stats-box d-flex align-items-center justify-content-center justify-content-md-start gap-4">
                            <div>
                                <div class="stats-box-icon d-flex">
                                    <i class="fa-solid fa-hand-holding-dollar fa-xl fa-fw m-auto"></i>
                                </div>
                            </div>
                            <div>
                                <p class="stats-box-text m-0">
                                    {{ widget('affiliate','stats','payment_stats') ? widget('affiliate','stats','payment_stats') : '-' }}
                                </p>
                                <p class="stats-box-subtext m-0">
                                    {{ __('lang.total_payments') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>