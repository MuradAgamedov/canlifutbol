<!-- Section Title -->
<div class="section-title" style="background:url('{{ $get_page_banner }}'); background-size:cover; background-position:center;">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1>{{ $title }}</h1>
            </div>

            <div class="col-md-4">
                <div class="breadcrumbs">
                    <ul>
                        @foreach($breadcrumbs as $breadcrumb)
                            <li>
                                <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['title'] }}</a>
                                @if(!$loop->last)
                                    <span> > </span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- End Section Title -->
