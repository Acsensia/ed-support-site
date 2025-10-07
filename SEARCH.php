<html>
<head>
    <title>Search</title>
    <link rel="stylesheet" href="SEARCHStyle.css">
    <link rel="icon" type="image/x-icon" href="imgcut/Logo.ico">
    <script src="libs/js/jquery-3.7.1.js"></script>
    <script type="text/javascript" src="Script.js"></script>
    <script type="text/javascript" src="libs/js/plugins/wonkyPagination.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            //GENERATEPAGE(<?php //echo isset($_REQUEST['id']) ? $_REQUEST['id'] : 0 ?>//);
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
            $('#results').wonkyPagination({
                url: "libs/js/plugins/filteredResultsDrawer.php",
                elementType: 'resultContainer',
                afterSuccess: [regeneratePageButtons, activeButtonHighlight]
            });
            $('.pagination button').on('click', ()=>{
                curPage = 1;
                $('#results').wonkyPagination({
                    url: "libs/js/plugins/filteredResultsDrawer.php",
                    elementType: 'resultContainer',
                    afterSuccess: [regeneratePageButtons, activeButtonHighlight]
                });
                $('#results').html('');
                $('#results').wonkyPagination('submit');
            })
            selectMenuOnChange();//
        })
    </script>
</head>
<?php include 'header.php'; ?>
<body>
    <div id="container">
        <div class="pagination">
            <span>Manufacturer:</span>
            <select id="manufacturers">

            </select>
            <span>Role:</span>
            <select id="roles">

            </select>
            <span>Size:</span>
            <select id="sizes">

            </select>
            <input type="search" name="q" />
            <button>Search</button>
        </div>
        <div id="paginationButtons">
            <select name="limit" id="limit-records" onchange="selectMenuOnChange()" onfocus="this.selectedIndex = -1;">
                <?php foreach ([6, 12, 18, 24] as $limit):?>
                    <option <?php if(isset($_GET["limit"]) && $_GET["limit"] == $limit) echo "selected"?> value="<?= $limit; ?>"><?= $limit; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div id="results"></div>
    </div>
</body>
<?php include 'footer.php'; ?>