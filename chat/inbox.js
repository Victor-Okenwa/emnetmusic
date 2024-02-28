// Update Users table fo status update
function updateStatus(status) {
    $.ajax({
        url: '../backend/update_status.php',
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

const wrapper = document.querySelector(".wrapper"),
    inboxMain = wrapper.querySelector(".main-area"),
    inboxContainer = wrapper.querySelector(".inbox"),
    inboxSearchInput = inboxMain.querySelector(".em-search-box"),
    inboxSearchBtn = inboxMain.querySelector(".search-btn"),
    infoText = document.getElementById("infoText");

var filterBox, blockButtons;

// Helper algorithm to sort divs
function sortDivsByIdDesc(divs) {
    divs.sort(function (a, b) {
        var idA = parseInt($(a).attr('id'));
        var idB = parseInt($(b).attr('id'));
        return idB - idA;
    });
}

const fillOpcaityError = (info, time) => {
    infoText.classList = "alert alert-danger fade";
    infoText.style.display = "initial";
    infoText.style.opacity = ".8";
    infoText.innerHTML = info;
    setTimeout(() => {
        infoText.style.display = "none";
        infoText.style.opacity = 0;
        infoText.innerHTML = "";
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

// Get new inbox 
function checkUpdate() {
    var inboxes = Array.from(document.querySelectorAll(".box"));
    if (inboxes.length > 0) {
        var lastInboxID = inboxes[0].getAttribute("data-msg-id");
    }

    $.ajax({
        url: "checkNewInbox.php",
        type: "POST",
        data: {
            lastInboxID: lastInboxID,
        },
        success: function (data) {
            if (data == 1)
                newInbox();
        },
    })
}

var isLoading = false;
var currentPage = 1;
var resultsPerPage = 15;

function newInbox() {
    var inboxes = Array.from(document.querySelectorAll(".box"));
    var inboxesLength = inboxes.length;
    var i;

    var senderIds = [];

    if (inboxes.length > 0) {
        var lastInboxID = inboxes[0].getAttribute("data-msg-id");
        var lastsenderID = inboxes[0].getAttribute("data-sender-id");
        for (i = 0; i < inboxesLength; i++) {
            senderIds.push(inboxes[i].getAttribute("data-sender-id"))
        }
    }

    $.ajax({
        url: "newInbox.php",
        type: "POST",
        dataType: 'json',
        data: {
            lastInboxID: lastInboxID,
            lastSender: lastsenderID,
            existingIds: senderIds,
        },
        success: function (data) {
            if (data.type == '1') {
                $(`#${inboxes[0].id}`).before(data.value);
            } else {
                for (i = 0; i < inboxesLength; i++) {
                    if (inboxes[i].getAttribute("data-sender-id") == data.sender_id) {
                        $(`#${inboxes[0].id}`).before(data.value);
                        inboxes[i].remove();
                    }
                }
            }
        },

    })
}

// Function to fetch Chat from php script through ajax
function loadMore() {
    isLoading = false;
    var noMore = Array.from(document.getElementsByClassName("no-more"));

    $.ajax({
        url: 'inboxList.php',
        method: 'POST',
        timeout: 50000,
        data: {
            page: currentPage,
            perPage: resultsPerPage,
        },

        success: function (data) {
            $("#loadermore").hide();
            if (currentPage <= 2) {
                var tempDiv = $('<div>').html(data);
                var setsOfDivs = tempDiv.find('.box'); // Replace with your actual div class selector
                sortDivsByIdDesc(setsOfDivs);
                $(".inbox").append(setsOfDivs);
            } else {
                $(".inbox").append(data);
            }
            currentPage++;
        },
        error: function (jqXhr, status, error) {
            isLoading = false;
            if(status == 'timeout'){
                $("#loadermore").html("Results load timed out");
            }else{
                $("#loadermore").html("Failed to load more results");
            }
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

window.addEventListener("DOMContentLoaded", (e) => {
    loadMore();
    setInterval(() => {
        checkUpdate();
    }, 1500);
});

inboxSearchInput.addEventListener("input", (e) => {
    var searchValue = inboxSearchInput.value.trim().toLowerCase();
    filterBox = Array.from(document.getElementsByClassName("box"));

    if(filterBox.length > 1){
        if (searchValue !== null) {
            inboxSearchBtn.classList.add("nullValue");
            inboxSearchBtn.innerHTML = `<i class="fas fa-times"></i>`;
        } else {
            inboxSearchBtn.classList.remove("nullValue");
            inboxSearchBtn.innerHTML = `<i class="fas fa-search"></i>`;
        }
    
        filterBox.forEach(box => {
            var userName = box.querySelector(".inbox-msg .user-info span").textContent.toLowerCase();
            if (box.style.display !== "flex") {
                inboxSearchBtn.classList.add("nullValue");
                inboxSearchBtn.innerHTML = `<i class="fas fa-times"></i>`;
            } else {
                inboxSearchBtn.classList.remove("nullValue");
                inboxSearchBtn.innerHTML = `<i class="fas fa-search"></i>`;
            }
    
            if (userName.includes(searchValue)) {
                box.style.display = "flex";
            } else {
                box.style.display = "none";
            }
        });
    }
});

inboxSearchInput.addEventListener("change", () => {
    filterBox = Array.from(document.getElementsByClassName("box"));

    if(filterBox.length > 1){
        filterBox.forEach(box => {
            if (box.style.display == "none") {
                box.style.display = "flex";
            }
        });
    }
});

inboxSearchBtn.onclick = function () {
    if (this.classList.contains("nullValue")) {
        inboxSearchInput.parentElement.reset();
        inboxSearchBtn.classList.remove("nullValue")
        inboxSearchBtn.innerHTML = `<i class="fas fa-search"></i>`;
    }
}

inboxContainer.addEventListener("scroll", () => {
    let currentHeight = inboxContainer.scrollTop + inboxContainer.clientHeight;
    if (currentHeight >= inboxContainer.scrollHeight) {
        loadMore();
    }
});


function callBlock(btn) {
    let userID = btn.getAttribute('data-user-id');
    let userName = btn.parentElement.parentElement.parentElement.querySelector(".message .inbox-msg .user-info span").innerText.trim();
    let inboxID = btn.parentElement.parentElement.parentElement.getAttribute('data-msg-id');
    let confirmBlock = confirm(`Do you want to BLOCK ${userName}?`);

    if (confirmBlock) {
        $.ajax({
            url: 'block_user.php',
            method: 'POST',
            timeout: 10000,
            data: {
                toBlockID: userID,
            },
            success: function (response) {
                if (response.status == 'error') {
                    fillOpcaityError(response.message, 3500);
                } else {
                    $(`#${inboxID}`).remove();
                    fillOpcaitySuccess(response.message, 3500);
                }
            },
            error: function (jqXhr, status, error) {
                jqXhr.abort();
                if (status == 'timeout') {
                    fillOpcaityError("Request Timed out", 3500);
                } else {
                    fillOpcaityError("Error occured, try again", 3500);
                }
            }
        });
    }
}

function clearChat(btn) {
    var userID = btn.id;
    var currentInboxID = btn.parentElement.parentElement.parentElement;
    var inboxName = btn.parentElement.parentElement.parentElement.querySelector('.message .inbox-msg .user-info span').textContent;
    var lastMessage = btn.parentElement.parentElement.parentElement.querySelector('.message .inbox-msg .user-info p');

    var confirmClear = confirm(`Do you want to CLEAR your chat with ${inboxName}?`);

    if (confirmClear) {
        $.ajax({
            url: 'clearChat.php',
            method: 'POST',
            timeout: 10000,
            data: {
                toClearId: userID,
            },
            beforeSend: function () {
                $("#loader-clear").show();
            },
            success: function (response) {
                $("#loader-clear").hide();
                if (response.status == 'error') {
                    fillOpcaityError(response.message, 3500);
                } else {
                    fillOpcaitySuccess(response.message, 'success');
                    lastMessage.textContent = 'No chat available';
                }
            },
            error: function (jqXhr, status, error) {
                $("#loader-clear").hide();
                console.log(jqXhr, status, error);
                if (status == 'timeout') {
                    fillOpcaityError('Request timed out', 3500);
                } else {
                    fillOpcaityError('Error occured', 3500);
                }
            }
        });
    }
}