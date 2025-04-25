@extends("layouts.app")
@push("seo")
    {!! get_page_seo('countries') !!}
    {!! get_page_tags("countries", "links") !!}
@endpush
@section('content')
    @include('layouts.includes.partials._breadcrump', [
        'get_page_banner' => get_page_banner("countries"),
        'title' => get_page("countries")->title,
        'breadcrumbs' => [
            ['url' => route('home'), 'title' => translate_value('home')],
            ['url' => route('areas'), 'title' => get_page('areas')->title],
            ['url' => route('countries', $area->slug), 'title' => get_page('countries')->title],

        ]
    ])

    <div class="container padding-top">
        <div class="row portfolioContainer">
            @foreach($countries as $country)
                <!-- Item Team Group A-->
                <div class="col-md-4 col-lg-3 col-xl-3 group-a">
                    <div class="item-team" style="position: relative; overflow: hidden; width: 100%; height: 250px; display: flex; justify-content: center; align-items: center;">
                        <div class="head-team" style="position: relative; width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
                            <div style="width: 100px; height: 60px; overflow: hidden; display: flex; justify-content: center; align-items: center;">
                                <img src="{{$country->getImage($country->flag)}}" alt="{{ $country->name }}" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                            </div>
                            <div class="overlay" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #fff; background-color: rgba(0, 0, 0, 0.5); padding: 10px; text-align: center; font-size: 18px; border-radius: 5px;">
                                <a href="{{route("leagues", $country->slug)}}" style="color: #fff; text-decoration: none;">+</a>
                            </div>
                            <!-- Country Name Added Below -->
                            <div class="country-name" style="position: absolute; bottom: 10px; left: 50%; transform: translateX(-50%); color: #fff; font-size: 16px; font-weight: bold;">
                                {{ $country->name }}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Item Team Group A-->
            @endforeach
        </div>
    </div>
@endsection
@push("scripts")
    {!! get_page_tags("countries", "scripts") !!}
@endpush

