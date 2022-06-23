import * as Helper from '../modules/helpers.js'


$(document).ready(function(){

    $(document).on("click", ".add-new-row", () => {
        let htmlElement = $(`script#RowTemplate`).html();
        $('.quantity-prices-box').append($(htmlElement))
    })


    $(document).on("click", "span.delete-option", async function () {
        if ($(this).hasClass("server-delete")) {
            const button = $(this)
            const id = $(this).data("id"),
                url = $(this).data('url'),
                userId = $(this).data("user-id"),
                lang = $('html').attr('lang')
            swal({
                title: button.data('title'),
                icon: "warning",
                buttons: [button.data("no"),button.data("yes")],
                dangerMode: true,
            }).then(async (willDelete) => {
                if(willDelete){
                    const response = await Helper.ajaxCall(url, {id, userId})
                    if(response.status_number === 'S201'){
                        swal($(this).data("success-message"), {
                            icon: "success",
                        });
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    }else{
                        swal($(this).data("error-message"), {
                            icon: "error",
                        });
                    }
                }
            })

        } else
            $(this).parents(".option-quantity-box").remove()
    })


});
