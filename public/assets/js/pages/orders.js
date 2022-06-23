import * as Helper from '../modules/helpers.js'
const lang = $('html').prop("lang")
$(".change-activation").on("change", async function (e) {
    const activation = $(this).prop("checked") ? 1 : 0
    console.log(activation)
    const orderId = $(this).data("orderid")
    const response = await Helper.ajaxCall(`/${lang}/ajax/orders/${orderId}/update/activation`, {activation})

});
