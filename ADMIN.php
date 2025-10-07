<html>
<head>
    <title>A: Ships Table</title>
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

            let selectableTables = $.parseJSON($.ajax({
                type: "GET",
                url: 'libs/js/plugins/selectOptionsSummarizer.php',
                async: false
            }).responseText);
            let allValuesForSelects = [];
            for(let selectableTable of selectableTables['values']){
                let someValuesForSelects = $.parseJSON($.ajax({
                    type: "GET",
                    url: 'libs/js/plugins/selectOptionGenerator.php?values=' + selectableTable['table_name'],
                    async: false
                }).responseText)

                let dummyAssocArray = [];
                dummyAssocArray[selectableTable['table_name']] = someValuesForSelects['values'];
                allValuesForSelects.push(dummyAssocArray);
            }

            $("#grid").jqGrid({
                url: "libs/tableDataLoader.php",
                editurl: "libs/tableDataEditor.php",
                datatype: 'json',
                mtype: 'POST',
                colNames: ['ID', 'NAME', 'SIZE', 'ROLE', 'MANUFACTURER', 'PRICE', 'LENGTH', 'WIDTH', 'HEIGHT', 'YEARS PRODUCED', 'INSURANCE',
                    'PILOT SEATS', 'MULTICREW', 'FIGHTER HANGAR', 'HULL MASS', 'MASS LOCK FACTOR', 'ARMOUR', 'ARMOUR HARDNESS', 'SHIELDS',
                    'HEAT CAPACITY', 'FUEL CAPACITY', 'MANEUVERABILITY', 'TOP SPEED', 'BOOST SPEED', 'UNLADEN JUMP RANGE', 'CARGO CAPACITY',
                'UTILITY HARDPOINTS', 'SMALL HARDPOINTS', 'MEDIUM HARDPOINTS', 'LARGE HARDPOINTS', 'HUGE HADRPOINTS', 'CLASS 1 COMPARTMENTS',
                'CLASS 2 COMPARTMENTS', 'CLASS 3 COMPARTMENTS', 'CLASS 4 COMPARTMENTS', 'CLASS 5 COMPARTMENTS', 'CLASS 6 COMPARTMENTS',
                'CLASS 7 COMPARTMENTS', 'CLASS 8 COMPARTMENTS', 'CLASS 1 RESERVED COMPARTMENTS', 'CLASS 2 RESERVED COMPARTMENTS',
                'CLASS 3 RESERVED COMPARTMENTS', 'CLASS 4 RESERVED COMPARTMENTS', 'CLASS 5 RESERVED COMPARTMENTS',
                'CLASS 6 RESERVED COMPARTMENTS', 'CLASS 7 RESERVED COMPARTMENTS', 'CLASS 8 RESERVED COMPARTMENTS'],
                colModel: [
                    generateColModel({type: 'number', name: 'id', index: 'id'}),
                    generateColModel({type: 'string', name: 'name', index: 'name'}),
                    generateColModel({type: 'select', name: 'size', index: 'size', selectValues: innerArrayScan(allValuesForSelects, 'sizes')}),
                    generateColModel({type: 'select', name: 'role', index: 'role', selectValues: innerArrayScan(allValuesForSelects, 'roles')}),
                    generateColModel({type: 'select', name: 'manufacturer', index: 'manufacturer', selectValues: innerArrayScan(allValuesForSelects, 'manufacturers')}),
                    generateColModel({type: 'number', name: 'price', index: 'price'}),
                    generateColModel({type: 'number', name: 'length', index: 'length'}),
                    generateColModel({type: 'number', name: 'width', index: 'width'}),
                    generateColModel({type: 'number', name: 'height', index: 'height'}),
                    generateColModel({type: 'string', name: 'years_produced', index: 'years_produced'}),
                    generateColModel({type: 'number', name: 'insurance', index: 'insurance'}),
                    generateColModel({type: 'number', name: 'pilot_seats', index: 'pilot_seats'}),
                    generateColModel({type: 'bool', name: 'multicrew', index: 'multicrew'}),
                    generateColModel({type: 'bool', name: 'fighter_hangar', index: 'fighter_hangar'}),
                    generateColModel({type: 'number', name: 'hull_mass', index: 'hull_mass'}),
                    generateColModel({type: 'number', name: 'mass_lock_factor', index: 'mass_lock_factor'}),
                    generateColModel({type: 'number', name: 'armour', index: 'armour'}),
                    generateColModel({type: 'number', name: 'armour_hardness', index: 'armour_hardness'}),
                    generateColModel({type: 'number', name: 'shields', index: 'shields'}),
                    generateColModel({type: 'number', name: 'heat_capacity', index: 'heat_capacity'}),
                    generateColModel({type: 'number', name: 'fuel_capacity', index: 'fuel_capacity'}),
                    generateColModel({type: 'number', name: 'maneuverability', index: 'maneuverability'}),
                    generateColModel({type: 'number', name: 'top_speed', index: 'top_speed'}),
                    generateColModel({type: 'number', name: 'boost_speed', index: 'boost_speed'}),
                    generateColModel({type: 'number', name: 'unladen_jump_range', index: 'unladen_jump_range'}),
                    generateColModel({type: 'number', name: 'cargo_capacity', index: 'cargo_capacity'}),
                    generateColModel({type: 'number', name: 'hrdp_utility', index: 'hrdp_utility'}),
                    generateColModel({type: 'number', name: 'hrdp_small', index: 'hrdp_small'}),
                    generateColModel({type: 'number', name: 'hrdp_medium', index: 'hrdp_medium'}),
                    generateColModel({type: 'number', name: 'hrdp_large', index: 'hrdp_large'}),
                    generateColModel({type: 'number', name: 'hrdp_huge', index: 'hrdp_huge'}),
                    generateColModel({type: 'number', name: 'cmpr_class1', index: 'cmpr_class1'}),
                    generateColModel({type: 'number', name: 'cmpr_class2', index: 'cmpr_class2'}),
                    generateColModel({type: 'number', name: 'cmpr_class3', index: 'cmpr_class3'}),
                    generateColModel({type: 'number', name: 'cmpr_class4', index: 'cmpr_class4'}),
                    generateColModel({type: 'number', name: 'cmpr_class5', index: 'cmpr_class5'}),
                    generateColModel({type: 'number', name: 'cmpr_class6', index: 'cmpr_class6'}),
                    generateColModel({type: 'number', name: 'cmpr_class7', index: 'cmpr_class7'}),
                    generateColModel({type: 'number', name: 'cmpr_class8', index: 'cmpr_class8'}),
                    generateColModel({type: 'number', name: 'rscm_class1', index: 'rscm_class1'}),
                    generateColModel({type: 'number', name: 'rscm_class2', index: 'rscm_class2'}),
                    generateColModel({type: 'number', name: 'rscm_class3', index: 'rscm_class3'}),
                    generateColModel({type: 'number', name: 'rscm_class4', index: 'rscm_class4'}),
                    generateColModel({type: 'number', name: 'rscm_class5', index: 'rscm_class5'}),
                    generateColModel({type: 'number', name: 'rscm_class6', index: 'rscm_class6'}),
                    generateColModel({type: 'number', name: 'rscm_class7', index: 'rscm_class7'}),
                    generateColModel({type: 'number', name: 'rscm_class8', index: 'rscm_class8'}),
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
    <h2>Table: Ships</h2>
</div>
<table id="grid"></table>
<div id="jqGridPager"></div>
</body>
<?php include 'footer.php'; ?>