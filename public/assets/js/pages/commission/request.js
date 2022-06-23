import * as Helper from "../../modules/helpers.js";

$("form").on("submit", async function (e){
    $("input.is-invalid").removeClass('is-invalid');
    $('.invalid-feedback').fadeOut().text('');
    e.preventDefault();
    let url = $(this).prop("action")
    let method = $(this).prop("method")
    let formData = new FormData($(this)[0]);
    let response = await Helper.formRequest(url, formData, method)
    if(response.status_number === "S400"){
        const {errors} = response
        Object.entries(errors).forEach((field) => {
            const [type, error] = field
            if(Array.isArray(error) || typeof error === 'object'){
                let inputs = $(`input[name="${type}[]"]`);
                for (let index in error){
                    let $Input = $(inputs[index]);
                    $Input.addClass("is-invalid");
                    let $ErrorElement;
                    if(type === "commission" || type === "commission_infinity")
                        $ErrorElement = $Input.parent().siblings('.invalid-feedback')
                    else
                        $ErrorElement = $Input.siblings('.invalid-feedback')
                    $ErrorElement.fadeIn().text(error[index])
                }
            }else{
                const $Input = $(`input[name="${type}"]`);
                $Input.addClass("is-invalid")
                let $ErrorElement;
                if(type === "commission" || type === "commission_infinity")
                    $ErrorElement = $Input.parent().siblings('.invalid-feedback')
                else
                    $ErrorElement = $Input.siblings('.invalid-feedback')

                $ErrorElement.fadeIn().text(response.errors[type])
            }
        })
    }else if(response.status_number === "S201"){
        window.location.reload();
    }





})
