<h3>風格</h3>
<section>
    <div class="container d-flex row justify-content-around mx-auto">
        <div class="col-11  text-center">
            <form>
                <div class="mb-5 mt-5 pb-3 row justify-content-center">
                    <h1 style="font-size:26px;font-weight:500;">窩們的主題風格</h1>
                    <div class="ml-2 pointer" style="width:30px" data-toggle="modal" data-target="#actibeeGuide" onclick='guide.changePage("skip",3)'>
                        <i class="fas fa-question-circle" style="color:var(--main-green)"></i>
                    </div>
                </div>
                <br>
                <div class="form-group mt-4" id="formStyleDiv">
                    <div class="row">
                        <label for="formStyleOne" class="col-5 col-md-2 text-center p-0 mx-auto mb-4">
                            <div class="card">
                                <div class="card-title mt-2">落葉黃&nbsp;楓橘</div>
                                <div class="card-body p-0">
                                    <div name="mainColor" style="background:#F9C16E;height:80px" class="col-12"></div>
                                    <div name="secondColor" style="background:#F1B434;height:80px" class="col-12"></div>
                                </div>
                            </div>
                            <input name="formStyle" id="formStyleOne" type="radio" class="col-2 mx-auto mt-2" checked>
                        </label>

                        <label for="formStyleTwo" class="col-5 col-md-2 text-center p-0 mx-auto mb-4">
                            <div class="card">
                                <div class="card-title mt-2">韭青&nbsp;藤黄</div>
                                <div class="card-body p-0">
                                    <div name="mainColor" style="background:#92B39C;height:80px" class="col-12"></div>
                                    <div name="secondColor" style="background:#CFB964;height:80px" class="col-12"></div>
                                </div>
                            </div>
                            <input name="formStyle" id="formStyleTwo" type="radio" class="col-2 mx-auto mt-2">
                        </label>

                        <label for="formStyleThree" class="col-5 col-md-2 text-center p-0 mx-auto mb-4">
                            <div class="card">
                                <div class="card-title mt-2">寂靜藍&nbsp;極境藍</div>
                                <div class="card-body p-0">
                                    <div name="mainColor" style="background:#2E323C;height:80px" class="col-12"></div>
                                    <div name="secondColor" style="background:#5B7D87;height:80px" class="col-12"></div>
                                </div>
                            </div>
                            <input name="formStyle" id="formStyleThree" type="radio" class="col-2 mx-auto mt-2">
                        </label>


                        <label for="formStyleCustom" class="col-5 col-md-2 text-center p-0 mx-auto mb-4">
                            <div class="card">
                                <div class="card-title mt-2">自訂風格(即將推出)</div>
                                <div class="card-body p-0">
                                    <div name="mainColor" style="background:white;height:80px" class="col-12"></div>
                                    <div name="secondColor" style="background:white;height:80px" class="col-12"></div>
                                </div>
                            </div>
                            <input name="formStyle" id="formStyleCustom" type="radio" style="display:none" class="col-2 mx-auto mt-2" disabled>
                        </label>
                    </div>
                </div>
            </form>
            <button id="preview_btn" type="button" onclick="stylePage.clickPreviewBtn" class="btn btn-bee-one mt-5 col-5">預覽</button>

        </div>
    </div>
</section>

<script>
    function stylePage() {

        function clickPreviewBtn() {
            if (!activity.checkInputAll()) return;
            activity.getPreview(activityForm.record_form());
        }

        //對外介面
        return {
            clickPreviewBtn : clickPreviewBtn(),
        }
    }
</script>