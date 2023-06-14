@section('BODY')
    @include('nabre-quickadmin::skeleton.widget.navbar')
    @section('CONTAINER')
        @include('nabre-quickadmin::skeleton.widget.breadcrumbs')
        <div class="row">
            <div class="col">
                @include('nabre-quickadmin::skeleton.widget.title')
                @include('nabre-quickadmin::skeleton.widget.yeld', ['YELD' => 'CONTENT'])
            </div>
            <div class="col-md-3">
                @include('nabre-quickadmin::skeleton.widget.yeld', ['YELD' => 'RIGHT-COL'])
            </div>
        </div>
    @endsection
    @include('nabre-quickadmin::skeleton.widget.container')
@endsection
