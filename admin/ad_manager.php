<?php require_once "./assets/access.php";
function adExpiration($ad_id, $ad_img, $period)
{
    global $conn;
    $expire = "{$period}";
    $expire_period = date_create($expire);
    $today =  new DateTime();
    $num_days = date_diff($today, $expire_period);
    $days_left = $num_days->d;
    $months_left = $num_days->m;

    // $num_days = date_create("{$num_days} days");
    if ($months_left > 0) {
        return $months_left . " month(s) left";
    } elseif ($days_left > 0) {
        return $days_left . " day(s) left";
    } else {
        $query_ad_del = mysqli_query($conn, "SELECT * FROM advert WHERE ad_id = $ad_id");
        $clear_ad = mysqli_query($conn, "DELETE FROM advert WHERE ad_id = $ad_id");
        $clear_clicks = mysqli_query($conn, "DELETE FROM clicks WHERE ad_id = $ad_id");
        unlink("../ad_thumbnail/{$ad_img}");
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include "./assets/vendors.html" ?>
    <?php include "./assets/datatables.html" ?>
</head>

<body class="bg-secondary text-bg-secondary">
    <?php include "./assets/navbar.php" ?>

    <div id="top_page"></div>

    <main class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3>+ Create new advert</h3>
            </div>
            <div class="card-body">
                <div id="output"></div>
                <form id="adForm" class="row was-validated">
                    <div class="col-md-4 mb-4">
                        <label class="form-label" for="company">Company name</label>
                        <input class="form-control" type="text" id="company" placeholder="optional" name="company">
                    </div>

                    <div class="col-md-6 mb-4">
                        <label class="form-label" for="link">Site Link</label>
                        <input class="form-control" type="url" id="link" name="link" placeholder="Must start with https://" required>
                    </div>

                    <div class="form-group col-md-6 mb-4">
                        <label for="period" class="form-label">Period</label>
                        <input type="date" id="period" name="period" class="form-control rounded-sm" min="<?= (date("Y-m-d"))  ?>" max="<?= (date("Y") + 20) ?>-12-31" required>
                    </div>

                    <div class="col-12 mb-3">
                        <img src="." id="previewImage" height="70" width="70">
                        <br>
                        <label class="form-label" for="thumbnail">Ad Thumbnail</label>
                        <input type="file" id="thumbnail" class="form-control" accept="image/*" name="thumbnail" required>
                        <div class="invalid-feedback">Maximum of 3mb</div>
                        <div class="mt-2"></div>
                        <input type="checkbox" id="imgquality" style="accent-color:green; transform: scale(1.1);">
                        <label for="imgquality">Reduce quality</label>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary d-flex justify-content-center col-md-6 col-sm-12" type="button" id="send" name="send">
                            <span class="mr-1">Add new ad</span>
                            <span class="spinner spinner-grow spinner-grow-sm d-none"></span>
                        </button>
                    </div>

                    <div class="d-block mt-2 text-center col-12">
                        <span id="progress" class="text-center"></span>
                    </div>
                </form>
            </div>
        </div>

        <?php
        $query_ad = $conn->query("SELECT * FROM advert");
        if ($query_ad->num_rows > 0) {
            $row = $query_ad->fetch_assoc();
        ?>

            <div class="jumbotron mt-5" id="<?= $row['ad_id'] ?>">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>Current Advert</h3>
                    </div>


                    <div class="card-body">
                        <div class="card-img bg-dark d-block">
                            <img src="../ad_thumbnail/<?= $row['ad_img'] ?>" class="d-block m-auto col-md-10 col-sm-12" style="width:80%; height:250px" alt="">
                        </div>
                        <div class="card-title text-capitalize text-center" style="font-weight:bolder; font-size:20px;letter-spacing: 2px;"><?= $row['name'] ?></div>

                        <div class="card-text text-center">
                            <p><b>Link:</b> <?= $row['ad_link'] ?></p>
                            <p><b>Creation time:</b> <?= $row['created_on'] ?></p>
                            <p><b>Time left:</b> <?= $row['created_on'] ?></p>

                            <p><b>Time left:</b> <?= adExpiration($row['ad_id'], $row['ad_img'],  $row['ad_duration']) ?></p>
                            <div id="deleteMsg"></div>
                            <button type="button" id="deleteAd" class="btn btn-danger">
                                <b>Delete</b>
                                <span class="spinner spinner-grow spinner-grow-sm d-none"></span>
                            </button>
                        </div>
                    </div>

                </div>
            </div>

            <form id="adDelete">
                <input type="number" name="id" class="field_1" value="<?= $row['ad_id'] ?>">
            </form>

        <?php } ?>
    </main>
    <?php include "./assets/footer.html" ?>
    <?php include "./assets/scripts.html" ?>

    <script>
        $(document).ready(() => {



            const message = document.getElementById('output'),
                adForm = document.getElementById("adForm"),
                previewImg = document.getElementById("previewImage"),
                thumbnailInput = document.getElementById("thumbnail"),
                imgQualityInput = document.getElementById("imgquality"),
                progress = document.getElementById("progress"),
                send = document.getElementById("send"),
                deleteAd = document.getElementById("deleteAd");

            let xhr = new XMLHttpRequest();

            let imageWidth,
                imageHeight;
            thumbnailInput.onchange = function(e) {
                const file = e.target.files[0];
                if (!file) return;
                if (file.type.startsWith('image/')) {
                    if (file.type == "image/jpg" || file.type == "image/png" || file.type == "image/jfif" || file.type == "image/gif" || file.type == "image/jpeg") {
                        if (file.size <= 3000000) {
                            previewImg.src = URL.createObjectURL(file);
                            previewImg.addEventListener("load", () => {
                                imageWidth = previewImg.naturalWidth;
                                imageHeight = previewImg.naturalHeight;
                            });
                        } else {
                            previewImg.src = "";
                            this.files[0] = "";
                            this.value = "";
                            outputMessage(message, "Thumbnail is larger than 2mb", 5000, 'error');
                        }
                    } else {
                        previewImg.src = "";
                        this.files[0] = "";
                        this.value = "";
                        outputMessage(message, "Only JPG, PNG, JFIF, JPEG and GIF files allowed", 5000, 'error');
                    }
                } else {
                    previewImg.src = "";
                    this.files[0] = "";
                    this.value = "";
                    outputMessage(message, "Thumbnail is not a valid image file", 5000, 'error');
                }
            }

            const resizeImage = function() {
                if (previewImg.src !== "") {
                    const canvas = document.createElement("canvas");
                    const ctx = canvas.getContext("2d");
                    const imgQuality = imgQualityInput.checked ? 0.8 : 1.0;
                    canvas.width = imageWidth;
                    canvas.height = imageHeight;
                    ctx.drawImage(previewImg, 0, 0, canvas.width, canvas.height);
                    const compressedImage = canvas.toDataURL("image/jpeg", imgQuality);
                    return compressedImage;
                } else {
                    return;
                }
            }

            send.onclick = function() {
                // if ($("#songName").val() == "" || $("#artist").val() == "" || $("#genre").val() == "" || $("#audio").val() == "") {
                const waitloader = $(`#${this.id} .spinner`);
                const formData = new FormData(adForm);
                formData.set('company', $('input[name="company"]').val());
                formData.set('link', $('input[name="link"]').val());
                formData.set('period', $('input[name="period"]').val());
                formData.set('thumbnail', resizeImage());

                $.ajax({
                    url: 'api/new_ad.php',
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    timeout: 20000,
                    beforeSend: function() {
                        progress.innerHTML = "";
                        waitloader.removeClass('d-none');
                    },
                    xhr: function(e) {
                        xhr.upload.addEventListener('progress', function(e) {
                            if (e.lengthComputable) {
                                const percent = Math.round((e.loaded / e.total) * 100);
                                $('#progress').text('Completion: ' + percent + '% uploaded');
                            }
                        });
                        return xhr;
                    },
                    complete: function() {
                        waitloader.addClass('d-none');
                        $('#progress').text("");
                    },
                    success: function(response) {
                        if (response.status == 'success') {
                            outputMessage(message, response.message, 5000, response.status);
                            previewImg.src = "";
                        } else {
                            outputMessage(message, response.message, 5000, response.status);
                        }
                    },
                    error: function(xhr, status, error) {
                        xhr.abort();
                        waitloader.addClass('d-none');
                        $('#progress').text("");
                        if (status === 'timeout') {
                            outputMessage(message, 'Request timed out!', 5000, 'error');
                        } else {
                            outputMessage(message, 'An Error Occured ' + error, 5000, 'error');
                        }
                    },
                });
            }

            if (deleteAd)
                deleteAd.addEventListener('click', () => {
                    if (confirm("Are you sure you want to delete this advert?")) {
                        sendToBackend(deleteAd, $("#adDelete"), 'api/delete_ad.php', 50000, document.getElementById('deleteMsg'), document.querySelector(".jumbotron").id);
                    }
                });
        });
    </script>
</body>

</html>