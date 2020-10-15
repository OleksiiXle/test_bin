/*
Приходит из вьюхи:
var _pjaxClientOptions = {
    container: "#users-grid-container"
    fragment: "#users-grid-container"
    push: true
    replace: false
    scrollTo: false
    timeout: 1000
    }
const USE_PJAX = 1 / 0
const PJAX_CONTAINER_ID = '#users-grid-container'
 */

var filterQuery = [];
var checkedIds = [];


$(document).ready(function(){
   // console.log(PJAX_CONTAINER_ID);
  //  console.log(USE_PJAX);
   // console.log(_pjaxClientOptions);

    if (USE_PJAX) {
        $(document).on('click', PJAX_CONTAINER_ID + ' a', function(event) {
            event.preventDefault();
            event.stopPropagation();
            doPjax(this.href);
        });
    }
});

function doPjax(href) {
    var hr = getHrefWithFilter(href);
    console.log(checkedIds);

    $.pjax({
        type: 'POST',
        url: hr,
        container: PJAX_CONTAINER_ID,
        fragment: PJAX_CONTAINER_ID,
      //  data: {'query' : filterQuery},
        data: {'checkedIds' : checkedIds},
    })
}

function useFilter() {
  //  checkedIds = [];
    doPjax(window.location.href);
}

function getHrefWithFilter(href) {
    //http://test/adminxx/user?filter=1&role=user&filterEnd=1
    getFilterQuery();
    var hr = href;
    var filterFragment = '';
    var hasFilter = false;
    var filterStart = hr.indexOf('&filter=1');
    var filterEnd;
    if (filterStart > 0) {
        console.log(filterStart);
        hasFilter = true;
    } else {
        filterStart = hr.indexOf('?filter=1');
        if (filterStart > 0) {
            console.log(filterStart);
            hasFilter = true;
        }
    }
    if (hasFilter) {
        filterEnd = hr.indexOf('&filterEnd=1');
        filterFragment = hr.substring(filterStart, filterEnd + 12);
        console.log(filterEnd);
        console.log(hr);
        console.log(filterFragment);
        hr = hr.substr(0, filterStart) + hr.substr(filterEnd + 12, hr.length);
    }
    if (filterQuery.length > 0){
        if (hr.indexOf('?') < 0) {
            hr += '?filter=1';
        } else {
            hr += '&filter=1';
        }
        $(filterQuery).each(function (index, value) {
            hr += '&' + value['name'] + '=' + value['value'];
        });
        hr += '&filterEnd=1';

    }

    return hr;
}

function getFilterQuery() {
    filterQuery = [];
    var bufName;
    $('[id^=' + FILTER_CLASS_SHORT_NAME.toLowerCase() + '-]').each(function(index, value) {
        if (value.value.length > 0) {
            bufName = value.name.replace(FILTER_CLASS_SHORT_NAME, '').replace('[', '').replace(']', '');
            switch (value.type) {
                case 'text':
                    filterQuery.push({'name' : bufName, 'value' : value.value });
                    break;
                case 'checkbox':
                    if (value.checked) {
                        filterQuery.push({'name' : bufName, 'value' : 1 });
                    }
                    break;
            }
        }
    });
    $('select[id^=' + FILTER_CLASS_SHORT_NAME.toLowerCase() + '-]').each(function(index, value) {
        if (value.value != 0) {
            bufName = value.name.replace(FILTER_CLASS_SHORT_NAME, '').replace('[', '').replace(']', '');
            filterQuery.push({'name' : bufName, 'value' : value.value });
        }
    });
    console.log(filterQuery);
}

function checkRow(checkbox){
    var id = parseInt($(checkbox)[0].dataset['id']);
    if (checkbox.checked) {
        checkedIds.push(id);
    } else {
        var ind = checkedIds.indexOf(id);
        if (ind > 0) {
            checkedIds.splice(ind, 1);
        }
    }
    console.log(checkedIds);
}

function actionWithChecked(action) {
    console.log(action.value);
    console.log(checkedIds);
}

function buttonFilterShow(button) {
    if ($("#filterZone").is(":hidden")) {
        $("#filterZone").show("slow");
        $(button).css("color", "#daa520");
        $(button)[0].innerHTML = '<span class="glyphicon glyphicon-chevron-up"></span>';
        if (typeof clickButtonFilterShowFunction == 'function'){
            clickButtonFilterShowFunction();
        }
    } else {
        $("#filterZone").hide("slow");
        $(button).css("color", "#00008b");
        $(button)[0].innerHTML = '<span class="glyphicon glyphicon-chevron-down"></span>';
        if (typeof clickButtonFilterHideFunction == 'function'){
            clickButtonFilterHideFunction();
        }

    }
}

function getGridFilterData(modelName, formId, urlName, container_id) {
    //   alert(modelName + ' ' + formId + ' ' + urlName + ' ' + container_id);
    var filterData = $("#" + formId).serialize();
    //  objDump(data);
    $.ajax({
        url: urlName ,
        type: "POST",
        data:  filterData,
        timeout: 3000,
        success: function(response){
            objDump(response);
        },
        error: function (jqXHR, error, errorThrown) {
            alert( "Ошибка фильтра : " + modelName + " " + error + " " +  errorThrown);
        }

    });



}

function cleanFilter(){
    var params = window
        .location
        .search
        .replace('?','')
        .split('&')
        .reduce(
            function(p,e){
                var a = e.split('=');
                p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                return p;
            },
            {}
        );

    console.log(params);
    console.log(window.location);
    console.log(window.location.origin +  window.location.pathname);
    $('input[type="text"][ id^=' + FILTER_CLASS_SHORT_NAME.toLowerCase() + '-]').val('');
    $('input[type="checkbox"][id^=' + FILTER_CLASS_SHORT_NAME.toLowerCase() + '-]').prop('checked', false);
    $('select[id^=' + FILTER_CLASS_SHORT_NAME.toLowerCase() + '-]').val(0);
    history.pushState({}, '', window.location.origin +  window.location.pathname);
    useFilter();
}








