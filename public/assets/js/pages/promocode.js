$("#promoCode-specific").on("change",function (){
    if($(this).find('option:selected').val() === '2'){
        let url = $(this).data("url"),
            userPromoCode = $("#UserPromoCode"),
            userPromoCodeBox = userPromoCode.parents("#UserPromoCodeBox");
        userPromoCodeBox.fadeOut();
        $.ajax({
            type: "GET",
            url:url,
            data:{}, // serializes the form's elements.
            success: function(response)
            {
                let htmlElements = '';
                for (users of response.data){
                    htmlElements += `<option value="${users.id}">${users.full_name}</option>`;
                }
                userPromoCode.empty().append(htmlElements);
                userPromoCodeBox.fadeIn();
            },
            error:function(){
                console.error("you have error");
            }
        });
    }else{
        let userPromoCode = $("#UserPromoCode"),
            userPromoCodeBox = userPromoCode.parents("#UserPromoCodeBox");
        userPromoCodeBox.fadeOut();
    }
});
