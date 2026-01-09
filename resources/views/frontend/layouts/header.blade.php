<!DOCTYPE html>
<html lang="zxx">

<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="t85U894qQ7CiIVcBipl2GrGhYH9hp5Ekdj-zwcrYs0g"/>
    <!-- Essential CSS Files -->
    <link rel="stylesheet" href="/assets/css/swiper-bundle.min.css">
    <link rel="stylesheet" href="/assets/css/flaticon_finto.css">
    <link rel="stylesheet" href="/assets/css/scrollCue.css">
    <link rel="stylesheet" href="/assets/css/remixicon.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/responsive.css">

    <!-- Title & Favicon -->
    <title>Dcash - Empowering Africans in the diaspora with seamless currency exchange, secure money wallets, flexible
        loans, and a vibrant marketplace.</title>
    <link rel="icon" type="image/png" href="/assets/images/favicon.png">
    @yield('additionalCss')

</head>
<body>
<!-- Start Preloader Area -->
<div class="preloader-area text-center position-fixed top-0 bottom-0 start-0 end-0" id="preloader">
    <div class="loader position-absolute start-0 end-0">
        <div class="wavy position-relative fw-light">
            <span class="d-inline-block">D</span>
            <span class="d-inline-block">C</span>
            <span class="d-inline-block">A</span>
            <span class="d-inline-block">S</span>
            <span class="d-inline-block">H</span>
        </div>
    </div>
</div>
<!-- End Preloader Area -->

<div class="top-header-info">
    <!-- Start Navbar Area -->
    <nav class="navbar main-navbar navbar-expand-lg bg-color-ffffff" id="navbar">
        <div class="container-fluid side-padding position-relative">
            <a class="navbar-brand logo-brand p-0" href="/">
                <img src="/assets/images/website-logo-white-bg.jpg" alt="image" width="180px">
            </a>
            <a class="navbar-toggler" data-bs-toggle="offcanvas" href="#navbarOffcanvas" role="button"
               aria-controls="navbarOffcanvas">
                        <span class="burger-menu">
                            <span class="top-bar"></span>
                            <span class="middle-bar"></span>
                            <span class="bottom-bar"></span>
                        </span>
            </a>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="/">
                            Home
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="/#about">
                            About
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link" href="/#services">
                            Services
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link" href="/#why-dcash">
                            Why Dcash
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link" href="/#testimonials">
                            Testimonials
                        </a>
                    </li>

                    <li class="nav-item dropdown">
                        <a class="nav-link" href="/#faq">
                            FAQ
                        </a>
                    </li>


                </ul>
            </div>

            <div class="others-options">
                <ul class="d-flex align-items-center ps-0 mb-0 list-unstyled">

                    <li>
                        <a href="https://app.dcashwallet.com" class="default-btn">Exchange Now<i
                                class="ri-arrow-right-up-line"></i></a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Navbar Area -->
</div>

<!-- Start Mobile Navbar Area -->
<div class="mobile-navbar offcanvas offcanvas-end border-0" data-bs-backdrop="static" tabindex="-1"
     id="navbarOffcanvas">
    <div class="offcanvas-header">
        <a href="/" class="logo d-inline-block">
            <img src="/assets/images/website-logo-white-bg.jpg" width="150px" alt="logo">
        </a>

        <button type="button" class="close-btn bg-transparent position-relative lh-1 p-0 border-0"
                data-bs-dismiss="offcanvas" aria-label="close">
            <i class="ri-close-fill"></i>
        </button>
    </div>

    <div class="offcanvas-body">
        <ul class="mobile-menu">


            <li class="mobile-menu-list without-icon">
                <a href="/" class="nav-link">
                    Home
                </a>
            </li>

            <li class="mobile-menu-list without-icon">
                <a href="/#about" class="nav-link">
                    About
                </a>
            </li>

            <li class="mobile-menu-list without-icon">
                <a href="/#services" class="nav-link">
                    Services
                </a>
            </li>

            <li class="mobile-menu-list without-icon">
                <a href="/#why-dcash" class="nav-link">
                    Why Us
                </a>
            </li>

            <li class="mobile-menu-list without-icon">
                <a href="/#testimonials" class="nav-link">
                    Testimonials
                </a>
            </li>

            <li class="mobile-menu-list without-icon">
                <a href="/#faq" class="nav-link">
                    FAQ
                </a>
            </li>
        </ul>

        <!-- Others options -->
        <div class="others-options">
            <ul class="d-flex align-items-center ps-0 mb-0 list-unstyled">

                <a href="https://app.dcashwallet.com" class="default-btn">Exchange Now<i
                        class="ri-arrow-right-up-line"></i></a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Mobile Navbar Area -->
