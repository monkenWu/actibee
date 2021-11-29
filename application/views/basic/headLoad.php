<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $pageTitle ?></title>
<link rel="shortcut icon" type="image/x-icon" href="Horizontal/favicon.ico">
<!-- google font -->
<link href="<?php echo base_url(); ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<!-- <link href="<?php echo base_url(); ?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css"> -->

<script src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
<!-- <script src="https://kit.fontawesome.com/888bf2b044.js" crossorigin="anonymous"></script> -->


<link href="<?php echo base_url(); ?>assets/css/ionicons.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>assets/css/simple-line-icons.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url(); ?>assets/css/jquery.mCustomScrollbar.css" rel="stylesheet">
<link href="<?php echo base_url(); ?>assets/css/weather-icons.min.css" rel="stylesheet">
<!--Morris Chart -->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/js/index/morris-chart/morris.css">
<?php
$baseurl = base_url();
if (isset($multiFunctions)) {
    echo "<link href='{$baseurl}assets/css/style.css' rel='stylesheet'>";
} else {
    echo "<link href='{$baseurl}assets/css/style-one.css' rel='stylesheet'>";
}
?>


<link href="<?php echo base_url(); ?>assets/css/responsive.css" rel="stylesheet">
<!--JavaScript Load-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/popper.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/sweetalert.min.js"></script>

<script src="<?php echo base_url(); ?>assets/js/babel.min.js"></script>

<!--customer css-->
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/beeStyle.css">

