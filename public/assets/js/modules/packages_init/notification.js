import * as Helper from "../helpers.js";
var firebaseConfig = {
    apiKey: "AIzaSyAwSTgxV6ZUWmqYEDw89Z65P6zX-J2bVyc",
    authDomain: "teacher-app-4a51b.firebaseapp.com",
    projectId: "teacher-app-4a51b",
    storageBucket: "teacher-app-4a51b.appspot.com",
    messagingSenderId: "597194163763",
    appId: "1:597194163763:web:c0a3027e82bf2411808546",
    measurementId: "G-2KS06RCEXT"
};
firebase.initializeApp(firebaseConfig);
const messaging = firebase.messaging();

messaging.onMessage(function(payload) {
    const noteTitle = payload.notification.title;
    const noteOptions = {
        body: payload.notification.body,
        // url: payload.notification.url,
        // icon: payload.notification.icon,
    };
    // console.log(noteTitle, noteOptions.body);
    var date = new Date;
    var hours = date.getHours();
    var minutes = date.getMinutes();
    var ampm = hours >= 12 ? 'pm' : 'am';
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'
    minutes = minutes < 10 ? '0'+minutes : minutes;
    var strTime = hours + ':' + minutes + ' ' + ampm;

    let htmlElement = `<li class="un-open">
                        <a class="app-notification__item notification-box">
                            <div class="notification">
                                <p class="app-notification__header">${noteTitle}</p>
                                <p class="app-notification__message">${noteOptions.body}</p>
                                <span class="app-notification__meta">${strTime}</span>
                            </div>
                        </a>
                    </li>`;

    $(".app-notification__content").append($(htmlElement))
    htmlElement = `<a class="notification-box" >
                        <div class="notification-header">
                        <span class="icon"><i class="fas fa-bell fa-lg fa-rotate"></i></span>
                        <span class="text">New Notifications</span></div>
                        <div class="notification-body">${noteOptions.body}</div>
                        <div class="notification-meta">${strTime}</div>
                    </a>`
    const $NewNotification = $(htmlElement)
    $(".new-notifications-box").prepend($NewNotification)
    $NewNotification.fadeIn(400, function (){
        $(this).delay(5000).fadeOut()
    })
    const audio = new Audio('/assets/sounds/notifiy.mp3');
    audio.play()


    const $NumberNonShow = $(".notification-number-non-show")
    const $NotificationIcon = $('.notification-icon')
    if(!$NotificationIcon.hasClass("fa-scale"))
        $NotificationIcon.addClass("fa-scale")
    let nonShowCount = parseInt($NumberNonShow.text()) + 1
    $NumberNonShow.text(nonShowCount)
    // new Notification(noteTitle, noteOptions);
});


$(".notification-read").on("click",async function (){
    await Helper.ajaxCall(`/${$("html").attr("lang")}/ajax/notifications/update`, {is_open: 1}, "POST")
    const $NumberNonShow = $(".notification-number-non-show")
    $NumberNonShow.text(0)
    $("#delete-scale").removeClass("fa-scale")

})
