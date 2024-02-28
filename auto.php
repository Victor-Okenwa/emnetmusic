<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Auto complete</title>
    <link rel="stylesheet" href="./css/boot4.css">
    <script src="./js/jquery3.4.1.min.js"></script>
    <script src="./js/popper1.160.min.js"></script>
    <script src="./js/bootstrap4.js"></script>
</head>

<body class="bg-info">
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 bg-light p-4 mt-3 rounded">
                <h4 class="text-center">Auto complete from search box</h4>
                <form action="test.php" method="POST" class="form-inline p-3">
                    <input type="text" name="search" id="search" class="form-control form-control-lg rounded-0 border-info" placeholder="Search..." style="width: 80%;">
                    <input type="submit" name="submit" value="Search" class="btn btn-info btn-lg rounded-0" style="width: 20%;">
                </form>
            </div>

            <div class="col-md-5" style="position: relative; margin-top: -38px; margin-left: 215px">
                <div class="list-group" id="show-list">
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $("#search").keyup(function() {
                var searchText = $(this).val();
                if (searchText != "") {
                    $.ajax({
                        url: "test.php",
                        method: "post",
                        data: {
                            query: searchText
                        },
                        success: function(response) {
                            $("#show-list").html(response);
                        }
                    })
                } else {
                    $("#show-list").html('');
                }
            });

            $(document).on('click', 'a', function() {
                $("#search").val(this.text);
                $("#show-list").html('');
            });
        })
    </script>
</body>

</html>