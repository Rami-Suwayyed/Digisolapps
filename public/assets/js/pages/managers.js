import * as Helper from '../modules/helpers.js'
import * as Actions from '../modules/actions.js'

$("#ManagersCategory").on("change", async function () {
    const $ManagersDropDown = $('#ManagerLeaders')
    const typeSelected = $(this).val();
    if (typeSelected === "ts") {
        const {data: managers} = await Helper.ajaxCall(`/${$('html').prop("lang")}/ajax/managers/all/change`, [], "GET")
        let htmlElement = ``
        managers.forEach(function (manager){
            htmlElement += `<option value="${manager.id}">${manager.full_name}</option>`
        });
        $ManagersDropDown.empty().html(htmlElement).parent().fadeIn()
    }else{
        $ManagersDropDown.empty().parent().fadeOut()
    }
})
$(".manager-form").on("submit",  async function (e) {
    e.preventDefault();
    const data = new FormData($(this)[0])
    const response = await Helper.formRequest(`/${$('html').prop("lang")}/ajax/managers/change/update`, data)
    window.location.reload()
})

$("#CopyManagerInfo").on("click", async function () {
    const fullNameInfo = await $("#FullNameInfo"),
        usernameInfo = await $("#UsernameInfo"),
        emailInfo = await $("#EmailInfo"),
        passwordInfo = await $("#PasswordInfo");
    let text = ` Full name : ${fullNameInfo.text()} \n Username: ${usernameInfo.text()} \n Email: ${emailInfo.text()} \n Password: ${passwordInfo.text()} `
    await Actions.copy(text)
})
