$(document).ready ( function(){
    if (_userDepartments != undefined && _userDepartments.length > 0){
        $.each(_userDepartments, function (index, value) {
            drawUserDepartment(value['id'], value['name'], value['can_department'], value['can_position'], value['can_personal']);
        });
    }
    if (_userRoles != undefined && _userRoles.length > 0){
        $.each(_userRoles, function (index, value) {
            drawUserRole(value['id'], value['name']);
        });
    }
   // $("#userm-last_name").val('Петров');
  //  $("#userm-first_name").val('Іван');
  //  $("#userm-middle_name").val('Іванович');
  //  $("#userm-email").val('qq.www@email.com');

});


//-------------------------------------------------------------------------- работа с карточкой сотрудника

//-- проверка по жетону и заполнение ФИО, места работы
function checkSpecDocument(){
    //--- 0033934
    var data = $("#userm-spec_document").val();
    if (data != ''){
        $.ajax({
            url: '/adminxx/user/get-personal-data',
            type: "POST",
            data: {
                'spec_document': data
            },
            dataType: 'json',
            beforeSend: function() {
                preloader('show', 'mainContainer', 0);
            },
            complete: function(){
                preloader('hide', 'mainContainer', 0);
            },
            success: function(response){
                console.log(response);
                if(response['status']){
                    document.getElementById('userm-personal_id').value = response['data']['personal_id'];
                    document.getElementById('userm-job_name').value = response['data']['positionFullName'];
                    document.getElementById('userm-last_name').value = response['data']['name_family'];
                    document.getElementById('userm-first_name').value = response['data']['name_first'];
                    document.getElementById('userm-middle_name').value = response['data']['name_last'];
                } else {
                    console.log(response['data']);
                    document.getElementById('userm-personal_id').value = null;
                    document.getElementById('userm-job_name').value = null;
                    console.log(response['data']);
                }
            },
            error: function (jqXHR, error, errorThrown) {
                console.log( "Помилка: " + error + " " +  errorThrown);
            }
        });

    } else {
        document.getElementById('userm-personal_id').value = null;
        document.getElementById('userm-job_name').value = null;
    }
}

//-- проверка по ФИО и заполнение жетона, места работы
function checkFIO(){
    //alert('dddd');
    //--- 0033934 Посохін Сергій Анатолійович
   // console.log(data);
    var last_name = document.getElementById('userm-last_name').value;//--фамилия
    var first_name = document.getElementById('userm-first_name').value; //--имя
    var middle_name = document.getElementById('userm-middle_name').value; //--отчество
    if (last_name != '' && last_name.length > 2){
        $.ajax({
            url: '/adminxx/user/get-personal-data-by-fio',
            type: "POST",
            data: {
                'last_name': last_name,
                'first_name': first_name,
                'middle_name': middle_name
            },
            dataType: 'json',
            beforeSend: function() {
                preloader('show', 'mainContainer', 0);
                },
            complete: function(){
                preloader('hide', 'mainContainer', 0);
                },
            success: function(response){
                  // console.log(response['data']);
                if(response['status']){
                    var list = $("#selectPosition");
                    list.html('');
                    $("#selectPositionBlock").show();
                    //console.log(response['data']);

                    $.each(response['data'], function (key, value) {
                        var r = this;
                        $('<option>').text(value['name']).val(value['id']).appendTo(list);
                    });

                } else {
                    var list = $("#selectPosition");
                    list.html('');
                    $("#selectPositionBlock").hide();


                    console.log(response['data']);
                }
            },
            error: function (jqXHR, error, errorThrown) {
                console.log( "Помилка: " + error + " " +  errorThrown);
            }
        });
    }

}

//-- выбор сотрудника из списка после проверки
function choosePersona(){
  // alert(pid);
    var pid = $("#selectPosition").val();

    $.ajax({
        url: '/adminxx/user/get-personal-data-by-id',
        type: "POST",
        data: {
            'id': pid
        },
        dataType: 'json',
        beforeSend: function() {
            preloader('show', 'mainContainer', 0);
        },
        complete: function(){
            preloader('hide', 'mainContainer', 0);
        },
        success: function(response){
           // console.log(response['data']);
            if(response['status']){
                var sid = document.getElementById('userm-spec_document').value;
                if (sid != '' && sid !=response['data']['spec_document']){
                    if (confirm('Номери жетонів не співпадають')){
                        $("#selectPosition").html('');
                        $("#selectPositionBlock").hide();

                        document.getElementById('userm-personal_id').value = response['data']['personal_id'];
                        document.getElementById('userm-job_name').value = response['data']['positionFullName'];
                        document.getElementById('userm-last_name').value = response['data']['name_family'];
                        document.getElementById('userm-first_name').value = response['data']['name_first'];
                        document.getElementById('userm-middle_name').value = response['data']['name_last'];
                        document.getElementById('userm-spec_document').value = response['data']['spec_document'];

                    }
                } else {
                    $("#selectPosition").html('');
                    $("#selectPositionBlock").hide();

                    document.getElementById('userm-personal_id').value = response['data']['personal_id'];
                    document.getElementById('userm-job_name').value = response['data']['positionFullName'];
                    document.getElementById('userm-last_name').value = response['data']['name_family'];
                    document.getElementById('userm-first_name').value = response['data']['name_first'];
                    document.getElementById('userm-middle_name').value = response['data']['name_last'];
                    document.getElementById('userm-spec_document').value = response['data']['spec_document'];

                }
            } else {
                alert(response['data']);
                document.getElementById('userm-personal_id').value = null;
                document.getElementById('userm-job_name').value = null;
                console.log(response['data']);
            }
        },
        error: function (jqXHR, error, errorThrown) {
            console.log( "Помилка: " + error + " " +  errorThrown);
        }
    });

}

