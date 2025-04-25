
<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.includes.head')
</head>

<body>

<!-- layout-->
<div id="layout">
    <x-header-component/>

    @yield('content')



    <x-footer-component/>
</div>
<!-- End layout-->

@include('layouts.includes.foot')

</body>
</html>
