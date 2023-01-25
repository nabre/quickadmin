@section('BODY')
    @include('nabre-quickadmin::skeleton.widget.navbar')
    @section('CONTAINER')
        @include('nabre-quickadmin::skeleton.widget.breadcrumbs')
        <div class="row">
            <div class="col-md-3">
                @include('nabre-quickadmin::skeleton.widget.yeld', ['YELD' => 'LEFT-COL'])
            </div>
            <div class="col">
                @include('nabre-quickadmin::skeleton.widget.title')
                @include('nabre-quickadmin::skeleton.widget.yeld', ['YELD' => 'CONTENT'])
            </div>
        </div>
    @endsection
    @include('nabre-quickadmin::skeleton.widget.container')
@endsection
