<!DOCTYPE html>
<html lang="zh-tw">
	<head>
		<?php $this->load->view('basic/headLoad'); ?>
		<!-- 頁面所需的額外Script載入放在這裡 -->
		<?php echo $headComponents ?? "" ?>
		<!-- 頁面所需的額外Script載入放在這裡 -->
	</head>
<body>
    <div class="wrapper">

        <!--loging form start-->
        <div class="container col-lg-6 col-md-6">
            <div class="card card-shadow ">
                <div class="card-header">
                    <div class="card-title text-center">
                        <?php echo $bodyTitle ?? "" ?>
                    </div>
                </div>
                <div class="card-body">
                    <?php echo $components ?? "" ?>
                </div>
            </div>
        </div>
        <!--loging form end-->
    </div>
</body>

</html>
