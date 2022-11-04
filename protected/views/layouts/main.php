<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="en">

    <!-- blueprint CSS framework -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print">

    <!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection">
	<![endif]-->

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/input.css">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

    <nav class="navbar navbar-expand navbar-dark bg-dark" aria-label="Second navbar example">
        <div class="container-fluid">

            <div class="collapse navbar-collapse justify-content-around" id="navbarsExample02">
                <ul class="navbar-nav mr-auto">
                    <?php if (!Yii::app()->user->isGuest) { ?>
                        <li class="nav-item">
                            <a class="nav-link" style="color: rgba(255,255,255,.5);" href="/board">Board</a>
                        </li>
                    <?php } ?>
                </ul>
                <?php if (!Yii::app()->user->isGuest) { ?>
                    <div>
                        <div class="row">
                            <div class="mb-3">
                                <input type="text" class="form-control" id="search-bar" placeholder="Search icon" />
                            </div>
                        </div>

                        <div class="row m-0 p-0 position-absolute">
                            <ul class="h-auto" id="list"></ul>
                        </div>
                    </div>

                    <ul>

                        <li class="nav-item">
                            <a class="nav-link" style="color: rgba(255,255,255,.5);" href="/user/logout">Logout ( <?= Yii::app()->user->name ?> )</a>
                        </li>

                    </ul>
                <?php } else { ?>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="/user/login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/user/signup">Register</a>
                        </li>
                    </ul>
                <?php } ?>
            </div>
        </div>
    </nav>
    <div class="container" id="page">
        <div class="col">


        </div>
        <?php echo $content; ?>

        <div class="clear"></div>

        <div id="footer">
            Copyright &copy; <?php echo date('Y'); ?> by My Company.<br />
            All Rights Reserved.<br />
            <?php echo Yii::powered(); ?>
        </div><!-- footer -->

    </div><!-- page -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

    <!-- JS code -->
    <script src="https://code.jquery.com/jquery-3.1.1.min.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js">
    </script>
    <!--JS below-->

    <script>
        const searchBar = document.getElementById("search-bar");
        const list = document.getElementById("list");

        const icons = [
            // { name: "GitHub" },
            // { name: "Grid" },
            // { name: "Code" },
            // { name: "Chat" },
            // { name: "Cloud" },
            // { name: "Discord" },
            // { name: "Google" },
            // { name: "Headphones" },
            // { name: "Info" },
            // { name: "Key" },
            // { name: "Trash" },
            // { name: "Twitch" },
            // { name: "Type" }
        ];

        searchBar.addEventListener("keyup", function(e) {
            console.log(e.target.value)

            icons.push({
                name: e.target.value
            })
        })
        const filter = () => {
            list.innerHTML = "";

            const text = searchBar.value.toLowerCase();

            for (let icon of icons) {
                let name = icon.name.toLowerCase();

                if (name.indexOf(text) !== -1) {
                    list.innerHTML += `	<div class="d-flex  flex-column mx-1 my-1">
														<li id="input_list" class="text-decoration-none w-100 px-3  py-2 d-block">
															<i class="bi bi-${name} me-2"></i> 
															<span> ${icon.name} </span>
														</li>
													</div> `;
                }
            }

            // if (list.innerHTML === "") {
            //     list.innerHTML += `<p> Icon not found. </p>`;
            // }
        };

        filter();

        searchBar.addEventListener("keyup", filter);
    </script>
</body>

</html>