//-- закрытие окна выбор сотрудника из списка после проверки
function selectPositionBlockClose(){
    $("#selectPosition").html('');
    $("#selectPositionBlock").hide();
}

//---------------------------------------------------------------------------- работа с деревом подразделений

//-- функция по нажатию на элемент дерева
function clickItemFunction__(id, type) {
    if (id > 0){
        $.ajax({
            url: '/adminxx/user/get-department-name',
            type: "GET",
            data: {
                'id' : id
            },
            dataType: 'json',
            success: function(response){
                // console.log(response);
                $("#newDepartmentId").html(id);
                $("#newDepartmentName").html(response['data']);
            },
            error: function (jqXHR, error, errorThrown) {
                errorHandler(jqXHR, error, errorThrown);
            }
        });
    }
}

//-- добавление выбранного подразделения пользователю
function addUserDepartment() {
   console.log(selected_id);

    if (selected_id != undefined && selected_id > 0){
        if ($(".userDepartment[data-id='" + selected_id + "']").length > 0){
            alert('Такий підрозділ вже є');
            return false;
        }

        $.ajax({
            url: '/adminxx/user/get-department-name',
            type: "GET",
            data: {
                'department_id' : selected_id
            },
            dataType: 'json',
            success: function(response){
                drawUserDepartment(selected_id, response['data'], '0', '0', '0');
            },
            error: function (jqXHR, error, errorThrown) {
                errorHandler(jqXHR, error, errorThrown);
            }
        });
    }
    return true;
}

