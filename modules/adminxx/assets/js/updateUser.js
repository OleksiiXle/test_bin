$(document).ready ( function(){
    if (typeof _userRoles != undefined && _userRoles.length > 0){
        $.each(_userRoles, function (index, value) {
            drawUserRole(value['id'], value['name']);
        });
    }
});


//-- добавление роли пользователю
function addUserRole() {
    var newId = $("[name='defaultRoles']").val();
    var newName = $("[name='defaultRoles']").find("option[value=" + newId+ "]").text();
   // console.log(newId);
  //  console.log(newName);
    if (newName != ''){
        if ($(".userRole[data-id='" + newId + "']").length > 0){
           alert('Така роль вже є');
           return false;
        }
        drawUserRole(newId, newName);
    }
}

//-- прорисовка роли пользователя
function drawUserRole(id, name) {
    var newRole = '<div '
        + 'class="userRole" '
        + 'data-id ="' + id + '" '
        + 'data-name ="' + name + '" '
        + '>'
        + '<span class="roleName" style="color: blue">'
        + '<b>'+ name + '</b>'
        + '<a href="#"'
        + 'title = "Видалити підрозділ"'
        + 'onclick="deleteUserRole(this);"'
        + '>' + '   ' + '<span class="glyphicon glyphicon-trash" style="color:red;"></span></a>'
        + '</span>'
        + '</div>';
    $("#userRoles").append(newRole);
}

//-- удаление роли пользователя
function deleteUserRole(item){
    if (confirm('Видалити роль')){
        $(item).parents(".userRole")[0].remove();
    }
}

//------------------------------------------------------------------------------------------- СОХРАНЕНИЕ
function saveUser(){
   // var multyField = ("#userm-userDepartmentsJSON").val();
    var depData = [];
    var roleData = [];
    var ret;
    var dsend = '';
    $('.userRole').each(function (index, value) {
       // console.log(this.dataset);
        roleData.push({
            'id' : this.dataset['id'],
            'name' : this.dataset['name'],
        });
    });
    ret = {
        'roles' : roleData};
   // console.log(ret);

    dsend = JSON.stringify(ret);
    $("#userm-multyfild").val(dsend);
    $("#form-update").submit();
}
