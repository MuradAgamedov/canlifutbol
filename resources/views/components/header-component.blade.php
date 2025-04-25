<!-- Header-->
<header>
    <!-- End headerbox-->
    <div class="headerbox">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <!-- Logo-->
                <div class="col">
                    <div class="logo">
                        <a href="index.html" title="Return Home">
                            <img src="{{$websiteInfo->getImage($websiteInfo->logo_header)}}" alt="Logo" class="{{$websiteInfo->logo_header_alt_text}}">
                        </a>
                    </div>
                </div>
                <!-- End Logo-->

                <!-- Adds Header-->
                <div class="col">
                    <div class="adds">
                        <a href="http://themeforest.net/user/iwthemes/portfolio?ref=iwthemes" target="_blank">
                            <img src="{{asset('assets/img/adds/banner.jpg')}}" alt="" class="img-responsive">
                        </a>
                    </div>

                    <!-- Call Nav Menu-->
                    <a class="mobile-nav" href="#mobile-nav"><i class="fa fa-bars"></i></a>
                    <!-- End Call Nav Menu-->
                </div>
                <!-- End Adds Header-->
            </div>
        </div>
    </div>
    <!-- End headerbox-->
</header>
<!-- End Header-->

<!-- mainmenu-->
<nav class="mainmenu">
    <div class="container">
        <!-- Menu-->
        <ul class="sf-menu" id="menu">
            @foreach($pages as $page)
                @if($page->children->count() > 0 && $page->show_on_header)
                    <li class="current">
                        <a title="{{ $page->title }}">{{ $page->title }}</a>
                        <ul class="sub-current">
                            @foreach($page->children as $subPage)
                                @if($subPage->show_on_header)
                                    <li>
                                        <a
                                            title="{{ $subPage->title }}"
                                            href="{{ $subPage->code == 'clubs' ? route('clubs.index') : url($subPage->slug) }}"
                                        >
                                            {{ $subPage->title }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </li>
                @elseif($page->show_on_header)
                    <li>
                        <a
                            href="{{ $page->code == 'clubs' ? route('areas') : url($page->slug) }}"
                        >
                            {{ $page->title }}
                        </a>
                    </li>
                @endif
            @endforeach
        </ul>

        <!-- End Menu-->
    </div>
</nav>
<!-- End mainmenu-->

<!-- Mobile Nav-->
<div id="mobile-nav">
    <!-- Menu-->
    <ul>
        <li>
            <a href="index.html">Home</a>
            <ul>
                <li>
                    <a href="index-1.html">Home 1</a>
                </li>
                <li>
                    <a href="index-2.html">Home 2</a>
                </li>
                <li>
                    <a href="index-3.html">Home 3</a>
                </li>
                <li>
                    <a href="index-4.html">Home 4</a>
                </li>
                <li>
                    <a href="index-5.html">Home 5</a>
                </li>
                <li>
                    <a href="index-6.html">Home 6</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="#">World Cup</a>
            <ul>
                <li>
                    <a href="#">World Cup</a>
                    <ul>
                        <li><a href="table-point.html">Point Table</a></li>
                        <li><a href="fixtures.html">Fixtures</a></li>
                        <li><a href="groups.html">Groups</a></li>
                        <li><a href="news-left-sidebar.html">News</a></li>
                        <li><a href="contact.html">Contact Us</a></li>
                    </ul>
                </li>

                <li><a href="teams.html">Teams List</a></li>
                <li><a href="players.html">Players List</a></li>
                <li><a href="results.html">Results List</a></li>
            </ul>
        </li>

        <li>
            <a href="teams.html">Teams</a>
            <ul>
                <li>
                    <a href="teams.html">Teams List</a>
                </li>
                <li>
                    <a href="single-team.html">Single Team</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="players.html">Players</a>
            <ul>
                <li>
                    <a href="players.html">Players List</a>
                </li>
                <li>
                    <a href="single-player.html">Single Player</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="fixtures.html">Fixtures</a>
        </li>

        <li>
            <a href="results.html">Results</a>
            <ul>
                <li>
                    <a href="results.html">Results List</a>
                </li>
                <li>
                    <a href="single-result.html">Single Result</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="table-point.html">Point Table</a>
        </li>

        <li>
            <a href="groups.html">Groups</a>
        </li>

        <li>
            <a href="#">Features</a>
            <ul>
                <li>
                    <a href="#">Features</a>
                    <ul>
                        <li><a href="page-full-width.html">Full Width</a></li>
                        <li><a href="page-left-sidebar.html">Left Sidebar</a></li>
                        <li><a href="page-right-sidebar.html">Right Sidebar</a></li>
                        <li><a href="page-404.html">404 Page</a></li>
                        <li><a href="page-faq.html">FAQ</a></li>
                        <li><a href="sitemap.html">Sitemap</a></li>
                        <li><a href="page-pricing.html">Pricing</a></li>
                        <li><a href="page-register.html">Register Form</a></li>
                    </ul>
                </li>

                <li>
                    <a href="#">Headers & Footers</a>
                    <ul>
                        <li><a href="feature-header-footer-1.html">Header Version 1</a></li>
                        <li><a href="feature-header-footer-2.html">Header Version 2</a></li>
                        <li><a href="feature-header-footer-3.html">Header Version 3</a></li>
                        <li><a href="index-5.html">Header Version 4</a></li>
                        <li><a href="feature-header-footer-1.html#footer">Footer Version 1</a></li>
                        <li><a href="feature-header-footer-2.html#footer">Footer Version 2</a></li>
                        <li><a href="feature-header-footer-3.html#footer">Footer Version 3</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">Pages</a>
                    <ul>
                        <li><a href="page-about.html">About Us</a></li>
                        <li><a href="single-player.html">About Me</a></li>
                        <li><a href="feature-header-footer-2.html#footer">Services</a></li>
                        <li><a href="single-team.html">Club Info</a></li>
                        <li><a href="single-result.html">Match Result</a></li>
                        <li><a href="table-point.html">Positions</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#">News</a>
                    <ul>
                        <li>
                            <a href="news-left-sidebar.html">News Lef Sidebar</a>
                        </li>
                        <li>
                            <a href="news-right-sidebar.html">News Right Sidebar</a>
                        </li>
                        <li>
                            <a href="news-no-sidebar.html">News No Sidebar</a>
                        </li>
                        <li>
                            <a href="single-news.html">Single News</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </li>

        <li>
            <a href="contact.html">Contact</a>
        </li>
    </ul>
    <!-- End Menu-->
</div>
<!-- End Mobile Nav-->

