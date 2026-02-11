<section class="blog-inner-page">
    <div class="small-container container">
        <article class="blog-inner-card">
            <div
                class="covered position-relative w-100{{ setting('lazyload') == 1 ? ' lazy' : '' }}" 
                @if (setting('lazyload') == 1)
                    data-bg="{{ img('blog', $post->featured_photo, 'lg') }}">
                @else
                    style="background: url({{ img('blog', $post->featured_photo, 'lg') }});">
                @endif
            </div>
            <div class="blog-info d-flex justify-content-center gap-4 my-4">
                <span class="post-badge d-flex align-items-center gap-2">
                    <i class="fa-solid fa-calendar fa-fw"></i>
                    <span>{{ dateFormat($post->created_at, false, true) }}</span>
                </span>
                <span class="post-badge d-flex align-items-center gap-2">
                    <i class="fa-solid fa-comment fa-fw"></i>
                    <span>
                        {{ $comments ? count($comments) : 0 }} {{ __('lang.comments') }}
                    </span>
                </span>
            </div>
            <div class="content ck-content">
                {!! $post->postContent !!}
            </div>
            <div class="share py-3 mt-4 d-flex align-items-center w-100">
                <span>{{ __('lang.share') }}</span>
                <div class="d-flex gap-3 ms-auto">
                    <a 
                        href="https://www.facebook.com/sharer/sharer.php?u={{ url()->full() }}" 
                        onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" 
                        target="_blank" 
                        title="Share on Facebook">
                        <i class="fa-brands fa-facebook-f fa-xl fa-fw"></i>
                    </a>
                    <a 
                        href="https://x.com/share?url={{ url()->full() }}" 
                        onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" 
                        target="_blank" 
                        title="Share on X">
                        <i class="fa-brands fa-x-twitter fa-xl fa-fw"></i>
                    </a>
                    <a 
                        href="whatsapp://send?text={{ url()->full() }}" 
                        onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=300,width=600');return false;" 
                        target="_blank" 
                        title="Share on Whatsapp">
                        <i class="fa-brands fa-whatsapp fa-xl fa-fw"></i>
                    </a>
                </div>
            </div>
        </article>
        <div class="comments mt-4">
            @if ($comments)
                <h2 class="card-heading pb-3 mb-3">{{ count($comments) }} {{ __('lang.comments') }}</h2>
            @endif
            <div class="comment-list">
                @if ($comments)
                    @foreach ($comments as $comment)
                        <div class="comment d-flex mt-4">
                            <div class="comment-image">
                                <div 
                                    class="covered{{ setting('lazyload') == 1 ? ' lazy' : '' }}" 
                                    @if (setting('lazyload') == 1)
                                        data-bg="{{ img('user', $comment->userPhoto) }}">
                                    @else
                                        style="background: url({{ img('user', $comment->userPhoto) }});">
                                    @endif
                                </div>
                            </div>
                            <div class="ms-4">
                                <p class="comment-name mb-2">
                                    {{ $comment->userName ?? __('lang.deleted_user') }}
                                </p>
                                <p class="comment-info mb-2">
                                    {{ dateFormat($comment->date, false, true) }}
                                </p>
                                <p class="comment-text text-md mb-0">
                                    {{ $comment->comment }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
            <form method="POST" action="{{ url()->current() . "/new-comment" }}">
                <div class="new-comment mt-4">
                    <h2 class="card-heading pb-3 mb-3">{{ __('lang.new_comment') }}</h2>
                    @if ($errors->any())
                        <div class="alert alert-1 alert-dismissible fade show" role="alert">
                            @foreach ($errors->all() as $error)
                                <p class="m-0">{{ $error }}</p>
                            @endforeach
                            <button 
                                type="button" 
                                class="btn-close" 
                                data-bs-dismiss="alert" 
                                aria-label="Close">
                            </button>
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="alert alert-2 alert-dismissible fade show" role="alert">
                            <p class="m-0">{{ session('success') }}</p>
                            <button 
                                type="button" 
                                class="btn-close" 
                                data-bs-dismiss="alert" 
                                aria-label="Close">
                            </button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-1 alert-dismissible fade show" role="alert">
                            <p class="m-0">{{ session('error') }}</p>
                            <button 
                                type="button" 
                                class="btn-close" 
                                data-bs-dismiss="alert" 
                                aria-label="Close">
                            </button>
                        </div>
                    @endif
                    <div>
                        <textarea class="form-control mb-4" name="comment" rows="4" aria-label="Comment" placeholder="{{ __('lang.comment_placeholder') }}"></textarea>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-color-1">{{ __('lang.send') }}</button>
                    </div>
                </div>
                @csrf
            </form>
        </div>
    </div>
</section>