import * as Helper from '../modules/helpers.js'
const lang = $('html').prop("lang")
$("#AssignTeacher").on("shown.bs.modal", async function () {
    const response = await Helper.ajaxCall(`/${lang}/ajax/teachers/all`, [], "GET")
    let htmlElement;
    for (const teacher of response.data)
        htmlElement += `<option value="${teacher.id}">${teacher.full_name}</option>`
    $("#Teachers").empty().append(htmlElement)
});
