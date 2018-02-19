$(document).ready(function(){
    var arrRegions = [];
    $.ajax({
        url: Routing.generate('get_regions_from_DB'),
        success: function (data) {
            var regions = data;

            regions.map(function (item, key) {
                console.log(item.name);
                arrRegions.push({"name": item.name, "code": item.regionId});
            });
            sortRegions();
        }
    });

    createNotification('AllRight','success','top center');

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
        }else {
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

