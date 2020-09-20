
function clickItemFunction(tree_id, id){
    $.ajax({
        url: '/binar/get-binar-info',
        type: "GET",
        data: {
            'id' : id
        },
        success: function(response){
            $("#binarInfo").html(response);
        },
        error: function (jqXHR, error, errorThrown) {
            console.log(error);
            console.log(errorThrown);
            console.log(jqXHR);
        }
    });

}
