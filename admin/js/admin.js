function outputMessage(object, text, time, status) {
    object.innerHTML = text;
    if (object.classList.contains('d-none')) {
        object.classList.remove('d-none');
    }
    if (status == 'success') {
        object.classList = 'alert alert-success';
    } else {
        object.classList = 'alert alert-danger';
    }

    setTimeout(() => {
        object.classList.add('d-none');
    }, time);
}

function moveValues(object, inputObject, nameObject) {
    const objectID = object.getAttribute('data-id');
    inputObject.value = objectID;
    nameObject.textContent = object.value;
}

function sendToBackend(button, form, directory, timeout, object, objectID) {
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
                outputMessage(object, 'Request Timed Out', 5000, 'error');
            } else {
                outputMessage(object, 'Error: ' + error, 5000, 'error');
            }
        },
        complete: function () {
            loader.classList.add('d-none');
        },
        success: function (response) {
            if (response.status == 'success') {
                outputMessage(object, response.message, 5000, 'success');
                if(objectID !== 'no_element'){
                    document.getElementById(`${objectID}`).remove();
                }
                if(directory == './api/passway.php'){
                    location.href = "./index.php";
                }
                return true;
            } else {
                outputMessage(object, response.message, 5000, 'error');
                return false;
            }
        }
    });
}

function simpleUpdate(button, field1, fieldName, fieldValue, result) {
    if (fieldValue !== "" && songid !== "") {
        $.ajax({
            url: 'api/edit_audio.php',
            method: "POST",
            timeout: 50000,
            data: {
                songid: field1,
                type: fieldName,
                value: fieldValue,
            },
            beforeSend: function () {
                document.querySelector(`#${button.id} svg`).classList.add('d-none');
                document.querySelector(`#${button.id} span`).classList.remove('d-none');
            },
            complete: function () {
                document.querySelector(`#${button.id} svg`).classList.remove('d-none');
                document.querySelector(`#${button.id} span`).classList.add('d-none');
            },
            success: function (response) {
                if (response.status == 'error') {
                    scrollToPosition();
                    outputMessage(result, response.message, 5000, response.status);
                } else {
                    outputMessage(result, response.message, 5000, response.status);
                }
            },
            error: function (xhr, status, error) {
                xhr.abort();
                scrollToPosition();
                if (status == 'timeout') {
                    outputMessage(result, `${fieldName} timed out`, 5000, 'error');
                } else {
                    outputMessage(result, `${fieldName} Error occured during song name update`, 5000, 'error');
                    document.querySelector(`#${button.id} svg`).classList.remove('d-none');
                    document.querySelector(`#${button.id} span`).classList.add('d-none');
                }
            },
        });

    } else {
        outputMessage(result, `${fieldName} of another required field is empty`, 5000, 'error');
        scrollToPosition();
    }
}

const numbersOnlyField = Array.from(document.querySelectorAll('input[type="number"]'));
numbersOnlyField.forEach(field => {
    field.addEventListener('input', () => {
        field.value = field.value.replace(/\D/g, '');
    });
});

const readOnlyField = Array.from(document.querySelectorAll('.field_1'));
readOnlyField.forEach(field => {
    field.addEventListener('input', () => {
        console.log(field.value)
        field.value = '';
        field.remove();
    });
});