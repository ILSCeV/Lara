{{-- Theme switcher --}}
<li class="nav-item dropdown">
    <button
        class="btn btn-link nav-link py-2 px-0 px-lg-2 dropdown-toggle d-flex align-items-center show"
        id="bd-theme" type="button" aria-expanded="true" data-bs-toggle="dropdown"
        data-bs-display="static" aria-label="{{__('mainLang.toggleTheme')}}">
        <i class="fa fa-lightbulb bi my-1 theme-icon-active" data-bs-toggle="tooltip"
        data-bs-placement="left" title="{{__('mainLang.toggleTheme')}}"></i>
        <span class="d-md-none ms-2" id="bd-theme-text">{{__('mainLang.toggleTheme')}}</span>
    </button>
    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme-text"
        data-bs-popper="static">
        <li>
            <button type="button" class="dropdown-item d-flex align-items-center active"
                data-bs-theme-value="light" aria-pressed="true">
                <i class="fa fa-sun bi me-2 opacity-50 theme-icon"></i>
                {{ __('mainLang.light') }}
                <svg class="bi ms-auto d-none">
                    <use href="#check2"></use>
                </svg>
            </button>
        </li>
        <li>
            <button type="button" class="dropdown-item d-flex align-items-center"
                data-bs-theme-value="dark" aria-pressed="false">
                <i class="fa fa-moon bi me-2 opacity-50 theme-icon"></i>
                {{ __('mainLang.dark') }}
                <svg class="bi ms-auto d-none">
                    <use href="#check2"></use>
                </svg>
            </button>
        </li>
        <li>
            <button type="button" class="dropdown-item d-flex align-items-center"
                data-bs-theme-value="auto" aria-pressed="false">
                <i class="fa fa-circle-half-stroke bi me-2 opacity-50 theme-icon"></i>
                Auto
                <i class="fa fa-check bi ms-auto d-none"></i>
            </button>
        </li>
    </ul>
</li>
