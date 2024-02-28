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
    headArea = wrapper.querySelector("header .headerArea"),
    fullOptions = wrapper.querySelector(".full-options"),
    fullOptionsIn = wrapper.querySelector(".full-options-in"),
    inboxAlert = wrapper.querySelector(".ico"),
    chatBox = wrapper.querySelector(".chat-box"),
    like = chatBox.querySelectorAll(".chat.incoming .like"),
    msgBox = document.getElementById("msg-box"),
    textarea = document.getElementById("text-box"),
    send = document.querySelector(".send"),
    likeTone = document.getElementById("toneAudio"),
    postAudio = document.getElementById("postAudio"),
    toBottom = wrapper.querySelector(".to-bottom"),
    internetStatus = wrapper.querySelector(".internetStatus"),

    // SENDING TEXT MESSAGE
    textSenderID = document.getElementById("sender_id"),
    textReceiverID = document.getElementById("receiver_id"),
    textBox = document.getElementById("text-box"),
    textSendBtn = document.getElementById("text-send-btn"),
    textSendBtnIcon = document.querySelector(".send-text-icon"),
    textSendBtnLoader = document.querySelector(".send-text-loader"),
    textInfomation = document.getElementById("infoText"),

    // SENDING IMAGE MESSAGES
    imageModal = document.getElementById("ImageModal"),
    imageMsgForm = document.getElementById("image_form"),
    imageSenderID = document.getElementById("image_sender_id"),
    imageReceiverID = document.getElementById("image_receiver_id"),
    uploadBox = document.querySelector(".upload-box"),
    previewImage = document.getElementById("previewImage"),
    imageFile = document.getElementById("image_file"),
    imageMsg = document.getElementById("image_msg"),
    imageUploadBtn = document.getElementById("imageUploadBtn"),
    imageUploadBtnText = document.querySelector(".btn-txt"),
    imageUploadBtnLoader = document.getElementById("imageLoader"),
    imageInformationError = document.getElementById("imageText"),
    imageProgress = document.querySelector(".info-on-upload"),

    // FOR MSG IMAGE DISPLAY
    viewMsgImage = wrapper.querySelector(".view-msg-image"),
    imgToDisplay = document.getElementById("msg-img-to-display"),
    imageSrc = document.getElementById("image-src"),

    // FOR THE OUTGOING MSG OPTIONS
    moreOut = document.querySelectorAll(".more-out"),
    copyMsg = document.getElementById("copy_msg"),
    editMsg = document.getElementById("edit_msg"),
    deleteMsg = document.getElementById("delete_msg"),
    msgTextBox = document.getElementById("em-htm"),
    msgType = document.getElementById("em-type"),

    // FOR THE INCOMING MSG OPTIONS
    moreIn = document.querySelectorAll(".more-in"),
    copyMsgIN = document.getElementById("copy_msg_in"),
    deleteMsgIN = document.getElementById("delete_msg_in"),
    msgTextBoxIN = document.getElementById("em-htm-in"),
    msgTypeIN = document.getElementById("em-type-in"),

    // FOR MODAL TEXTS
    modalId = document.getElementById("msg_id"),
    modalMsg = document.getElementById("modal_msg"),
    modalFile = document.getElementById("msg_file");

var chatsArray, lastMessageID;
var msgCont, msgTxt;

// create an XMLHttpRequest object to track upload progress
var xhr = new XMLHttpRequest();

// ======== MY FUNCTIONS =========

function scrollToBotom(object) {
    object.scrollTop = 0;
}

// COPY TO CLIPBOARD FUNCTION
const copyToClipboard = (text) => {
    if (text != "") {
        navigator.clipboard.writeText(text)
            .then(() => {
                alert('Copied to clipboard');
            })
            .catch((error) => {
                alert('Failed to copy to clipboard'.error);
            });
    } else {
        alert("Message empty")
    }
}

// SCROLL TO BOTTOM BUTTON FUNCTION
function scrollButtom(btn) {
    scrollToBotom(chatBox);
    btn.style.opacity = "0 !important";
}

