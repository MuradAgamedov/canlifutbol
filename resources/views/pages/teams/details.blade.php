@extends("layouts.app")
@push("seo")
{!! get_page_seo('clubs') !!}
{!! get_page_tags("clubs", "links") !!}
@endpush
@section('content')


<!-- Section Title -->
<div class="section-title-team">
    <div class="container">
        <div class="row">
            <div class="col-xl-7">
                <div class="row">
                    <div class="col-md-3">
                        <img src="img/clubs-logos/col_logo.png" alt="">
                    </div>

                    <div class="col-md-9">
                        <h1>{{$team->team_name}}</h1>
                        <ul class="general-info">
                            <li>
                                <h6><strong>Foundation:</strong> {{$team->date}}</h6>
                            </li>
                            <li>
                                <h6><strong>Address:</strong> {{$team->address}}</h6>
                            </li>
                            <li>
                                <h6><strong>Manager:</strong> {{$coach->name}}</h6>
                            </li>
                            <li>
                                <h6><strong>Stadium:</strong> {{$team->stadium}}</h6>
                            </li>
                            <li>
                                <h6><strong>Stadium capacity:</strong> {{$team->stadium_capacity}}</h6>
                            </li>
                            <li>
                                <h6><strong>Location:</strong> {{$league->country->name}}</h6>
                            </li>
                            <li>
                                <h6>
                                    <i class="fa fa-link" aria-hidden="true"></i>
                                    <a href="{{$team->website}}" target="_blank">{{$team->website}}</a>
                                </h6>
                            </li>
                        </ul>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-image-team" style="background:url(img/clubs-teams/colombia.jpg);"></div>
</div>
<!-- End Section Title -->

