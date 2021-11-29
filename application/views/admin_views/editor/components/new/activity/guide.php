<!-- Modal -->
<div class="modal fade" id="actibeeGuide" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 1100px;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="guideTitle">活動蜂功能導覽01/04</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <button type="button" class="btn" onclick='guide.changePage("prev",0)'><i class="fas fa-chevron-left"></i></button>
                    <img class="col-11" id="guideImg" src="<?php echo base_url() ?>assets/beeImage/guide/guide01.jpg" alt="">
                    <button type="button" class="btn" onclick='guide.changePage("next",0)'><i class="fas fa-chevron-right"></i>
                    </button>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-bee-four" data-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>

<script type="text/babel">
    var guide = {
    changePage:function(change,nowPage){

        //頁數上限
        var maxPage = 4;

        //單頁切換
        var changeIndex = 1;
        if(change=="next"){
            changeIndex = 1;
        }else if(change=="prev"){
            changeIndex = -1;
        }else if(change=="skip"){
            changeIndex = 0;
        }

        var newImgUrl = $("#guideImg").attr("src")
        //取得0幾
        var ImgNum = newImgUrl.substr(-6,2);

        //處理切換頁數
        if(ImgNum.substr(0,1)==0){
            var ImgNum = Number(ImgNum)+changeIndex;
            //如果有指定位置則顯示該張
            if(nowPage!=0){
                ImgNum = nowPage;
            }
            //避免加到沒有圖片
            if(ImgNum<1||ImgNum>maxPage){
                return
            }
            ImgNum = "0"+ImgNum;
        }

        // 更改Title
        $("#guideTitle").html("活動蜂功能導覽"+ImgNum+`/0${maxPage}`);
        newImgUrl = newImgUrl.substring(0, newImgUrl.length-11);
        newImgUrl = newImgUrl + "guide" + ImgNum +".jpg"
        console.log(newImgUrl);
        $("#guideImg").attr("src",newImgUrl);
    },
}
</script>