window.addEventListener("DOMContentLoaded", (e) => {
    loadMore(1);
    setTimeout(() => {
        msgCont = chatBox.querySelectorAll(".msg-cont");
        msgTxt = chatBox.querySelectorAll(".msg-txt");
        textToLink();
    }, e.timeStamp);

    // SET INTERVAL FOR FETCHING RECEIVER INFO
    setInterval(() => {
        $.ajax({
            method: "POST",
            url: "topStatus.php",

            data: {
                senderID: textSenderID.value,
                receiverID: textReceiverID.value,
            },

            success: function (response) {
                headArea.innerHTML = response;
            }
        })
    }, 1500);

    // CHECKING IF THERE IS AN UNREAD POST FOR UPDATE
    setInterval(() => {
        checkUpdate();
        visibilityUpdates();
        checkBlock();
    }, 2000);
});
window.addEventListener("load", () => {
    checkUpdate();
});

// OPACITY AND DISPLAY TIMEOUT FOR MESSAGES
const fillOpcaityError = (html, info, time) => {
    html.classList = "alert alert-danger fade";
    html.style.display = "initial";
    html.style.opacity = ".8";
    html.innerText = info;
    setTimeout(() => {
        html.style.display = "none";
        html.style.opacity = 0;
        html.innerText = "";
        html.classList = "";
    }, time);
}

const fillOpcaitySuccess = (html, info, time) => {
    html.classList = "alert alert-success fade";
    html.style.display = "initial";
    html.style.opacity = ".8";
    html.innerText = info;
    setTimeout(() => {
        html.style.display = "none";
        html.style.opacity = 0;
        html.innerText = "";
        html.classList = "";
    }, time);
}

function updateReadStatus() {
    $.ajax({
        method: "POST",
        url: "updateRead.php",
        data: {
            senderID: textReceiverID.value,
        },
    })
}

function checkUpdate() {
    $.ajax({
        method: "POST",
        url: "checkUpdate.php",
        data: {
            senderID: textReceiverID.value,
        },
        success: function (response) {
            if (response == 1) {
                chatsArray = Array.from(chatBox.querySelectorAll(".chat"));

                if (chatsArray.length > 0) {
                    lastMessageID = chatsArray[0].id;
                } else {
                    lastMessageID = '';
                }

                if (!chatBox.classList.contains('active') && chatBox.scrollTop !== 0) {
                    scrollToBotom(chatBox);
                }
                newChatReceiver(lastMessageID);
                updateReadStatus();
            }
        }
    });
}

function checkBlock() {
    $.ajax({
        url: 'checkBlock.php',
        method: 'POST',
        data: {
            blockerID: textReceiverID.value,
        },
        success: function (response) {
            if (response) {
                $("#image-modal-caller").remove();
                $("#text-send-btn").remove();
                $("#text-box").remove();
            } else {
                if (!$("#image-modal-caller")) {
                    $("#image-modal-caller").add();

                }
            }
        },
    });
}


function visibilityUpdates() {
    $.ajax({
        method: "POST",
        url: "visibilityUpdate.php",
        data: {
            senderID: textReceiverID.value,
        },
        success: function (response) {
            if (response.status == 1) {
                var message_id = response.message_id;
                var idsLength = response.message_id.length;

                for (var i = 0; i < idsLength; i++) {
                    document.getElementById(message_id[i]).remove();
                }
            }
        }
    });
}


var isLoading = false;
var currentPage = 1;
var resultsPerPage = 20;

// Function to fetch Chat from php script through ajax
function loadMore(index) {
    isLoading = true;
    $("#loadermore").show();
    var noChat = document.querySelectorAll(".no-chat");

    if (index == 1) {
        $.ajax({
            url: 'getChat.php',
            method: 'POST',
            data: {
                senderID: textSenderID.value,
                receiverID: textReceiverID.value,
                page: currentPage,
                perPage: resultsPerPage,
            },
            success: function (data) {
                $("#loadermore").hide();
                $(".chat-box").append(data);
                currentPage++;
                $("#loadermore").html("Load more");
            },
            error: function () {
                isLoading = false;
                $("#loadermore").html("Failed to load more results");
            },
            beforeSend: function (xhr) {
                if (noChat.length > 0) {
                    xhr.abort();
                    $("#loadermore").html("End of chat");
                } else {
                    $("#loadermore").html("Loading <span class='spinner-grow spinner-grow-sm'></span>");
                }
            }
        })
    } else {
        $.ajax({
            url: 'getChat.php',
            method: 'POST',
            data: {
                senderID: textSenderID.value,
                receiverID: textReceiverID.value,
                page: currentPage,
                perPage: resultsPerPage,
            },

            success: function (data) {
                $("#loadermore").hide();
                chatBox.innerHTML = data;
                $("#loadermore").html("Load more");
                textToLink();
            },
            error: function () {
                isLoading = false;
                $("#loadermore").html("Failed to load more results");
            },
            beforeSend: function (xhr) {
                $("#loadermore").html("Loading <span class='spinner-border spinner-border-sm'></span>");
            }
        })
    }
}

