<div class="container col-xl-8 col-lg-10 col-md-12 px-0 mx-auto">
    <ul class="nav nav-tabs mb-4" id="settingTab" role="tablist">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="far fa-clipboard"></i> 會員</a>
            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">
                <a class="dropdown-item" data-toggle="tab" href="#profile">個人資料修改</a>
                <!-- <a class="dropdown-item" data-toggle="tab" href="#editPassword">密碼修改</a> -->
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <i class="fa fa-gift "></i> 支付設定</a>
            <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 40px, 0px); top: 0px; left: 0px; will-change: transform;">
                <a class="dropdown-item" data-toggle="tab" href="#realInfo">收款認證</a>
                <a class="dropdown-item" data-toggle="tab" href="#wallet">錢包</a>
            </div>
        </li>
    </ul>

    <div class="tab-content">
        <?php
        $childComponent("tabpanel", [
            "profile", "editPassword", "realInfo", "wallet"
        ]);
        ?>
    </div>
</div>
<script type="text/babel">
    var infoMain = {
        hash : window.location.hash.substring(1),
        tag : ["profile","editPassword","realInfo","wallet"],
        watchFirstTab : function(){
            if(this.hash != "" && this.tag.indexOf(this.hash) != -1){
                this.getItem(this.hash).tab('show');
            }else{
                this.getItem("profile").tab('show');
            }
        },
        getItem : function(tabID){
            return $(`#settingTab li a.dropdown-item[href='#${tabID}']`);
        }
    }
    basic.pushReady(function(){
        infoMain.watchFirstTab();
    });
</script>