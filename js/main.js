    var sortMenu = document.getElementById('sort-menu');
    var sortBtn = document.getElementById('sort-btn');
    var sortItems = [...sortMenu.getElementsByClassName('dropdown-item')];
    const topNav = document.querySelector('.upper-navigation'),
        navbarNav = [...document.querySelectorAll('.navbar-nav li')],
        navbarSpan = document.querySelectorAll('.navbar-nav li a span'),
        menuBtn = document.querySelector('.menu-btn'),
        mainSection = document.getElementById('main'),
        sideNavigation = document.querySelector('.side-navigation'),
        htmlBody = document.querySelector('body'),
        searchLink = document.querySelector(".searchLink"),
        buttonDismiss = document.querySelector(".button-dismiss"),
        searchModal = document.querySelector(".searchModal"),
        musicItem = mainSection.querySelectorAll("audio-item"),
        mainTitle = document.getElementById("title"),
        mainImg = document.getElementById("main-img"),
        music = document.getElementById("audio_player"),
        audioItems = document.querySelectorAll(".song-item");

    let masterPlay = document.getElementById('masterPlay');

    const songItemForDisplay = [...document.getElementsByClassName('song-item')];
    const mobileViewBtn = document.querySelector(".mobile-view");
    const playFooter = document.getElementById("play-footer");
    let masterPlayDiv = playFooter.querySelector(".master-play");
    const PlayForItem = document.getElementById("play-footer");
    const playingID = document.querySelector(".playingID");
    const resizer = playFooter.querySelector(".resizer");
    const likeTone = document.getElementById("tone-like");

    audioItems.forEach((item) => {
        window.addEventListener('DOMContentLoaded', () => {
            let loadImageItem = item.querySelector('.load-item-img');
            let loadItemText = item.querySelector('.load-item-text');

            loadImageItem.remove();
            loadItemText.remove();
        });
    });


    //-- REM TO WORK ON REALTIME AUDIO WAVES 
    // const beatWave = document.getElementById('beat-wave');
    // const ctx = beatWave.getContext('2d');
    // let playingAudio;

    // const loadItems = document.querySelectorAll('.song-item');
    // const loaderHtml = '<div class="loading"><span class="text-light">Loading...</span></div>';
    // let counter = 0;

    // function showLoader() {
    //     for (let i = 0; i < loadItems.length; i++) {
    //       loadItems[i].insertAdjacentHTML("afterbegin", loaderHtml);
    // }

    // }

    // function hideLoader() {
    //   const loading = document.querySelectorAll('.loading');

    //   for(let i = 0; i < loadItems.length; i++){
    //       if (loading[i]) {
    //         loading[i].remove();
    //       }
    //   }
    // }

    // function loadItem(item) {
    //   showLoader();
    //   setTimeout(() => {
    //     hideLoader();
    //   }, 2000);
    // }

    // for (let i = 0; i < loadItems.length; i++) {
    //   loadItem(loadItems[i]);
    //   counter++;
    //   if (counter % 5 === 0) {
    //     showLoader();
    //   }
    // }


    // songItemForDisplay.forEach((item)=>{
    //     item.addEventListener("load", function() {
    //         item.innerHTML = ""
    //     });
    // });



    function updateStatus(status) {
        $.ajax({
            url: 'backend/update_status.php',
            method: 'POST',
            data: {
                status: status,
            },
        });
    }

    $(document).ready(function () {
        $(window).on('load', function () {
            updateStatus('Active now');
        });

        $(window).on('beforeunload', function () {
            updateStatus('Offline now');
        });
    });

    const userIdList = document.querySelector(".userid_list");
    const songIdList = document.querySelector(".songid_list");
    const songNameList = document.querySelector(".songname_list");
    const posterList = document.querySelector(".poster_list");
    const artistList = document.querySelector(".artist_list");

    searchLink.onclick = function () {
        searchModal.style.display = "initial";
    }

    buttonDismiss.onclick = function () {
        searchModal.style.display = "none";
    }

    window.addEventListener("loadeddata", () => {
        window.style.background = "black !important";
    })

    sortItems.forEach((item) => {
        item.onclick = function () {
            var filter, songItemsFilter, a, i, sortValue;
            sortBtn.innerText = item.innerHTML;

            sortValue = sortBtn.innerText;
            // sortValue.append("<i class='fa fa-caret-down'></i>")
            filter = item.value.toUpperCase();
            songItemsFilter = document.querySelectorAll('.all-songs .songs .song-item');

            // Loop through all list items, and hide those who don't match the search query

            for (i = 0; i < songItemsFilter.length; i++) {
                a = songItemsFilter[i].getElementsByClassName('genre')[0];

                if (a.innerHTML.toUpperCase().indexOf(filter) > -1) {
                    songItemsFilter[i].style.display = "";
                    playMusic();
                } else {
                    songItemsFilter[i].style.display = "none";
                    pauseMusic()
                }
            }
        }
    });

    ///////// burger menu and resize navbar



    function navView(btn) {
        let showMenu = btn.classList.contains('open');
        if (!showMenu) {
            btn.classList.add('open');
            sideNavigation.classList.add('large-nav');
            mainSection.classList.add('mainPad');
            playFooter.classList.add("footer-index1");
            playFooter.classList.remove("footer-index2");
            showMenu = true;
        } else {
            btn.classList.remove('open');
            sideNavigation.classList.remove('large-nav');
            mainSection.classList.remove('mainPad');
            playFooter.classList.add("footer-index2");
            playFooter.classList.remove("footer-index1");
            showMenu = false;
        }
    }

    // menuBtn.onclick = () => {

    // }

    ///////// active class navigation


    for (let i = 0; i < navbarNav.length; i++) {
        navbarNav[i].onclick = function () {
            let j = 0;
            while (j < navbarNav.length) {
                navbarNav[j++].className = 'nav-item';
            }
            navbarNav[i].className = 'nav-item active';
        }
    }



    ////// Ambient mode selector

    function ambient(btn) {
        let showAmbient = btn.classList.contains('ambient');
        const ambientDivs = document.querySelectorAll('.ambientDiv');
        if (!showAmbient) {
            btn.classList.add('ambient');
            ambientDivs.forEach((div) => div.classList.add('added'))
        } else {
            btn.classList.remove('ambient');
            ambientDivs.forEach((div) => div.classList.remove('added'))
        }
    }



    ////     AUDIO SECTION

    //  displaying master when song item is clicked and playing music

    if (songItemForDisplay) {
        for (let i = 0; i < songItemForDisplay.length; i++) {
            songItemForDisplay[i].onclick = function () {
                let j = 0;
                playFooter.classList.remove("d-none");
                while (j < songItemForDisplay.length) {
                    songItemForDisplay[j++].className = 'song-item';
                }
                songItemForDisplay[i].className = 'song-item active';
                playingID.id = songItemForDisplay[i].id;
                songIdList.value = playingID.id;
                playingAudio = songItemForDisplay[i].querySelector(".audio-item");
                music.src = songItemForDisplay[i].querySelector(".audio-item").src;
                mainTitle.innerHTML = songItemForDisplay[i].querySelector("h5").innerHTML;
                songNameList.value = songItemForDisplay[i].querySelector("h5").querySelector(".song-name").innerHTML;
                if (posterList) {
                    posterList.value = songItemForDisplay[i].querySelector(".poster_id").innerText;
                }
                artistList.value = songItemForDisplay[i].querySelector("h5").querySelector(".artist").innerHTML;

                mainImg.src = songItemForDisplay[i].querySelector("img").src;

                playMusic();
            }
        }
    }

    function selectSong(songItem) {
        let j = 0;
        playFooter.classList.remove("d-none");
        while (j < songItemForDisplay.length) {
            songItemForDisplay[j++].className = 'song-item';
        }
        songItem.className = 'song-item active';
        playingID.id = songItem.id;
        songIdList.value = playingID.id;
        playingAudio = songItem.querySelector(".audio-item");
        music.src = songItem.querySelector(".audio-item").src;
        mainTitle.innerHTML = songItem.querySelector("h5").innerHTML;
        songNameList.value = songItem.querySelector("h5").querySelector(".song-name").innerHTML;
        if (posterList) {
            posterList.value = songItem.querySelector(".poster_id").innerText;
        }
        artistList.value = songItem.querySelector("h5").querySelector(".artist").innerHTML;

        mainImg.src = songItem.querySelector("img").src;

        playMusic();
    }



    //  song repeat or loop


    /// music play pause 


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

    var homeBody = document.getElementById('homepg');
    var offset = 0;
    var currentPage = 1;
    var resultsPerPage = 4;

    function loadMoreSongs(btn) {
        $.ajax({
            url: 'songlists/recommendations.php',
            method: 'GET',
            data: {
                offset: offset,
                page: currentPage,
                perPage: resultsPerPage,
            },
            beforeSend: function () {
                btn.querySelector(' .spinner').classList.remove('d-none');
            },
            complete: function () {
                btn.querySelector('.spinner').classList.add('d-none');
            },
            success: function (response) {
                document.querySelector(".all-songs .songs").insertAdjacentHTML('beforeend', response);
                currentPage++;
            }
        })
    }

    if (homeBody) {
        homeBody.addEventListener('scroll', () => {
            let currentPageHeight = Math.round(homeBody.scrollTop) + homeBody.clientHeight;
            let pageHeight = homeBody.scrollHeight;
            const loadMoreBtn = document.getElementById("loadMore");
            if (currentPageHeight >= pageHeight && loadMoreBtn.style.display == 'none') {
                loadMoreBtn.style.display = 'grid';
            }
        });
    }

    //-- REM TO WORK ON REALTIME AUDIO WAVES 


    // FOR CANVAS FOR THE BEAT WAVE AKA [REAL TIME WAVE]

    // let audioContext;

    // function waveRender() {
    //     audioContext = new AudioContext();

    //     const sourceNode = audioContext.createMediaElementSource(playingAudio);

    //     // create an analyser node and connect it to the source node
    //     const analyserNode = audioContext.createAnalyser();
    //     sourceNode.connect(analyserNode);
    //     analyserNode.connect(audioContext.destination);

    //     // set up the canvas
    //     const width = beatWave.width;
    //     const height = beatWave.height;
    //     const analyserData = analyserNode.frequencyBinCount -1020;
    //     const barWidth = width / analyserData;
    //     console.log(barWidth ,[analyserNode.connect(audioContext.destination)])

    //     // render the audio wave
    //     // clear the canvas
    //     ctx.clearRect(0, 0, width, height);

    //         // get the frequency data
    //         const frequencyData = new Uint8Array(analyserData);
    //         analyserNode.getByteFrequencyData(frequencyData);
    //         console.log([new Uint8Array(analyserData)])

    //         // draw the bars
    //         for (let i = 0; i < analyserData; i++) {
    //             const barHeight = frequencyData[i] / 500 * height;
    //             ctx.fillRect((i * barWidth), (height - barHeight), barWidth, barHeight);
    //             console.log([analyserData,barHeight, barWidth, frequencyData])
    //         }

    //         // request the next frame
    //         requestAnimationFrame(waveRender);
    // }

    // create an audio context and source node

    function repeatSong() {
        music.addEventListener("ended", function () {
            music.currentTime = 0;
            seek.value = `0`;
            playMusic();
        })
    }

    function unrepeatSong() {
        music.addEventListener("ended", function () {
            music.currentTime = music.currentTime;
            pauseMusic();
        })
    }

    masterPlay.addEventListener('click', () => {
        if (music.paused || music.currentTime <= 0) {
            playMusic();
        } else {
            pauseMusic();
        }
    })

    document.addEventListener('keydown', (event) => {
        if (!playFooter.classList.contains("d-none")) {
            if (event.code == "Space") {
                event.preventDefault();
                masterPlay.click();
            }
        }
    })

    music.addEventListener('ended', () => {
        masterPlay.classList.add('fa-play')
        masterPlay.classList.remove('fa-pause')
        wave.forEach((item) => item.classList.remove('active-wave'))
    })


    ///// volume display

    const volIcon = document.getElementById('vol_icon');
    const volBar = document.getElementById('vol');

    // Mobile View toggle


    // function checkDeviceWidth() {
    //     if (window.innerWidth < 900) {
    //         console.log('This content is replaced on small screens')
    //     } else {
    //         console.log('This is the original content')
    //     }
    // }

    // window.onload = checkDeviceWidth;
    // window.onresize = checkDeviceWidth;

    resizer.onclick = function () {
        playFooter.classList.toggle("compressed")

        if (playFooter.classList.contains("compressed")) {
            this.querySelector("i").classList.replace("fa-compress-alt", "fa-compress")
        } else {
            this.querySelector("i").classList.replace("fa-compress", "fa-compress-alt")
        }
    }

    mobileViewBtn.addEventListener('click', () => {
        masterPlayDiv.classList.toggle('footer-2');
        playFooter.classList.toggle('mobileFooter');

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

        let progressWidth = Math.floor((musicCurr / musicDur) * 100);
        seek.value = `${progressWidth}`;

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

        if (currentEnd.innerText == `NaN:NaN`) {
            currentEnd.innerText = `0:00`;
            seek.value = 0;
            // wave.classList.remove('active-wave');
        }
    })

    seek.addEventListener('change', () => {
        music.currentTime = seek.value * music.duration / 100;
        playMusic();
    })


    // function volumeLevel(state) {
    //     if (state) {
    //         vol_txt.classList.remove("d-none");
    //         setTimeout(() => {
    //             vol_txt.classList.add("d-none")
    //         }, 2500)
    //     }
    // }

    let vol_icon = document.getElementById('vol_icon');
    let vol = document.getElementById('vol');
    let vol_txt = document.getElementById('vol-txt');
    let vol_a = vol.value;

    music.volume = vol_a / 1;
    vol.addEventListener('change', () => {
        let vol_level = vol.value * 100;
        vol.title = vol_level;
        vol_txt.innerText = `${vol_level}%`;
        // volumeLevel(vol_txt.classList.contains("d-none"))
        if (vol.value == 0) {
            vol_icon.innerText = "volume_mute";
        }
        if (vol.value > 0) {
            vol_icon.innerText = "volume_down";
        }
        if (vol.value > 0.5) {
            vol_icon.innerText = "volume_up";
        }
        let vol_a = vol.value;
        music.volume = vol_a / 1;
    });

    volIcon.onclick = () => {
        let getIconName = volIcon.innerText;
        likeTone.play()

        switch (getIconName) {
            case "volume_down":
                volIcon.innerText = "volume_mute";
                vol_a = vol.value = 0;
                vol_txt.innerText = `${vol_a}%`;
                // volumeLevel(vol_txt.classList.contains("d-none"))
                music.volume = vol_a;
                break;

            case "volume_up":
                volIcon.innerText = "volume_mute";
                vol_a = vol.value = 0;
                vol_txt.innerText = `${vol_a}%`;
                // volumeLevel(vol_txt.classList.contains("d-none"))
                music.volume = vol_a;
                break;

            case "volume_mute":
                volIcon.innerText = "volume_down";
                vol_a = vol.value = 0.5;
                vol_txt.innerText = `50%`;
                // volumeLevel(vol_txt.classList.contains("d-none"))
                music.volume = vol_a;
                break;
        }
    }


    document.addEventListener('keydown', (event) => {
        if (!playFooter.classList.contains("d-none")) {
            if (event.key == "m") {
                event.preventDefault();
                volIcon.click();
            }
        }
    })

    // FOR SONG [MUSIC] LOOP/REPEAT
    const loopSong = document.querySelector('.audio-contol .icon #main-audio-loop');

    if (loopSong) {
        loopSong.addEventListener('click', () => {
            let getIcon = loopSong;
            getIcon.classList.toggle("active")
            likeTone.play()

            if (getIcon.classList.contains("active")) {
                getIcon.title = "Song Looped";
                repeatSong();
            } else {
                getIcon.title = "Song Unlooped";
                unrepeatSong()
            }
        })

        document.addEventListener('keydown', (event) => {
            if (!playFooter.classList.contains("d-none")) {
                if (event.key == "l") {
                    event.preventDefault();
                    loopSong.click();
                }
            }
        })
    }

    // THIS IS FOR THE TOOLTIP[SHARE] ICONS AND COPY LINK

    const share = document.getElementById("share");
    const overlay = document.getElementById("overlay-tooltip");
    const tooltip = document.getElementById("tooltip");
    const tooltiptext = document.getElementById("tooltip-text");
    const copy_link = document.getElementById("copy_link");
    const close_link = document.getElementById("close");
    const about_artist = document.getElementById("about_artist");
    const songitem_copy = [...document.querySelectorAll(".song-item")];
    const facebook = document.getElementById("facebook");
    const twitter = document.getElementById("twitter");
    const whatsapp = document.getElementById("whatsapp");
    const linkedin = document.getElementById("linkedin");
    const reddit = document.getElementById("reddit");
    const msg = encodeURIComponent("Hi, Checkout this music on emnetmusic please follow and stream");

    const title = encodeURIComponent('Emnet Music');
    let link = encodeURI(`https://emnetmusic.com`);

    songitem_copy.forEach((item) => {
        item.addEventListener("click", () => {
            tooltiptext.value = "";
            link = encodeURI(item.querySelector(".xb42dee").textContent.trim());

            let artist_link = item.querySelector(".xb42dee2").textContent.trim();
            tooltiptext.value = link;
            about_artist.href = artist_link;

            facebook.setAttribute("data-url", `${link}`);

            twitter.setAttribute("data-url", `${link}`);
            twitter.setAttribute("data-title", `${title}`);
            whatsapp.setAttribute("data-url", `${link}`);
            linkedin.setAttribute("data-url", `${link}`);
            reddit.setAttribute("data-url", `${link}`);

        })
    })

    window.addEventListener("click", (e) => {
        if (e.target == overlay) {
            overlay.style.display = "none";
            tooltip.classList.remove("re-pos");
        }
    })

    share.addEventListener("click", () => {
        likeTone.play()
        if (navigator.share) {
            navigator.share({
                title: decodeURIComponent(title),
                url: decodeURI(link),
                text: decodeURIComponent(msg),
            });
        } else {
            let isShow = tooltip.classList.contains("re-pos");
            if (isShow) {
                tooltip.classList.remove("re-pos");
                overlay.style.display = "none";
            } else {
                tooltip.classList.add("re-pos");
                overlay.style.display = "block";
            }
        }
    });

    close_link.addEventListener("click", () => {
        let isShow = tooltip.classList.contains("re-pos");

        if (isShow) {
            tooltip.classList.remove("re-pos");
            overlay.style.display = "none";
        }
    })

    const copyToClipboard = (text) => {
        navigator.clipboard.writeText(text)
            .then(() => {
                alert('Copied to clipboard');
            })
            .catch((error) => {
                alert('Failed to copy to clipboard: ' + error);
            });
    }

    // Example usage


    copy_link.addEventListener("click", () => {
        copyToClipboard(link);
    })

    // THIS IS FOR THE TOOLTIP[SHARE] ICONS AND COPY LINK

    // THIS IS FOR LIKE [ADD] SONG 
    const addSong = document.querySelector('.icon .fa-plus-square');

    let addSongCondition = 0;

    addSong.addEventListener('click', () => {
        if (addSongCondition == 0) {
            addSong.classList.add('active');
            addSongCondition = 1;
            likeTone.play()
        } else {
            addSong.classList.remove('active')
            addSongCondition = 0;
            likeTone.play()
        }
    })


    const loveSong = document.querySelector('.icon .fa-heart');

    let loveSongCondition = 0;

    loveSong.addEventListener('click', () => {
        if (loveSongCondition == 0) {
            loveSong.classList.add('active');
            loveSongCondition = 1;
            likeTone.play()
        } else {
            loveSong.classList.remove('active')
            loveSongCondition = 0;
            likeTone.play()
        }
    })

    document.addEventListener('keydown', (event) => {
        if (!playFooter.classList.contains("d-none")) {
            if (event.key == "+") {
                event.preventDefault();
                loveSong.click();
            }
        }
    });