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
    checkedIds = [];
    doPjax(window.location.href);
}

function getHrefWithFilter(href) {
    var hr = href;
    getFilterQuery();
    var hasFilter = false;
    var filterStart = hr.indexOf('&filter');
    var filterEnd;
    if (filterStart > 0) {
        hasFilter = true;
    } else {
        filterStart = hr.indexOf('?filter');
        if (filterStart > 0) {
            hasFilter = true;
        }
    }
    if (hasFilter) {
        filterEnd = hr.indexOf('&filterEnd');
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
}

function actionWithChecked(action) {
    console.log(action.value);
    console.log(checkedIds);
}




function hello(){
    alert('hello');
}

function buttonFilterShow(button) {

    if ($("#filterZone").is(":hidden")) {
        $("#filterZone").show("slow");
        $(button).css("color", "#daa520");
        if (typeof clickButtonFilterShowFunction == 'function'){
            clickButtonFilterShowFunction();
        }
    } else {
        $("#filterZone").hide("slow");
        $(button).css("color", "#00008b");
        if (typeof clickButtonFilterHideFunction == 'function'){
            clickButtonFilterHideFunction();
        }

    };
}

/*
jQuery(".btn-filter-apply").on("click", function (e) {
    e.preventDefault();
  //  alert('asdasd');
});
*/

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







