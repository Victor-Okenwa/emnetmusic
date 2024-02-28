var sortMenu = document.getElementById('sort-menu');
var sortBtn = document.getElementById('sort-btn');
var sortItems = sortMenu.getElementsByClassName('dropdown-item');


for (let i = 0; i < sortItems.length; i++) {
    sortItems[i].onclick = function () {
        let j = 0;
        while (j < sortItems.length) {
            sortItems[j++].className = 'dropdown-item';
        }
        sortItems[i].className = 'dropdown-item selected';
        sortBtn.innerHTML = `${sortItems[i].innerHTML} <i class="fas fa-caret-down">`;
    }
}

////// Ambient mode selector

const ambientBtn = document.querySelector('.em-mode');
const ambientDivs = document.querySelectorAll('.ambientDiv')

let showAmbient = 0;
ambientBtn.addEventListener('click', () => {
    if (showAmbient == 0) {
        ambientBtn.classList.add('ambient');
        ambientDivs.forEach((div) => div.classList.add('added'))
        showAmbient = 1;
    } else {
        ambientBtn.classList.remove('ambient');
        ambientDivs.forEach((div) => div.classList.remove('added'))
        showAmbient = 0;
    }
})
// for search box

const searchBox = document.querySelector(".em-search-box"),
    searchIcon1 = document.querySelector(".search-icon1"),
    searchIcon2 = document.querySelector(".search .search-icon2"),
    menuBtn = document.querySelector('.menu-btn'),
    mainSection = document.getElementById('main'),
    sideNavigation = document.querySelector('.side-navigation'),
    htmlBody = document.querySelector('body');

let showMenu = false;

menuBtn.onclick = () => {
    if (!showMenu) {
        menuBtn.classList.add('open');
        sideNavigation.classList.add('large-nav');
        mainSection.classList.add('mainPad');
        showMenu = true;
    } else {
        menuBtn.classList.remove('open');
        sideNavigation.classList.remove('large-nav');
        mainSection.classList.remove('mainPad');
        showMenu = false;
    }
}


searchBox.addEventListener("focus", () => {
    searchIcon1.classList.add('opacity-view')
})

searchBox.addEventListener("keydown", () => {
    // console.log(searchBox.value.length - 1)
    if (searchBox.value.length <= 0) {
        searchIcon2.classList.remove("opacity-view")
    } else {
        searchIcon2.classList.add("opacity-view");
    }
})

searchIcon2.onclick = () => {
    searchBox.value = "";
}

//  displaying master when song item is clicked

let masterPlay = document.getElementById('masterPlay');
const songItemForDisplay = [...document.getElementsByClassName('song-item')];
const mobileViewBtn = document.querySelector(".mobile-view");
const playFooter = document.getElementById("play-footer");
const PlayForItem = document.getElementById("play-footer");

songItemForDisplay.forEach((item) => {
    item.onclick = function () {
        playFooter.classList.remove("d-none");
    }
})



//add active for playlist adder & reapeat

const loveSong = document.querySelector('.icon .fa-heart');
const loopSong = document.querySelector('.icon .fa-redo');
// const shuffleSong = document.querySelector('.icon .fa-random');

let loveSongCondition = 0;
let loopSongCondition = 0;
// let shuffleCondition = 0;

loveSong.addEventListener('click', () => {
    if (loveSongCondition == 0) {
        loveSong.classList.add('active');
        loveSongCondition = 1;
    } else {
        loveSong.classList.remove('active')
        loveSongCondition = 0;
    }
})

//  song repeat or loop
loopSong.addEventListener('click', () => {
    if (loopSongCondition == 0) {
        loopSong.classList.add('active');
        loopSongCondition = 1;
    } else {
        loopSong.classList.remove('active')
        loopSongCondition = 0;
    }
})


/// music play pause 