// Function for on page update for the current user
function newChatSender(lastMessageID) {
    chatsArray = Array.from(chatBox.querySelectorAll(".chat"));
    $.ajax({
        method: "POST",
        url: "update_chat.php",
        timeout: 20000,

        data: {
            requestType: 'sender',
            receiverID: textReceiverID.value,
            lastMessageID: lastMessageID
        },

        success: function (response) {
            if (chatsArray.length > 0) {
                $(`#${chatsArray[0].id}`).before(response);
            } else {
                $(".chat-box").html(response);
            }
            scrollToBotom(chatBox);
            setTimeout(() => {
                msgCont = chatBox.querySelectorAll(".msg-cont");
                msgTxt = chatBox.querySelectorAll(".msg-txt");
                textToLink();
            }, 500);
        },
        error: function (xhr, timeout, error) {
            if (timeout == 'timeout') {
                fillOpcaityError(textInfomation, "Timeout! Failed to update chat", 4000);
            } else {
                // fillOpcaityError(textInfomation, "Error in updating chat: " + error, 4000);
            }
        }
    })
}

// Function for the onpage update for the receiver
function newChatReceiver(lastMessageID) {
    $.ajax({
        method: "POST",
        url: "update_chat.php",
        timeout: 20000,

        data: {
            requestType: 'receiver',
            receiverID: textReceiverID.value,
            lastMessageID: lastMessageID
        },

        success: function (response) {
            chatsArray = Array.from(chatBox.querySelectorAll(".chat"));
            if (chatsArray.length > 0) {
                $(`#${chatsArray[0].id}`).before(response);
            } else {
                $(".chat-box").html(response);
            }
            scrollToBotom(chatBox);
            setTimeout(() => {
                msgCont = chatBox.querySelectorAll(".msg-cont");
                msgTxt = chatBox.querySelectorAll(".msg-txt");
                textToLink();
            }, 500);
        },
        error: function (xhr, timeout, error) {
            if (timeout == 'timeout') {
                fillOpcaityError(textInfomation, "Timeout! Failed to update chat", 4000);
            } else {
                fillOpcaityError(textInfomation, "Error in updating chat: " + error, 4000);
            }
        }
    })
}


function requestLike(msgID, status) {
    $.ajax({
        method: "POST",
        url: "like.php",

        data: {
            likeStatus: status,
            messageID: msgID,
        }
    })
}

// TO LOOP THROUGH EACH WORD TO CHECK IF IT IS A URL
function textToLink() {
    for (var i = 0; i < msgTxt.length; i++) {
        var text = msgTxt[i].innerHTML;

        // Split the text into an array of words
        var words = text.split(" ");

        // Loop through each word
        for (var j = 0; j < words.length; j++) {
            // Check if the word is a URL
            if (isValidUrl(words[j])) {
                // Replace the word with an anchor tag
                words[j] = `<a href="${words[j]}" target="_blank"> ${words[j]} </a>`;
            }
        }
        var new_text = words.join(" ");
        msgTxt[i].innerHTML = new_text;
    }
}

// Helper function to check if a string is a URL
function isValidUrl(string) {
    try {
        new URL(string);
    } catch (_) {
        return false;
    }
    return true;
}

// TIMEOUT FUNCTION TO START AFTER TIME FOR SCROLL BOTTOM BUTTON DISPLAY
setTimeout(() => {
    chatBox.onscroll = function () {
        let currentPosition = Math.round(chatBox.scrollTop);
        if (currentPosition + chatBox.clientHeight >= chatBox.scrollHeight) {
            loadMore(1);
        } else {
            $("#loadermore").hide();
        }

        if (currentPosition > 400) {
            toBottom.style.opacity = .4;
        } else {
            toBottom.style.opacity = 0;
        }
        // chatBox.scrollHeight - chatBox.clientHeight
        if (currentPosition > 100) {
            chatBox.classList.add("active");
        } else {
            chatBox.classList.remove("active");
        }
    }
}, 100);

