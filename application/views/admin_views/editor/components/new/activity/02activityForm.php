<h3>製作表單</h3>
<section>
    <div class="container" id="formWrap">


        <div class="mb-5 mt-5 pb-3 row justify-content-center">
            <h1 style="font-size:26px;font-weight:500;">製作活動報名表單</h1>
            <div class="ml-3 pointer" style="width:30px" data-toggle="modal" data-target="#actibeeGuide" onclick='guide.changePage("skip",2)'>
                <i class="fas fa-question-circle" style="color:var(--main-green)"></i>
            </div>
        </div>

        <div class="col-12 my-5 py-3" id="questionBackTip">
            <h1 class="text-center" style="font-size:40px;color:var(--third-gray)">按下方新增問題開始製作你的表單吧!</h1>
        </div>
        <form id="edit_form" style="display:none">
            <div class="custom-control custom-checkbox col-12 mt-3">
                <input type="checkbox" class="form-check-input mt-1 feeCheck" id="cashFlow" onchange="formFeeQuestion.cashFlowToggle(this)">
                <label for="cashFlow">
                    <h1>啟用線上支付</h1>
                </label>
            </div>
            <div id="feeEmail" style="display:none">
                <!-- <h3 class="mt-2">開啟線上支付，都會向付款人要求email來通知付款狀況喔！</h3> -->
                <li class="list-group-item pt-0 mt-2 mb-2 px-0">
                    <div class="col-12 text-center pb-3 mb-3" style="background:var(--main-purple)"></div>
                    <div class="form-group  col-11 mb-1 mx-auto" question_num="">
                        <div class="container">
                            <div class="row">
                                <input type="text" class="form-control col-8 question_tittle" placeholder="開啟線上支付，都會向付款人要求email來通知付款狀況喔！" disabled>
                                <div class="col-1">
                                    <!--空格用-->
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            </div>
            <div class="mt-5 mb-5">
                <ol id="form_ul" class="list-group col-12">

                </ol>
            </div>
        </form>
        <div class="row">
            <div class="col-3"></div>
            <button type="button" class="btn btn-bee-one mt-3 col-12 col-md-6" style="font-size:20px" onclick="activityForm.newQuestion()">新增問題</button>
            <div class="col-3"></div>
        </div>
        <div id="fee_div" style="display:none">
            <div class="mb-5 mt-5 pb-3 ml-3 row">
                <h1 style="font-size:26px;font-weight:500;">活動收費</h1>
                <div class="ml-3 pointer" style="width:30px" data-toggle="modal" data-target="#actibeeGuide" onclick='guide.changePage("skip",2)'>
                    <i class="fas fa-question-circle" style="color:var(--main-green)"></i>
                </div>
            </div>
            <select class="form-control col-3 question_type mb-1" id="fee_type" onchange="formFeeQuestion.feeTypeChange(this)">
                <option selected>一般定價收費</option>
                <option>付費者自行輸入</option>
                <!-- <option>多種費用</option> -->
            </select>
            <div id="fee_ans_div">
                <div class="my-3">
                    <h2 class="mb-2">如果您的付費方案中有免費方案，請在該方案金額直接輸入0元喔!</h2>
                    <h3>ex.有無繳交會費的收費差異</h3>
                </div>
                <ul class="list-group" id="multi_ul">
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
                </ul>
                <button type="button" class="btn btn-primary mt-3" id="new_multi_option" onclick="formFeeQuestion.newMulti()">新增方案</button>
            </div>
        </div>
        <div id="feeNoneDiv">
            <!-- <h2 class="mt-5 mb-3">未開啟線上支付服務</h2> -->
        </div>
        <div class="mb-5 pb-5"></div>

    </div>
</section>