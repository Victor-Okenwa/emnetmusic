function outputMessage(object, message, status) {
    object.classList = "";
    object.innerHTML = "";
    object.innerHTML = message;

    if (object.style.display == "none") {
        object.style.display = "block";
    }

    if (status == 'success') {
        object.classList = "alert alert-success text-center";
    } else {
        object.classList = "alert alert-danger text-center";
    }

    setTimeout(() => {
        object.innerHTML = "";
        object.classList = "";
        object.style.display = "none";
    }, 5000);
}


window.addEventListener("load", function () {
    var loader = document.getElementById("loader");
    var blur = 10;
    var interval = setInterval(function () {
        blur -= 1;
        loader.style.backdropFilter = `blur(${blur}px)`;
        loader.style.webkitBackdropFilter = `blur(${blur}px)`;
        if (blur <= 0) clearInterval(interval);
        if (blur <= 0) loader.style.display = "none";
    }, 50);
});


function sendToBackend(button, form, directory, timeout, object, header) {
    const loader = button.querySelector('.spinner'),
        formData = form.serialize();
    form.onsubmit - function (e) {
        e.preventDefault();
    }

    $.ajax({
        url: directory,
        method: 'POST',
        timeout: timeout,
        data: formData,
        beforeSend: function () {
            loader.classList.remove('d-none');
        },
        error: function (xhr, status, error) {
            loader.classList.add('d-none');
            if (status === 'timeout') {
                outputMessage(object, 'Request Timed Out', 'error');
            } else {
                outputMessage(object, 'Error: ' + error, 'error');
            }
        },
        complete: function () {
            loader.classList.add('d-none');
        },
        success: function (response) {
            if (response.status == 'success') {
                outputMessage(object, response.message, 'success');
                if (response.status == 'success' && header.trim() !== '') {
                    location.href = header;
                }
            } else {
                outputMessage(object, response.message, 'error');
            }
        }
    });
}

function viewPassword(button) {
    const passwordInput = button.parentElement.querySelector('input');
    if (passwordInput.type == "password") {
        passwordInput.type = "text";
        button.classList.replace("fa-eye", "fa-eye-slash");
    } else {
        passwordInput.type = "password";
        button.classList.replace("fa-eye-slash", "fa-eye");
    }
}

function passwordStrength(input) {
    let paswordLength = input.value.trim().length;
    var weak = input.parentElement.parentElement.querySelector(".strength-text  .strength1");
    var average = input.parentElement.parentElement.querySelector(".strength-text  .strength2");
    var strong = input.parentElement.parentElement.querySelector(".strength-text  .strength3");
    if (paswordLength == 0) {
        weak.classList.remove("length-strength");
    } else if (paswordLength < 9) {
        average.classList.remove("length-strength");
    } else if (paswordLength < 15) {
        strong.classList.remove("length-strength");
    }

    if (paswordLength <= 8) {
        weak.classList.add("length-strength")
    } else if (paswordLength <= 15) {
        average.classList.add("length-strength")
    } else {
        strong.classList.add("length-strength")
    }

    if (paswordLength == 0) {
        weak.classList.remove("length-strength");
    }
}

function scrollToPosition(element) {
    element.scrollIntoView({
        behavior: "auto",
        wait: 2000
    });

}

function hideBtn() {
    const toppageBtn = document.querySelector('.toppage');
    if (toppageBtn.classList.contains('d-none')) {
        toppageBtn.classList.remove('d-none')
    }
}

function displayBtn() {
    const toppageBtn = document.querySelector('.toppage');
    if (!toppageBtn.classList.contains('d-none')) {
        toppageBtn.classList.add('d-none')
    }
}

const readOnlyField = Array.from(document.querySelectorAll('.field_1'));
readOnlyField.forEach(field => {
    field.addEventListener('input', () => {
        field.value = '';
        field.remove();
    });
});