// let masterPlay = document.getElementById('masterPlay');
const songItemForDisplay = [...document.getElementsByClassName('song-item')];
const mobileViewBtn = document.querySelector(".mobile-view");
const playFooter = document.getElementById("play-footer");
const PlayForItem = document.getElementById("play-footer");


for (let i = 0; i < songItemForDisplay.length; i++) {
    songItemForDisplay[i].onclick = function () {
        let j = 0;
        playFooter.classList.remove("d-none");
        while (j < songItemForDisplay.length) {
            songItemForDisplay[j++].className = 'song-item';
        }
        songItemForDisplay[i].className = 'song-item active';
        music.src = songItemForDisplay[i].querySelector(".audio-item").src;
        mainTitle.innerHTML = songItemForDisplay[i].querySelector("h5").innerHTML;
        mainImg.src = songItemForDisplay[i].querySelector("img").src;

        playMusic();
    }
}

let wave = [...document.getElementsByClassName('wave1')];

function playMusic() {
    music.play();
    masterPlay.classList.remove('fa-play');
    masterPlay.classList.add('fa-pause');
    wave.forEach((item) => item.classList.add('active-wave'));
    htmlBody.classList.remove("paused")
}

function pauseMusic() {
    music.pause();
    masterPlay.classList.add('fa-play');
    masterPlay.classList.remove('fa-pause');
    wave.forEach((item) => item.classList.remove('active-wave'))
    htmlBody.classList.add("paused")
}

///// volume display

const volIcon = document.getElementById('vol_icon');
const volBar = document.getElementById('vol');

volIcon.onclick = () => {
    volBar.classList.toggle('opacity-view')
}

// Mobile View toggle



mobileViewBtn.addEventListener('click', () => {
    playFooter.classList.toggle('mobileFooter');
    mobileViewBtn.classList.toggle('mobileFooter');

    if (playFooter.classList.contains('mobileFooter')) {
        htmlBody.classList.add('scrollNone');
    } else {
        htmlBody.classList.remove('scrollNone');
    }
})

let currentStart = document.getElementById('currentStart');
let currentEnd = document.getElementById('currentEnd');
let seek = document.getElementById('seek');

music.addEventListener("timeupdate", () => {
    let musicCurr = music.currentTime;
    let musicDur = music.duration;

    let min = Math.floor(musicDur / 60);
    let sec = Math.floor(musicDur % 60);
    if (sec < 10) {
        sec = `0${sec}`;
    }
    currentEnd.innerText = `${min}:${sec}`;

    let min1 = Math.floor(musicCurr / 60);
    let sec1 = Math.floor(musicCurr % 60);
    if (sec1 < 10) {
        sec1 = `0${sec1}`;
    }
    currentStart.innerText = `${min1}:${sec1}`;


    // let progressbar = parseInt((music.currentTime / music.duration) * 100);
    // seek.value = progressbar;

    let seekbar = seek.value;

    if (currentEnd.innerText == `NaN:NaN`) {
        seekbar = 0;
        currentEnd.innerText = `0:00`;
        wave.classList.remove('active-wave');
    }


})

seek.addEventListener('change', () => {
    music.currentTime = seek.value * music.duration / 100;
})



let vol_icon = document.getElementById('vol_icon');
let vol = document.getElementById('vol');

let vol_a = vol.value;

music.volume = vol_a / 1;

vol.addEventListener('change', () => {

    if (vol.value == 0) {
        vol_icon.classList.remove('fa-volume-down');
        vol_icon.classList.add('fa-volume-mute');
        vol_icon.classList.remove('fa-volume-up');
    }
    if (vol.value > 0) {
        vol_icon.classList.add('fa-volume-down');
        vol_icon.classList.remove('fa-volume-mute');
        vol_icon.classList.remove('fa-volume-up');
    }
    if (vol.value > 0.5) {
        vol_icon.classList.remove('fa-volume-down');
        vol_icon.classList.remove('fa-volume-mute');
        vol_icon.classList.add('fa-volume-up');
    }

    let vol_a = vol.value;
    music.volume = vol_a / 1;

})