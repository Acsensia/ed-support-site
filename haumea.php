<html>
<head>
    <title>Page</title>
    <link rel="stylesheet" href="haumeaStyle.css">
    <link rel="icon" type="image/x-icon" href="imgcut/Logo.ico">
    <script src="libs/js/jquery-3.7.1.js"></script>
    <script type="text/javascript" src="Script.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            GENERATEPAGE(<?php echo isset($_REQUEST['id']) ? $_REQUEST['id'] : 0 ?>);
        })
    </script>
</head>
<?php include 'header.php'; ?>
<body>
    <div id="container">

    </div>
</body>
<?php include 'footer.php'; ?>