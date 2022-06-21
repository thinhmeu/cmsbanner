@extends('web.layout')
@section('content')
    <section class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-6 my-5 text-center">
                <h1 class="font-36 font-weight-500 mb-0">Ooops... Error 404</h1>
                <h2 class="font-20 text-grey2 my-5">Sorry, but the page you are looking for doesn't exist.</h2>
                <p class="font-14">You can go to the <a href="{{ getUrlLink('/') }}" class="border px-3 py-2 text-decoration-none view-more">HOMEPAGE</a></p>
            </div>
        </div>
    </section>
@endsection
