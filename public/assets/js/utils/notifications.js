// import * as Helper from "../modules/helpers.js";
// //
// // let socket = io("http://localhost:8031/admin/socket/notification", { transports: ['websocket'] });
// let socket = io("https://node.sahla-iq.com:8031/admin/socket/notification", { transports: ['websocket'] })
//
// console.log(socket.connected)
// console.log("suu")
//
// socket.on('get-notification', (data) => {
//     console.log("aaaaaaaaaaaaaaaaaaaaaaaaaaaaLoai");
//     let htmlElement = `<li class="un-open">
//                         <a class="app-notification__item notification-box" href="${data.url}" data-id="${data.id}">
//                             <div class="notification">
//                                 <p class="app-notification__header">${data.title}</p>
//                                 <p class="app-notification__message">${data.body}</p>
//                                 <span class="app-notification__meta"></span>
//                             </div>
//                         </a>
//                     </li>`;
//
//     $(".app-notification__content").append($(htmlElement))
//     htmlElement = `<a class="notification-box" href="${data.url}" data-id="${data.id}">
//                         <div class="notification-header">
//                         <span class="icon"><i class="fas fa-bell fa-lg fa-rotate"></i></span>
//                         <span class="text">New Notifications</span></div>
//                         <div class="notification-body">${data.body}</div>
//                         <div class="notification-meta">${data.time}</div>
//                     </a>`
//     const $NewNotification = $(htmlElement)
//     $(".new-notifications-box").prepend($NewNotification)
//     $NewNotification.fadeIn(400, function (){
//         $(this).delay(5000).fadeOut()
//     })
//     const audio = new Audio('/assets/sounds/notifiy.mp3');
//     audio.play()
//     const $NumberNonShow = $(".notification-number-non-show")
//     const $NotificationIcon = $('.notification-icon')
//     if(!$NotificationIcon.hasClass("fa-scale"))
//         $NotificationIcon.addClass("fa-scale")
//     let nonShowCount = parseInt($NumberNonShow.text()) + 1
//     $NumberNonShow.text(nonShowCount)
// })
//
// $(document).on("click", ".notification-box", async function (e) {
//     e.preventDefault();
//     const parent = $(this).parent()
//     const url = $(this).attr("href")
//
//
//     if(parent.hasClass("new-notifications-box") || parent.hasClass("un-open"))
//         await Helper.ajaxCall(`/${$("html").attr("lang")}/ajax/notifications/update`, {is_open: 1, id:$(this).data("id")}, "POST")
//     if(url === "#")
//         window.location.reload()
//     else
//         window.location = url
// })
