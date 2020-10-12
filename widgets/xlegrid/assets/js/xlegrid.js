/*
$(document).ready(function(){
});
*/




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





