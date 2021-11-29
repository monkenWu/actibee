<style>

    .beedie-bg{
        height: 100vh;
    }

    body{
        display: block;
        background-color: white;
        /* overflow:hidden */
    }


    .beedie-top-bg {
        position: relative;
        right: 20px;
        display: block;
        width: 130%;
        height: 31rem;
        z-index: -1;
        background: url('<?php echo base_url() ?>assets/beeImage/beedieUp.png') no-repeat center center/cover;
        /*    background-position: 0px -300px;*/
    }

    h1{ 
        font-weight:700;
        font-size:50px;
        margin-bottom:20px;
    }

    .beedie-text{
        position: relative;
        bottom: 50px;
    }

    .beedie-down-bg {
        position: relative;
        right: 250px;
        bottom: 300px;
        display: block;
        width: 50%;
        height: 31rem;
        z-index: -1;
        background: url('<?php echo base_url() ?>assets/beeImage/beedieDown.png') no-repeat center center/cover;
        /*    background-position: 0px -300px;*/
    }

    .footer{
        position: relative;
        bottom: 450px;
        height: 120px;
        background-color: var(--main-green);
    }
</style>