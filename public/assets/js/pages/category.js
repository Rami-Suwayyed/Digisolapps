import * as Helper from '../modules/helpers.js'


$(document).ready(function(){
    let $SubjectsCommission = $("#SubjectCommission")
    const $SubjectsSelectedBox = $("#SubjectsSelectedBox")

    const drawSubjectSelected = (subjectId, text) =>{
        let htmlElement = `<div class="subject-item" id="SubjectItem${subjectId}">
                                        <input type="hidden" value="${subjectId}" name="subjects[]">
                                        <span class="text">${text}</span>
                                    </div>`
        const $SubjectItem = $(htmlElement)
        $SubjectsSelectedBox.append($SubjectItem)
        $SubjectItem.fadeIn()

        let generalCommission = $("input[name='application_commission']").val()
        htmlElement = `<div class="form-group subject_commiation_${subjectId}">
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


    $(document).on("click", ".action-tree", function (){
        var id = $(this).data("id"),
            url = $(this).data("url"),
        isCheck = $(this).siblings("input").is(":checked"),
        page = $(this).data("page"),
        teacherId = 0,
        type = $(".type").val(),
        priceInput = $("#priceInput").val();
        if(page === "edit")
           teacherId = $(this).data("teacher-id");
        var parent = $(this);
        $.ajax({
            type: "get",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: url,
            data: {"id":id, "isCheck":isCheck, "page":page, "teacher_id":teacherId, "price_value": priceInput, "type": type},
            success: function( response ) {
                if(!parent.hasClass("open")){
                    if(!parent.siblings("ul").hasClass("tree")) {
                        parent.parent("li").append(response.data);
                    }
                    parent.addClass("open").siblings("ul").slideDown();
                }else{
                    parent.removeClass("open").siblings("ul").slideUp();
                }
            },
            error : function (response) {
                console.log("Error!");
            }
        });
    });

    $(document).on("click", ".remove-all-checkbox", async function(){
        const isChecked = $(this).is(":checked");
        let url = $(this).data("sub-url");
        if(isChecked){
            $(".group" + $(this).val()).attr("disabled", true).attr("hidden", true).attr("checked", true);
            let response = await Helper.ajaxCall(url, [], "GET");
            const data = response.data;
            const subjectsSelected = $("#SubjectsSelectedBox .subject-item input[name='subjects[]']")
            let subjectsSelectedValues = []

            for (const subject of subjectsSelected)
                subjectsSelectedValues[subject.value] = true
            data.forEach((subject, ind) => {
                if(!subjectsSelectedValues[subject.id])
                    drawSubjectSelected(subject.id, subject.name)
            })
            $("#SubjectsCommissionButton")[0].removeAttribute("disabled")
        }else{
            $(".group" + $(this).val()).attr("disabled", false).attr("hidden", false).attr("checked", false);
            let response = await Helper.ajaxCall(url, [], "GET");
            const data = response.data;
            data.forEach((subject, ind) => {
                const subjectsSelected = $("#SubjectsSelectedBox #SubjectItem" + subject.id)
                subjectsSelected.remove();
                $(".subject_commiation_" + subject.id).remove();
            })
            const SubjectCommissionSelected = $("#SubjectCommission .form-group");
            if(SubjectCommissionSelected.length === 0)
                $("#SubjectsCommissionButton").attr("disabled", true)

        }
    });

    $(document).on("click", ".remove-all-subject-checkbox", async function (){
        const isChecked = $(this).is(":checked");
        let url = $(this).data("sub-url");
        if(isChecked){
            $(".groupSub" + $(this).val()).attr("disabled", true).attr("hidden", true).attr("checked", true);
            let response = await Helper.ajaxCall(url, [], "GET");
            const data = response.data;
            const subjectsSelected = $("#SubjectsSelectedBox .subject-item input[name='subjects[]']")
            let subjectsSelectedValues = []
            for (const subject of subjectsSelected)
                subjectsSelectedValues[subject.value] = true

            data.forEach((subject, ind) => {
                if(!subjectsSelectedValues[subject.id])
                    drawSubjectSelected(subject.id, subject.name)
            })
            $("#SubjectsCommissionButton")[0].removeAttribute("disabled")
        }else{
            $(".groupSub" + $(this).val()).attr("disabled", false).attr("hidden", false).attr("checked", false);
            let response = await Helper.ajaxCall(url, [], "GET");
            const data = response.data;
            data.forEach((subject, ind) => {
                const subjectsSelected = $("#SubjectsSelectedBox #SubjectItem" + subject.id)
                subjectsSelected.remove();
                $(".subject_commiation_" + subject.id).remove();
            })
            const SubjectCommissionSelected = $("#SubjectCommission .form-group");
            if(SubjectCommissionSelected.length === 0)
                $("#SubjectsCommissionButton").attr("disabled", true)
        }
    });


    $(document).on("click", ".subject-check", function (){
        const isChecked = $(this).is(":checked");
        if(isChecked){
            drawSubjectSelected($(this).val(), $(this).siblings(".action-tree").text())
            $("#SubjectsCommissionButton")[0].removeAttribute("disabled")
        }else{
            const subjectsSelected = $("#SubjectsSelectedBox #SubjectItem" + $(this).val())
            subjectsSelected.remove();
            $(".subject_commiation_" + $(this).val()).remove();
            const SubjectCommissionSelected = $("#SubjectCommission .form-group");
            if(SubjectCommissionSelected.length === 0)
                $("#SubjectsCommissionButton").attr("disabled", true)
        }
    });



});
