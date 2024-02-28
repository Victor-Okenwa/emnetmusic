const sidebar = document.querySelector('.navigation-bar .sidebar'),
    navbarToggler = document.getElementById('navbar-toggler'),
    toPageTopBtn = document.getElementById('toPageTop');
// const navbarTogglerIcon = document.querySelector("#navbar-toggler i");

navbarToggler.onclick = () => {
    sidebar.classList.toggle('show');
    if (sidebar.classList.contains('show')) {
        navbarToggler.innerHTML = `<i class="fa fa-times"></i>`;
    } else {
        navbarToggler.innerHTML = `<i class="fa fa-bars"></i>`;
    }
}

// window.onclick = function(e){

//     if(e.target !== sidebar && e.target !== navbarToggler && e.target !== navbarTogglerIcon && sidebar.classList.contains('show')){
//         sidebar.classList.remove('show');
//     }
// }

window.addEventListener("scroll", () => {
    const currentPosition = Math.round(window.scrollY) + window.screen.height;
    const totalPosition = document.body.scrollHeight;

    const scrollPercentage = Math.round(currentPosition/totalPosition * 100);
    
    if(scrollPercentage >= 25){
        toPageTopBtn.style.display = 'block';
        toPageTopBtn.style.animation = 'shrinkUp .5s ease';
    }else{
        toPageTopBtn.style.display = 'none';
    }
});

toPageTopBtn.onclick = function(){
    this.style.display = 'none';
}