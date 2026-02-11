<div class="topbar d-flex">
    <a  
        class="action-button btn btn-sm d-block d-xl-none" 
        data-bg="gray" 
        data-bs-toggle="offcanvas" 
        href="#side-menu" 
        role="button" 
        aria-label="Mobil menu" 
        aria-controls="side-menu">
        <i class="fa-solid fa-bars fa-fw"></i>
    </a>
    <div class="page-name d-none d-xl-flex">
        <p>{{ $pageName ?? __('lang.dashboard') }}</p>
    </div>
    <div class="d-flex gap-2 ms-auto">
        <div class="action-button d-none d-sm-block p-1" data-bg="gray">
            <input 
                type="radio" 
                class="btn-check" 
                name="color-mode" 
                id="option1" 
                value="light" 
                aria-label="Light mode" 
                @if (setting('default_color_mode') == 1)
                    {{ session('color') == 'dark' ? '' : ' checked' }}>
                @else
                    {{ session('color') == 'light' ? ' checked' : '' }}>
                @endif
            <label class="btn btn-sm" for="option1">
                <i class="fa-regular fa-sun"></i>
            </label>
            <input 
                type="radio" 
                class="btn-check" 
                name="color-mode" 
                id="option2" 
                value="dark" 
                aria-label="Dark mode" 
                @if (setting('default_color_mode') == 1)
                    {{ session('color') == 'dark' ? ' checked' : '' }}>
                @else
                    {{ session('color') == 'light' ? '' : ' checked' }}>
                @endif
            <label class="btn btn-sm" for="option2">
                <i class="fa-solid fa-moon"></i>
            </label>
        </div>
        <button 
            type="button" 
            class="action-button btn btn-sm fullscreen d-none d-lg-block" 
            data-bg="gray" 
            aria-label="Fullscreen mode">
            <i class="fa-solid fa-expand fa-fw pe-none"></i>
        </button>
        <a href="{{ LaravelLocalization::localizeUrl("/") }}" class="action-button btn btn-sm" data-bg="gray" aria-label="Go home">
            <i class="fa-solid fa-house fa-fw"></i>
        </a>
        <button 
            type="button" 
            class="action-button btn btn-sm" 
            data-bs-toggle="modal" data-bs-target="#logout" 
            data-bg="gray" 
            aria-label="Logout">
            <i class="fa-solid fa-right-from-bracket fa-fw"></i>
        </button>
        <div class="avatar covered" 
            style="background:url('{{ img('user', Auth::user()->photo) }}')">
        </div>
    </div>
</div>