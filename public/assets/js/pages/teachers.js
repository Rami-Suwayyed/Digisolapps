import * as Helper from '../modules/helpers.js'
import * as Actions from '../modules/actions.js'

$("#TeacherCategory").on("change", async function () {
    const $TeachersDropDown = $('#TeacherLeaders')
    const typeSelected = $(this).val();
    if (typeSelected === "ts") {
        const {data: users} = await Helper.ajaxCall(`/${$('html').prop("lang")}/ajax/teachers/all/leaders`, [], "GET")
        let htmlElement = ``
        users.forEach(function (user){
            htmlElement += `<option value="${user.id}">${user.full_name}</option>`
        });
        $TeachersDropDown.empty().html(htmlElement).parent().fadeIn()
    }else{
        $TeachersDropDown.empty().parent().fadeOut()
    }
})
$(".teacher-form").on("submit",  async function (e) {
    e.preventDefault();
    const data = new FormData($(this)[0])
    const response = await Helper.formRequest(`/${$('html').prop("lang")}/ajax/teachers/types/update`, data)
    window.location.reload()
})

$("#CopyTeacherInfo").on("click", async function () {
    const fullNameInfo = await $("#FullNameInfo"),
        usernameInfo = await $("#UsernameInfo"),
        emailInfo = await $("#EmailInfo"),
        passwordInfo = await $("#PasswordInfo");
    let text = ` Full name : ${fullNameInfo.text()} \n Username: ${usernameInfo.text()} \n Email: ${emailInfo.text()} \n Password: ${passwordInfo.text()} `
    await Actions.copy(text)
})
