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
var filterQueryJSON = '{}';
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
        $('#' + FILTER_CLASS_SHORT_NAME.toLowerCase() + '-showonlychecked').prop('checked', false);

        getFilterQuery();
        useFilter();
    }
});

function doPjax(href) {
    var hr = getHrefWithFilter(href);
   // console.log(checkedIds);
   // console.log('+++++ doPjax for ++' + hr);
 //  return;

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
    console.log(checkedIds);
    doPjax(window.location.href);
}

function getHrefWithFilter(href) {
   // console.log('**************************');
    getFilterQuery();
    var url = parseUrl(href);
  //  console.log(url.params);
    var newHref = url.path;
    if (filterQuery.length > 0) {
        url.params['filter'] = filterQueryJSON;
    }
 //   console.log(url.params);
    var first = true;
    for (var key in url.params) {
        if (first) {
            first = false;
            newHref += '?' + key + '=' + url.params[key];
        } else {
            newHref += '&' + key + '=' + url.params[key];
        }
    }

 //   console.log(href);
 //   console.log(newHref);
 //   console.log('**************************');

    return newHref;
}

function getFilterQuery() {
    filterQuery = [];
    filterQueryJSON = '{}';
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
    filterQueryJSON = JSON.stringify(filterQuery);
   // console.log(filterQuery);
   // console.log(filterQueryJSON);
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
   // console.log(checkedIds);
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

function cleanFilter(reload){
  //  console.log(parseUrl());
  //  console.log(window.location);
 //   console.log(window.location.origin +  window.location.pathname);
    $('input[type="text"][ id^=' + FILTER_CLASS_SHORT_NAME.toLowerCase() + '-]').val('');
    $('input[type="checkbox"][id^=' + FILTER_CLASS_SHORT_NAME.toLowerCase() + '-]').prop('checked', false);
    $('select[id^=' + FILTER_CLASS_SHORT_NAME.toLowerCase() + '-]').val(0);
    history.pushState({}, '', window.location.origin +  window.location.pathname);
    if (reload) {
        useFilter();
    }
}

function parseUrl(href) {
//    console.log(window.location);
   // console.log('**** parseUrl ');
  //  console.log(href);
    var paramsStr = '';
    var res = {
        path : window.location.origin +  window.location.pathname,
        params : {}
    };
    if (href == undefined) {
     //   console.log('href == undefined');
        paramsStr = window.location.search;
    } else {
   //     console.log('href != undefined');
       // console.log(href);
        var startParams = href.indexOf('?');
        if (startParams > 0) {
            paramsStr = href.substr(startParams);
        }
    }
  //  console.log('paramsStr = ' + paramsStr);
    if (paramsStr !== '') {
        res.params = paramsStr.replace('?','').split('&').reduce(
            function(p,e){
                var a = e.split('=');
                p[ decodeURIComponent(a[0])] = decodeURIComponent(a[1]);
                return p;
            },
            {}
        );
    }
  //  console.log(res);
  //  console.log('**** parseUrl ');

    return res;
}

function checkOnlyChecked(item) {
    if ($(item).prop('checked')) {
        $('input[type="text"][ id^=' + FILTER_CLASS_SHORT_NAME.toLowerCase() + '-]').val('');
        $('input[type="checkbox"][id^=' + FILTER_CLASS_SHORT_NAME.toLowerCase() + '-][id!=' + FILTER_CLASS_SHORT_NAME.toLowerCase() + '-showonlychecked]')
            .prop('checked', false);
        $('select[id^=' + FILTER_CLASS_SHORT_NAME.toLowerCase() + '-]').val(0);
        history.pushState({}, '', window.location.origin +  window.location.pathname);
    }

}




function getHrefWithFilter__COPY(href) {
    var hr = href;
    var filterFragment = '';
    var hasFilter = false;
    var filterStart = hr.indexOf('&filter=1');
    var filterEnd;
    if (filterStart > 0) {
        hasFilter = true;
    } else {
        filterStart = hr.indexOf('?filter=1');
        if (filterStart > 0) {
            hasFilter = true;
        }
    }
    if (hasFilter) {
        filterEnd = hr.indexOf('&filterEnd=1');
        filterFragment = hr.substring(filterStart, filterEnd + 12);
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







