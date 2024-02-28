var loopBtn = document.querySelector(".second-icon-set .material-icons.repeat");

loopBtn.id = "favoritePlayOption";
loopBtn.classList.add("repeating")

document.querySelector(".playlist-adder").style.display = "none";

var playlistPage = document.getElementById("playlistPage"),
    songList = document.querySelector(".all-songs"),
    playListItem = Array.from(playlistPage.getElementsByClassName("song-item")),
    playListFooter = playlistPage.querySelector(".playList-player"),
    shuffleCheck = playlistPage.querySelector("#shuffle_check"),
    prevBtn = document.getElementById("back"),
    nextBtn = document.getElementById("next");
    loopBtn = document.getElementById("favoritePlayOption");
let musicIndex = 0;

// adding

// HERE IS FOR SORTING THE PLAYLIST
var checkerArr = document.getElementById("check_arr"),
    arrangeBtn = document.querySelector(".arrangeBtn");

function sortListDir(dir) {
    var container, i, switching, b, shouldSwitch, dir, switchcount = 0;
    container = document.getElementById("favoriteSongs");
    switching = true;
    b = container.getElementsByClassName("song-item");

    while (switching) {
        switching = false;

        for (i = 0; i < (b.length - 1); i++) {
            shouldSwitch = false;
            if (dir == "asc") {
                if (b[i].querySelector("h5 .song-name").innerHTML.toLowerCase() > b[i + 1].querySelector("h5 .song-name").innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            } else if (dir == "desc") {
                if (b[i].innerHTML.toLowerCase() < b[i + 1].innerHTML.toLowerCase()) {
                    shouldSwitch = true;
                    break;
                }
            } else {
                switching = false;
                break;
            }
        }

        if (shouldSwitch) {
            b[i].parentNode.insertBefore(b[i + 1], b[i]);
            switching = true;
            switchcount++;
        } else {
            if (switchcount == 0 && dir == "asc") {
                dir = "desc";
                switching = true
            }
        }
    }


    playListItem = Array.from(playlistPage.getElementsByClassName("song-item"));

    for (i = 0; i < playListItem.length; i++) {
        playListItem[i].setAttribute("index", `${i}`);
    }

}

sortListDir("");

checkerArr.onchange = () => {
    var checkReport = arrangeBtn.value;

    if (checkReport == "") {
        arrangeBtn.textContent = "Ascending";
        arrangeBtn.value = "1";
        sortListDir("asc");
    } else if (checkReport == "1") {
        arrangeBtn.textContent = "Descending";
        arrangeBtn.value = "2";
        sortListDir("desc");
    } else {
        arrangeBtn.textContent = "Ascending";
        arrangeBtn.value = "1";
        sortListDir("asc");
    }
}

if (playListItem.length <= 1) {
    playListFooter.classList.add("few");
} else if (playListItem.length == 2) {
    playListFooter.classList.add("few2")
} else if (playListItem.length == 3) {
    playListFooter.classList.add("few3")
} else if (playListItem.length == 4) {
    playListFooter.classList.add("few4")
}

function playMusic() {
    music.play();
    masterPlay.classList.remove('fa-play');
    masterPlay.classList.add('fa-pause');
    wave.forEach((item) => item.classList.add('active-wave'));
    htmlBody.classList.remove("paused")
}

playListItem.forEach((item) => {
    item.addEventListener("click", () => {
        musicIndex = item.getAttribute("index");
    })

    let itemAudio = item.querySelector("audio");
    let itemAudioDurrText = item.querySelector(".audio-duration");

    itemAudio.addEventListener('loadeddata', () => {
        let itemAudioDuration = itemAudio.duration;
        let min = Math.floor(itemAudioDuration / 60);
        let sec = Math.floor(itemAudioDuration % 60);
        if (sec < 10) {
            sec = `0${sec}`;
        }

        itemAudioDurrText.innerHTML = `${min}:${sec}`;
    })
});


// // previous music function

function prevMusic() {
    // here we will just decrement by 1
    musicIndex--;
    musicIndex < 0 ? musicIndex = (playListItem.length - 1) : musicIndex = musicIndex;
    playMusic();
}

function nextMusic() {
    // here we will just increment by 1
    musicIndex++;
    musicIndex >= (playListItem.length) ? musicIndex = 0 : musicIndex = musicIndex;
    playMusic();
}


prevBtn.onclick = () => {
    prevMusic();
    for (let i = 0; i < songItemForDisplay.length; i++) {
        let j = 0;
        playFooter.classList.remove("d-none");
        while (j < songItemForDisplay.length) {
            songItemForDisplay[j++].className = 'song-item ambientDiv';
        }

        let playingIndex = parseInt(songItemForDisplay[i].getAttribute("index"));

        if (playingIndex == musicIndex) {
            songItemForDisplay[musicIndex].style.border = "1px solid #63ff69";
            // songItemForDisplay[i].classList.add("active")
            music.src = songItemForDisplay[musicIndex].querySelector(".audio-item").src;
            mainTitle.innerHTML = songItemForDisplay[musicIndex].querySelector("h5").innerHTML;
            mainImg.src = songItemForDisplay[musicIndex].querySelector("img").src;
            mainImg.src = songItemForDisplay[musicIndex].querySelector("img").src;
            playListItem.forEach((item) => item.classList.remove("active"));

            songItemForDisplay[musicIndex].querySelector(".fa-play-circle").style.display = "none";
            songItemForDisplay[musicIndex].querySelector(".fa-pause-circle").style.display = "initial";

            playMusic();
        } else {
            songItemForDisplay[i].querySelector(".fa-play-circle").style.display = "initial";
            songItemForDisplay[i].querySelector(".fa-pause-circle").style.display = "none";
            songItemForDisplay[i].style.border = "none";
        }
    }
}

document.addEventListener('keyup', (event) => {
    if (!playFooter.classList.contains("d-none")) {
        if (event.key == "p") {
            event.preventDefault();
            prevBtn.click();
        }
    }
})


nextBtn.onclick = () => {
    nextMusic();
    for (let i = 0; i < songItemForDisplay.length; i++) {
        songItemForDisplay[i].querySelector(".fa-play-circle").style.display = "initial";
        songItemForDisplay[i].querySelector(".fa-pause-circle").style.display = "none";
        songItemForDisplay[i].style.border = "none";
        let j = 0;
        playFooter.classList.remove("d-none");
        while (j < songItemForDisplay.length) {
            songItemForDisplay[j++].className = 'song-item ambientDiv';
        }

        let playingIndex = parseInt(songItemForDisplay[i].getAttribute("index"));

        if (playingIndex == musicIndex) {
            songItemForDisplay[musicIndex].style.border = "1px solid #63ff69";
            music.src = songItemForDisplay[musicIndex].querySelector(".audio-item").src;
            mainTitle.innerHTML = songItemForDisplay[musicIndex].querySelector("h5").innerHTML;
            mainImg.src = songItemForDisplay[musicIndex].querySelector("img").src;
            mainImg.src = songItemForDisplay[musicIndex].querySelector("img").src;
            playListItem.forEach((item) => item.classList.remove("active"));

            songItemForDisplay[musicIndex].querySelector(".fa-play-circle").style.display = "none";
            songItemForDisplay[musicIndex].querySelector(".fa-pause-circle").style.display = "initial";

            playMusic();
        } else {
            songItemForDisplay[i].querySelector(".fa-play-circle").style.display = "initial";
            songItemForDisplay[i].querySelector(".fa-pause-circle").style.display = "none";
            songItemForDisplay[i].style.border = "none";
        }
    }

}

document.addEventListener('keyup', (event) => {
    if (!playFooter.classList.contains("d-none")) {
        if (event.key == "n") {
            event.preventDefault();
            nextBtn.click();
        }
    }
})

var recallState = false;
var loopState = false;
var randomState = false;
let geticon = "";

loopBtn.addEventListener("click", () => {
    geticon = loopBtn.innerText;

    if (loopBtn.classList.contains("active")) {
        loopBtn.classList.remove("active");
    }

    if (geticon == "shuffle") {
        loopBtn.innerText = "repeat_one";
        loopBtn.title = "Repeat Music";
        recallState = true;
        loopState = false;
        randomState = false;
        recall();

    } else if(geticon == "loop") {
        loopBtn.innerText = "shuffle";
        loopBtn.title = "Shuffle list";
    
            recallState = false;
            loopState = false;
            randomState = true;
            randomMusic();
    }else{
        loopBtn.innerText = "loop";
        loopBtn.title = "Loop list";
            
        recallState = false;
        loopState = true;
        randomState = false;        
       loop();
    }
})

document.addEventListener('keydown', (event) => {
    if (!playFooter.classList.contains("d-none")) {
        if (event.key == "l") {
            event.preventDefault();
            loopBtn.click();
        }
    }
})

// // REM  to fix it - > ------------------ > -------------------- > -------------

// shuffleCheck.addEventListener("change", () => {
//     geticon = loopBtn.innerText;
//     if (shuffleCheck.checked) {
//         shuffleCheck.title = "shuffle on";
//         if (geticon == "loop") {
//             recallState = false;
//             loopState = false;
//             randomState = true;
//             randomMusic();
//             // loop();
//         }
//     } else {
//         shuffleCheck.title = "shuffle off";
//         if (geticon == "loop") {
//             recallState = false;
//             loopState = true;
//             randomState = false;
//             loop();
//         }
//     }
// })

function recall() {
    music.addEventListener("ended", () => {
        if (!recallState) {
            return false;
        } else {
            console.log(["from recall", recallState, loopState, randomState, geticon])
            songItemForDisplay[musicIndex].classList.remove("active");
            seek.value = 0;
            musicIndex = musicIndex;
            music.currentTime = 0;
            for (let i = 0; i < songItemForDisplay.length; i++) {
                let j = 0;
                playFooter.classList.remove("d-none");
                while (j < songItemForDisplay.length) {
                    songItemForDisplay[j++].className = 'song-item ambientDiv';
                }

                let playingIndex = parseInt(songItemForDisplay[i].getAttribute("index"));
                if (playingIndex == musicIndex) {
                    // songItemForDisplay[musicIndex].style.border = "1px solid #63ff69";
                    music.src = songItemForDisplay[musicIndex].querySelector(".audio-item").src;
                    mainTitle.innerHTML = songItemForDisplay[musicIndex].querySelector("h5").innerHTML;
                    mainImg.src = songItemForDisplay[musicIndex].querySelector("img").src;
                    mainImg.src = songItemForDisplay[musicIndex].querySelector("img").src;
                    // playListItem.forEach((item) => item.classList.remove("active"));

                    // songItemForDisplay[musicIndex].querySelector(".fa-play-circle").style.display = "none !important";
                    // songItemForDisplay[musicIndex].querySelector(".fa-pause-circle").style.display = "initial !important";
                    playMusic();
                } else {
                    // songItemForDisplay[i].querySelector(".fa-play-circle").style.display = "initial !important";
                    // songItemForDisplay[i].querySelector(".fa-pause-circle").style.display = "none !important";
                    // songItemForDisplay[i].style.border = "none !important";
                }
            }
            return false;
        }
    })
}

function loop() {

    music.addEventListener("ended", (e) => {
        if (!loopState) {
            return false;
        } else {
            e.currentTarget
            console.log(["from loop", recallState, loopState, randomState, e.currentTarget, e.isTrusted, e.defaultPrevented, e.eventPhase]);
            songItemForDisplay[musicIndex].classList.remove("active");
            nextMusic();
            songItemForDisplay[musicIndex].classList.add("active");
            for (let i = 0; i < songItemForDisplay.length; i++) {
                let j = 0;
                playFooter.classList.remove("d-none");
                while (j < songItemForDisplay.length) {
                    songItemForDisplay[j++].className = 'song-item ambientDiv';
                }

                let playingIndex = parseInt(songItemForDisplay[i].getAttribute("index"));

                if (playingIndex == musicIndex) {
                    // alert([i, musicIndex, playingIndex, songItemForDisplay[i].id]);

                    // songItemForDisplay[musicIndex].style.border = "1px solid #63ff69 !important";

                    mainTitle.innerHTML = songItemForDisplay[musicIndex].querySelector("h5").innerHTML;
                    mainImg.src = songItemForDisplay[musicIndex].querySelector("img").src;
                    mainImg.src = songItemForDisplay[musicIndex].querySelector("img").src;
                    // playListItem.forEach((item) => item.classList.remove("active"));
                    // songItemForDisplay[musicIndex].classList.add("active")
                    // songItemForDisplay[musicIndex].querySelector(".fa-play-circle").style.display = "none !important";
                    // songItemForDisplay[musicIndex].querySelector(".fa-pause-circle").style.display = "initial !important";
                    music.src = songItemForDisplay[musicIndex].querySelector(".audio-item").src;
                    playMusic()
                }
            }
            return true;
        }
    })
}

function randomMusic() {
    // playListItem.forEach((item) => {
    //         item.classList.remove("active")
    // });

    music.addEventListener("ended", () => {
        if (!randomState) {
            return false;
        } else {

            console.log(["from rand", recallState, loopState, randomState])
            let randIndex = Math.floor((Math.random() * (playListItem.length)));
            do {
                randIndex = Math.floor((Math.random() * (playListItem.length)));

            } while (musicIndex == randIndex);
            musicIndex = randIndex;

            for (let i = 0; i < songItemForDisplay.length; i++) {
                let j = 0;
                playFooter.classList.remove("d-none");
                while (j < songItemForDisplay.length) {
                    songItemForDisplay[j++].className = 'song-item ambientDiv';
                }

                let playingIndex = parseInt(songItemForDisplay[i].getAttribute("index"));

                if (playingIndex == musicIndex) {
                    songItemForDisplay[musicIndex].style.border = "1px solid #63ff69";
                    music.src = songItemForDisplay[musicIndex].querySelector(".audio-item").src;
                    mainTitle.innerHTML = songItemForDisplay[musicIndex].querySelector("h5").innerHTML;
                    mainImg.src = songItemForDisplay[musicIndex].querySelector("img").src;
                    mainImg.src = songItemForDisplay[musicIndex].querySelector("img").src;
                    playListItem.forEach((item) => item.classList.remove("active"));

                    songItemForDisplay[musicIndex].querySelector(".fa-play-circle").style.display = "none";
                    songItemForDisplay[musicIndex].querySelector(".fa-pause-circle").style.display = "initial";
                    playMusic();
                } else {
                    songItemForDisplay[i].querySelector(".fa-play-circle").style.display = "initial";
                    songItemForDisplay[i].querySelector(".fa-pause-circle").style.display = "none";
                    songItemForDisplay[i].style.border = "none";
                }
            }
        }
    })
}

function runRecall() {
    recallState = true;
    loopState = false;
    randomState = false;
    recall();
}

function runLoop() {
    recallState = false;
    loopState = true;
    randomState = false;
    loop();
}

function runRandom() {
    recallState = false;
    loopState = false;
    randomState = true;
    randomMusic();
}