document.addEventListener("click", (e) => {
    //  TO CLOSE FULL OPTIONS
    if (e.target == fullOptions) {
        if (fullOptions.classList.contains("d-block")) {
            fullOptions.classList.remove("d-block")
            fullOptions.style.opacity = "0";
        }
    }


    if (e.target == fullOptionsIn) {
        if (fullOptionsIn.classList.contains("d-block")) {
            fullOptionsIn.classList.remove("d-block");
        }
    }

    if (e.target == viewMsgImage) {
        if (viewMsgImage.classList.contains("d-flex")) {
            viewMsgImage.classList.remove("d-flex")
        }
    }
});



function moreForOut(btn) {
    btn.onclick = function () {
        var messageText = ''
        if (this.parentElement.querySelector(".msg-cont .msg-txt")) {
            messageText = this.parentElement.querySelector(".msg-cont .msg-txt").innerText.trim();
        }
        let isVisibleOptions = fullOptions.classList.contains("d-block");
        let msgID = this.id;

        let msgTypeText = this.getAttribute("data-type");

        if (!isVisibleOptions) {
            fullOptions.classList.add("d-block")
            fullOptions.style.opacity = "1";
            fullOptions.setAttribute('data-msg-id', msgID);
            msgTextBox.innerText = messageText;
            msgType.innerText = msgTypeText;
        }
    }
}

function moreForIn(btn) {
    var incomingMsgText = "";

    if (btn.parentElement.parentElement.querySelector(".msg-cont .msg-txt")) {
        incomingMsgText = btn.parentElement.parentElement.querySelector(".msg-cont .msg-txt").innerText.trim();
    }

    let isVisibleOptionsIn = fullOptionsIn.classList.contains("d-block");
    let msgID = btn.id;
    let msgTypeText = btn.getAttribute("data-type");
    if (!isVisibleOptionsIn) {
        fullOptionsIn.classList.add("d-block")
        fullOptionsIn.style.opacity = "1";
        fullOptionsIn.setAttribute('data-msg-id', msgID);
        msgTextBoxIN.innerText = incomingMsgText;
        msgTypeIN.innerText = msgTypeText;
    }
}


// FOR THE MAIN SEND MESSAGE TEXT AREA
textarea.addEventListener("keyup", (e) => {

    if (textarea.value != "") {
        send.removeAttribute("disabled");
    } else {
        send.setAttribute("disabled", "");
    }
})


// FOR THE MAIN SEND TEXT MESSAGE SEND CLAUSE
textSendBtn.addEventListener("click", function (e) {
    $(document).ready(function () {
        chatsArray = Array.from(chatBox.querySelectorAll(".chat"));
        if (chatsArray.length > 0) {
            lastMessageID = chatsArray[0].id;
        } else {
            lastMessageID = '';
        }

        $.ajax({
            type: "POST",
            url: "insert_text_chat.php",
            timeout: 20000,

            data: {
                senderID: textSenderID.value,
                receiverID: textReceiverID.value,
                message: textBox.value,
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.log("drxfgvbhjnk");
                if (textStatus === 'timeout') {
                    // handle timeout error here
                    fillOpcaityError(textInfomation, "You're request timed out", 4000);
                    textSendBtnLoader.classList.add("d-none");
                    textSendBtnIcon.classList.remove("d-none");

                } else {
                    // handle other error cases here
                    fillOpcaityError(textInfomation, "Error occured, try again", 4000);
                    textSendBtnLoader.classList.add("d-none");
                    textSendBtnIcon.classList.remove("d-none");
                }
            },
            beforeSend: function () {
                textSendBtnLoader.classList.remove("d-none");
                textSendBtnIcon.classList.add("d-none");
                scrollToBotom(chatBox);
            },
            success: function (response) {
                textSendBtnLoader.classList.add("d-none");
                textSendBtnIcon.classList.remove("d-none");
                if (response.status == "error") {
                    fillOpcaityError(textInfomation, response.message, 4000);
                } else {
                    postAudio.play();
                    textBox.value = "";
                    newChatSender(lastMessageID);
                    textToLink();
                }

            }
        })
    })
})


// FOR THE IMAGE SEND MESSAGE CLAUSE

let imageWidth, imageHeight;

