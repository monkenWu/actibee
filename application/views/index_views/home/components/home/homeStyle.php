<!-- //引入滾動淡入功能 -->
<link rel="stylesheet" href="<?php echo base_url() ?>assets/WOW/animate.css">
<!-- <script src="<?php echo base_url() ?>assets/WOW/wow.js"></script> -->
<script src="<?php echo base_url() ?>assets/WOW/wow.min.js"></script>

<script type="text/babel">
    basic.pushReady(function(){
        new WOW({
            boxClass:     'wow',      // default
            animateClass: 'bounce', // default
            offset:       50,          // default
            mobile:       true,       // default
            live:         true        // default
        }).init(); //引入滾動淡入功能
    });
    
</script>

<style>
    html {
        scroll-behavior: smooth;
    }

    body {
        background-color: var(--main-white);
    }

    /*el*/
    a {
        color: white;
    }

    .nav-link {
        color: white;
        -webkit-transition-duration: 0.2s;
        transition-duration: 0.2s;
    }

    .nav-link:hover {
        color: black;
    }


    button {
        background-color: var(--main-yellow);
        border: none;
        color: black;
        padding: 12px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        border-radius: 6px;
    }

    button:hover {
        background-color: #ffac00;
        color: white;
        -webkit-transition-duration: 0.2s;
        transition-duration: 0.2s;
    }

    * {
        font-family: Microsoft JhengHei;
        /*    overflow-x: hidden;*/

    }

    p,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    button {
        font-family: Microsoft JhengHei;
        font-weight: 800;
    }


    /*nav*/
    nav {
        background-color: var(--main-green);
    }

    /* .logo {
        margin: 0rem;
        font-weight: 1000;
        font-size: 5rem;
        color: var(--main-green);
    } */

    .nav-div hr {
        border-top: 1px var(--main-green) solid;
    }



    /*Logo Showcase*/
    .logo-title {
        font-size: 70px;
    }



    /*Section1*/
    .showcase1 {
        margin-left: 2vw;
        margin-right: 2vw;
    }

    .showcase1 .background {
        display: block;
        width: 100%;
        height: 31rem;
        z-index: -3;
        background: url('<?php echo base_url() ?>assets/homePageImg/background/showcase.jpg') no-repeat center center/cover;
        /*    background-position: 0px -300px;*/
        border-radius: 0 5rem 0 0;

    }


    .left-showcase {
        background-color: rgba(0, 0, 0, 0.6);
        height: 20rem;


    }

    .left-showcase h1,
    .left-showcase p,
    .left-showcase h4,
    .left-showcase hr {
        color: white;
    }

    .left-showcase hr {
        border-top: 1px white solid;
    }


    .right-showcase {
        display: block;
        background-color: white;
    }

    /*intro*/
    .intro-bg {
        display: block;
        width: 100%;
        height: 45rem;
        background: url('<?php echo base_url() ?>assets/homePageImg/background/intro_bg.png') no-repeat center center/cover;
        z-index: -3;
    }

    .intro-wrapper {
        margin-top: 1rem;
        margin-bottom: 1rem;
    }

    .intro-div {
        /*
    display: -webkit-box;
    display: -ms-flexbox;
    -webkit-box-orient: vertical;
    -webkit-box-direction: normal;
    -ms-flex-direction: column;
    flex-direction: column;
    display: flex;
*/
        display: block;
        width: 12rem;
        height: 13rem;
        background-color: white;
        border-radius: 1rem;
    }

    .intro1 {
        display: block;
        width: 10rem;
        height: 10rem;
        background: url('<?php echo base_url() ?>assets/homePageImg/icon/1.png') no-repeat center center/cover;
        background-size: 250px 250px;
    }

    .intro2 {
        display: block;
        width: 10rem;
        height: 10rem;
        background: url('<?php echo base_url() ?>assets/homePageImg/icon/2.png') no-repeat center center/cover;
        background-size: 250px 250px;
        background-position: -40px -15px;
    }

    .intro3 {
        display: block;
        width: 10rem;
        height: 10rem;
        background: url('<?php echo base_url() ?>assets/homePageImg/icon/3.png') no-repeat center center/cover;
        background-size: 250px 250px;
    }

    .intro4 {
        display: block;
        width: 10rem;
        height: 10rem;
        background: url('<?php echo base_url() ?>assets/homePageImg/icon/4.png') no-repeat center center/cover;
        background-size: 250px 250px;
    }

    .intro5 {
        display: block;
        width: 10rem;
        height: 10rem;
        background: url('<?php echo base_url() ?>assets/homePageImg/icon/5.png') no-repeat center center/cover;
    }


    /*short intro*/
    .short-intro {
        display: block;
        width: 85%;
        height: 7rem;
        border-radius: 0 7rem 0 0;
        background-color: var(--main-green);
    }

    .short-intro h3,
    .short-intro h1 {
        color: white;
    }


    /*Intro2*/
    /*

.intro2-bg {
    display: block;
    width: 30rem;
    height: 30rem;
    background: url('icon/6.png') no-repeat center center/cover;
    z-index: -4;
}
*/

    .intro-title {
        color: var(--main-green);
    }

    .intro2-item {
        background-color: rgba(5, 90, 18, 0.3);
        border-radius: 1rem;
    }

    .intro2-text {
        background-color: white;
        border-radius: 1rem;
    }

    .intro2-1 {
        display: block;
        width: 10rem;
        height: 10rem;
        background: url('<?php echo base_url() ?>assets/homePageImg/icon/7.png') no-repeat center center/cover;
        background-size: 300px 300px;

    }

    .intro2-2 {
        display: block;
        width: 10rem;
        height: 10rem;
        background: url('<?php echo base_url() ?>assets/homePageImg/icon/8.png') no-repeat center center/cover;
        background-size: 300px 300px;
    }

    .intro2-3 {
        display: block;
        width: 10rem;
        height: 10rem;
        background: url('<?php echo base_url() ?>assets/homePageImg/icon/9.png') no-repeat center center/cover;
        background-size: 300px 300px;
    }

    .intro2-4 {
        display: block;
        width: 10rem;
        height: 10rem;
        background: url('<?php echo base_url() ?>assets/homePageImg/icon/10.png') no-repeat center center/cover;
        background-size: 300px 300px;
    }



    /*ecpay*/
    .ecpay-img {
        display: block;
        width: 30rem;
        height: 10rem;
        background: url('<?php echo base_url() ?>assets/homePageImg/icon/ecPay.png') no-repeat center center/cover;
        background-size: 300px 300px;
    }


    /*bonus*/
    .bonus-up-bg {
        display: block;
        width: 100%;
        height: 10rem;
        /*background: url('background/bouns-up.png') no-repeat center center/cover;*/
        background-color: var(--main-yellow);
        z-index: -3;
    }

    .bonus-down-bg {
        display: block;
        width: 100%;
        height: 13rem;
        /*background: url('background/bouns-down.png') no-repeat center center/cover;*/
        background-color: var(--main-green);
        z-index: -3;
    }

    .bonus-title {
        position: relative;
        top: 7rem;
        text-shadow: 7px 5px #124512;
        color: white;
        font-size: 5rem;
        font-weight: 1000;
        letter-spacing: 3rem;
        z-index: 2;
    }

    .bonus-text h3,
    .bonus-text h5 {
        color: white;
        position: relative;
        top: 4.5rem;
    }



    /*photo*/
    .photo-bg {
        display: block;
        width: 70%;
        height: 30rem;
        background: url('<?php echo base_url() ?>assets/homePageImg/background/photo.png') no-repeat center center/cover;
        z-index: -3;
    }

    .photo-title {
        position: relative;
        top: -10rem;
        color: var(--main-green);
        font-size: 5rem;
        font-weight: 1000;
    }

    .photo-show {
        position: relative;
        top: -8rem;
    }

    /*Footer*/
    .footer {
        background-color: var(--main-white);
    }

    .footer .left {
        margin: 0;
        color: white;
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
        background-color: var(--main-green);
    }

    .footer .left:hover {
        background-color: #005028;
        -webkit-transition-duration: 0.2s;
        transition-duration: 0.2s;
    }

    .footer .right {
        margin: 0;
        color: white;
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
        background-color: var(--main-yellow);
    }

    .footer .right:hover {
        background-color: #ffc400;
        -webkit-transition-duration: 0.2s;
        transition-duration: 0.2s;
    }

    .footer-center {
        background-color: rgba(5, 90, 18, 0.3);
    }

    .footer-end {
        height: 5rem;
        background-color: var(--main-green);
    }

    .footer-end p {
        color: white;
    }




    @media (max-width: 992px) {

        .showcase1 .background {
            height: 50rem;
        }

        .intro-bg {
            height: 60rem;
        }

        .bonus-up-bg {
            height: 15rem;
        }

        .bonus-text {
            top: 3rem;
        }

        .photo-bg {
            width: 100%;
        }

        .fa-3x {
            font-size: 8rem;
        }

        .footer-center p {
            font-size: 3rem;
        }
    }


    @media (max-width: 600px) {


        /*Logo Showcase*/
        .logo-title {
            font-size: 50px;
        }

        .showcase1 .background {
            height: 50rem;
        }

        .left-showcase {
            height: 30rem;
        }

        .right-showcase {
            margin-top: 0;
        }

        .intro-bg {
            height: 100rem;
        }

        .short-intro {
            border-radius: 0;
            width: 100%;
        }

        .short-intro h1 {
            font-size: 2rem;
        }

        .short-intro h3 {
            font-size: 1rem;
        }


        .intro2-item {
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
        }

        .bonus-up-bg {
            height: 3rem;
        }

        .bonus-title {
            font-size: 3rem;
            top: 2rem;
            letter-spacing: 1rem;
        }


        .bonus-down-bg {
            height: 10rem;
        }

        .bonus-text {

            top: 2.5rem;
        }

        .bonus-text h3 {
            font-size: 1.5rem;
        }

        .bonus-text h5 {
            font-size: 1rem;
        }

        .photo-bg {
            height: 20rem;
        }

        .footer h3 {
            font-size: 1rem;
        }

        .footer-end {
            height: 7rem;
        }

        .footer-btn-wrapper {
            -webkit-box-orient: vertical;
            -webkit-box-direction: normal;
            -ms-flex-direction: column;
            flex-direction: column;
        }

        .fa-3x {
            font-size: 6rem;
        }

        .footer-center p {
            font-size: 2rem;
        }
    }
</style>