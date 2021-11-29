<!-- template -->
<template id="formFeeQuestion-fee_ans_div-free">
    <div class="my-3">
        <h2 class="mb-2">付款人可自行決定要付多少金額，您可限定最低付款金額</h2>
        <h3>ex.公益活動、金額贊助</h3>
    </div>
    <div class="col-12 text-center pb-3" style="background:#9779bb"></div>
    <div class="card p-0">
        <div class="card-title">
            <div class="row mx-auto mt-3 pl-3">
                <p class="mt-2 mr-2">收費標題</p>
                <input type="text" class="form-control col-3 fee_title" placeholder="收費標題">
                <p class="mt-2 mr-2 ml-4">最低支付金額</p>
                <div class="col-3">
                    <input type="number" class="form-control fee_price" placeholder="最低支付金額" min="0" onchange='$(this).minMaxInput()'>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <p class="pb-0 mb-2">收費說明</p>
            <textarea type="text" cols="50" rows="2" class="form-control col-12 fee_content" placeholder="收費說明"></textarea>
        </div>
    </div>
</template>

<template id="formFeeQuestion-fee_ans_div-fix">
    <div class="col-12 text-center pb-3" style="background:#9779bb"></div>
    <div class="card p-0">
        <div class="card-title">
            <div class="row mx-auto mt-3 pl-3">
                <p class="mt-2 mr-2">收費標題</p>
                <input type="text" class="form-control col-3 fee_title" placeholder="收費標題">
                <p class="mt-2 mr-2 ml-4">金額</p>
                <div class="col-3">
                    <input type="number" class="form-control fee_price" placeholder="金額" min="0" onchange='$(this).minMaxInput()'>
                </div>
            </div>
        </div>
        <div class="card-body pt-0">
            <p class="pb-0 mb-2">收費說明</p>
            <textarea type="text" cols="50" rows="2" class="form-control col-12 fee_content" placeholder="收費說明"></textarea>
        </div>
    </div>
</template>

<template id="formFeeQuestion-fee_ans_div-multi">
    <div class="my-3">
        <h2 class="mb-2">如果您的付費方案中有免費方案，請在該方案金額直接輸入0元喔!</h2>
        <h3>ex.有無繳交會費的收費差異</h3>
    </div>
    <ul class="list-group" id="multi_ul">
    </ul>
    <button type="button" class="btn btn-primary mt-3" id="new_multi_option" onclick="formFeeQuestion.newMulti()">新增方案</button>
</template>

<template id="formFeeQuestion-fee_ans_div-multiOption">
    <li class="list-group-item fee_multi_div mb-1">
        <div class="col-12 text-center pb-3 questionHandler" style="background:#9779bb"></div>
        <div class="card p-0">
            <div class="card-title">
                <div class="row mx-auto mt-3 pl-3">
                    <p class="mt-2 mr-2 mb-0">收費標題</p>
                    <input type="text" class="form-control col-3 fee_title" placeholder="收費標題">
                    <p class="mt-2 mr-2 ml-4 mb-0">金額</p>
                    <div class="col-3">
                        <input type="number" min="0" class="form-control fee_price" placeholder="金額" min="0" onchange='$(this).minMaxInput()'>
                    </div>
                    <div class="col-2">
                        <!--空格-->
                    </div>
                    <button type="button" class="btn btn-outline-primary col-1 del_multi_option" onclick="formFeeQuestion.delMulti(this)">X</button>
                </div>
            </div>
            <div class="card-body pt-0">
                <p class="pb-0 mb-2 ">收費說明</p>
                <textarea type="text" cols="50" rows="2" class="form-control col-12 fee_content" placeholder="收費說明"></textarea>
            </div>
        </div>
    </li>
</template>

<script type="text/babel">
const formFeeQuestion = {
        //開啟金流才顯示收費
        cashFlowToggle: function(dom, ctrl = false) {
            var checkBox = dom;
            if (!($(checkBox).prop("checked"))) {
                $("#fee_div").hide();
                $("#feeEmail").hide();
                $("#feeNoneDiv").show();
                return;
            }
            basic.ajaxPost(basic.url("admin/api/editor/checkCashFlow"))
                .done(function(e) {
                    if (e.status == 1) {
                        if ($(checkBox).prop("checked")) {
                            $("#fee_div").show();
                            $("#feeEmail").show();
                            $("#feeNoneDiv").hide();
                        }
                        return;
                    } else if (e.status == 3) {
                        swal({
                            title: "注意",
                            text: "你尚未提供啟用線上支付之相關資料，需要為您開啟分頁前往填寫嗎？",
                            icon: "warning",
                            buttons: ["否", "開啟分頁"],
                        }).then(function(e) {
                            if (e) {
                                window.open(basic.url("admin/account/info#realInfo"), "_blank");
                            }
                        });
                    } else if (e.status == 2) {
                        swal({
                            title: "注意",
                            text: "你的線上支付相關資料審核被拒絕，需要為您開啟分頁前往修正嗎？",
                            icon: "warning",
                            buttons: ["否", "開啟分頁"],
                        }).then(function(e) {
                            if (e) {
                                window.open(basic.url("admin/account/info#realInfo"), "_blank");
                            }
                        });
                    } else if (e.status == 0) {
                        swal("注意", "已收到您的資料，資料審核中，請稍待。", "warning");
                    }
                    $(dom).click();
                });
        },

        fee_ans_div: {
            free: function() {
                let template = document.getElementById('formFeeQuestion-fee_ans_div-free').innerHTML;
                return template;
            },

            fix: function() {
                let template = document.getElementById('formFeeQuestion-fee_ans_div-fix').innerHTML;
                return template;
            },
            multi: function() {
                let template = document.getElementById('formFeeQuestion-fee_ans_div-multi').innerHTML;
                return template;
            },
            multiOption: function() {
                let template = document.getElementById('formFeeQuestion-fee_ans_div-multiOption').innerHTML;
                return template;
            },
            /********************
                收費類型切換
                *********************/
            feeTypeChange: function(el) {
                var fee_type_val = $(el).val();
                switch (fee_type_val) {
                    case feeTypeText.free:
                        $("#fee_ans_div").html(this.fee_ans_div.free);
                        break;
                    case feeTypeText.fix:
                        $("#fee_ans_div").html(this.fee_ans_div.fix);
                        break;
                    case feeTypeText.multi:
                        var feeMultiUl = $(this.fee_ans_div.multi);
                        //將選項組合進去
                        $("#fee_ans_div").html(feeMultiUl);
                        $("#multi_ul").append(this.fee_ans_div.multiOption);

                        //外部引入
                        activityForm.editorSortable($("#multi_ul"));

                        break;
                }
            },
        },
        /********************
         多重收費新增與刪除
        *********************/
        newMulti: function() {
            $("#multi_ul").append(this.fee_ans_div["multiOption"]);
        },
        delMulti: function(el) {
            $(el).parent().parent().parent().parent().remove();
        },

    }
</script>