const loadFile = (e) => {
    const file = e.target.files[0];
    if (!file) return;
    if (file.type.startsWith("image/")) {
        if (file.type == "image/jpg" || file.type == "image/png" || file.type == "image/jfif" || file.type == "image/gif" || file.type == "image/jpeg") {

            if (file.size <= 2000000) {
                previewImage.src = URL.createObjectURL(file);

                previewImage.addEventListener("load", () => {
                    imageWidth = previewImage.naturalWidth;
                    imageHeight = previewImage.naturalHeight;
                })
            } else {
                previewImage.src = "";
                fillOpcaityError(imageInformationError, "File is larger than 2mb", 6000);
            }
        } else {
            previewImage.src = "";
            fillOpcaityError(imageInformationError, "Only JPG, PNG. JFIF, GIF and JPEG allowed", 6000);
        }
    } else {
        previewImage.src = "";
        fillOpcaityError(imageInformationError, "File is not a valid image file ".file.type, 6000);
    }
}

const resizeImage = () => {
    const canvas = document.createElement("canvas");
    const ctx = canvas.getContext("2d");
    canvas.width = imageWidth;
    canvas.height = imageHeight;

    ctx.drawImage(previewImage, 0, 0, canvas.width, canvas.height);

    const compressedImage = canvas.toDataURL("image/jpeg", 0.7);
    return compressedImage;
}

imageFile.addEventListener("change", loadFile);
uploadBox.addEventListener('click', () => imageFile.click());

imageUploadBtn.addEventListener("click", function () {
    imageUploadBtn.setAttribute('disabled', '');
    chatsArray = Array.from(chatBox.querySelectorAll(".chat"));
    if (chatsArray.length > 0) {
        lastMessageID = chatsArray[0].id;
    } else {
        lastMessageID = '';
    }

    if (previewImage.src == "") {
        fillOpcaityError(imageInformationError, "Image field is empty", 6000);
        return;
    }

    imageMsgForm.onsubmit = function (e) {
        e.preventDefault();
    }

    xhr.open("POST", "insert_img_chat.php", true);
    xhr.timeout = 25000;

    xhr.onload = () => {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                let data = JSON.parse(xhr.responseText);

                if (data.status == "success") {
                    imageFile.value = "";
                    imageMsg.value = "";
                    imageModal.click();
                    imageUploadBtn.removeAttribute('disabled');
                    newChatSender(lastMessageID);
                    postAudio.play();
                } else {
                    imageUploadBtn.removeAttribute('disabled');
                    fillOpcaityError(imageInformationError, data.message, 10000);
                }
            }
        }
    }

    xhr.onprogress = function () {
        imageUploadBtnLoader.classList.remove("d-none");
        imageUploadBtnText.classList.add("d-none");
    }

    xhr.onreadystatechange = function () {
        if (xhr.readyState < 4) {
            imageUploadBtnLoader.classList.remove("d-none");
            imageUploadBtnText.classList.add("d-none");
        } else {
            imageUploadBtnLoader.classList.add("d-none");
            imageUploadBtnText.classList.remove("d-none");
            imageProgress.classList.add("d-none");
            imageProgress.querySelector("i").innerText = "";
        }
    }

    xhr.onerror = function () {
        imageUploadBtn.removeAttribute('disabled');
        fillOpcaityError(imageInformationError, "Error occured, try again", 20000);
        imageProgress.classList.add("d-none");
        imageProgress.querySelector("i").innerText = "";
    }

    xhr.upload.addEventListener('progress', function (event) {
        if (event.lengthComputable) {
            // calculate percentage of completion
            var percentComplete = event.loaded / event.total * 100;

            // update the progress element
            imageProgress.classList.remove("d-none");
            imageProgress.querySelector("i").innerText = "";
            imageProgress.querySelector("i").innerText = percentComplete.toFixed(1) + '%';
            imageUploadBtn.setAttribute('disabled', '');
        }
    }, false);

    xhr.upload.ontimeout = function () {
        imageUploadBtn.removeAttribute('disabled');
        fillOpcaityError(imageInformationError, `Timeout error: ${xhr.status}`, 6000);
    }

    xhr.upload.abort = function () {
        fillOpcaityError(imageInformationError, `Message aborted: ${xhr.status}`, 6000);
    }

    xhr.upload.onerror = function () {
        alert(`Error during the upload: ${xhr.status}`);
    };
    // we have to send the form data through ajax to php
    let formData = new FormData(imageMsgForm);
    formData.append("imageFile", resizeImage());
    xhr.send(formData); //sending the form to php
})

