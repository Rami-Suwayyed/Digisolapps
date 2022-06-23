const copy = async text => {

    if (navigator.clipboard && window.isSecureContext) {
        // navigator clipboard api method'
        await navigator.clipboard.writeText(text);
        swal("Successfully", 'Copied Success', "success");
        console.log("clip")
    }else{
        const textArea = await document.getElementById("textCopied")
        textArea.innerHTML = text
        await textArea.focus();
        await textArea.select();
        return new Promise((res, rej) => {
            // here the magic happens
            document.execCommand('copy', true) ? res() : rej();
            swal("Successfully", 'Copied Success', "success");
            console.log("origin")

        });

    }



}

export {copy};

