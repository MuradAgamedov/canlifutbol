@extends("layouts.app")
@push("seo")
    {!! get_page_seo('live') !!}
    {!! get_page_tags("live", "links") !!}
@endpush
@section('content')
    @include('layouts.includes.partials._breadcrump', [
        'get_page_banner' => get_page_banner("live"),
        'title' => translate_value("live_game_banner_title"),
        'link' => route("live")
    ])


    <!-- Section Area - Content Central -->
    <section class="content-info">

        <div class="container paddings-mini">
            <div class="row">

                <div class="col-lg-12">
                    <h3 class="clear-title">{{translate_value("live_game_banner_title")}}</h3>
                </div>

                <div class="col-lg-12">
                    <table class="table-striped table-responsive table-hover">
                        <thead>
                        <tr>
                            <th>Team A</th>
                            <th class="text-center">VS</th>
                            <th>Team B</th>
                            <th>Details</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>
                                <img src="img/clubs-logos/colombia.png" alt="icon">
                                <strong>Colombia</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td class="text-center">Vs</td>
                            <td>
                                <img src="img/clubs-logos/japan.png" alt="icon1">
                                <strong>Japan</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td>
                                Jun 19,  07:00<br>
                                <small class="meta-text">Mordovia Arena,Saransk</small>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="img/clubs-logos/pol.png" alt="icon1">
                                <strong>Poland</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td class="text-center">Vs</td>
                            <td>
                                <img src="img/clubs-logos/colombia.png" alt="icon">
                                <strong>Colombia</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td>
                                Jun 24,  13:00<br>
                                <small class="meta-text">Kazan Arena,Kazan</small>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="img/clubs-logos/sen.png" alt="icon1">
                                <strong>Senegal</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td class="text-center">Vs</td>
                            <td>
                                <img src="img/clubs-logos/colombia.png" alt="icon">
                                <strong>Colombia</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td>
                                Jun 28, 09:00<br>
                                <small class="meta-text">Samara Arena,Samara</small>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="img/clubs-logos/colombia.png" alt="icon">
                                <strong>Colombia</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td class="text-center">Vs</td>
                            <td>
                                <img src="img/clubs-logos/pol.png" alt="icon1">
                                <strong>Poland</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td>
                                Jun 24,  13:00<br>
                                <small class="meta-text">Kazan Arena,Kazan</small>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="img/clubs-logos/colombia.png" alt="icon">
                                <strong>Colombia</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td class="text-center">Vs</td>
                            <td>
                                <img src="img/clubs-logos/japan.png" alt="icon1">
                                <strong>Japan</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td>
                                Jun 19,  07:00<br>
                                <small class="meta-text">Mordovia Arena,Saransk</small>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="img/clubs-logos/pol.png" alt="icon1">
                                <strong>Poland</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td class="text-center">Vs</td>
                            <td>
                                <img src="img/clubs-logos/colombia.png" alt="icon">
                                <strong>Colombia</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td>
                                Jun 24,  13:00<br>
                                <small class="meta-text">Kazan Arena,Kazan</small>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="img/clubs-logos/sen.png" alt="icon1">
                                <strong>Senegal</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td class="text-center">Vs</td>
                            <td>
                                <img src="img/clubs-logos/colombia.png" alt="icon">
                                <strong>Colombia</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td>
                                Jun 28, 09:00<br>
                                <small class="meta-text">Samara Arena,Samara</small>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="img/clubs-logos/colombia.png" alt="icon">
                                <strong>Colombia</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td class="text-center">Vs</td>
                            <td>
                                <img src="img/clubs-logos/pol.png" alt="icon1">
                                <strong>Poland</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td>
                                Jun 24,  13:00<br>
                                <small class="meta-text">Kazan Arena,Kazan</small>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="img/clubs-logos/colombia.png" alt="icon">
                                <strong>Colombia</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td class="text-center">Vs</td>
                            <td>
                                <img src="img/clubs-logos/japan.png" alt="icon1">
                                <strong>Japan</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td>
                                Jun 19,  07:00<br>
                                <small class="meta-text">Mordovia Arena,Saransk</small>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="img/clubs-logos/pol.png" alt="icon1">
                                <strong>Poland</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td class="text-center">Vs</td>
                            <td>
                                <img src="img/clubs-logos/colombia.png" alt="icon">
                                <strong>Colombia</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td>
                                Jun 24,  13:00<br>
                                <small class="meta-text">Kazan Arena,Kazan</small>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="img/clubs-logos/sen.png" alt="icon1">
                                <strong>Senegal</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td class="text-center">Vs</td>
                            <td>
                                <img src="img/clubs-logos/colombia.png" alt="icon">
                                <strong>Colombia</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td>
                                Jun 28, 09:00<br>
                                <small class="meta-text">Samara Arena,Samara</small>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="img/clubs-logos/colombia.png" alt="icon">
                                <strong>Colombia</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td class="text-center">Vs</td>
                            <td>
                                <img src="img/clubs-logos/pol.png" alt="icon1">
                                <strong>Poland</strong><br>
                                <small class="meta-text">GROUP H.</small>
                            </td>
                            <td>
                                Jun 24,  13:00<br>
                                <small class="meta-text">Kazan Arena,Kazan</small>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

      @include('layouts.includes.partials._newletter_input')
    </section>
    <!-- End Section Area -  Content Central -->
@endsection
@push("scripts")
    {!! get_page_tags("live", "scripts") !!}
@endpush
