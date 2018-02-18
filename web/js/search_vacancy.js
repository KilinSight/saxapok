var progress = {'done': false, 'offset': 0};
var count = 1;
var timeout;
var $regionDone = false;
var $profDone = false;
var $industryDone = false;
var $organizationDone = false;
var $vacancyDone = false;
var $cvDone = false;
var tmp = false;
var step = 0;
var namesArray = {
    0: 'region',
    1: 'prof',
    2: 'industry',
    3: 'organization',
    4: 'cv'
};

var urlsArray = {
    0: 'http://opendata.trudvsem.ru/7710538364-regions/data-20180208T044857-structure-20161130T143000.xml',
    1: 'http://opendata.trudvsem.ru/7710538364-professions/data-20180206T053855-structure-20161130T143000.xml',
    2: 'http://opendata.trudvsem.ru/7710538364-industries/data-20180206T053855-structure-20161130T143000.xml',
    3: 'http://opendata.trudvsem.ru/7710538364-organizations/data-20180206T053855-structure-20161130T143000.xml',
    4: 'http://opendata.trudvsem.ru/7710538364-cv/data-20180213T050836-structure-20161130T143000.xml'
};
var sendAjaxVacancies = function (progress) {

    step = 4;
    // truncateDB();
    // processDB(progress,urlsArray,namesArray);
    reProcessVacancy();
    //     .then(function(result){
    //     return processDB(progress,urlsArray,namesArray);
    // });

    // .then(function(result){
    //     return processDB(progress,urlsArray,namesArray);
    // });
    //     .then(function(result){
    //     return processDB(progress,urlsArray,namesArray);
    // }).then(function(result){
    //     return processDB(progress,urlsArray,namesArray);
    // }).then(function(result){
    //     return processDB(progress,urlsArray,namesArray);
    // }).then(function(result){
    //     return processDB(progress,urlsArray,namesArray);
    // });
};


function processDB(progress, urlsArray, namesArray) {
    $.ajax({
        url: Routing.generate('write_to_db'),
        data: {
            url: urlsArray[step],
            name: namesArray[step],
            limit: 100000000,
            offset: progress.offset
        },
        success: function (arr) {
            if (arr.done) {
                progress = {'done': false, 'offset': 0};
                step++;
            }
        }
    });
}

function reProcessVacancy() {
    return $.ajax({
        url: Routing.generate('get_vacancies'),
        success: function (data) {
            if (data.success) {
                console.log('OK');
            } else {
                console.log('FUCK');
            }
        }
    });
}

var getRawXml = function () {
    $.ajax({
        url: Routing.generate('get_raw_xml'),
        data: {
            // url:itemUrl,
            // name: indexUrl,
            url: urlsArray[4],
            name: namesArray[4],
            limit: 100000000,
            offset: 0
        },
        success: function (arr) {
        }
    });
};

var truncateDB = function () {
    $.ajax({
        url: Routing.generate('truncate_db'),
        success: function () {
            console.log(success);
        }
    });
};

$('#update_db').on('click', function () {
    sendAjaxVacancies(progress);
});

$('#get_raw_xml').on('click', function () {
    getRawXml();
});

$(document).on('click', '#search_button', function () {
    $.ajax({
        url: Routing.generate('search_vacancies'),
        data: {
            region: $('#region').val(),
            vacancy: $('#vacancy').val(),
        },
        success: function (data) {
            $('#result_area').html(' ');
            if (data.success) {
                $('#result_area').append('Racoon Poloscoon');
            } else {
                $('#result_area').append('Even your mother');
            }
        }
    });
});

$(document).ready(function () {
    var arrRegions = [];
    $.ajax({
        url: Routing.generate('api_regions'),
        success: function (data) {
            var regions = JSON.parse(data);
            console.log(regions.region[0].name);
            regions.region.map(function (item, key) {
                // console.log(item);
                arrRegions.push({"name": item.name, "code": item.code});
            });
            sortRegions();
        }
    });

    createNotification('AllRight','success','top center', '#searchQuery');
    createNotification('AllRight','success','top center');
    createNotification('AllRight','success','left center');
    createNotification('AllRight','success','bottom center');
    createNotification('AllRight','success','right center');

    var arrSort = arrRegions.slice();

    function sortRegions() {
        arrRegions.sort(function (a, b) {
            return a.name.localeCompare(b.name);
        });
        showData(arrRegions);
    }

    $('.c-regions__input').focus(function () {
        $('.c-regions__container').addClass('c-regions__container--active');
    });


    $('.c-regions__input').blur(function () {
        setTimeout(function () {
            $('.c-regions__container').removeClass('c-regions__container--active');
        }, 200)
    });

    $('.c-regions__input').keyup(function () {
        var inputValue = $('.c-regions__input').val().toUpperCase(),
            container = $('.c-regions__container');
        if (inputValue == false) {
            arrSort = arrRegions.slice();
            showData(arrRegions);
        }
        else {
            container.empty();
            arrSort = [];
            arrRegions.forEach(function (elem) {

                if ((elem.name.toUpperCase().indexOf(inputValue) >= 0) || (('' + elem.code).toUpperCase().indexOf(inputValue)) >= 0) {
                    arrSort[arrSort.length] = {
                        name: elem.name,
                        code: elem.code
                    };
                }
            })
            showData(arrSort);
        }
    });


    function showData(arr) {
        var container = $('.c-regions__container'),
            html = [];

        container.empty();

        arr.forEach(function (value) {
            html.push('<li data-id="' + value.code + '">' + value.name + '</li>');
        });
        container.append(html);

        $('.c-regions__container li').click(function () {
            var regionId = $(this).data('id'),
                regionName = $(this).html();
            $('.c-regions__input').attr('data-id', regionId);
            $('.c-regions__input').val(regionName);

        })
    }
});