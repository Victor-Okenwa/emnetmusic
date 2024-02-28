const wrapper = document.querySelector(".wrapper"),
    blockedMain = wrapper.querySelector(".main-area"),
    inboxContainer = wrapper.querySelector(".inbox"),
    serarchBox = wrapper.querySelector(".em-search-box"),
    blockedSearchBtn = blockedMain.querySelector(".search-btn"),
    blockedUserNames = wrapper.querySelectorAll(".message .inbox-msg .user-info span"),
    inboxArea = wrapper.querySelector(".inbox"),
    unBlocker = wrapper.querySelectorAll(".box .unblocker"),
    infoText = document.getElementById("infoText");



const fillOpcaityError = (info, time) => {
    infoText.classList = "alert alert-danger fade";
    infoText.style.display = "initial";
    infoText.style.opacity = ".8";
    infoText.innerText = info;
    setTimeout(() => {
        infoText.style.display = "none";
        infoText.style.opacity = 0;
        infoText.innerText = "";
        infoText.classList = "";
    }, time);
}

const fillOpcaitySuccess = (info, time) => {
    infoText.classList = "alert alert-success fade";
    infoText.style.display = "initial";
    infoText.style.opacity = ".8";
    infoText.innerText = info;
    setTimeout(() => {
        infoText.style.display = "none";
        infoText.style.opacity = 0;
        infoText.innerText = "";
        infoText.classList = "";
    }, time);
}

var isLoading = false;
var currentPage = 1;
var resultsPerPage = 7;

function loadMore() {
    isLoading = false;
    var noMore = Array.from(document.getElementsByClassName("no-more"));

    $.ajax({
        url: 'blockList.php',
        method: 'GET',
        data: {
            page: currentPage,
            perPage: resultsPerPage,
        },

        success: function (data) {
            $("#loadermore").hide();
            $(".inbox").append(data);
            currentPage++;
        },
        error: function () {
            isLoading = false;
            $("#loadermore").html("Failed to load more results");
        },
        beforeSend: function (xhr) {
            if (noMore.length > 0) {
                xhr.abort();
            } else {
                $("#loadermore").show();
            }
        }
    })
}


window.addEventListener("DOMContentLoaded", () => {
    loadMore();
})

serarchBox.addEventListener("keyup", (e) => {
    var filter = serarchBox.value.trim().toLowerCase();

    if (serarchBox.value !== "") {
        blockedSearchBtn.classList.add("nullValue");
        blockedSearchBtn.innerHTML = `<i class="fas fa-times"></i>`;
    } else {
        blockedSearchBtn.classList.remove("nullValue");
        blockedSearchBtn.innerHTML = `<i class="fas fa-search"></i>`;
    }

    blockedUserNames.forEach(user => {
        if (serarchBox.value == "") {
            user.parentElement.parentElement.parentElement.parentElement.style.display = "initial";
        }

        userName = user.textContent;


        if (userName.toLowerCase().indexOf(filter) > -1) {
            user.parentElement.parentElement.parentElement.parentElement.style.display = "flex";
        } else {
            user.parentElement.parentElement.parentElement.parentElement.style.display = "none";
        }
    });
});


serarchBox.addEventListener("change", () => {
    filterBox = Array.from(document.getElementsByClassName("box"));

    filterBox.forEach(box => {
        if (box.style.display == "none") {
            box.style.display = "flex";
        }
    });
});

blockedSearchBtn.onclick = function () {
    if (this.classList.contains("nullValue")) {
        serarchBox.parentElement.reset();
        blockedSearchBtn.classList.remove("nullValue");
        blockedSearchBtn.innerHTML = `<i class="fas fa-search"></i>`;
    }
}

unBlocker.forEach(btn => {
    btn.onclick = () => {
        var confirmUnblock = confirm("Are you sure you want to unblock " + btn.parentElement.querySelector(".message .user-info span").innerText);
    }
})

function callUnBlock(btn) {
    let unblockID = btn.getAttribute("data-user-id");
    var confirmUnblock = confirm("Are you sure you want to unblock " + btn.parentElement.querySelector(".message .user-info span").innerText);

    if (confirmUnblock) {
        $.ajax({
            url: 'block_user.php',
            method: 'POST',
            data: {
                unblockID: unblockID,
            },
            success: function (response) {
                if (response.status == 'error') {
                    fillOpcaityError(response.message, 3500);
                } else {
                    $(`#${unblockID}`).remove();
                    fillOpcaitySuccess(response.message, 3500);
                }
            },
            error: function (jqXhr, status, error) {
                jqXhr.abort();
                if (status == 'timeout') {
                    fillOpcaityError('Request timed out', 3500);
                } else {
                    fillOpcaitySuccess('Error ocurred during unblocking', 3500);
                }
            }
        })
    }
}

inboxContainer.addEventListener("scroll", () => {
    let currentHeight = inboxContainer.scrollTop + inboxContainer.clientHeight;
    if (currentHeight >= inboxContainer.scrollHeight) {
        loadMore();
    }
})