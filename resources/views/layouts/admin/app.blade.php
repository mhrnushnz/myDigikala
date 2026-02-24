<!DOCTYPE html>
<html lang="en">
<head>
    <title>داشبورد ادمین دیجیکالا</title>
    @include('layouts.admin.links')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font">


<!-- BEGIN LOADER -->
<div id="load_screen">
    <div class="loader">
        <div class="loader-content">
            <div class="spinner-grow align-self-center"></div>
        </div>
    </div>
</div>
<!--  END LOADER -->



<!--  BEGIN NAVBAR  -->
<livewire:admin.layout.navbar>
<!--  END NAVBAR  -->



<!--  BEGIN NAVBAR  -->
<div class="sub-header-container">
    <header class="header navbar navbar-expand-sm">
        <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a>

        <ul class="navbar-nav flex-row">
            <li>
                <div class="page-header">
                    <nav class="breadcrumb-one" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="javascript:void(0);">داشبورد</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><span>تجزیه و تحلیل</span></li>
                        </ol>
                    </nav>
                </div>
            </li>
        </ul>
        <ul class="navbar-nav flex-row ml-auto ">
            <li class="nav-item more-dropdown">
                <div class="dropdown  custom-dropdown-icon">
                    <a class="dropdown-toggle btn" href="#" role="button" id="customDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><span>تنظیمات</span> <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-down"><polyline points="6 9 12 15 18 9"></polyline></svg></a>

                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="customDropdown">
                        <a class="dropdown-item" data-value="Settings" href="javascript:void(0);">تنظیمات</a>
                        <a class="dropdown-item" data-value="Mail" href="javascript:void(0);">نامه</a>
                        <a class="dropdown-item" data-value="Print" href="javascript:void(0);">پرینت</a>
                        <a class="dropdown-item" data-value="Download" href="javascript:void(0);">دانلود</a>
                        <a class="dropdown-item" data-value="Share" href="javascript:void(0);">اشتراک گذاری</a>
                    </div>
                </div>
            </li>
        </ul>
    </header>
</div>
<!--  END NAVBAR  -->



<!--  BEGIN MAIN CONTAINER  -->
<div class="main-container " id="container">

    <div class="overlay"></div>
    <div class="search-overlay"></div>

    <!--  BEGIN SIDEBAR  -->
    <livewire:admin.layout.sidebar>
    <!--  END SIDEBAR  -->

    <!--  BEGIN CONTENT PART  -->
    <div id="content" class="main-content">{{ $slot }}</div>
    <!--  END CONTENT PART  -->
</div>


<!-- END MAIN CONTAINER -->



<!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
@include('layouts.admin.scripts')
<!-- END GLOBAL MANDATORY SCRIPTS -->



    <script>
        document.addEventListener("livewire:navigated", () => {
            if (typeof loadApp !== "undefined") {
                loadApp(); // فانکشنی که تم رو فعال می‌کنه
            }
        });
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireScripts

</body>
</html>