<!-- Section Area - Content Central -->
<section class="content-info">

    <!-- Single Team Tabs -->
    <div class="single-team-tabs">
        <div class="container">
            <div class="row">
                <!-- Left Content - Tabs and Carousel -->
                <div class="col-xl-12 col-md-12">
                    <!-- Nav Tabs -->
                    <ul class="nav nav-tabs" id="myTab">
                        <li class="active"><a href="#overview" data-toggle="tab">Overview</a></li>
                        <li><a href="#squad" data-toggle="tab">Squad</a></li>
                        <li><a href="#results" data-toggle="tab">RESULTS</a></li>
                    </ul>
                    <!-- End Nav Tabs -->
                </div>

                <div class="col-lg-9 padding-top-mini">
                    <!-- Content Tabs -->
                    <div class="tab-content">
                        <!-- Tab One - overview -->
                        <div class="tab-pane active" id="overview">

                            <div class="panel-box padding-b">
                                <div class="titles">
                                    <h4>{{$team->team_name}}</h4>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12 col-xl-4">
                                        <img src="{{$team->getImage($team->logo)}}" alt="">
                                    </div>

                                    <div class="col-lg-12 col-xl-8">
                                        {{$team->info}}
                                    </div>
                                </div>
                            </div>

                            <!--Items Club News -->
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="clear-title">Latest Club News</h3>
                                </div>

                                <!--Item Club News -->
                                <div class="col-lg-6 col-xl-4">
                                    <!-- Widget Text-->
                                    <div class="panel-box">
                                        <div class="titles no-margin">
                                            <h4><a href="#">World football's dates.</a></h4>
                                        </div>
                                        <a href="#"><img src="img/blog/1.jpg" alt=""></a>
                                        <div class="row">
                                            <div class="info-panel">
                                                <p>Fans from all around the world can apply for 2018 FIFA World Cup™ tickets as the first window of sales.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Widget Text-->
                                </div>
                                <!--End Item Club News -->

                                <!--Item Club News -->
                                <div class="col-lg-6 col-xl-4">
                                    <!-- Widget Text-->
                                    <div class="panel-box">
                                        <div class="titles no-margin">
                                            <h4><a href="#">Mbappe’s year to remember</a></h4>
                                        </div>
                                        <a href="#"><img src="img/blog/2.jpg" alt=""></a>
                                        <div class="row">
                                            <div class="info-panel">
                                                <p>Tickets may be purchased online by using Visa payment cards or Visa Checkout. Visa is the official.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Widget Text-->
                                </div>
                                <!--End Item Club News -->

                                <!--Item Club News -->
                                <div class="col-lg-6 col-xl-4">
                                    <!-- Widget Text-->
                                    <div class="panel-box">
                                        <div class="titles no-margin">
                                            <h4><a href="#">Egypt are one family</a></h4>
                                        </div>
                                        <a href="#"><img src="img/blog/3.jpg" alt=""></a>
                                        <div class="row">
                                            <div class="info-panel">
                                                <p>Successful applicants who have applied for supporter tickets and conditional supporter tickets will.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Widget Text-->
                                </div>
                                <!--End Item Club News -->
                            </div>
                            <!--End Items Club News -->


                            <!--Items Club video -->
                            <div class="row no-line-height">
                                <div class="col-md-12">
                                    <h3 class="clear-title">Latest Club Videos</h3>
                                </div>

                                <!--Item Club News -->
                                <div class="col-lg-6 col-xl-4">
                                    <!-- Widget Text-->
                                    <div class="panel-box">
                                        <div class="titles no-margin">
                                            <h4><a href="#">Eliminatory to the world.</a></h4>
                                        </div>
                                        <iframe class="video" src="https://www.youtube.com/embed/Ln8rXkeeyP0" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>
                                    </div>
                                    <!-- End Widget Text-->
                                </div>
                                <!--End Item Club News -->

                                <!--Item Club News -->
                                <div class="col-lg-6 col-xl-4">
                                    <!-- Widget Text-->
                                    <div class="panel-box">
                                        <div class="titles no-margin">
                                            <h4><a href="#">Colombia classification</a></h4>
                                        </div>
                                        <iframe class="video" src="https://www.youtube.com/embed/Z5cackyUfgk" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>
                                    </div>
                                    <!-- End Widget Text-->
                                </div>
                                <!--End Item Club News -->

                                <!--Item Club News -->
                                <div class="col-lg-6 col-xl-4">
                                    <!-- Widget Text-->
                                    <div class="panel-box">
                                        <div class="titles no-margin">
                                            <h4><a href="#">World Cup goal</a></h4>
                                        </div>
                                        <iframe class="video" src="https://www.youtube.com/embed/hW3hnUoUS0k" frameborder="0" gesture="media" allow="encrypted-media" allowfullscreen></iframe>
                                    </div>
                                    <!-- End Widget Text-->
                                </div>
                                <!--End Item Club News -->
                            </div>
                            <!--End Items Club video -->

                            <!--Sponsors CLub -->
                            <div class="row no-line-height">
                                <div class="col-md-12">
                                    <h3 class="clear-title">Sponsors Club</h3>
                                </div>
                            </div>
                            <!--End Sponsors CLub -->

                            <ul class="sponsors-carousel">
                                <li><a href="#"><img src="img/sponsors/1.png" alt=""></a></li>
                                <li><a href="#"><img src="img/sponsors/2.png" alt=""></a></li>
                                <li><a href="#"><img src="img/sponsors/3.png" alt=""></a></li>
                                <li><a href="#"><img src="img/sponsors/4.png" alt=""></a></li>
                                <li><a href="#"><img src="img/sponsors/5.png" alt=""></a></li>
                                <li><a href="#"><img src="img/sponsors/3.png" alt=""></a></li>
                            </ul>

                        </div>
                        <!-- Tab One - overview -->

                        <!-- Tab Two - squad -->
                        <div class="tab-pane" id="squad">
                            <div class="row">
                                @foreach($players as $player)
                                <!-- Item Player -->
                                <div class="col-xl-4 col-lg-6 col-md-6">
                                    <div class="item-player">
                                        <div class="head-player">
                                            <img src="{{ str_replace('storage/', 'storage/players/', $player->getImage($player->photo)) }}" alt="location-team">

                                            <div class="overlay"><a href="single-player.html">+</a></div>
                                        </div>
                                        <div class="info-player">
                                            <span class="number-player">
                                                {{$player->number}}
                                            </span>
                                            <h4>
                                                {{$player->name}}
                                                <span>{{$player->position}}</span>
                                            </h4>
                                            <ul>
                                                <li>
                                                    <strong>NATIONALITY</strong> <span><img src="img/clubs-logos/colombia.png" alt=""> {{$player->country}} </span>
                                                </li>
                                                @php

                                                $age = null;
                                                if (!empty($player->birthday)) {
                                                try {
                                                $birthYear = \Carbon\Carbon::parse($player->birthday)->year;
                                                $currentYear = \Carbon\Carbon::now()->year;
                                                $age = $currentYear - $birthYear;
                                                } catch (\Exception $e) {
                                                $age = 'Invalid date';
                                                }
                                                }
                                                @endphp

                                                <li><strong>AGE:</strong> <span>{{ $age }}</span></li>
                                                <span>{{ is_numeric($player->value) ? number_format($player->value * 10) : 'N/A' }}</span>


                                            </ul>
                                        </div>
                                        <a href="single-player.html" class="btn">View Player <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                                <!-- End Item Player -->
                                @endforeach

                            </div>
                        </div>
                        <!-- End Tab Two - squad -->

                        <!-- Tab Theree - results -->
                        <div class="tab-pane" id="results">
                            <div class="recent-results results-page">
                                <div class="info-results">
                                    <ul>
                                        @foreach($matches as $game)
                                        <li>
                                            <span class="head">
                                                {{$game->league_name}} <span class="date">{{$game->start_time }}</span>
                                            </span>

                                            <div class="goals-result">
                                                <a href="single-team.html">
                                                    <img src="img/clubs-logos/por.png" alt="">
                                                    {{$game->home_club_name}}
                                                </a>

                                                <span class="goals">
                                                    <b>{{$game->home_club_goals }}</b> - <b>{{$game->away_club_goals }}</b>
                                                    <a href="single-result.html" class="btn theme">View More</a>
                                                </span>

                                                <a href="single-team.html">
                                                    <img src="img/clubs-logos/esp.png" alt="">
                                                    {{$game->away_club_name}}
                                                </a>
                                            </div>
                                        </li>
                                        @endforeach

                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!-- End Tab Theree - results -->


                    </div>
                    <!-- Content Tabs -->
                </div>

                <!-- Side info single team-->
                <div class="col-lg-3 padding-top-mini">
                    <!-- Diary -->
                    <div class="panel-box">
                        <div class="titles">
                            <h4><i class="fa fa-calendar"></i>Diary</h4>
                        </div>

                        <!-- List Diary -->
                        <ul class="list-diary">
                            <!-- Item List Diary -->
                            <li>
                                <h6>GROUP A <span>14 JUN 2018 - 18:00</span></h6>
                                <ul class="club-logo">
                                    <li>
                                        <img src="img/clubs-logos/rusia.png" alt="">
                                        <span>RUSSIA</span>
                                    </li>
                                    <li>
                                        <img src="img/clubs-logos/arabia.png" alt="">
                                        <span>SAUDI ARABIA</span>
                                    </li>
                                </ul>
                            </li>
                            <!-- End Item List Diary -->

                            <!-- Item List Diary -->
                            <li>
                                <h6>GROUP E <span>22 JUN 2018 - 15:00</span></h6>
                                <ul class="club-logo">
                                    <li>
                                        <img src="img/clubs-logos/bra.png" alt="">
                                        <span>BRAZIL</span>
                                    </li>
                                    <li>
                                        <img src="img/clubs-logos/costa-rica.png" alt="">
                                        <span>COSTA RICA</span>
                                    </li>
                                </ul>
                            </li>
                            <!-- End Item List Diary -->

                            <!-- Item List Diary -->
                            <li>
                                <h6>GROUP H <span>19 JUN 2018 - 15:00</span></h6>
                                <ul class="club-logo">
                                    <li>
                                        <img src="img/clubs-logos/colombia.png" alt="">
                                        <span>COLOMBIA</span>
                                    </li>
                                    <li>
                                        <img src="img/clubs-logos/japan.png" alt="">
                                        <span>JAPAN</span>
                                    </li>
                                </ul>
                            </li>
                            <!-- End Item List Diary -->

                            <!-- Item List Diary -->
                            <li>
                                <h6>GROUP C <span>16 JUN 2018 - 15:00</span></h6>
                                <ul class="club-logo">
                                    <li>
                                        <img src="img/clubs-logos/fra.png" alt="">
                                        <span>FRANCE</span>
                                    </li>
                                    <li>
                                        <img src="img/clubs-logos/aus.png" alt="">
                                        <span>AUSTRALIA</span>
                                    </li>
                                </ul>
                            </li>
                            <!-- End Item List Diary -->
                        </ul>
                        <!-- End List Diary -->
                    </div>
                    <!-- End Diary -->

                    <!-- Video presentation -->
                    <div class="panel-box">
                        <div class="titles no-margin">
                            <h4>Presentation</h4>
                        </div>
                        <!-- Locations Video -->
                        <div class="row">
                            <iframe src="https://www.youtube.com/embed/AfOlAUd7u4o" class="video"></iframe>
                            <div class="info-panel">
                                <h4>Rio de Janeiro</h4>
                                <p>Lorem ipsum dolor sit amet, sit amet, consectetur adipisicing elit, elit, incididunt ut labore et dolore magna aliqua sit amet, consectetur adipisicing elit,</p>
                            </div>
                        </div>
                        <!-- End Locations Video -->
                    </div>
                    <!-- End Video presentation-->

                    <!-- Widget Text-->
                    <div class="panel-box">
                        <div class="titles no-margin">
                            <h4>Widget Image</h4>
                        </div>
                        <img src="img/slide/1.jpg" alt="">
                        <div class="row">
                            <div class="info-panel">
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, ut sit amet, consectetur adipisicing elit, labore et dolore.</p>
                            </div>
                        </div>
                    </div>
                    <!-- End Widget Text-->
                </div>
                <!-- end Side info single team-->

            </div>
        </div>
    </div>
    <!-- Single Team Tabs -->

    <!-- Newsletter -->
    <div class="section-newsletter">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center">
                        <h2>Enter your e-mail and <span class="text-resalt">subscribe</span> to our newsletter.</h2>
                        <p>Duis non lorem porta, eros sit amet, tempor sem. Donec nunc arcu, semper a tempus et, consequat.</p>
                    </div>
                    <form id="newsletterForm" action="php/mailchip/newsletter-subscribe.php">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                    <input class="form-control" placeholder="Your Name" name="name" type="text" required="required">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-addon">
                                        <i class="fa fa-envelope"></i>
                                    </span>
                                    <input class="form-control" placeholder="Your  Email" name="email" type="email" required="required">
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" type="submit" name="subscribe">SIGN UP</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div id="result-newsletter"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Newsletter -->
</section>
<!-- End Section Area -  Content Central -->
@endsection
@push("scripts")
{!! get_page_tags("clubs", "scripts") !!}
@endpush
