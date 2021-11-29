<div class="nav-div">
    <nav class="pt-1 pb-1 fixed-top">
        <ul class="nav justify-content-end">
            <?php if ($isLogin) : ?>
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo base_url("admin") ?>">進入系統</a>
                </li>
            <?php else : ?>
                <li class="nav-item">
                    <a class="nav-link active" href="<?php echo base_url("login") ?>">登入</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url("signup") ?>">註冊</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>
<div class="container-fluid mt-5 mb-5 pt-5 pb-5 px-0 logo-showcase">
    <div class="row mx-auto col-12 col-md-9">
        <!-- <img class="col-12 col-md-4" src="<?php echo base_url() ?>assets/beeImage/logo.png" alt="活動蜂"> -->
        <!-- <div class="logo-img col-12 col-md-5"> -->
        <div class="col-12 col-md-5">
            <img class="col-12" src="<?php echo base_url() ?>assets/beeImage/logo.png" alt="活動蜂">
        </div>
        <!-- <h1 class="text-center logo col-12">活動蜂</h1> -->
        <div class="text-center mt-4 col-12 col-md-4 my-auto">
            <h1 class="logo-title">Actibee</h1>
            <h3 style="font-size:20px;font-weight:700">活動蜂</h3>
        </div>
    </div>
</div>