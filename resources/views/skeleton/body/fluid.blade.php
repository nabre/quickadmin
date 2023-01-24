@section('BODY')
    <div class="container">
        <div class="row">
            <div class="col">
                {!! Nabre\Quickadmin\Facades\Repositories\Page::breadcrumbs() !!}
            </div>
            <div class="col">
                {!! Nabre\Quickadmin\Facades\Repositories\Page::menuPrint('QuickBar')->asUl() !!}
            </div>
        </div>
        <div class="row">
            <div class="col">
                {!! Nabre\Quickadmin\Facades\Repositories\Page::menuPrint('AdminBar')->asUl() !!}
            </div>
            <div class="col">
                {!! Nabre\Quickadmin\Facades\Repositories\Page::menuPrint('ManageBar')->asUl() !!}
            </div>
            <div class="col">
                {!! Nabre\Quickadmin\Facades\Repositories\Page::menuPrint('UserBar')->asUl() !!}
            </div>
        </div>
    </div>
@endsection
