<nav class="navbar navbar-expand-lg bg-body-tertiary @if ($DARK ?? false) navbar-dark bg-dark @endif sticky-top mb-2"
    data-bs-theme="dark">
    <div class="@if ($FLUID ?? false) container-fluid @else container @endif">
        <a class="navbar-brand" href="/">Navbar</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav  me-auto mb-2 mb-lg-0">
                {!! menuRender('TopBar', 'navbar-nav mb-2 mb-lg-0') !!}
            </ul>

            <ul class="navbar-nav  mb-2 mb-lg-0">
                @include('nabre-quickadmin::skeleton.widget.user-info')
                {!! menuRender('QuickBar', 'navbar-nav mb-2 mb-lg-0') !!}
            </ul>
        </div>
    </div>
</nav>