//-- добавление роли пользователю
function addUserRole() {
    var newId = $("[name='defaultRoles']").val();
    var newName = $("[name='defaultRoles']").find("option[value=" + newId+ "]").text();
    console.log(newId);
    console.log(newName);
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

//-- прорисовка подразделения пользователя
function drawUserDepartment(id, name, can_department, can_position, can_personal) {
    if (id != '' && name != ''){
        if ($(".userDepartment[data-id='" + id + "']").length > 0){
           alert('Такий підрозділ вже є');
           return false;
        }
        var newDep = '<div '
            + 'class="userDepartment" '
            + 'data-id ="' + id + '" '
            + 'data-can_department = "' + can_department + '" '
            + 'data-can_position = "' + can_position + '" '
            + 'data-can_personal = "' + can_personal + '" '
            + '>'
            + '<div class="departmentName" style="color: blue">'
            + '<b>'+ name + '</b>'
            + '</div>'
            + '<div class="userPermission_">'
            + '<input type="checkbox"'
            + 'value="can_department"'
            + 'onclick="changeDepartmentPermission(this);"'
            + ((can_department == '1') ? 'checked' : '')
            + '> '
            + ' Підрозділи '
            + '<input type="checkbox"'
            + 'value="can_position"'
            + 'onclick="changeDepartmentPermission(this);"'
            + ((can_position == '1') ? 'checked' : '')
            + '>'
            + '  Посади '
            + '<input type="checkbox"'
            + 'value="can_personal"'
            + 'onclick="changeDepartmentPermission(this);"'
            + ((can_personal == '1') ? 'checked' : '')
            + '>'
            + '  Працівники '
            + '<a href="#"'
            + 'title = "Видалити підрозділ"'
            + 'onclick="deleteUserDepartment(this);"'
            + '>' + '   ' + '<span class="glyphicon glyphicon-trash"></span></a>'
            + '</div>'
        //    + '<hr>'
            + '</div>';
        $("#userDepartments").append(newDep);
       // $("#departmentsArea").show();
       // $("#selectArea").hide();
        //-----------------------------------------

    }



}

//-- изменение разрешений подразделения пользователя
function changeDepartmentPermission(item){
   // console.log(item.checked);
   // console.log(item.value);
   // console.log(item);
    var target = $(item).parents(".userDepartment");
    if (item.checked){
        target[0].dataset[item.value] = '1';
    } else {
        target[0].dataset[item.value] = '0';
    }

  //  console.log(target[0].dataset);
}

//-- удаление подразделения пользователя
function deleteUserDepartment(item){
    if (confirm('Видалити підрозділ')){
        $(item).parents(".userDepartment")[0].remove();

    }
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
    $('.userDepartment').each(function (index, value) {
       // console.log(this.dataset);
        depData.push({
            'id' : this.dataset['id'],
            'can_department' : this.dataset['can_department'],
            'can_position' : this.dataset['can_position'],
            'can_personal' : this.dataset['can_personal'],
        });
    });
    $('.userRole').each(function (index, value) {
       // console.log(this.dataset);
        roleData.push({
            'id' : this.dataset['id'],
            'name' : this.dataset['name'],
        });
    });
    ret = {
        'departments': depData,
        'roles' : roleData};
    console.log(ret);

    dsend = JSON.stringify(ret);
    $("#userm-multyfild").val(dsend);
    $("#form-update").submit();




}


//----------------------------------------------------------------------------------------------------------

//********************** DEPRECATED

$("#checkSpecDocument_btn").on('click', function () {
    alert(this.value);
    return true;
    var spec_document = document.getElementById('userm-spec_document').value;

    $.ajax({
        url: '/adminxx/user/get-personal-data',
        type: "POST",
        data: {
            'spec_document': spec_document
        },
        dataType: 'json',
        beforeSend: function() {
            $("#preloader").show();
        },
        complete: function(){
            $("#preloader").hide();
        },
        success: function(response){
            console.log(response);
            if(response['status']){
                document.getElementById('userm-personal_id').value = response['data']['personal_id'];
                document.getElementById('userm-job_name').value = response['data']['positionFullName'];
                document.getElementById('userm-last_name').value = response['data']['name_family'];
                document.getElementById('userm-first_name').value = response['data']['name_first'];
                document.getElementById('userm-middle_name').value = response['data']['name_last'];
            } else {
                alert(response['data']);
                document.getElementById('userm-personal_id').value = null;
                document.getElementById('userm-job_name').value = null;
                console.log(response['data']);
            }
        },
        error: function (jqXHR, error, errorThrown) {
            console.log( "Помилка: " + error + " " +  errorThrown);
        }
    });

});

$("#checkFIO_btn").on('click', function ()  {
    var last_name = document.getElementById('userm-last_name').value;//--фамилия
    var first_name = document.getElementById('userm-first_name').value; //--имя
    var middle_name = document.getElementById('userm-middle_name').value; //--отчество
    $.ajax({
        url: '/adminxx/user/get-personal-data-by-fio',
        type: "POST",
        data: {
            'last_name': last_name,
            'first_name': first_name,
            'middle_name': middle_name
        },
        dataType: 'json',
        beforeSend: function() {
            $("#preloader").show();
        },
        complete: function(){
            $("#preloader").hide();
        },
        success: function(response){
         //   console.log(response['data']);
            if(response['status']){
                var list = $("#selectPosition");
                list.html('');
                $("#selectPositionBlock").show();
                $.each(response['data'], function (key, value) {
                    var r = this;
                    // console.log(this[0]);
                    $('<option>').text(value).val(key).appendTo(list);
                });

            } else {
                alert(response['data']);
                document.getElementById('userm-personal_id').value = 0;
                document.getElementById('userm-job_name').value = '';
                console.log(response['data']);
            }
        },
        error: function (jqXHR, error, errorThrown) {
            console.log( "Помилка: " + error + " " +  errorThrown);
        }
    });

});

$("#btnChoosePersona").on('click', function ()  {
    var item = $("#selectPosition")[0].value;
    console.log(item);
    $.ajax({
        url: '/adminxx/user/get-personal-data-by-id',
        type: "POST",
        data: {
            'id': item
        },
        dataType: 'json',
        beforeSend: function() {
            $("#preloader").show();
        },
        complete: function(){
            $("#preloader").hide();
        },
        success: function(response){
            console.log(response['data']);
            if(response['status']){
                $("#selectPosition").html('');
                $("#selectPositionBlock").hide();

                document.getElementById('userm-personal_id').value = response['data']['personal_id'];
                document.getElementById('userm-job_name').value = response['data']['positionFullName'];
                document.getElementById('userm-last_name').value = response['data']['name_family'];
                document.getElementById('userm-first_name').value = response['data']['name_first'];
                document.getElementById('userm-middle_name').value = response['data']['name_last'];
                document.getElementById('userm-spec_document').value = response['data']['spec_document'];
            } else {
                alert(response['data']);
                document.getElementById('userm-personal_id').value = null;
                document.getElementById('userm-job_name').value = null;
                console.log(response['data']);
            }
        },
        error: function (jqXHR, error, errorThrown) {
            console.log( "Помилка: " + error + " " +  errorThrown);
        }
    });

});

$("#btnResetChoosePersona").on('click', function ()  {
    $("#selectPosition").html('');
    $("#selectPositionBlock").hide();
});
