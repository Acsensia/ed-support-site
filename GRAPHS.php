<html>
<head>
    <title>Diagrams</title>
    <link rel="stylesheet" href="GRAPHSStyle.css">
    <link rel="icon" type="image/x-icon" href="imgcut/Logo.ico">
    <script src="libs/js/jquery-3.7.1.js"></script>
    <script type="text/javascript" src="Script.js"></script>
    <script type="text/javascript" src="libs/js/plugins/wonkyPagination.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#manufacturers').wonkyPagination({
                url: "libs/js/plugins/selectOptionGenerator.php?values=manufacturers",
                elementType: 'select'
            });
            $('#roles').wonkyPagination({
                url: "libs/js/plugins/selectOptionGenerator.php?values=roles",
                elementType: 'select'
            });
            $('#sizes').wonkyPagination({
                url: "libs/js/plugins/selectOptionGenerator.php?values=sizes",
                elementType: 'select'
            });
            $('#values').wonkyPagination({
                url: "libs/js/plugins/selectOptionGenerator.php?values=graph-able_columns",
                elementType: 'select',
                selectNoAll: true
            });
            $('.pagination button').on('click', ()=>{
                $('#graph').wonkyPagination({
                    url: "libs/main_SelectionGraphGenerator.php",
                    elementType: 'graphContainer',
                });
                $('#graph').html('');
                $('#graph').wonkyPagination('submit');
            });
        })
    </script>
</head>
<?php include 'header.php'; ?>
<body>
<div id="container">
    <div class="pagination">
        <span>Manufacturer:</span>
        <select id="manufacturers"></select>
        <span>Role:</span>
        <select id="roles"></select>
        <span>Size:</span>
        <select id="sizes"></select>
        <span>Value:</span>
        <select id="values"></select>
        <button>Search</button>
    </div>
    <div id="graph">

    </div>
</div>
</body>
<?php include 'footer.php'; ?>