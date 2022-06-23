import * as Helper from "./modules/helpers.js"
(function () {
    "use strict";

    $("#purchase_order").on("click", ".changes-status input[type=\"checkbox\"]",async function (){

        let url = $(this).data("url"),
            id = $(this).data("id"),
            type = $(this).data("status-type"),
            value = $(this)[0].checked ? 1 : 0,
            checkBox = $(this);

        if(value)
            checkBox.attr("checked", false);
        else
            checkBox.attr("checked", true);
        let data = {id: id, value: value};
        let response = await Helper.ajaxCall(url, data);
        console.log(response.status_number);
        if(response.status_number === "S200"){
            $(this).unbind(e);
            if(value)
                checkBox.attr("checked", true);
            else
                checkBox.attr("checked", false);
        }
    });

})();
