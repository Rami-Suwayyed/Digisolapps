function leftTrim(str, v){
    if(str[0] == v)
        str = str.substring(1);
    return str;
}
class Firebase {

    sendingResult = null;
    constructor() {
        this.firebaseConfig = {
            apiKey: "AIzaSyAwSTgxV6ZUWmqYEDw89Z65P6zX-J2bVyc",
            authDomain: "teacher-app-4a51b.firebaseapp.com",
            projectId: "teacher-app-4a51b",
            storageBucket: "teacher-app-4a51b.appspot.com",
            messagingSenderId: "597194163763",
            appId: "1:597194163763:web:c0a3027e82bf2411808546",
            measurementId: "G-2KS06RCEXT"
        };
        firebase.initializeApp(this.firebaseConfig);
    }

    render() {
        window.recaptchaVerifier=new firebase.auth.RecaptchaVerifier('recaptcha-container', {
            size: "invisible"
        });
        recaptchaVerifier.render();
    }

     SendCode(phoneNumber) {
        console.log(phoneNumber)
        var number = `+962${leftTrim(phoneNumber, "0")}`;
        console.log(number);
        var obj = this;
        return new Promise((resolve ,reject) => {
            firebase.auth().signInWithPhoneNumber(number,window.recaptchaVerifier).then(function (confirmationResult) {
                window.confirmationResult=confirmationResult;
                 obj.sendingResult = confirmationResult;
                resolve(true);
            }).catch(function (error) {
                console.log(error);
                return resolve(false);
            });
        });
    }

     VerifyCode(code) {
        console.log(code);
         return new Promise(resolve => {
             this.sendingResult.confirm(code).then(function (result) {
                 var user=result.user;
                 // console.log("success");
                 firebase.auth().currentUser.getIdToken().then(token => {
                     console.log(token);
                     resolve (token);
                 })

             }).catch(function (error) {
                 console.log(`Error ${error}`);
                 resolve(false);
             });
         });
    }


}
export default Firebase;
