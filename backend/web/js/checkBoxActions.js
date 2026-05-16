var url = window.location.pathname;
var language = url.split('/')[1];
var lastUri = '';
var controller = '';
var deleteErrorMessage = 'you can not remove red rows, they have relations';

$(document).ready(function () {
    $('.chk-usr').click(function () {
        var checked = $(this).prop('checked');
        if (checked === false) {
            if ($(this).parents('tr').hasClass('danger')) {
                $(this).parents('tr').removeClass('danger');
            }
        }
    });
    $('#select-all-users').click(function () {
        if ($('#select-all-users').prop('checked') === false) {
            $('body').find(".odd").each(function (key, value) {
                $(value).removeClass('danger');
            });
        }
    });
    $('.action-block>input').click(
        function () {
            var action = $('#checkbox-actions option:selected')[0].className;
            var controller = $('#checkbox-actions').attr('data-action');
            var uri = "/" + language + "/" + controller + "/";
            var checked = getIdsAndIndexChecked();
            if (checked[0].length > 0) {
                var ids = checked[0];
                var index = checked[1];
                if (action == "delete") {
                    full_uri = uri + "delete-by-ajax";
                    var data = {ids: ids};
                    var query = ajaxQuery(full_uri, data);
                    if (query === true) {
                        hideItems(index);
                        if ($('#admin-alerts').hasClass('bg-danger')) {
                            $('#admin-alerts').removeClass('bg-danger')
                        }

                        $('#admin-alerts').addClass('bg-success');
                        $('#admin-alerts').css('display', 'block');
                        $('#admin-alerts').children('div').text('successfully removed');
                    } else {
                        $.each($.parseJSON(query), function (key, value) {
                            $("tbody>tr[data-key = " + key + "]").addClass('danger');
                        });
                        if ($('#admin-alerts').hasClass('bg-success')) {
                            $('#admin-alerts').removeClass('bg-success')
                        }
                        $('#admin-alerts').show();
                        $('#admin-alerts').addClass('bg-danger');
                        $('#admin-alerts').children('div').text(deleteErrorMessage);
                    }
                }
                if(action == 'disactivating' || action == 'activating' || action == 'low-stock' || action == 'out-of-stock'){
                    var status = '';
                    if(action == 'disactivating'){
                        status = 0;
                    }if (action == 'activating') {
                        status = 1;
                    }
                    if(action == 'low-stock'){
                        status = 2;
                    }
                    if (action == 'out-of-stock') {
                        status = 3;
                    }
                    lastUri = "changeitemsstatus";
                    uri += lastUri;
                    var data = {data: {id: ids, status: status}};
                    if(ajaxQuery(uri, data) === true){
                        if($('#admin-alerts').hasClass('bg-danger')){
                            $('#admin-alerts').removeClass('bg-danger')
                        }
                        $('#admin-alerts').addClass('bg-success');
                        $('#admin-alerts').css('display', 'block');
                        $('#admin-alerts').children('div').text('successfully' + " status changed into " + action);
                        $.pjax.reload({container: "#" + controller + "Pjaxtbl"});
                    }else {
                        if ($('#admin-alerts').hasClass('bg-success')) {
                            $('#admin-alerts').removeClass('bg-success')
                        }
                        $('#admin-alerts').show();
                        $('#admin-alerts').addClass('bg-danger');
                        $('#admin-alerts').children('div').text("status does not changed into " + action);
                    }
                }
                if (action == "in_slider"){
                    lastUri = "addinslider";
                    uri += lastUri
                    var data = {data: {id: ids}};
                    if(ajaxQuery(uri, data) === true){
                        if($('#admin-alerts').hasClass('bg-danger')){
                            $('#admin-alerts').removeClass('bg-danger')
                        }
                        $('#admin-alerts').addClass('bg-success');
                        $('#admin-alerts').css('display', 'block');
                        $('#admin-alerts').children('div').text('successfully adding in slider');
                        $.pjax.reload({container: "#" + controller + "Pjaxtbl"});
                    }else {
                        if ($('#admin-alerts').hasClass('bg-success')) {
                            $('#admin-alerts').removeClass('bg-success')
                        }
                        $('#admin-alerts').show();
                        $('#admin-alerts').addClass('bg-danger');
                        $('#admin-alerts').children('div').text('error is not adding in slider');
                    }
                }
            }
        }
    )
});

var getIdsAndIndexChecked = function () {
    var ids = [];
    var index = [];
    $('tbody>').find('.chk-usr').each(function (key, value) {
        if ($(value).prop('checked')) {
            index.push(key);
            ids.push($(value).attr('value'));
        }
    });
    return [ids, index];
}

var hideItems = function (indexes) {
    $.each(indexes, function (index, value) {
        $("tbody>tr").eq(value).addClass('hide');
    });
}

var ajaxQuery = function (uri, data) {
    var result = false;
    $.ajax({
        method: 'POST',
        url: uri,
        data: data,
        cache: false,
        async: false,
        success: function (res) {
            if (res == 1) {
                result = true;
            } else {
                result = res;
            }
        },
    });
    return result;
}