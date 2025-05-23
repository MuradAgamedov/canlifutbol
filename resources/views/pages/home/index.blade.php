@extends('layouts.app')

@section('content')
    <!-- section-hero-posts-->
    <div class="hero-header">
        <!-- Hero Slider-->
        <div id="hero-slider" class="hero-slider">

            <!-- Item Slide-->
            <div class="item-slider" style="background:url(img/slide/3.jpg);">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <div class="info-slider">
                                <h1>Group Stage Breakdown</h1>
                                <p>While familiar with fellow European nation France, Hareide admits that South American side Peru.</p>
                                <a href="#" class="btn-iw outline">Read More <i class="fa fa-long-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Item Slide-->

            <!-- Item Slide-->
            <div class="item-slider" style="background:url(img/slide/2.jpg);">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <div class="info-slider">
                                <h1>World Cup rivalries reprised</h1>
                                <p>The outdoor exhibition on Manezhnaya Square comprises 11 figures that symbolise the main sites of interest.</p>
                                <a href="#" class="btn-iw outline">Read More <i class="fa fa-long-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Item Slide-->

            <!-- Item Slide-->
            <div class="item-slider" style="background:url(img/slide/1.jpg);">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-7">
                            <div class="info-slider">
                                <h1>Group Stage Breakdown</h1>
                                <p>While familiar with fellow European nation France, Hareide admits that South American side Peru.</p>
                                <a href="#" class="btn-iw outline">Read More <i class="fa fa-long-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Item Slide-->

        </div>
        <!-- End Hero Slider-->
    </div>
    <!-- End section-hero-posts-->

    <!-- Section Area - Content Central -->
    <section class="content-info">
        <!-- Dark Home -->
        <div class="dark-home">
            <div class="container">
                <div class="row">
                    <!-- Left Content - Tabs and Carousel -->
                    <div class="col-xl-9 col-md-12">
                        <!-- Nav Tabs -->
                        <ul class="nav nav-tabs" id="myTab">
                            <li class="active"><a href="#statistics" data-toggle="tab">STATISTICS</a></li>
                            <li><a href="#groups" data-toggle="tab">GROUPS</a></li>
                            <li><a href="#description" data-toggle="tab">DESCRIPTION</a></li>
                        </ul>
                        <!-- End Nav Tabs -->

                        <!-- Content Tabs -->
                        <div class="tab-content">
                            <!-- Tab Theree - statistics -->
                            <div class="tab-pane active" id="statistics">
                                <div class="row">
                                    <!-- Club Ranking -->
                                    <div class="col-lg-4">
                                        <div class="club-ranking">
                                            <h5><a href="group-list.html">Club Ranking</a></h5>
                                            <div class="info-ranking">
                                                <ul>
                                                    <li>
                                                              <span class="position">
                                                                  1
                                                              </span>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/rusia.png" alt="">
                                                            Russia
                                                        </a>
                                                        <span class="points">
                                                                    90
                                                                </span>
                                                    </li>

                                                    <li>
                                                              <span class="position">
                                                                  2
                                                              </span>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/arabia.png" alt="">
                                                            Saudi Arabia
                                                        </a>
                                                        <span class="points">
                                                                    86
                                                                </span>
                                                    </li>

                                                    <li>
                                                              <span class="position">
                                                                  3
                                                              </span>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/egy.png" alt="">
                                                            Egypt
                                                        </a>
                                                        <span class="points">
                                                                    84
                                                                </span>
                                                    </li>

                                                    <li>
                                                              <span class="position">
                                                                  4
                                                              </span>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/uru.png" alt="">
                                                            Uruguay
                                                        </a>
                                                        <span class="points">
                                                                  70
                                                               </span>
                                                    </li>

                                                    <li>
                                                              <span class="position">
                                                                  5
                                                              </span>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/por.png" alt="">
                                                            Portugal
                                                        </a>
                                                        <span class="points">
                                                                    67
                                                                </span>
                                                    </li>

                                                    <li>
                                                              <span class="position">
                                                                  6
                                                              </span>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/esp.png" alt="">
                                                            Spain
                                                        </a>
                                                        <span class="points">
                                                                    60
                                                                </span>
                                                    </li>

                                                    <li>
                                                              <span class="position">
                                                                  55
                                                              </span>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/mar.png" alt="">
                                                            Morocco
                                                        </a>
                                                        <span class="points">
                                                                    90
                                                                </span>
                                                    </li>

                                                    <li>
                                                              <span class="position">
                                                                  8
                                                              </span>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/irn.png" alt="">
                                                            IR Iran
                                                        </a>
                                                        <span class="points">
                                                                    53
                                                                </span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Club Ranking -->

                                    <!-- recent-results -->
                                    <div class="col-lg-4">
                                        <div class="recent-results">
                                            <h5><a href="group-list.html">Recent Result</a></h5>
                                            <div class="info-results">
                                                <ul>
                                                    <li>
                                                                <span class="head">
                                                                    Portugal Vs Spain <span class="date">27 Jun 2017</span>
                                                                </span>

                                                        <div class="goals-result">
                                                            <a href="single-team.html">
                                                                <img src="img/clubs-logos/por.png" alt="">
                                                                Portugal
                                                            </a>

                                                            <span class="goals">
                                                                        <b>2</b> - <b>3</b>
                                                                    </span>

                                                            <a href="single-team.html">
                                                                <img src="img/clubs-logos/esp.png" alt="">
                                                                Spain
                                                            </a>
                                                        </div>
                                                    </li>

                                                    <li>
                                                                <span class="head">
                                                                    Rusia Vs Colombia <span class="date">30 Jun 2017</span>
                                                                </span>

                                                        <div class="goals-result">
                                                            <a href="single-team.html">
                                                                <img src="img/clubs-logos/rusia.png" alt="">
                                                                Rusia
                                                            </a>

                                                            <span class="goals">
                                                                        <b>2</b> - <b>3</b>
                                                                    </span>

                                                            <a href="single-team.html">
                                                                <img src="img/clubs-logos/colombia.png" alt="">
                                                                Colombia
                                                            </a>
                                                        </div>
                                                    </li>

                                                    <li>
                                                                <span class="head">
                                                                    Uruguay Vs Portugal <span class="date">31 Jun 2017</span>
                                                                </span>

                                                        <div class="goals-result">
                                                            <a href="single-team.html">
                                                                <img src="img/clubs-logos/uru.png" alt="">
                                                                Uruguay
                                                            </a>

                                                            <span class="goals">
                                                                        <b>2</b> - <b>3</b>
                                                                    </span>

                                                            <a href="single-team.html">
                                                                <img src="img/clubs-logos/por.png" alt="">
                                                                Portugal
                                                            </a>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end recent-results -->

                                    <!-- Top player -->
                                    <div class="col-lg-4">
                                        <div class="player-ranking">
                                            <h5><a href="group-list.html">Top player</a></h5>
                                            <div class="info-player">
                                                <ul>
                                                    <li>
                                                              <span class="position">
                                                                  1
                                                              </span>
                                                        <a href="single-team.html">
                                                            <img src="img/players/1.jpg" alt="">
                                                            Cristiano R.
                                                        </a>
                                                        <span class="points">
                                                                    90
                                                                </span>
                                                    </li>

                                                    <li>
                                                              <span class="position">
                                                                  2
                                                              </span>
                                                        <a href="single-team.html">
                                                            <img src="img/players/2.jpg" alt="">
                                                            Lionel Messi
                                                        </a>
                                                        <span class="points">
                                                                    88
                                                                </span>
                                                    </li>

                                                    <li>
                                                              <span class="position">
                                                                  3
                                                              </span>
                                                        <a href="single-team.html">
                                                            <img src="img/players/3.jpg" alt="">
                                                            Neymar
                                                        </a>
                                                        <span class="points">
                                                                    86
                                                                </span>
                                                    </li>

                                                    <li>
                                                              <span class="position">
                                                                  4
                                                              </span>
                                                        <a href="single-team.html">
                                                            <img src="img/players/4.jpg" alt="">
                                                            Luis Suárez
                                                        </a>
                                                        <span class="points">
                                                                  80
                                                               </span>
                                                    </li>

                                                    <li>
                                                              <span class="position">
                                                                  5
                                                              </span>
                                                        <a href="single-team.html">
                                                            <img src="img/players/5.jpg" alt="">
                                                            Gareth Bale
                                                        </a>
                                                        <span class="points">
                                                                    76
                                                                </span>
                                                    </li>

                                                    <li>
                                                              <span class="position">
                                                                  6
                                                              </span>
                                                        <a href="single-team.html">
                                                            <img src="img/players/6.jpg" alt="">
                                                            Sergio Agüero
                                                        </a>
                                                        <span class="points">
                                                                    74
                                                                </span>
                                                    </li>

                                                    <li>
                                                              <span class="position">
                                                                  7
                                                              </span>
                                                        <a href="single-team.html">
                                                            <img src="img/players/2.jpg" alt="">
                                                            Jamez R.
                                                        </a>
                                                        <span class="points">
                                                                    70
                                                                </span>
                                                    </li>

                                                    <li>
                                                              <span class="position">
                                                                  8
                                                              </span>
                                                        <a href="single-team.html">
                                                            <img src="img/players/1.jpg" alt="">
                                                            Falcao Garcia
                                                        </a>
                                                        <span class="points">
                                                                    65
                                                                </span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End Top player -->
                                </div>
                            </div>
                            <!-- Tab Theree - statistics -->

                            <!-- Tab One - Groups List -->
                            <div class="tab-pane" id="groups">
                                <div class="groups-list">
                                    <div class="row">
                                        <div class="col-lg-3 col-md-6">
                                            <h5><a href="groups.html">GROUP A</a></h5>
                                            <div class="item-group">
                                                <ul>
                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/rusia.png" alt="">
                                                            Russia
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/arabia.png" alt="">
                                                            Saudi Arabia
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/egy.png" alt="">
                                                            Egypt
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/uru.png" alt="">
                                                            Uruguay
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <h5><a href="groups.html">GROUP B</a></h5>
                                            <div class="item-group">
                                                <ul>
                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/por.png" alt="">
                                                            Portugal
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/esp.png" alt="">
                                                            Spain
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/mar.png" alt="">
                                                            Morocco
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/irn.png" alt="">
                                                            IR Iran
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <h5><a href="group-list.html">GROUP C</a></h5>
                                            <div class="item-group">
                                                <ul>
                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/fra.png" alt="">
                                                            France
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/aus.png" alt="">
                                                            Australia
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/per.png" alt="">
                                                            Peru
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/den.png" alt="">
                                                            Denmark
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <h5><a href="group-list.html">GROUP D</a></h5>
                                            <div class="item-group">
                                                <ul>
                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/arg.png" alt="">
                                                            Argentina
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/isl.png" alt="">
                                                            Iceland
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/cro.png" alt="">
                                                            Croatia
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/nga.png" alt="">
                                                            Nigeria
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-3 col-md-6">
                                            <h5><a href="group-list-html">GROUP E</a></h5>
                                            <div class="item-group">
                                                <ul>
                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/bra.png" alt="">
                                                            Brazil
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/sui.png" alt="">
                                                            Switzerland
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/costa-rica.png" alt="">
                                                            Costa rica
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/srb.png" alt="">
                                                            Serbia
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <h5><a href="group-list-html">GROUP F</a></h5>
                                            <div class="item-group">
                                                <ul>
                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/ger.png" alt="">
                                                            Germany
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/mex.png" alt="">
                                                            Mexico
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/swe.png" alt="">
                                                            Sweden
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/kor.png" alt="">
                                                            Korea Rep
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <h5><a href="group-list-html">GROUP G</a></h5>
                                            <div class="item-group">
                                                <ul>
                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/bel.png" alt="">
                                                            Belgium
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/pan.png" alt="">
                                                            Panama
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/tun.png" alt="">
                                                            Tunisia
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/eng.png" alt="">
                                                            England
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="col-lg-3 col-md-6">
                                            <h5><a href="group-list-html">GROUP H</a></h5>
                                            <div class="item-group">
                                                <ul>
                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/pol.png" alt="">
                                                            Poland
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/sen.png" alt="">
                                                            Senegal
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/colombia.png" alt="">
                                                            Colombia
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="single-team.html">
                                                            <img src="img/clubs-logos/japan.png" alt="">
                                                            Japan
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Tab One - Groups List -->

                            <!-- Tab Two - Destinations -->
                            <div class="tab-pane" id="description">
                                <div class="info-general">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <img src="img/locations/1.jpg" alt="">
                                        </div>

                                        <div class="col-md-8">
                                            <h3>2018 FIFA World Cup</h3>
                                            <p class="lead">The 2018 FIFA World Cup will be the 21st FIFA World Cup, a quadrennial international football tournament contested by the men's national teams.  West of the Ural Mountains to keep travel time manageable.</p>
                                        </div>

                                        <div class="col-md-12">
                                            <p>It is scheduled to take place in Russia from 14 June to 15 July 2018,[2] after the country was awarded the hosting rights on 2 December 2010. This will be the first World Cup held in Europe since 2006; all but one of the stadium venues are in European Russia, west of the Ural Mountains to keep travel time manageable.</p>
                                            <h4>Gianni Infantino - Fifa President</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- End Tab Two - Destinations -->
                        </div>
                        <!-- Content Tabs -->
                    </div>
                    <!-- Left Content - Tabs and Carousel -->

                    <!-- Right Content - Content Counter -->
                    <div class="col-xl-3 col-md-12">
                        <div class="counter-home-wraper">
                            <div class="title-color text-center">
                                <h4>World Cup Russia 2018</h4>
                            </div>

                            <div class="content-counter content-counter-home">
                                <p class="text-center">
                                    <i class="fa fa-clock-o"></i>
                                    Countdown Till Start
                                </p>
                                <div id="event-one" class="counter"></div>
                                <ul class="post-options">
                                    <li><i class="fa fa-calendar"></i>14 June, 2018</li>
                                    <li><i class="fa fa-clock-o"></i>Kick-of, 12:00 PM</li>
                                </ul>

                                <div class="list-groups">
                                    <div class="row align-items-center">

                                        <div class="col-md-12">
                                            <p>GROUP A, Luzhniki Stadium Moscow</p>
                                        </div>

                                        <div class="col-md-5">
                                            <img src="img/clubs-logos/rusia.png" alt="">
                                            <span>RUSSIA</span>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="vs">Vs</div>
                                        </div>

                                        <div class="col-md-5">
                                            <img src="img/clubs-logos/arabia.png" alt="">
                                            <span>SAUDI ARABIA</span>
                                        </div>
                                    </div>
                                </div>

                                <a class="btn btn-primary" href="#">
                                    PURCHASE
                                    <i class="fa fa-trophy"></i>
                                </a>
                            </div>
                        </div>
                        <!-- Content Counter -->
                    </div>
                    <!-- End Right Content - Content Counter -->
                </div>
            </div>
        </div>
        <!-- Dark Home -->

        <!-- Content Central -->
        <div class="container padding-top">
            <div class="row">

                <!-- content Column Left -->
                <div class="col-lg-6 col-xl-7">
                    <!-- Recent Post -->
                    <div class="panel-box">

                        <div class="titles">
                            <h4>Recent News</h4>
                        </div>

                        <!-- Post Item -->
                        <div class="post-item">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="img-hover">
                                        <img src="img/blog/1.jpg" alt="" class="img-responsive">
                                        <div class="overlay"><a href="single-news.html">+</a></div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h5><a href="single-news.html">Group Stage Breakdown</a></h5>
                                    <span class="data-info">January 3, 2014  / <i class="fa fa-comments"></i><a href="#">0</a></span>
                                    <p>While familiar with fellow European nation France, Hareide admits that South American side Peru.<a href="single-news.html">Read More [+]</a></p>
                                </div>
                            </div>
                        </div>
                        <!-- End Post Item -->

                        <!-- Post Item -->
                        <div class="post-item">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="img-hover">
                                        <img src="img/blog/2.jpg" alt="" class="img-responsive">
                                        <div class="overlay"><a href="single-news.html">+</a></div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h5><a href="single-news.html">Russia 2018’s potential classic match-ups</a></h5>
                                    <span class="data-info">January 9, 2014  / <i class="fa fa-comments"></i><a href="#">5</a></span>
                                    <p>Our goal is very clear, it didn’t change after the draw. We should qualify for the knockout stage.<a href="single-news.html">Read More [+]</a></p>
                                </div>
                            </div>
                        </div>
                        <!-- End Post Item -->

                        <!-- Post Item -->
                        <div class="post-item">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="img-hover">
                                        <img src="img/blog/3.jpg" alt="" class="img-responsive">
                                        <div class="overlay"><a href="single-news.html">+</a></div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h5><a href="single-news.html">World Cup rivalries reprised</a></h5>
                                    <span class="data-info">January  4, 2014  / <i class="fa fa-comments"></i><a href="#">3</a></span>
                                    <p>The outdoor exhibition on Manezhnaya Square comprises 11 figures that symbolise the main sites of interest.<a href="single-news.html">Read More [+]</a></p>
                                </div>
                            </div>
                        </div>
                        <!-- End Post Item -->

                        <!-- Post Item -->
                        <div class="post-item">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="img-hover">
                                        <img src="img/blog/4.jpg" alt="" class="img-responsive">
                                        <div class="overlay"><a href="single-news.html">+</a></div>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <h5><a href="single-news.html">All set for your trip to Russia?</a></h5>
                                    <span class="data-info">January 8, 2014  / <i class="fa fa-comments"></i><a href="#">2</a></span>
                                    <p>Colombia play Japan on 19 June at the Mordovia Arena, where the piling and casting operations.<a href="single-news.html">Read More [+]</a></p>
                                </div>
                            </div>
                        </div>
                        <!-- End Post Item -->
                    </div>
                    <!-- End Recent Post -->

                    <!-- Experts -->
                    <div class="panel-box">
                        <div class="titles">
                            <h4>Best Players</h4>
                        </div>

                        <div class="row">
                            <div class="col-xs-6 col-sm-3 col-md-4 col-lg-4">
                                <div class="box-info">
                                    <a href="single-player.html">
                                        <img src="img/players/1.jpg" alt="" class="img-responsive">
                                    </a>
                                    <h6 class="entry-title"><a href="single-player.html">Cristiano Ronaldo</a></h6>
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-3 col-md-4 col-lg-4">
                                <div class="box-info">
                                    <a href="single-player.html">
                                        <img src="img/players/2.jpg" alt="" class="img-responsive">
                                    </a>
                                    <h6 class="entry-title"><a href="single-player.html">Lionel Messi</a></h6>
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-3 col-md-4 col-lg-4">
                                <div class="box-info">
                                    <a href="single-player.html">
                                        <img src="img/players/3.jpg" alt="" class="img-responsive">
                                    </a>
                                    <h6 class="entry-title"><a href="single-player.html">Neymar</a></h6>
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-3 col-md-4 col-lg-4">
                                <div class="box-info">
                                    <a href="single-player.html">
                                        <img src="img/players/4.jpg" alt="" class="img-responsive">
                                    </a>
                                    <h6 class="entry-title"><a href="single-player.html">Luis Suárez</a></h6>
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-3 col-md-4 col-lg-4">
                                <div class="box-info">
                                    <a href="single-player.html">
                                        <img src="img/players/5.jpg" alt="" class="img-responsive">
                                    </a>
                                    <h6 class="entry-title"><a href="single-player.html"> Gareth Bale</a></h6>
                                </div>
                            </div>
                            <div class="col-xs-6 col-sm-3 col-md-4 col-lg-4">
                                <div class="box-info">
                                    <a href="single-player.html">
                                        <img src="img/players/6.jpg" alt="" class="img-responsive">
                                    </a>
                                    <h6 class="entry-title"><a href="single-player.html">Sergio Agüero</a></h6>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- End Experts -->
                </div>
                <!-- End content Left -->

                <!-- content Sidebar Center -->
                <aside class="col-sm-6 col-lg-3 col-xl-3">
                    <!-- Locations -->
                    <div class="panel-box">
                        <div class="titles no-margin">
                            <h4>Locations</h4>
                        </div>
                        <!-- Locations Carousel -->
                        <ul class="single-carousel">
                            <li>
                                <img src="img/locations/1.jpg" alt="" class="img-responsive">
                                <div class="info-single-carousel">
                                    <h4>Saint Petersburg</h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo cillum dolore eu fugiat nulla  sit amet, consectetur adipisicing elit, pariatur.</p>
                                </div>
                            </li>
                            <li>
                                <img src="img/locations/2.jpg" alt="" class="img-responsive">
                                <div class="info-single-carousel">
                                    <h4>Ekaterinburg</h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo cillum dolore eu fugiat nulla pariatur.</p>
                                </div>
                            </li>
                            <li>
                                <img src="img/locations/3.jpg" alt="" class="img-responsive">
                                <div class="info-single-carousel">
                                    <h4>Nizhny Novgorod</h4>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo cillum dolore eu fugiat nulla pariatur.</p>
                                </div>
                            </li>
                        </ul>
                        <!-- Locations Carousel -->
                    </div>
                    <!-- End Locations -->

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

                    <!-- Widget img-->
                    <div class="panel-box">
                        <div class="titles no-margin">
                            <h4>Widget Image</h4>
                        </div>
                        <img src="img/slide/1.jpg" alt="">
                        <div class="row">
                            <div class="info-panel">
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit,  ut sit amet, consectetur adipisicing elit, labore et dolore.</p>
                            </div>
                        </div>
                    </div>
                    <!-- End Widget img-->
                </aside>
                <!-- End content Sidebar Center -->

                <!-- content Sidebar Right -->
                <aside class="col-sm-6 col-lg-3 col-xl-2">
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

                    <!-- Adds Sidebar -->
                    <div class="panel-box">
                        <div class="titles no-margin">
                            <h4><i class="fa fa-link"></i>Cta</h4>
                        </div>
                        <a href="http://themeforest.net/user/iwthemes/portfolio?ref=iwthemes" target="_blank">
                            <img src="img/adds/sidebar.png" class="img-responsive" alt="">
                        </a>
                    </div>
                    <!-- End Adds Sidebar -->
                </aside>
                <!-- End content Sidebar Right -->

            </div>
        </div>
        <!-- End Content Central -->

        <!-- Newsletter -->
        <div class="section-newsletter">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="text-center">
                            <h2>Enter your e-mail and <span class="text-resalt">subscribe</span> to our newsletter.</h2>
                            <p>Duis non lorem porta,  eros sit amet, tempor sem. Donec nunc arcu, semper a tempus et, consequat.</p>
                        </div>
                        <form id="newsletterForm" action="php/mailchip/newsletter-subscribe.php">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-envelope"></i>
                                                </span>
                                        <input class="form-control" placeholder="Your Name" name="name"  type="text" required="required">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input-group">
                                                <span class="input-group-addon">
                                                    <i class="fa fa-envelope"></i>
                                                </span>
                                        <input class="form-control" placeholder="Your  Email" name="email"  type="email" required="required">
                                        <span class="input-group-btn">
                                                    <button class="btn btn-primary" type="submit" name="subscribe" >SIGN UP</button>
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
