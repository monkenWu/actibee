<header class="main-header">
    <div class="container_header col-xl-10 mx-auto">
        <a href="<?php echo base_url() ?>home">
            <div class="logo d-flex align-items-center p-0">
                <div class="login-logo ml-3 mr-2"></div>
                <img height="50" src="<?php echo base_url() ?>assets/beeImage/logo.png" alt="">
                <h5>
                    Actibee
                </h5>
            </div>
        </a>
        <div class="right_detail">
            <div class="row d-flex align-items-center min-h pos-md-r"> 
                <div class="col-xl-5 col-3 search_col "><!-- 空格 --></div>
                <div class="col-xl-6 col-8 d-flex justify-content-end">
                    <div class="right_bar_top d-flex align-items-center">
                        <!-- logout_Start -->
                        <div class="dropdown dropdown-notification">
                            <a title="登出" onclick="checkLogout()" href="#" class="dropdown-toggle"><i class="fas fa-sign-out-alt fa-1x"></i></i> </a>
                        </div>
                        <!-- logout_End -->
                        <!-- notification_Start -->
                        <!-- <div class="dropdown dropdown-notification">
                            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" aria-expanded="false"> <i class="fas fa-bell"></i> <span class="badge_coun"> 1 </span> </a>
                            <ul class="dropdown-menu scroll_auto height_fixed">
                                <li class="bigger">
                                    <h3><span class="bold">系統通知</span></h3>
                                </li>
                                <li>
                                    <ul class="dropdown-menu-list">
                                        <li>
                                            <a href="javascript:;"> <span class="time">入帳</span> <span class="details"> <span class="notification-icon deepPink-bgcolor"> <i class="fa fa-check"></i> </span> OOO專案有新的款項 </span> </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div> -->
                        <!-- notification_End -->
                        <!-- 回帳戶 -->
                        <div class="dropdown dropdown-notification">
                            <a title="個人首頁" href="<?php echo base_url() ?>admin/account" class="dropdown-toggle"> <i class="fas fa-home"></i></a>
                        </div>
                        <!--回帳戶_End -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/babel">
        function checkLogout(){
            swal({
                icon:"warning",
                title:"確定要登出嗎?",
                buttons: ["先不用","確定"]
            }).then((result)=>{
                if(result){
                    window.location.href="<?php echo base_url('login/out') ?>";
                }
            });
            
        } 
    </script>
    <?php echo $multiFunctions ?? "" ?>
</header>