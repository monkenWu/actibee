<!DOCTYPE html>
<html lang="zh-tw">

<head>
    <?php $this->load->view("basic/headLoad") ?>
    <?php echo $headComponents ?? "" ?>
</head>

<body>
    <div class="container-fluid">   
        <?php echo $components ?? "" ?>
    </div>
    <?php if(isset($_SESSION["verify"])): ?>
        <?php if($_SESSION["verify"] == "success"): ?>
            <script>
                swal("認證成功","信箱認證成功！立即登入吧！","success");
            </script>
        <?php else:?>
            <script>
                swal("認證失敗","請確認來源網址是否正確。","error");
            </script>
        <?php endif;?>
    <?php endif;?>
</body>
</html>
