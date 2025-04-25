@extends("layouts.app")
@push("seo")
    {!! get_page_seo('areas') !!}
    {!! get_page_tags("areas", "links") !!}
@endpush
@section('content')
    @include('layouts.includes.partials._breadcrump', [
        'get_page_banner' => get_page_banner("areas"),
        'title' => get_page("areas")->title,
         'breadcrumbs' => [
            ['url' => route('home'), 'title' => translate_value('home')],
            ['url' => route('areas'), 'title' => get_page('areas')->title],

        ]
    ])

    <div class="container padding-top">
        <div class="row portfolioContainer">
            @foreach($areas as $area)
                <!-- Item Team Group A-->
                <div class="col-md-6 col-lg-4 col-xl-3 group-a">
                    <div class="item-team">
                        <div class="head-team">
                            <img src="{{$area->getImage($area->icon)}}" alt="location-team">
                            <div class="overlay"><a href="{{route("countries", $area->slug)}}">+</a></div>
                        </div>

                        <a href="{{route("countries", $area->slug)}}" class="btn">{{$area->title}}<i class="fa fa-angle-right" aria-hidden="true"></i></a>
                    </div>
                </div>
                <!-- End Item Team Group A-->
            @endforeach



        </div>
    </div>
@endsection
@push("scripts")
    {!! get_page_tags("areas", "scripts") !!}
@endpush
