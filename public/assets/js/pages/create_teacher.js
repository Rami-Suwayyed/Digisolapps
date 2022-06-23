import * as Helper from "../modules/helpers.js"
$(document).ready(function (){
    let $SubjectsCommission = $("#SubjectCommission")
    const $SubjectsSelectedBox = $("#SubjectsSelectedBox")


    const drawSubjectSelected = (subjectId, text) =>{
        let htmlElement = `<div class="subject-item" id="SubjectItem${subjectId}">
                                        <input type="hidden" value="${subjectId}" name="subjects[]">
                                        <span class="text">${text}</span>
                                        <span class="icon-close"><i class="fas fa-times"></i></span>
                                    </div>`
        const $SubjectItem = $(htmlElement)
        $SubjectsSelectedBox.append($SubjectItem)
        $SubjectItem.fadeIn()

        let generalCommission = $("input[name='application_commission']").val()
        htmlElement = `<div class="form-group">
                                        <div class="row">
                                            <div class="col-lg-2">
                                                <h5 style="padding-top: 7px;">${text}</h5>
                                            </div>
                                            <div class="col-lg-5">
                                                <div class="input-group">
                                                    <input class="form-control" type="number" value="0" name="subject_commission_${subjectId}">
                                                    <div class="input-group-append"><span class="input-group-text">%</span></div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                </div>`;
        $SubjectsCommission.append(htmlElement)
    }


    $("#MainCategories").on("change", async function () {
        const option = $(this).find(":selected"),
            $MainCategoriesSelect = $(this),
            $SubCategoriesSelect = $("#SubCategories")

        let url = option.data("sub-url")

        console.log(url)
        if (url !== null && url !== undefined) {
            let response = await Helper.ajaxCall(url, [], "GET");
            let htmlElement = '';
            const data = response.data
            if(data.length > 0){
                htmlElement = '<option value="">None</option>'
                data.forEach((category, ind) => {
                    htmlElement += `<option value="${category.id}">${category.name}</option>`
                })
                $SubCategoriesSelect.empty().append(htmlElement).parents("#SubCategoriesBox").fadeOut(400, function(){
                    $(this).fadeIn()
                })

            }else{
                $SubCategoriesSelect.empty().parents("#SubCategoriesBox").fadeOut()
            }
        }
    });


    $(document).on("change", "#SubCategories", async function () {

        const subCategoryId = $(this).val(),
            mainCategoryId = $("#MainCategories").val(),
            $SubjectsBox = $("#SubjectsBox"),
            lang = $("html").attr('lang')
        console.log(subCategoryId, mainCategoryId)

        if (subCategoryId && mainCategoryId ) {
            let response = await Helper.ajaxCall(`/${lang}/ajax/main-categories/${mainCategoryId}/sub-categories/${subCategoryId}/subjects`, [], "GET");
            let htmlElement = '';
            const data = response.data
            console.log(data)
            const subjectsSelected = $("#SubjectsSelectedBox .subject-item input[name='subjects[]']")
            let subjectsSelectedValues = []

            for (const subject of subjectsSelected)
                subjectsSelectedValues[subject.value] = true

            if (data.length > 0) {
                htmlElement = ` <div class="col-lg-3 subject-checkbox-main-box">
                                        <div class="form-group">
                                          <div class="animated-checkbox">
                                                <label>
                                                    <input type="checkbox"  class="subjects-checkbox checked-creator-action all">
                                                        <span class="label-text">All</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>`;
                data.forEach((subject, ind) => {
                    htmlElement += ` <div class="col-lg-3 subject-checkbox-main-box">
                                        <div class="form-group">
                                          <div class="animated-checkbox">
                                                <label>
                                                    <input type="checkbox"  class="subjects-checkbox checked-creator-action" value="${subject.id}" ${subjectsSelectedValues[subject.id] ? "checked" : ''}>
                                                        <span class="label-text">${subject.name}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>`;
                })
                $SubjectsBox.empty().append(htmlElement).fadeOut(function (){
                    $(this).fadeIn();
                });
            } else {
                $SubjectsBox.empty().fadeOut()
            }
        }
    });



    $(document).on("change", ".subjects-checkbox",function(){

        const isChecked = $(this).is(":checked")


        if(isChecked){
            if($(this).hasClass("all")){
                const $SubjectsCheckBoxes = $(this).parents(".subject-checkbox-main-box").siblings().find(".subjects-checkbox");
                $SubjectsCheckBoxes.attr("checked", true)
                for (let $SubjectCheckBox of $SubjectsCheckBoxes){
                    $SubjectCheckBox = $($SubjectCheckBox);
                    let text = $SubjectCheckBox.siblings(".label-text").text(),
                        subjectId = $SubjectCheckBox.val()
                    drawSubjectSelected(subjectId, text)
                }
                $("#SubjectsCommissionButton")[0].removeAttribute("disabled")
            }else{
                let text = $(this).siblings(".label-text").text(),
                    subjectId = $(this).val()
                if(document.getElementById(`SubjectItem${subjectId}`) === null){
                    drawSubjectSelected(subjectId, text)
                    $("#SubjectsCommissionButton")[0].removeAttribute("disabled")
                }
            }

        }else{
            const item = document.getElementById(`SubjectItem${$(this).val()}`)
            item.remove()
            $(`input[name='subject_commission_${$(this).val()}']`).parents(".form-group").remove()

        }
    })

    $(document).on("click", ".subject-item .icon-close", function (){
        console.log("s")
        const $SubjectsCheckboxes = $("#SubjectsSelectedBox .subject-item input[name='subjects[]']"),
            subjectId  = $(this).siblings("input").val()
        $(this).parents(".subject-item").remove()
    })



});
