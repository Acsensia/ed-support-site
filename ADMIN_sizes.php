<html>
<head>
    <title>A: Sizes Table</title>
    <link rel="stylesheet" href="css/jquery-ui-1.9.2.custom.css">
    <link rel="stylesheet" href="css/ui.jqgrid.css">
    <link rel="stylesheet" href="ADMINStyle.css">
    <link rel="icon" type="image/x-icon" href="imgcut/Logo.ico">
    <script src="libs/js/jquery-1.7.2.min.js"></script>
    <script src="libs/js/jquery-ui-1.9.2.custom.min.js"></script>
    <script src="libs/js/jqGrid/i18n/grid.locale-en.js"></script>
    <script src="libs/js/jqGrid/jquery.jqGrid.min.js"></script>
    <script type="text/javascript" src="Script.js"></script>
    <script type="text/javascript">
        function buildGrid(options) {

            $("#grid").jqGrid({
                url: "libs/sizes_DataLoader.php",
                editurl: "libs/sizes_DataEditor.php",
                datatype: 'json',
                mtype: 'POST',
                colNames: ['ID', 'NAME'],
                colModel: [
                    generateColModel({type: 'number', name: 'id', index: 'id'}),
                    generateColModel({type: 'string', name: 'name', index: 'name'}),
                ],
                rowNum:10,
                rowList:[10,15,20],
                pager: '#jqGridPager',
                sortname: 'id',
                sortorder: 'asc',
                viewrecords: true,
                gridview: true,
                editable:true,
                shrinkToFit :false,
                width: 1198,
                height: 550,
                subGrid: false,
                //toppager: true,
            });
            $("#grid").navGrid('#jqGridPager',
                {
                    del: true,
                    add: true,
                    edit:true,
                    search: true,
                    refresh: true,
                    view: true,
                    cloneToTop: true,
                },{}, {}, {},{
                    multipleSearch:true,
                },{});
            jQuery("#grid").jqGrid('filterToolbar',{
                stringResult: true,
                searchOnEnter: true,
            });
        }

        $(document).ready(function() {
            buildGrid();
        });
    </script>
</head>
<?php include 'ADMINheader.php'; ?>
<body>
<div id="title">
    <h2>Table: Sizes</h2>
</div>
<table id="grid"></table>
<div id="jqGridPager"></div>
</body>
<?php include 'footer.php'; ?>