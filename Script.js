var slides, slides2, textarea, imgN, imgNB;
var fileName = location.href.split("/").slice(-1);
if (fileName == "Interceptors.php"){
    slides = document.getElementsByTagName("img");
    imgN = 3;
    imgNB = 2;
    for (var i = 0; i < slides.length; i += imgN) slides[i].style.display = "inline-block";
}
else if (fileName == "Ships.php"){
    slides = document.getElementsByTagName("img");
    imgN = 4;
    imgNB = 3;
    for (var m = 0; m < slides.length; m += imgN) slides[m].style.display = "inline-block";
}
else if (fileName == "Weapons.php") {
    slides = document.getElementsByTagName("h4");
    slides2 = document.getElementsByClassName("card-card");
    var imgs = document.getElementsByTagName("img");
    imgN = [4, 4, 2];
    for (var n = 0; n < imgs.length; n += 1) imgs[n].style.display = "inline-block";
}
else {
    slides = document.getElementsByTagName("input");
    textarea = document.getElementsByTagName("textarea");
}



function showSlides(n, pn) {
    for (var i = 0; i < imgN; i++){
        if (slides[pn*imgN + i + imgNB].style.display == "inline-block"){
            slides[pn*imgN + i + imgNB].style.display = "none";
            if (i + n > imgN - 1) slides[pn*imgN + imgNB].style.display = "inline-block";
            else if (i + n < 0) slides[pn*imgN + imgNB + imgN - 1].style.display = "inline-block";
            else slides[pn*imgN + i + n + imgNB].style.display = "inline-block";
            break;
        }
    }
}



function switchCard(n, pn) {
    var elementsbefore = 0;
    for (var l = 0; l < pn; l++) {
        elementsbefore += imgN[l];
    }
    for (var i = 0; i < imgN[pn]; i++) {
        if (slides[elementsbefore + i].classList.contains("active")){
            slides[elementsbefore + i].classList.remove("active");
            slides2[elementsbefore + i].style.display = "none";
            break;
        }
    }
    slides[elementsbefore + n].classList.add("active");
    slides2[elementsbefore + n].style.display = "flex";
}



