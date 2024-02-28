// scroll for recent songs
const scrollLeft = document.getElementById('recentscrollL');
const scrollRight = document.getElementById('recentscrollR');
const songItem = document.querySelectorAll('.song-list')[0];

if(scrollLeft && scrollRight){
    scrollLeft.addEventListener('click', () => {
        songItem.scrollLeft -= 330;
    })
    scrollRight.addEventListener('click', () => {
        songItem.scrollLeft += 330;
    })
}
// scroll for recent songs

// scroll for top songs
const scrollLeftTop = document.getElementById('topscrollL');
const scrollRightTop = document.getElementById('topscrollR');
const songItemTop = document.querySelectorAll('.top-songs')[0];

if(scrollLeftTop && scrollRightTop){
    scrollLeftTop.addEventListener('click', () => {
        songItemTop.scrollLeft -= 330;
    })
    scrollRightTop.addEventListener('click', () => {
        songItemTop.scrollLeft += 330;
    })

}
// scroll for top songs

// scroll for Trending songs
const tredscrollL = document.getElementById('tredscrollL');
const tredscrollR = document.getElementById('tredscrollR');
const tredItem = document.querySelectorAll('.tred-song-list')[0];

if(tredscrollL && tredscrollR){
    tredscrollL.addEventListener('click', () => {
        tredItem.scrollLeft -= 330;
    })
    tredscrollR.addEventListener('click', () => {
        tredItem.scrollLeft += 330;
    })
}

// scroll for afrobeat songs
const afrobeatscrollL = document.getElementById('afrobeatscrollL');
const afrobeatscrollR = document.getElementById('afrobeatscrollR');
const songItemAfroBeat = document.querySelectorAll('.afro-beat')[0];

if(afrobeatscrollL && afrobeatscrollR){
    afrobeatscrollL.addEventListener('click', () => {
        songItemAfroBeat.scrollLeft -= 330;
    })
    afrobeatscrollR.addEventListener('click', () => {
        songItemAfroBeat.scrollLeft += 330;
    })
}

// scroll for afropop songs

// scroll for afropop songs
const afropopscrollL = document.getElementById('afropopscrollL');
const afropopscrollR = document.getElementById('afropopscrollR');
const songItemAfroPop = document.querySelectorAll('.afro-pop')[0];

if(afropopscrollL && afropopscrollR){
    afropopscrollL.addEventListener('click', () => {
        songItemAfroPop.scrollLeft -= 330;
    })
    afropopscrollR.addEventListener('click', () => {
        songItemAfroPop.scrollLeft += 330;
    })
}
// scroll for afropop songs

// scroll for amapiano songs
const amapianoscrollL = document.getElementById('amapianoscrollL');
const amapianoscrollR = document.getElementById('amapianoscrollR');
const songItemAmapiano = document.querySelectorAll('.amapiano')[0];

if(amapianoscrollL && amapianoscrollR){
    amapianoscrollL.addEventListener('click', () => {
        songItemAmapiano.scrollLeft -= 330;
    })
    amapianoscrollR.addEventListener('click', () => {
        songItemAmapiano.scrollLeft += 330;
    })
}
// scroll for amapiano songs