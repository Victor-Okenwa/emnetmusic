const offlineStatus = document.querySelector(".offlineStatus");
const onlineStatus = document.querySelector(".onlineStatus");

offlineStatus.querySelector("button").onclick = function(){
    offlineStatus.style.opacity = 0;
    offlineStatus.style.visibility = "hidden";
}

onlineStatus.querySelector("button").onclick = function(){
    onlineStatus.style.opacity = 0;
    onlineStatus.style.visibility = "hidden";
}


window.addEventListener("offline", () => {
    offlineStatus.querySelector("span").innerText = "You are currently offline";
    offlineStatus.style.opacity = .9;
    offlineStatus.style.visibility = "visible";

    if(onlineStatus.style.opacity == .9)
        onlineStatus.style.opacity = 0;

    setTimeout(() => {
        offlineStatus.style.opacity = 0;
        offlineStatus.style.visibility = "hidden";
    }, 15000);
})

window.addEventListener("online", () => {
    onlineStatus.querySelector("span").innerText = "Back online";
    onlineStatus.style.opacity = .9;
    onlineStatus.style.visibility = "visible";

    if(offlineStatus.style.opacity == .9)
        offlineStatus.style.opacity = 0;


    setTimeout(() => {
        onlineStatus.style.opacity = 0;
        onlineStatus.style.visibility = "hidden";
    }, 15000);
})


// window.addEventListener("offline", () => {
//     internetStatus.querySelector("span").innerText = "You are currently offline";
//     if (internetStatus.classList.contains("d-none")) {
//         internetStatus.classList.replace("d-none", "d-flex");
//     }
//     if (internetStatus.classList.contains("alert-success")) {
//         internetStatus.classList.replace("alert-success", "alert-danger");
//     }
// })
// this.clearInterval(500);