<script type="text/babel">

    function base_url(str=""){
		return '<?php echo base_url() ?>'+str;
	}

    $.fn.getFormObject = function() {
        var obj = {};
        var arr = this.serializeArray();
        arr.forEach(function(item, index) {
            if (obj[item.name] === undefined) { // New
                obj[item.name] = item.value || '';
            } else {                            // Existing
                if (!obj[item.name].push) {
                    obj[item.name] = [obj[item.name]];
                }
                obj[item.name].push(item.value || '');
            }
        });
        return obj;
    };

    $.fn.isBlank = function(unCheck=[]) {
        var blankInput = false;
        var allInput = this.find("input");
        $(allInput).each(function(index){
            if(unCheck.indexOf($(this).attr("name")) != -1){
                return;
            }
            if($(this).data("orgin") == undefined ){
                $(this).data("orgin",$(this).css("border-color"));
            }
            //判斷是否為disable
            if($(this).prop("disabled")==false){
                if($(this).val()==""){
                    $(this).css("border","1px solid #ff3111");
                    blankInput = true;
                }else{
                    $(this).css("border",`1px solid ${$(this).data("orgin")}`);
                } 
            }   
        });
        return blankInput;
    };

    $.fn.minMaxInput = function(){
        //請用一個div將input包起來，避面remove.next其他dom元素
        //max還沒寫
        //顏色處理
        if($(this).data("orgin") == undefined ){
                $(this).data("orgin",$(this).css("border-color"));
            }
        if($(this).attr("min")!=""){
            if($(this).val() < $(this).attr("min")){
                $(this).val($(this).attr("min"));
                $(this).css("border","1px solid #ff3111");
                //加註解
                var warningDiv = `<p class="ml-1 mt-2" style="color:red">請最少輸入${$(this).attr("min")}</p>`;
                    $(this).next().remove();//避免重複疊加，刪除原本的
                    $(this).after(warningDiv);
            }else{
                $(this).css("border",`1px solid ${$(this).data("orgin")}`);
                $(this).next().remove();
            }
        }
        
    };

    var basic = {
        documentReady : [],
        pushReady : function(fun){
			this.documentReady.push(fun);
		},
        url : function(str=""){
            return '<?php echo base_url() ?>'+str;
        },
        ajaxPost : function (postUrl,postData={}){
            return $.ajax({
                url: postUrl,
                type: 'POST',
                dataType: 'json',
                data: {data : JSON.stringify(postData)},
            }).pipe(function(e){
                if(e.status == "dbErrDev"){
                    return $.Deferred().reject({status:e.status,statusText:e.msg,statusCode:e.code});
                }else if((e.status == "dbErr")){
                    return $.Deferred().reject({status:e.status,statusCode:e.code});
                }else{
                    return e;
                }
            }).fail(function(e) {
                swal('執行失敗',basic.errorText(postUrl,e),'error')
                .then(function(){
                    location.reload();
                });
            });
        },
        ajaxPostFile : function (postUrl,file){
            return $.ajax({
                url: postUrl,
                type: 'POST',
                dataType: 'json',
                data: file,
                cache: false, 
                processData: false, 
                contentType: false
            }).pipe(function(e){
                if(e.status == "dbErrDev"){
                    return $.Deferred().reject({status:e.status,statusText:e.msg,statusCode:e.code});
                }else if((e.status == "dbErr")){
                    return $.Deferred().reject({status:e.status,statusCode:e.code});
                }else{
                    return e;
                }
            }).fail(function(e) {
                swal('執行失敗',basic.errorText(postUrl,e),'error')
                .then(function(){
                    location.reload();
                });
            });
        },
        ajaxGetBlob : function(postUrl,data){
            return $.ajax({
                url: postUrl,
                type: "POST",
                data: {
                    data : {data : JSON.stringify(data)}
                },
                xhrFields: {
                    // 將回傳結果以 Blob ，也就是保持原本二進位的格式回傳
                    responseType: "blob"
                }
            }).pipe(function(e){
                if(e.status == "dbErrDev"){
                    return $.Deferred().reject({status:e.status,statusText:e.msg,statusCode:e.code});
                }else if((e.status == "dbErr")){
                    return $.Deferred().reject({status:e.status,statusCode:e.code});
                }else{
                    return e;
                }
            }).fail(function(e) {
                swal('執行失敗',basic.errorText(postUrl,e),'error')
                .then(function(){
                    location.reload();
                });
            });
        },
        errorText : function (postUrl,e){
            var text = "";
            //console.log(e);
            if(e.status == "dbErrDev"){
                if(e.statusCode == 1451){
                    text = "您欲刪除的對象在資料庫內已有所關聯，為求資料完整性無法刪除。";
                    text += '\n網址：'+postUrl;
                    text += '\n狀態碼：('+e.status+')'; 
                    text += '\n伺服器訊息：('+e.statusCode+')'+e.statusText;
                }else{
                    text = "資料庫出現錯誤，請重新再試，若錯誤重複出現請回傳本畫面給予資訊人員。";
                    text += '\n網址：'+postUrl;
                    text += '\n狀態碼：('+e.status+')'; 
                    text += '\n伺服器訊息：('+e.statusCode+')'+e.statusText;
                }
            }else if(e.status == "dbErr"){
                if(e.statusCode == 1451){
                    text = "您欲刪除的對象在資料庫內已有所關聯，為求資料完整性無法刪除。";
                    text += '\n狀態碼：('+e.status+')'; 
                }else{
                    text = "資料庫出現錯誤，請重新再試，若錯誤重複出現請回傳本畫面給予資訊人員。";
                    text += '\n網址：'+postUrl;
                    text += '\n狀態碼：('+e.status+')'; 
                }
            }else if (e.status == 200){
                text = "無法解析伺服器回傳內容，請重新再試，若錯誤重複出現請回傳本畫面給予資訊人員。";
                text += '\n網址：'+postUrl;
                text += '\n伺服器訊息：('+e.status+')'+"Server return data not a json-text.";
            }else{
                text = "連線出現異常，請重新再試，若錯誤重複出現請回傳本畫面給予資訊人員。";
                text += '\n網址：'+postUrl;
                if(e.status == "dbErrDev"){
                    text += '\n伺服器訊息：('+e.status+')'+e.statusText;
                }else{
                    text += '\n狀態碼：('+e.status+')';
                }
            }
            return text;
        }
    }

	$(document).ready(function() {
		basic.documentReady.forEach(element => {
			element();
		});
	});
    
</script>

<?php if (isset($reCAPTCHA)) : ?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?= $reCAPTCHA ?>"></script>
    <script type="text/babel">
        basic.setReCAPTCHA = function(){
            grecaptcha.execute('<?= $reCAPTCHA ?>', {action: '<?= $reCAPTCHA_pageName ?>'}).then(function(token) {
                basic.reCAPTCHA = token;
            });
        };
        grecaptcha.ready(function() {
            basic.setReCAPTCHA();
        });
    </script>
<?php endif; ?>

<?php if (isset($ajaxToken)) : ?>
    <script type="text/babel">
        basic.ajaxToken = "<?= $ajaxToken ?>";
    </script>
<?php endif; ?>

<?php if (isset($ajaxTokenID)) : ?>
    <script type="text/babel">
        basic.ajaxTokenID = "<?= $ajaxTokenID ?>";
    </script>
<?php endif; ?>