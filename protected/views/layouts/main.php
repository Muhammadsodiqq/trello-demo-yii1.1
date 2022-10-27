<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="en">

    <!-- blueprint CSS framework -->
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css"
        media="screen, projection">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css"
        media="print">
        
    <!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection">
	<![endif]-->

    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css">
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css"
        integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">

    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

    <nav class="navbar navbar-expand navbar-dark bg-dark" aria-label="Second navbar example">
        <div class="container-fluid">

            <div class="collapse navbar-collapse justify-content-around" id="navbarsExample02">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Link</a>
                    </li>
                </ul>
                <?php if(!Yii::app()->user->isGuest){?>
                <form>
                    <input class="form-control" type="text" placeholder="Search" aria-label="Search">
                </form>
                <ul>

                    <li class="nav-item">
                        <a class="nav-link" style="color: rgba(255,255,255,.5);" href="/user/logout">Logout ( <?=Yii::app()->user->name?> )</a>
                    </li>

                </ul>
                <?php }else {?>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/user/login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/user/signup">Register</a>
                    </li>
                </ul>
                <?php }?>
            </div>
        </div>
    </nav>
    <div class="container" id="page">

        <?php echo $content; ?>

        <div class="clear"></div>

        <div id="footer">
            Copyright &copy; <?php echo date('Y'); ?> by My Company.<br />
            All Rights Reserved.<br />
            <?php echo Yii::powered(); ?>
        </div><!-- footer -->

    </div><!-- page -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<!-- JS code -->
<script src="https://code.jquery.com/jquery-3.1.1.min.js">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js">
</script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js">
</script>
<!--JS below-->

</body>

</html>