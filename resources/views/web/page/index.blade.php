@extends(TEMPLATE)
@section('content')
    <main class="container mt-4">
        <div class="d-flex flex-wrap">
            <div class="col-12 col-lg-12 px-0 mb-5">
                <section class="mb-5 w-100 text-black3 post-content">{!! $oneItem->content !!}</section>
            </div>
        </div>
    </main>
@endsection
