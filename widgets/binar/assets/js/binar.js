const ITEM_CSS = 'item_css';
const ITEM_ACTIVE_CSS = 'item_active_css';
const ICON_CSS = 'icon_css';
const LI_CSS = 'li_css';
const UL_CSS = 'ul_css';
const ICON_OPEN  =  '<span class="glyphicon glyphicon-folder-open"></span>';
const ICON_CLOSE = '<span class="glyphicon glyphicon-folder-close"></span>';


var BINAR_TREE = {
    tree_id: null,
    item_id : null,
    icon_id : null,
    li_id : null,
    ul_id : null,
    selected_id : 0,

    init: function(binar_id){
        this.tree_id = binar_id;
        this.item_id = binar_id + '_item_';
        this.icon_id = binar_id + '_icon_';
        this.li_id   = binar_id + '_li_';
        this.ul_id   = binar_id + '_ul_';

        this.drawDefaultTree(this.tree_id);
        //-- обработчики кликов на иконки и листья
        var that = this;
        $("#" + this.tree_id).on("click", "." + ICON_CSS, function () {
            that.clickIcon(this);
        });
        $("#" + this.tree_id).on("click", "." + ITEM_CSS, function () {
            that.clickItem(this);
        });
        //-- обработчики событий на кнопки редактирования, если они есть
        if ($("#actionButtons_" + this.tree_id).length > 0){
            $(document)
                .on("click", "#btn_" + that.tree_id + '_updateForm', function () {
                    that.binarUpdate();
                });

            $("#btn_" + this.tree_id + '_appendChild').on("click", function () {
                that.modalOpenUpdate('appendChild');
            });
            $("#btn_" + this.tree_id + '_deleteItem').on("click", function () {
                that.deleteItem();
            });
        }
    },

    //-- возвращает строку для рисования наименования с закрытой иконкой, если есть потомки, если нет - без иконки
    getItem: function (data) {
        var that = this;
        var result =
            '<li class="' + LI_CSS + '"' +
            ' id="' + this.li_id + data['id'] +  '"' +
            ' data-tree_id="' + this.tree_id + '" ' +
            ' data-id="' + data['id'] + '"' +
            ' data-parent_id="' + data['parent_id'] + '">';
        if (data['hasChildren']){
            result = result +
                '<a class="' + ICON_CSS + '"' +
                ' id="' + this.icon_id + data['id'] +  '"' +
                ' data-tree_id="' + this.tree_id + '"' +
                ' data-id="' + data['id'] + '" ' +
                ' data-parent_id="' + data['parent_id'] + '"' +
                // ' onClick="' + that.drawChildren(this.dataset.id)+ ';"'
                '>' +
                ICON_CLOSE  +
                '</a>  ';
        }
        result = result +
            '<a class="' + ITEM_CSS + '"' +
            ' id="' + this.item_id + data['id'] +  '"' +
            ' data-tree_id="' + this.tree_id + '" ' +
            ' data-id="' + data['id'] + '" ' +
            ' data-parent_id="' + data['parent_id'] +'" ' +
            // ' onClick="' + that.clickItem(this) + ';"'  +
            '> ' +
            data['name'] +
            '</a></li>' ;
        return result;
    },

    //-- возвращает строку для рисования иконки
    getIcon: function (data, state) {
        var picture = (state === 'open') ? ICON_OPEN : ICON_CLOSE;
        var result = '';
        if (data['hasChildren']){
            result = result +
                '<a class="' + ICON_CSS + '"' +
                ' id="' + this.icon_id + data['id'] +  '"' +
                ' data-tree_id="' + this.tree_id + '"' +
                ' data-id="' + data['id'] + '" ' +
                ' data-parent_id="' + data['parent_id'] + '"' +
                '>' +
                picture  +
                '</a>  ';
        }
        return result;
    },

    //-- рисует дефолтное дерево (вершины, у которых нет парентов
    drawDefaultTree: function (tree_id) {
        var that = this;
        $.ajax({
            url: '/binar/get-default-tree',
            type: "POST",
            data: {'_csrf':_csrfT},
            dataType: 'json',
            success: function(response){
                if (response['status']) {
                    var firstDiv = $("#" + tree_id);
                    $(firstDiv).append(that.getItem(response['data']));
                    that.selectedIdChange(response['data']['id']);
                } else {
                    console.log(response['data']);
                }
            },
            error: function (jqXHR, error, errorThrown) {
                console.log(jqXHR);
                console.log(error);
                console.log(errorThrown);
            }
        });
    },

    //-- рисует потомков первого уровня узлу parent_id
    clickIcon: function (parent) {
        //   console.log(parent.innerHTML);
        var parent_id = parent.dataset.id;
        var that = this;
        switch (parent.innerHTML){
            case ICON_CLOSE:
                parent.innerHTML = ICON_OPEN;
                $.ajax({
                    url: '/binar/get-children',
                    type: "POST",
                    data: {
                        'id' : parent_id,
                        '_csrf':_csrfT
                    },
                    dataType: 'json',
                    success: function(response){
                        if (response['status']) {
                            var children = response['data'];
                            if (children.length > 0){
                                //--найти родителя в этом дереве
                                var parent = $("#" + that.item_id + parent_id);
                                //    console.log(parent);
                                //-- добавить после него ul
                                parent.after(
                                    '<ul class="'+ UL_CSS + '"' +
                                    ' id="' + that.ul_id + parent_id +  '"' +
                                    '></ul>'
                                );
                                //-- получить єтот ul
                                var parent_ul = $("#" + that.ul_id + parent_id);
                                //-- добавить в ul потомков
                                $.each(children, function(index, value){
                                    $(parent_ul).append(that.getItem(value))
                                });

                            }
                            that.selectedIdChange(parent_id);
                        } else {
                            console.log(response['data']);
                        }
                    },
                    error: function (jqXHR, error, errorThrown) {
                        console.log(jqXHR);
                        console.log(error);
                        console.log(errorThrown);
                    }
                });
                break;
            case ICON_OPEN:
                parent.innerHTML = ICON_CLOSE;
                $("#" + that.ul_id + parent_id).remove();
                that.selectedIdChange(parent_id);
                break;
        }
    },

    clickItem: function (item) {
      //  console.log(item);
     //   alert('item ' + item.dataset.id);
        this.selectedIdChange(item.dataset.id);
    },

    //-- изменение текущего выбранного элемента
    selectedIdChange: function (new_id) {
        if (typeof clickItemFunction == 'function'){
            clickItemFunction(this.tree_id, new_id);
        }

        var that = this;
        //  $.post(_urlSetConserve, {'id' : new_id, 'type' : type, 'staffOrder_id': _treeParams[tree_id]['staffOrder_id'], 'tree_id' : tree_id});

        var old_selected_id = that.selected_id;
        var new_selected_id = new_id;
        var oldNode =$("#" + that.item_id + old_selected_id);
        var newNode =$("#" + that.item_id + new_selected_id);

        oldNode.removeClass(ITEM_ACTIVE_CSS).addClass(ITEM_CSS);
        newNode.removeClass(ITEM_CSS).addClass(ITEM_ACTIVE_CSS);
        that.selected_id = new_selected_id;

        var container = $("#" + that.tree_id),
            scrollTo = $('#' + new_selected_id);
        if (container.length > 0 && scrollTo.length > 0){
            //    console.log(container);
            //    console.log(scrollTo);
            container.stop().animate({
                scrollTop: scrollTo.offset().top -
                container.offset().top +
                container.scrollTop() - 200
            });
        }
        return true;
    },

    //************************************************************************************** редактирование дерева

    //-- открытие модального окна для редактирования, добавления потомка, добавления соседа
    modalOpenUpdate : function (nodeAction) {
        var that = this;
        var url = '/binar/modal-open-update?id=' + that.selected_id + '&binar_id=' + that.tree_id;
        var title = 'Добавить бинар';
        $('#main-modal-md').modal('show')
            .find('#modalContent_md')
            .load(url);
        document.getElementById('modalHeader_md').innerHTML = '<b>' + title + '</b>';
    },

    //-- редактирование, добавление потомка, добавление соседа снизу
    binarUpdate: function () {
        var that = this;
        var data = $("#binarMmodifyForm").serialize();
        var new_item;
        $.ajax({
            url: '/binar/add-child',
            type: "POST",
            data: data,
            dataType: 'json',
            success: function(response){
                if (response['status']) {
                    var new_node_data = response['data']['newNode'];
                    var parent_node_data = response['data']['parentNode'];
                    var new_parent_icon = $("#" + that.icon_id + parent_node_data['id']);
                    // console.log("#" + that.icon_id + parent_node_data['id']);
                    //  console.log(new_parent_icon);
                    var new_node_li = that.getItem(new_node_data);
                    if (new_parent_icon.length > 0){
                        //-- у нового родителя node2_id уже есть иконка
                        if (new_parent_icon[0].innerHTML == ICON_OPEN){
                            //-- иконка открыта и потомки показаны
                            //-- найти первого ли потомка и перед ним нарисовать ли $node1_id
                            var parent_ul = $("#" + that.ul_id + parent_node_data['id']);
                            var children_li = parent_ul.find("li");
                            var last_li_id = children_li[children_li.length - 1].dataset.id;
                            $("#" + that.li_id + last_li_id).after(new_node_li);
                            //console.log(first_child_li);

                        } else {
                            //-- иконка закрыта и потомки скрыты
                            //-- имитировать нажатие на иконку
                            that.clickIcon(new_parent_icon[0]);
                        }

                    } else {
                        //-- у нового родителя node2_id еще нет иконки и нет потомков
                        //-- вставить закрытую иконку в ли перед итемом
                        //-- имитировать нажатие на иконку
                        var new_parent_item = $("#" + that.item_id + parent_node_data['id']);
                        new_parent_item.before(that.getIcon(parent_node_data, 'close'));
                        new_parent_icon = $("#" + that.icon_id + parent_node_data['id'])[0];
                        console.log("#" + that.icon_id + parent_node_data['id']);

                        that.clickIcon(new_parent_icon);
                    }

                    $("#main-modal-md").modal("hide");
                } else {
                    alert(response['data']);
                    console.log(response);
                }
            },
            error: function (jqXHR, error, errorThrown) {
                console.log(jqXHR);
                console.log(error);
                console.log(errorThrown);
            }
        });

    },

    //--удаление наименования вместе с потомками
    deleteItem : function () {
    //  alert(this.tree_id + ' deleteItem ' + this.selected_id);
        if (confirm('Подтвердите удаление')){
            var that = this;
            $.ajax({
                url: '/binar/binar-delete',
                type: "POST",
                data: {
                    '_csrf':_csrfT,
                    'id' : that.selected_id
                },
                dataType: 'json',
                success: function(response){
                    if (response['status']) {
                        var removed_item_li = $("#" + that.li_id + that.selected_id);
                        var prev_item_li = removed_item_li.prev('LI');
                        var parent_id = removed_item_li[0].dataset.parent_id;
                        var new_selected_id = 0;

                        removed_item_li.remove();

                        if ((typeof response['data']['node2'] === 'object') && !response['data']['node2']['hasChildren']){

                            $("#" + that.icon_id + response['data']['node2']['id'] ).remove();
                        }

                        if (prev_item_li.length > 0){
                            new_selected_id = prev_item_li[0].dataset.id;
                        } else {
                            if (parent_id != 0){
                                new_selected_id = parent_id;
                            }
                        }
                        that.selectedIdChange(new_selected_id);
                    } else {
                        alert(response['data']);
                    }
                },
                error: function (jqXHR, error, errorThrown) {
                    console.log(jqXHR);
                    console.log(error);
                    console.log(errorThrown);
                }
            });

        }

    },




};


/*
$(document).ready ( function(){
    var tree1 = Object.create(MENU_TREE);
    tree1.init(_menu_id);
});
*/