moreIn.forEach((btn) => {
    btn.onclick = function () {
        var incomingMsgText = "";

        if (this.parentElement.parentElement.querySelector(".msg-cont .msg-txt")) {
            incomingMsgText = this.parentElement.parentElement.querySelector(".msg-cont .msg-txt").innerText.trim();
        }

        let isVisibleOptionsIn = fullOptionsIn.classList.contains("d-block");
        let msgID = this.id;
        let msgTypeText = this.getAttribute("data-type");
        if (!isVisibleOptionsIn) {
            fullOptionsIn.classList.add("d-block")
            fullOptionsIn.style.opacity = "1";
            fullOptionsIn.id = msgID;
            msgTextBoxIN.innerText = incomingMsgText;
            msgTypeIN.innerText = msgTypeText;
        }

    }
})

// TO COPY MESSAGE TEXT
copyMsg.addEventListener("click", function () {
    copyToClipboard(msgTextBox.innerText)
})

function textEdit(id, type, message) {
    $.ajax({
        method: "POST",
        url: "editchat.php",
        dataType: 'json',

        data: {
            chatID: id,
            chatType: type,
            message: message,
        },

        success: function (response) {
            let data = JSON.parse(response);
            console.log(data);
        }
    })
}

// TO EDIT AND ADD TEXT VALUE TO MODAL TEXTAREA
// editMsg.addEventListener("click", () => {
//     let typeGet = msgType.innerText;
//     modalMsg.value = "";
//     modalMsg.classList = "form-control";
//     modalFile.classList = "";

//     modalId.value = fullOptions.id;

//     if (typeGet == "text") {
//         modalMsg.classList.add("d-block")
//         modalMsg.value = msgTextBox.innerText;
//         console.log(modalId.value);
//         textEdit(fullOptions.id, typeGet, modalMsg.value);
//     } else if (typeGet == "text/img") {
//         modalMsg.classList.add("d-block");
//         modalFile.classList.add("d-block");
//         modalMsg.value = msgTextBox.innerText;
//     } else {
//         modalFile.classList.add("d-block");
//     }
// })

// THIS BLOCK IS TO DELETE THE MESSAGE THROUGH AJAX-PHP
deleteMsg.addEventListener("click", () => {
    let msgID = fullOptions.getAttribute('data-msg-id');
    let confirmDelete = confirm("Are you sure you want to delete your msg?");

    if (confirmDelete) {
        $.ajax({
            url: "delete_chat.php",
            method: "POST",
            dataType: 'json',
            data: {
                messageID: msgID,
            },
            success: function (response) {
                if (response.status == "error") {
                    alert(response.message);
                } else {
                    fillOpcaitySuccess(textInfomation, response.message, 4000);
                    document.getElementById(msgID).remove();
                    fullOptions.click();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (textStatus == 'timeout') {
                    alert("Your request timed out");
                } else {
                    alert("Error: " + errorThrown);
                }
            }
        })
    }
})

// TO LIKE AND PLAY/STOP CLICK TONE

function likeMsg(item) {
    let isLiked = item.classList.contains("liked");
    let msgID = item.parentElement.querySelector(".more-in").id;
    if (isLiked == false) {
        item.classList.add("liked")
        likeTone.play();
        requestLike(msgID, 1);
    } else {
        item.classList.remove("liked")
        likeTone.play();
        requestLike(msgID, 0);
    }
}

// FOR INCOMING MESSAGES FULL OPTIONS
copyMsgIN.addEventListener("click", function () {
    copyToClipboard(msgTextBoxIN.innerText)
})

deleteMsgIN.addEventListener("click", () => {
    let msgID = fullOptionsIn.getAttribute('data-msg-id');
    let confirmDelete = confirm("This message will be hidden forever?");

    if (confirmDelete) {
        $.ajax({
            url: "delete_chat.php",
            method: "POST",
            dataType: 'json',
            data: {
                messageID: msgID,
            },
            success: function (response) {
                if (response.status == "error") {
                    alert(response.message);
                } else {
                    fillOpcaitySuccess(textInfomation, response.message, 4000);
                    document.getElementById(msgID).remove();
                    console.log(2);
                    fullOptionsIn.click();

                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (textStatus == 'timeout') {
                    alert("Your request timed out");
                } else {
                    alert("Error: " + errorThrown);
                }
            }
        })
    }
})

// var msgImgDisplay = document.querySelectorAll(".msg-img-display");

// if (msgImgDisplay) {
//     msgImgDisplay.forEach((img) => {
//         img.ondblclick = function () {
//             viewMsgImage.classList.add("d-flex");
//             imgToDisplay.src = img.src;
//             imageSrc.href = img.src;
//         }
//     })
// }