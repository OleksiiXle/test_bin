/*
$(document).ready ( function(){
});
*/
(function ($) {
    $.fn.selectMultiXle = function (selectId, items, textAreaId) {
        let key;
        let item;
     //   console.log(selectId);
    //    console.log(textAreaId);
    //    console.log(typeof items);
    //    console.log(items);
        for (key in items){
            console.log(key + ' = ' + items[key]);
        }

        $(".choise-row-" + selectId).on('click', function () {
           // console.log(this.dataset.key);
            this.dataset.choised = '1';
            key = this.dataset.key;
            item = $("#selected_" + selectId + " div[data-key='" + key + "']")[0];
            // console.log(item);
            item.dataset.selected = '1';
            $(this).removeClass('active').addClass('no-active');
            $(item).removeClass('no-active').addClass('active');
            $("#no-selected-message-"+ selectId).removeClass('active').addClass('no-active');

        });

        $(".selection-row-" + selectId).on('click', function () {
           // console.log(this.dataset.key);
            this.dataset.selected = '0';
            key = this.dataset.key;
            item = $("#choise_" + selectId + " div[data-key='" + key + "']")[0];
            // console.log(item);
            item.dataset.choised = '0';
            $(this).removeClass('active').addClass('no-active');
            $(item).removeClass('no-active').addClass('active');
            if ($("#selected_" + selectId + " div[data-selected='1']").length == 0 ){
                $("#no-selected-message-"+ selectId).removeClass('no-active').addClass('active');
            }



        });

    };
})(window.jQuery);


