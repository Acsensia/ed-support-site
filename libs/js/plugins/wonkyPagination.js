(function ($) {
    function generateSpecificUrlParameters(type = 'SEARCH') {
        let filteringValue = $('.pagination #manufacturers').find(":selected").text();
        let filteringValues = [];

        //For SEARCH.php
        if (filteringValue != 'All' && filteringValue != ''){
            filteringValues.push(["manufacturers=" + filteringValue]);
        }
        filteringValue = $('.pagination #roles').find(":selected").text();
        if (filteringValue != 'All' && filteringValue != ''){
            filteringValues.push(["roles=" + filteringValue]);
        }
        filteringValue = $('.pagination #sizes').find(":selected").text();
        if (filteringValue != 'All' && filteringValue != ''){
            filteringValues.push(["sizes=" + filteringValue]);
        }
        if (type === 'SEARCH'){
            filteringValue = $('.pagination input').val();
            if (filteringValue != ''){
                filteringValues.push(["text=" + filteringValue]);
            }

            let aElements = $('#paginationButtons a');
            if (aElements){
                let iteration = 0;
                for(let aElement of aElements){
                    if (aElement.classList.contains('active')){
                        break;
                    }
                    ///console.log(iteration);
                    iteration++;
                }
                filteringValue = iteration - 1;
                filteringValues.push(["page=" + filteringValue]);
            }

            filteringValue = $('#paginationButtons #limit-records').find(":selected").text();
            filteringValues.push(["limit=" + filteringValue]);
        }
        else{
            //For GRAPHS.php
            filteringValue = $('.pagination #values').find(":selected").text();
            if (filteringValue != 'All' && filteringValue != ''){
                filteringValues.push(["values=" + filteringValue]);
            }
        }

        let urlParameters = "?";
        for(let filteringValue of filteringValues){
            urlParameters += filteringValue + "&";
        }
        urlParameters = urlParameters.substring(0, urlParameters.length - 1);
        ///console.log(urlParameters);
        return urlParameters;
    }

    let methods = {
        init: function (options) {
            //initial page changes?
            return $(this).each(function() {
                if ($.data(this, 'wonkyPagination')){
                    $(this).removeData('wonkyPagination');
                }
                $.data(this, 'wonkyPagination', options);
                if (options['elementType'] == 'select'){
                    let select = this;
                    if (options.url){
                        $.ajax({
                            url: options.url,
                            method: 'GET',
                            success: function (json, status, xhr) {
                                if (json.status){
                                    if (!options['selectNoAll']){
                                        $(select).append($('<option>').html('All'));
                                    }
                                    for (let [key, value] of Object.entries(json['values'])){
                                        $(select).append($('<option>').html(value['name']));
                                    }
                                }
                                else{
                                    this.error(0, false, json['error']);
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log('FAILURE YOU ARE A FAILURE (error while executing wonkyPagination initialization), error message: ' + error);
                            }
                        })
                    }
                    else{
                        $.error('No url')
                    }
                }
                else if (options['elementType'] == 'resultContainer'){
                    //What to initialize at the start?...
                }
                else if (options['elementType'] == 'graphContainer'){
                    //What to initialize at the start?...
                }
            });
        },
        submit: function () {
            return $(this).each(function() {
                let options = $.data(this, 'wonkyPagination');

                if (options.elementType == 'resultContainer'){
                    let urlParameters = generateSpecificUrlParameters();

                    if (options.url){
                        $.ajax({
                            url: options.url + urlParameters,
                            method: 'GET',
                            success: function (json, status, xhr) {
                                ///console.log('SUCCESS');
                                if (json.status){
                                    pageCount = json['pagesCount'];
                                    ///console.log(json['pagesCount']);

                                    let container = $('#results');
                                    container.html('');

                                    let singularResult;
                                    for(let page of json['pages']){
                                        singularResult = $('<div>');
                                        singularResult.addClass('singularResult');

                                        let signPicture = $('<div>');
                                        signPicture.addClass('signPicture');
                                        let href = 1;
                                        let h2 = $('<h2>').append($('<a>').attr('href', 'haumea.php?id=' + page['id']).html(page['name']));
                                        signPicture.append(h2);
                                        if (page['image']){
                                            let image = $('<img>');
                                            image.attr('src', page['image']);
                                            signPicture.append(image);
                                        }
                                        singularResult.append(signPicture);

                                        let section = formSection('Overview');
                                        section.addClass('section');
                                        section.append(formDiv('Manufacturer', page['manufacturer']));
                                        section.append(formDiv('Years Produced', page['years_produced']));
                                        section.append(formDiv('Type', page['role']));
                                        section.append(formDiv('Cost', (page['price']) + ' CR'));
                                        section.append(formDiv('Dimensions',
                                            (page['length'] + 'm x ' + page['width'] + 'm x ' + page['height'] + 'm')));
                                        section.append(formDiv('Size', page['size']));
                                        singularResult.append(section);

                                        container.append(singularResult);
                                    }
                                    if (options.afterSuccess){
                                        options.afterSuccess.forEach((func) => {func();})
                                    }
                                }
                                else{
                                    this.error(0, false, json['error']);
                                }
                            },
                            error: function (xhr, status, error) {
                                console.log('FAILURE YOU ARE A FAILURE (error while executing wonkyPagination initialization), error message: ' + error);
                            }
                        })
                    }
                    else{
                        $.error('No url');
                    }
                }
                else if (options.elementType == 'graphContainer'){
                    let urlParameters = generateSpecificUrlParameters('GRAPHS');

                    if (options.url){
                        let xmlHTTP = new XMLHttpRequest();
                        xmlHTTP.open('GET',options.url + urlParameters,true);
                        xmlHTTP.responseType = 'arraybuffer';
                        xmlHTTP.onload = function(e)
                        {

                            let arr = new Uint8Array(this.response);

                            let raw = String.fromCharCode.apply(null,arr);

                            let b64=btoa(raw);
                            if (b64){
                                let dataURL="data:image/png;base64,"+b64;
                                $('#graph').append($('<div>').append($('<img>').attr('src', dataURL)));
                            }
                            else{
                                $('#graph').append($('<h2>').html('<---NO DATA FOUND--->'));
                            }
                            if (options.afterSuccess){
                                options.afterSuccess.forEach((func) => {func();})
                            }
                        };
                        xmlHTTP.send();
                    }
                    else{
                        $.error('No url');
                    }
                }
                else{
                    $.error('This object is not permitted to submit info. object:' + this);
                }
            });
        }
    }

    $.fn.wonkyPagination = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        }
        else if (typeof method === 'object'|| !method) {
            return methods.init.apply(this, [method]);
        } else {
            $.error('Method ' + method + ' does not exist');
        }
    }
})(jQuery);