function testname() {
    if (!slides[0].value.match(/^[a-zа-яё]{1,} [a-zа-яё]{1,}$/i)) slides[0].classList.add("invalid");
    else slides[0].classList.remove("invalid");
}
function testmail() {
    if (!slides[1].value.match(/[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/)) slides[1].classList.add("invalid");
    else slides[1].classList.remove("invalid");
}
function testphone() {
    if (slides[2].value != "" && !slides[2].value.match(/^\d{10}$/)) slides[2].classList.add("invalid");
    else slides[2].classList.remove("invalid");
}
function testmessage() {
    if (!textarea[0].value.match(/^.{10,400}$/)) textarea[0].classList.add("invalid");
    else textarea[0].classList.remove("invalid");
}


let curPage = 1;
let pageCount = 1;

function activeButtonHighlight(newIndex = null){
    let aElements = $('#paginationButtons a');
    aElements.removeClass('active');
    if (!newIndex){
        aElements.eq(curPage + 1).addClass('active');
    }
    else {
        aElements.eq(newIndex + 1).addClass('active');
    }
}
function pageButtonOnClick(pageIndex, checkSame = true, afterThen = [activeButtonHighlight]){
    if (checkSame){
        if ((curPage == 1 && pageIndex <= curPage) || (curPage == pageCount && pageIndex >= pageCount)) return;
    }
    $('#results').wonkyPagination({
        url: "libs/js/plugins/filteredResultsDrawer.php",
        elementType: 'resultContainer',
        afterSuccess: afterThen
    });
    curPage = pageIndex;
    activeButtonHighlight();
    $('#results').html('');
    $('#results').wonkyPagination('submit');
}
function regeneratePageButtons(){
    function appendNewAElement(value, parent, callbacks = []) {
        let newAElement = $("<a>");
        newAElement.html(value);
        if (callbacks){
            callbacks.forEach((callback) => {
                if (callback.length && callback.length > 1){
                    newAElement.on(callback[0], callback[1])
                }
            })
        }
        parent.append(newAElement);
    }

    $('#paginationButtons a').remove();

    let paginationButtons = $('#paginationButtons').eq(0);
    appendNewAElement('<<', paginationButtons, [['click', () => {pageButtonOnClick(1)}]]);
    appendNewAElement('<', paginationButtons, [['click', () => {pageButtonOnClick(curPage - 1)}]]);
    for (let i = 1; i <= pageCount; i++){
        appendNewAElement(i.toString(), paginationButtons, [['click', () => {pageButtonOnClick(i)}]]);
    }
    appendNewAElement('>', paginationButtons, [['click', () => {pageButtonOnClick(curPage + 1)}]]);
    appendNewAElement('>>', paginationButtons, [['click', () => {pageButtonOnClick(pageCount)}]]);
}
function selectMenuOnChange(){
    pageButtonOnClick(1, false, [regeneratePageButtons, activeButtonHighlight]);
}

function formDiv(h3Value, innerDivValue) {
    let newDiv = $('<div>').append($('<h3>').html(h3Value));
    newDiv.append($('<div>').html(innerDivValue))
    return newDiv;
}

function formSection(name) {
    return $('<section>').append($('<h2>').html(name));
}
function GENERATEPAGE(pageId) {
    $.ajax({
        url: "libs/pagedataLoader.php?id=" + pageId,
        method: 'GET',
        success: function (json, status, xhr) {
            if (json.status) {
                document.title = json['ship_data'][0]['name'];

                let body = $('#container');

                //contentLeft////////////////////////////////////////////////////
                let contentLeft = $('<div>');
                //'page' table processing
                contentLeft.addClass('contentLeft');
                contentLeft.append($('<h1>').html(json['ship_data'][0]['name']));
                contentLeft.append(json['page'][0]['desc_short']);

                //'desc_paragraphs' table processing
                for (let [key, value] of Object.entries(json['paragraphs'])){
                    if (value['divider']){
                        contentLeft.append($('<h2>').html(value['divider']));
                    }
                    contentLeft.append(value['text']);
                    contentLeft.append('<br><br>');
                }

                //'desc_notes' table processing
                let uls = [];
                let i = -1;

                for (let [key, value] of Object.entries(json['notes'])){
                    if (value['divider']){
                        if (i == -1){
                            uls = [$('<ul>')];
                        }
                        else{
                            contentLeft.append(uls[i]);
                            uls.push($('<ul>'));
                        }
                        contentLeft.append($('<h2>').html(value['divider']));
                        i++;
                    }
                    uls[i].append($("<li>").text(value['text'])[0]);
                }
                if (i > -1){
                    contentLeft.append(uls[i]);
                }

                body.append(contentLeft);
                //contentLeft end///////////////////////////////////////////////

                //characteristicsRight//////////////////////////////////////////

                let characteristicsRight = $('<aside>');
                characteristicsRight.addClass('characteristicsRight');
                characteristicsRight.append($('<h2>').html(json['ship_data'][0]['name']));

                if (json['main_image'][0]['image']){
                    let mainImage = $('<img>');
                    mainImage.attr('src', json['main_image'][0]['image']);
                    characteristicsRight.append(mainImage);
                }

                let overviewSection = formSection('Overview');
                overviewSection.append(formDiv('Manufacturer', json['ship_data'][0]['manufacturer']));
                overviewSection.append(formDiv('Years Produced', json['ship_data'][0]['years_produced']));
                overviewSection.append(formDiv('Type', json['ship_data'][0]['role']));
                overviewSection.append(formDiv('Cost', (json['ship_data'][0]['price']) + ' CR'));
                overviewSection.append(formDiv('Insurance', (json['ship_data'][0]['insurance'] + ' CR')));
                characteristicsRight.append(overviewSection);

                let specificationsSection = formSection('Specifications');
                specificationsSection.append(formDiv('Landing Pad Size', json['ship_data'][0]['size']));
                specificationsSection.append(formDiv('Dimensions',
                    (json['ship_data'][0]['length'] + 'm x ' + json['ship_data'][0]['width'] + 'm x ' + json['ship_data'][0]['height'] + 'm')));
                specificationsSection.append(formDiv('Pilot Seats', json['ship_data'][0]['pilot_seats']));
                specificationsSection.append(formDiv('Multicrew', (json['ship_data'][0]['multicrew']) ? 'Yes' : 'No'));
                specificationsSection.append(formDiv('Fighter Hangar', (json['ship_data'][0]['fighter_hangar']) ? 'Yes' : 'No'));
                specificationsSection.append(formDiv('Hull Mass', (json['ship_data'][0]['hull_mass'] + ' t')));
                specificationsSection.append(formDiv('Mass Lock Factor', json['ship_data'][0]['mass_lock_factor']));
                specificationsSection.append(formDiv('Armour', json['ship_data'][0]['armour']));
                specificationsSection.append(formDiv('Armour Hardness', json['ship_data'][0]['armour_hardness']));
                specificationsSection.append(formDiv('Shields', (json['ship_data'][0]['shields'] + ' MJ')));
                specificationsSection.append(formDiv('Heat Capacity', json['ship_data'][0]['heat_capacity']));
                specificationsSection.append(formDiv('Fuel Capacity', (json['ship_data'][0]['fuel_capacity'] + ' t')));
                specificationsSection.append(formDiv('Maneuverability', json['ship_data'][0]['maneuverability']));
                specificationsSection.append(formDiv('Top Speed', (json['ship_data'][0]['top_speed'] + ' m/s')));
                specificationsSection.append(formDiv('Boost Speed', (json['ship_data'][0]['boost_speed']  + ' m/s')));
                specificationsSection.append(formDiv('Unladen Jump Range', (json['ship_data'][0]['unladen_jump_range'] + ' ly')));
                specificationsSection.append(formDiv('Cargo Capacity', (json['ship_data'][0]['cargo_capacity']  + ' t')));
                characteristicsRight.append(specificationsSection);

                function formWordAfterNumber(number, wordSingularForm, wordPluralForm) {
                    return (number>1)?wordPluralForm:wordSingularForm;
                }

                function formDivMultipleLines(h3Value, innerDivValues = []) {
                    let newDiv = $('<div>').append($('<h3>').html(h3Value));
                    let innerDiv = $('<div>');

                    let i = 0;
                    for (let innerDivValue of innerDivValues){
                        innerDiv.append(innerDivValue);
                        if (i != innerDivValues.length) innerDiv.append('<br>');
                        i++;
                    }

                    newDiv.append(innerDiv);
                    return newDiv;
                }

                function textSpecificTransformPush(number, unchanged_text, changed_text_singular, changed_text_plural, arrayToPushInto) {
                    if (number != 0){
                        arrayToPushInto.push(number + unchanged_text + formWordAfterNumber(number, changed_text_singular, changed_text_plural));
                    }
                }

                let outfittingSection = formSection('Outfitting');

                let hardpointsValues = [];
                let hN = ['Hardpoint', 'Hardpoints']//singular and plural forms of a word 'Hardpoint'
                textSpecificTransformPush(json['ship_data'][0]['hrdp_utility'], 'x Utility ', 'Mount', 'Mounts', hardpointsValues);
                textSpecificTransformPush(json['ship_data'][0]['hrdp_small'], 'x Small ', hN[0], hN[1], hardpointsValues);
                textSpecificTransformPush(json['ship_data'][0]['hrdp_medium'], 'x Medium ', hN[0], hN[1], hardpointsValues);
                textSpecificTransformPush(json['ship_data'][0]['hrdp_large'], 'x Large ', hN[0], hN[1], hardpointsValues);
                textSpecificTransformPush(json['ship_data'][0]['hrdp_huge'], 'x Huge ', hN[0], hN[1], hardpointsValues);
                outfittingSection.append(formDivMultipleLines(hN[1], hardpointsValues));

                let compartmentValues = [];
                let cN = ['Compartment', 'Compartments']//singular and plural forms of a word 'Compartment'
                textSpecificTransformPush(json['ship_data'][0]['cmpr_class1'], 'x Class 1  ', cN[0], cN[1], compartmentValues);
                textSpecificTransformPush(json['ship_data'][0]['cmpr_class2'], 'x Class 2  ', cN[0], cN[1], compartmentValues);
                textSpecificTransformPush(json['ship_data'][0]['cmpr_class3'], 'x Class 3  ', cN[0], cN[1], compartmentValues);
                textSpecificTransformPush(json['ship_data'][0]['cmpr_class4'], 'x Class 4  ', cN[0], cN[1], compartmentValues);
                textSpecificTransformPush(json['ship_data'][0]['cmpr_class5'], 'x Class 5  ', cN[0], cN[1], compartmentValues);
                textSpecificTransformPush(json['ship_data'][0]['cmpr_class6'], 'x Class 6  ', cN[0], cN[1], compartmentValues);
                textSpecificTransformPush(json['ship_data'][0]['cmpr_class7'], 'x Class 7  ', cN[0], cN[1], compartmentValues);
                textSpecificTransformPush(json['ship_data'][0]['cmpr_class8'], 'x Class 8  ', cN[0], cN[1], compartmentValues);
                outfittingSection.append(formDivMultipleLines(cN[1], compartmentValues));

                characteristicsRight.append(outfittingSection);

                body.append(characteristicsRight);
                //characteristicsRight end//////////////////////////////////////
            } else {
                console.log('ERROR DB CONNECTION ' + json.error);
            }
        },
        error: function (xhr, status, error) {
            console.log(xhr);
            console.log(error);
        }
    })
}


function innerArrayScan(outerArray, scanValue) {
    let result = 0;
    for (let innerArray of outerArray.values()){
        if (Object.keys(innerArray)[0] == scanValue){
            result = [];
            for (let innerValue of innerArray[scanValue]){
                let innerKey = Object.keys(innerValue)[0];
                result[innerValue[innerKey]] = innerValue[innerKey];
            }
        }
    }
    return result;
}

function generateColModel(options) {
    let result = [];
    if (options['type'] != undefined && options['name'] != undefined && options['index'] != undefined){
        result['name'] = options['name'];
        result['index'] = options['index'];
        result['editable'] = (options['editable'] != undefined)?options['editable']:true;
        result['editrules'] = {};
        result['searchrules'] = {};
        result['editrules']['required'] = (options['required_edit'] != undefined)?options['required_edit']:true;
        result['searchrules']['required'] = (options['required_search'] != undefined)?options['required_search']:true;
        switch (options['type']) {
            case 'string':
                $.extend(result, {
                    sortable: true,
                    searchoptions: {
                        sopt: ['bw','bn', 'eq','ne','ew','en','cn','nc'],
                    },
                    search: true,
                })
                break;
            case 'number':
                $.extend(result, {
                    sortable: true,
                    searchoptions: {
                        sopt: ['eq','ne','lt','le','gt', 'ge'],
                    },
                    searchrules:{ number: true },
                    search: true,
                })
                break;
            case 'select':
                if (options['selectValues'] != undefined && typeof (options['selectValues']) == 'object'){
                    checksPassed = true;
                    $.extend(result, {
                        sortable: true,
                        edittype: 'select',
                        editoptions:{
                            value: options['selectValues'],
                        },
                        stype: 'select',
                        searchoptions: {
                            sopt: ['eq','ne'],
                            value: $.extend({'':''} ,options['selectValues'])
                        },
                        search: true,
                    })
                }
                break;
            case 'bool':
                $.extend(result, {
                    align: 'center',
                    sortable: true,
                    search: true,
                    stype: 'select',
                    edittype: 'checkbox',
                    searchoptions: {
                        sopt: ['eq','ne'],
                        value: {'':'', "1":"Yes", "0":"No"},
                        defaultValue:"",
                    },
                    formatter: function ( cellvalue, options, rowObject ) {
                        if (cellvalue == 1) {
                            return '<img src="img/checked.jpg" width="20">';
                        }
                        return '';
                    },
                })
                break;
        }
    }
    return result;
}