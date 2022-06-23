import * as Helper from '../modules/helpers.js'
import * as Actions from '../modules/actions.js'

$("#type_education").on("change", async function () {

    const education = $(this).val();

    let url = $(this).data("url");
    // alert(url)
        const {data: users} = await Helper.ajaxCall(url, {education:education}, "GET")
            let htmlElement = ``
            // console.log(users);
            let TeachersDropDown=$("#teachers");
            users.forEach(function (user){
                htmlElement += `<option value="${user.id}">${user.full_name}</option>`
            });
            TeachersDropDown.hide();
            TeachersDropDown.empty().html(htmlElement).parent().fadeIn()
            TeachersDropDown.fadeIn(1000);
})
