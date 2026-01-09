@extends('frontend.layouts.master')
@section('body')
    <!-- Start Banner Area -->
    <div class="banner-area bg-color-edf1ee position-relative overflow-hidden">
        <div class="container">
            <div class="banner-content" data-cues="slideInUp" data-duration="800">
                <span class="sub-t">Welcome to DCash</span>
                <h1>A Simplified Currency Exchange</h1>
                <p class="mb-5">Exchange currencies effortlessly with our fast and reliable platform. Designed for
                    Africans in the diaspora, we offer competitive rates and secure transactions, making cross-border
                    exchanges seamless and hassle-free</p>

            </div>
        </div>


        <div class="shape-image">
            <img class="hero-shape-1 moveHorizontal_reverse" src="/assets/images/shape/hero-shape-1.png" alt="image">
            <img class="hero-shape-2 rotate" src="/assets/images/shape/hero-shape-2.png" alt="image">
        </div>
    </div>
    <!-- End Banner Area -->

    <!-- Start Financial About Area -->
    <div id="about" class="financial-about-area pb-50 pt-5 pb-5 overflow-hidden">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 col-md-6" data-cues="slideInRight" data-duration="800">
                    <div class="about-image position-relative">
                        <img class="app-image-6" src="/assets/images/app/app-image-6.png" alt="image">
                        <img class="app-image-7 bounce" src="/assets/images/shape/feature-shape-1.png" alt="image">
                        <!--                            <img class="app-image-8 radius-30" src="/assets/images/app/app-image-8.jpg" alt="image">-->
                        <img class="feature-shape-1 rotate" src="/assets/images/shape/feature-shape-1.png" alt="image">
                    </div>
                </div>
                <div class="col-lg-6 col-md-6" data-cues="slideInLeft" data-duration="800">
                    <div class="financial-about-content">
                        <div class="section-heading">
                            <span class="sub-title">ABOUT</span>
                            <h2>Financial services for africans in the diaspora</h2>
                            <p>Empowering Africans in the diaspora, we provide seamless currency exchange, secure wallet
                                services, accessible loans, and a vibrant marketplace. Our mission is to connect and
                                support the global African community with innovative financial solutions tailored to
                                their unique needs. With a focus on convenience, security, and inclusivity, we strive to
                                build bridges across borders, enabling opportunities and fostering economic growth for
                                Africans worldwide.
                            </p>
                        </div>

                        <a href="https://app.dcashwallet.com" class="default-btn two">Exchange Now <i
                                class="ri-arrow-right-up-line"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Financial About Area -->

    <!-- Start Our Services Area -->
    <div id="services" class="partner-area two bg-color-edf1ee pt-5 overflow-hidden">
        <div class="container pb-5">
            <div class="row">
                <div class="col-xl-4" data-cues="slideInRight" data-duration="800">
                    <div class="section-heading mb-0">
                        <span class="sub-title">OUR SERVICES</span>
                        <h2>Our Financial Services</h2>
                        <p class="mb-5">Offering a comprehensive suite of products, including currency exchange, wallet
                            services, loans, and a marketplace for Africans in the diaspora.</p>
                    </div>
                </div>
                <div class="col-xl-8">
                    <div class="row g-4" data-cues="slideInUp" data-duration="800">
                        <div class="col-lg-6 col-md-6">
                            <div class="single-services-card bg-color-fffaeb radius-30">
                                <h3>
                                    <a href="service-details.html">Currency Exchange</a>
                                </h3>
                                <p>Effortlessly exchange currencies at competitive rates, ensuring quick and secure
                                    transfers for Africans in the diaspora.</p>
                                <div class="flex-warp d-flex align-items-center justify-content-between">
                                    <i class="flaticon-mission mission"></i>
                                    <!--                                        <a href="service-details.html" class="arrow-btn"><i class="ri-arrow-right-up-line"></i></a>-->
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="single-services-card bg-color-fffaeb radius-30">
                                <h3>
                                    <a href="service-details.html">Mobile Wallet</a>
                                </h3>
                                <p>Manage, store, and transfer your money safely with our user-friendly digital wallet,
                                    designed for Africans living abroad.</p>
                                <div class="flex-warp d-flex align-items-center justify-content-between">
                                    <i class="flaticon-online-meeting mission"></i>
                                    <!--                                        <a href="service-details.html" class="arrow-btn"><i class="ri-arrow-right-up-line"></i></a>-->
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="single-services-card bg-color-fffaeb radius-30">
                                <h3>
                                    <a href="service-details.html">Loans</a>
                                </h3>
                                <p>Access flexible and reliable loans tailored for Africans in the diaspora, empowering
                                    your
                                    financial goals with ease.</p>
                                <div class="flex-warp d-flex align-items-center justify-content-between">
                                    <i class="flaticon-corporation mission"></i>
                                    <!--                                        <a href="service-details.html" class="arrow-btn"><i class="ri-arrow-right-up-line"></i></a>-->
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6">
                            <div class="single-services-card bg-color-fffaeb radius-30">
                                <h3>
                                    <a href="service-details.html">Marketplace</a>
                                </h3>
                                <p>Explore a dynamic marketplace offering goods and services to meet the needs of
                                    Africans
                                    in the diaspora.</p>
                                <div class="flex-warp d-flex align-items-center justify-content-between">
                                    <i class="flaticon-investor mission"></i>
                                    <!--                                        <a href="service-details.html" class="arrow-btn"><i class="ri-arrow-right-up-line"></i></a>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Our Services Area -->

    <!-- Start why us Area -->
    <div id="why-dcash" class="payments-processing-area pt-100 overflow-hidden">
        <div class="container">
            <div class="section-title">
                <span class="sub-title">WHY DCASH</span>
                <h2>What Makes Us Stand Out From The Rest</h2>
            </div>
            <div class="row g-4 align-items-center">
                <div class="col-lg-6 col-md-12" data-cues="slideInLeft" data-duration="800">
                    <ul class="processing">
                        <li class="bg-color-fffaeb radius-30 position-relative">
                            <div class="number">01</div>
                            <h3>Tailored for the Diaspora</h3>
                            <p>Tailored for the diaspora - our services are specifically designed to meet the unique
                                needs of Africans living abroad. We make it easy for you to access your funds and
                                receive it in any currency you like, wherever you are.</p>
                        </li>
                        <li class="bg-color-fffaeb radius-30 position-relative">
                            <div class="number">02</div>
                            <h3>Competitive Exchange Rates</h3>
                            <p>We offer some of the best rates for currency exchange, ensuring you get more value for
                                your
                                money.</p>
                        </li>
                        <li class="bg-color-fffaeb radius-30 position-relative">
                            <div class="number">03</div>
                            <h3>24/7 Customer Support:</h3>
                            <p>We’re always here to help with reliable, responsive customer service whenever you need
                                assistance</p>
                        </li>
                        <li class="bg-color-fffaeb radius-30 position-relative">
                            <div class="number">04</div>
                            <h3>Innovative Solutions</h3>
                            <p>DCash always seeks innovative solutions to provide ease, security, and safety to all
                                Africans on the diaspora.</p>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-6 col-md-12" data-cues="slideInRight" data-duration="800">
                    <div class="processing-image position-relative">
                        <img class="app-image-9" src="/assets/images/app/app-image-9.png" alt="image">
                        <!--                            <img class="app-image-8 radius-30" src="/assets/images/app/app-image-8.jpg" alt="image">-->
                        <img class="app-image-10 bounce" src="/assets/images/shape/feature-shape-1.png" alt="image">
                        <img class="feature-shape-1 moveVertical" src="/assets/images/shape/feature-shape-1.png"
                             alt="shape">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End why us Area -->

    <!-- Start Testimonial Area -->
    <div id="testimonials" class="reviews-area pt-5">
        <div class="container-fluid eighteen-padding bg-color-0c3a30 ptb-120 radius-30">
            <div class="container">
                <div class="about-top mb-5">
                    <div class="row align-items-center">
                        <div class="col-lg-6 col-md-7">
                            <div class="section-heading mb-0">
                                <span class="sub-title">TESTIMONIAL</span>
                                <h2 class="text-white mb-0">Hear from our happy customers</h2>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="row g-4" data-cues="slideInUp" data-duration="800">
                    <div class="col-lg-6 col-md-6">
                        <div class="review-card bg-color-ffffff radius-30 position-relative">
                            <img class="rounded-circle" src="/assets/images/user/image-3.jpg" alt="image">

                            <ul class="star">
                                <li>
                                    <i class="flaticon-star-2"></i>
                                    <i class="flaticon-star-2"></i>
                                    <i class="flaticon-star-2"></i>
                                    <i class="flaticon-star-2"></i>
                                    <i class="flaticon-star-2"></i>
                                </li>
                            </ul>

                            <p>“I’ve been using their currency exchange service for months, and the rates are
                                unbeatable!
                                It’s fast, secure, and always reliable. Highly recommended for Africans in the
                                diaspora!”</p>

                            <h3>Amaka</h3>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="review-card bg-color-ffffff radius-30 position-relative">
                            <img class="rounded-circle" src="/assets/images/user/image-4.jpg" alt="image">

                            <ul class="star">
                                <li>
                                    <i class="flaticon-star-2"></i>
                                    <i class="flaticon-star-2"></i>
                                    <i class="flaticon-star-2"></i>
                                    <i class="flaticon-star-2"></i>
                                    <i class="flaticon-star-2"></i>
                                </li>
                            </ul>

                            <p>"Switching to DCash exchange service transformed how I handle finances abroad. Their
                                customer support is stellar, transfers are lightning-fast, and the security measures
                                give me total peace of mind. Fellow Africans, don’t miss out on this gem!"</p>

                            <h3>Adamu Muhammad</h3>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="review-card bg-color-ffffff radius-30 position-relative">
                            <img class="rounded-circle" src="/assets/images/user/image-5.jpg" alt="image">

                            <ul class="star">
                                <li>
                                    <i class="flaticon-star-2"></i>
                                    <i class="flaticon-star-2"></i>
                                    <i class="flaticon-star-2"></i>
                                    <i class="flaticon-star-2"></i>
                                    <i class="flaticon-star-2"></i>
                                </li>
                            </ul>

                            <p>"Speed, simplicity, and stellar rates—this service checks every box! My funds arrive
                                instantly, and the app is so user-friendly. Highly recommend it to Africans abroad
                                seeking a trustworthy way to manage remittances!"</p>

                            <h3>Oluwaseun</h3>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <div class="review-card bg-color-ffffff radius-30 position-relative">
                            <img class="rounded-circle" src="/assets/images/user/image-6.jpg" alt="image">

                            <ul class="star">
                                <li>
                                    <i class="flaticon-star-2"></i>
                                    <i class="flaticon-star-2"></i>
                                    <i class="flaticon-star-2"></i>
                                    <i class="flaticon-star-2"></i>
                                    <i class="flaticon-star-2"></i>
                                </li>
                            </ul>

                            <p>"As someone who’s tried multiple platforms, nothing compares to their reliability and
                                transparency. Whether I’m saving on fees or avoiding delays, they always deliver.
                                Essential for anyone in the diaspora supporting family back in Africa!”</p>

                            <h3>Kunle .O.</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Testimonial Area -->

    <!-- Start Faq Area -->
    <div id="faq" class="faq-area two ptb-120">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-5">
                    <div class="section-heading mb-0">
                        <span class="sub-title">FAQ</span>
                        <h2>Frequently Asked Questions</h2>
                        <p class="mb-5">Frequently Asked Questions: Get Answers to Common Queries About Our Currency
                            Exchange, Wallet Services, Loans, and Marketplace</p>
                    </div>
                </div>
                <div class="col-lg-7 col-md-7">
                    <div class="faq-content">
                        <div class="accordion" id="accordionShould">
                            <div class="accordion-item">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse20" aria-expanded="false" aria-controls="collapse20">
                                    1. How can I exchange currency using your service?
                                </button>
                                <div id="collapse20" class="accordion-collapse collapse show"
                                     data-bs-parent="#accordionShould">
                                    <div class="accordion-body">
                                        <p>You can easily exchange currency through our platform by creating an account,
                                            selecting your preferred currencies, and completing the transaction. Our
                                            competitive rates and secure system ensure that your money reaches its
                                            destination quickly and safely.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse21" aria-expanded="false" aria-controls="collapse21">
                                    2. Is the money wallet service secure?
                                </button>
                                <div id="collapse21" class="accordion-collapse collapse"
                                     data-bs-parent="#accordionShould">
                                    <div class="accordion-body">
                                        <p>Yes, our money wallet service is built with top-tier encryption and security
                                            protocols to protect your funds. We prioritize the safety of your personal
                                            and
                                            financial information, ensuring a safe and reliable experience.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse22" aria-expanded="false" aria-controls="collapse22">
                                    3. How quickly can I access a loan?
                                </button>
                                <div id="collapse22" class="accordion-collapse collapse"
                                     data-bs-parent="#accordionShould">
                                    <div class="accordion-body">
                                        <p>Once your loan application is submitted, it is processed quickly. Depending
                                            on
                                            your eligibility and the loan amount, you could receive the funds within a
                                            few
                                            minutes. We aim to make the process as fast and seamless as possible.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse23" aria-expanded="false" aria-controls="collapse23">
                                    4. Are there any fees for using the exchange and wallet services?

                                </button>
                                <div id="collapse23" class="accordion-collapse collapse"
                                     data-bs-parent="#accordionShould">
                                    <div class="accordion-body">
                                        <p>Our currency exchange and wallet services come with NO FEES, clearly outlined
                                            during the transaction process, ensuring you get the best value for your
                                            money.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Faq Area -->

    <!-- Start Advice Area -->
    <div class="advice-revenue-area pt-120 mb-5">
        <div class="container-fluid">
            <div class="advice-content">
                <ul>
                    <li>Currency Exchange_Effortlessly exchange currencies</li>
                    <li>Wallet Services_Manage, store, and transfer</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- End Advice Area -->

    <!-- Start Advice Area -->
    <div class="advice-revenue-area two">
        <div class="container-fluid">
            <div class="advice-content">
                <ul>
                    <li>Quick Loans_Access flexible and reliable loans</li>
                    <li>Vibrant Marketplace_dynamic marketplace offering goods and services</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- End Advice Area -->
@endsection
