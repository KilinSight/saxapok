$(document).ready(function(){
    var arrRegions = [];
    var arrIndustries = [];
    var arrOrganizations = [];
    var arrProfessions = [];
    var dataRegions = [];
    var dataIndustries = [];
    var dataOrganizations = [];
    var dataProfessions = [];
    var timeout;
    // $('#searchQuery').select2();


    prepareInput('c-regions',arrRegions, dataRegions);
    prepareInput('c-industries',arrIndustries, dataIndustries);
    prepareInput('c-organizations',arrOrganizations, dataOrganizations);
    prepareInput('c-professions',arrProfessions, dataProfessions);

    createNotification('AllRight','success','top center');

    function prepareInput(inputName, arrayName, dataArray) {
        var arrSort = arrayName.slice();

        function sortRegions() {
            arrayName.sort(function (a, b) {
                return a.name.localeCompare(b.name);
            });
            showData(arrayName, inputName, dataArray);
        }

        $('.' + inputName + '__input').focus(function (event) {
            if (checkInput($('.' + inputName + '__input'))) {
                $('.' + inputName + '__container').addClass(inputName + '__container--active');
            }
        });


        $('.' + inputName + '__input').blur(function () {
            setTimeout(function () {
                $('.' + inputName + '__container').removeClass(inputName + '__container--active');
            }, 200)
        });

        $('.' + inputName + '__input').keyup(function (event) {
            var delay = 500;

            clearTimeout(timeout);

            timeout = setTimeout(function(){
                if (checkInput($('.' + inputName + '__input'))) {
                    arrayName = [];
                    $('.' + inputName + '__container').addClass(inputName + '__container--active');
                    if (inputName === 'c-regions') {
                        $.ajax({
                            url: Routing.generate('get_regions_from_DB'),
                            data: {
                                text:  $(event.target).val()
                            },
                            success: function (data) {
                                var regions = data;
                                regions.map(function (item, key) {
                                    arrayName.push({"name": item.name, "code": item.regionId});
                                });
                                sortRegions();
                            }
                        });
                    } else if (inputName === 'c-industries') {
                        $.ajax({
                            url: Routing.generate('get_industries_from_DB'),
                            data: {
                                text:  $(event.target).val()
                            },
                            success: function (data) {
                                var industries = data;
                                industries.map(function (item, key) {
                                    console.log(item);
                                    arrayName.push({"name": item.name, "code": item.id});
                                });
                                sortRegions();
                            }
                        });
                    } else if (inputName === 'c-organizations') {
                        $.ajax({
                            url: Routing.generate('get_organizations_from_DB'),
                            data: {
                                regions: dataRegions,
                                text:  $(event.target).val()
                            },
                            success: function (data) {
                                var organizations = data;
                                organizations.map(function (item, key) {
                                    console.log(item);
                                    arrayName.push({"name": item.name, "code": item.id});
                                });
                                sortRegions();
                            }
                        });
                    } else if (inputName === 'c-professions') {
                        $.ajax({
                            url: Routing.generate('get_professions_from_DB'),
                            data: {
                                text:  $(event.target).val()
                            },
                            success: function (data) {
                                var professions = data;
                                professions.map(function (item, key) {
                                    console.log(item);
                                    arrayName.push({"name": item.name, "code": item.id});
                                });
                                sortRegions();
                            }
                        });
                    }

                    var inputValue = $('.' + inputName + '__input').val().toUpperCase(),
                        container = $('.' + inputName + '__container');

                    if (inputValue == false) {
                        arrSort = arrayName.slice();
                        showData(arrayName, inputName, dataArray);
                    } else {
                        container.empty();
                        arrSort = [];
                        arrayName.forEach(function (elem) {

                            if ((elem.name.toUpperCase().indexOf(inputValue) >= 0) || (('' + elem.code).toUpperCase().indexOf(inputValue)) >= 0) {
                                arrSort[arrSort.length] = {
                                    name: elem.name,
                                    code: elem.code
                                };
                            }
                        })
                        showData(arrSort, inputName, dataArray);
                    }
                }else{
                    arrayName = [];
                    setTimeout(function () {
                        $('.' + inputName + '__container').removeClass(inputName + '__container--active');
                    }, 200)
                }
            },delay);
        });
    }

    function checkInput(aim) {
        var result = true;
        if (aim.val().length === 1) {
            createNotification('Еще ' + 2 + ' символа...', 'warning', 'right-center', aim);
            result = false;
        } else if (aim.val().length === 2) {
            createNotification('Еще ' + 1 + ' символ...', 'warning', 'right-center', aim);
            result = false;
        } else if (aim.val().length === 0) {
            createNotification('Для вывода результатов введите хотя бы 3 символа', 'warning', 'right-center', aim);
            result = false;
        }
        return result;
    }

    function showData(arr, inputName, dataArray) {
        var container = $('.'+inputName+'__container'),
            html = [];

        container.empty();
        if (arr.length !== 0){
            arr = arr.slice(0,20);
            arr.forEach(function (value) {
                html.push('<li data-id="' + value.code + '">' + value.name + '</li>');
            });
            container.append(html);

            $('.'+inputName+'__container li').click(function () {
                var regionId = $(this).data('id'),
                    regionName = $(this).html();

                $('.'+inputName+'__input').attr('data-id', regionId);
                $('.'+inputName+'__input').val(regionName);
                setTimeout(function(){
                    dataArray.push($('.'+inputName+'__input').attr('data-id'));
                    // console.log(dataArray);
                },200);

            })
        }else{
            html.push('<li> Не результатов по заданным параметрам</li>');
            container.append(html);
        }

    }


    //************************************Start*************************************************************************
    $('#regions_input').focusout(function(event) {
        console.log('OUT');
        if (typeof $(event.target).attr('data-id') !== 'undefined' && !$(event.target).val()){
            dataRegions = [];
            $(event.target).removeAttr('data-id');
        }
    });

    $('#industries_input').focusout(function(event) {
        console.log('OUT');
        if (typeof $(event.target).attr('data-id') !== 'undefined' && !$(event.target).val()){
            dataIndustries = [];
            $(event.target).removeAttr('data-id');
        }
    });

    $('#organizations_input').focusout(function(event) {
        console.log('OUT');
        if (typeof $(event.target).attr('data-id') !== 'undefined' && !$(event.target).val()){
            dataOrganizations = [];
            $(event.target).removeAttr('data-id');
        }
    });

    $('#professions_input').focusout(function(event) {
        console.log('OUT');
        if (typeof $(event.target).attr('data-id') !== 'undefined' && !$(event.target).val()){
            dataProfessions = [];
            $(event.target).removeAttr('data-id');
        }
    });

    $(document).on('click', '#regions_tab_search_button', function(event){
        console.log(dataRegions);
        console.log(dataIndustries);
        console.log(dataProfessions);
        console.log(dataOrganizations);
        $.ajax({
            url: Routing.generate('get_resumes_from_DB'),
            data: {
                regions:dataRegions,
                industries:dataIndustries,
                professions:dataProfessions,
                organizations:dataOrganizations
            },
            success: function (data) {

            }
        });
    });

});

