$(document).ready(function () {
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    $('#redirect').DataTable({
        "order": [[ 0, "desc" ]]
    });
    $('#redirect').attr('style', 'border-collapse: collapse !important');
});




$(document).on('click', '.ajax-login', function (e) {
    e.preventDefault();
    let form = $(this).closest('form');
    let url = form.attr('action');
    let data = form.serialize();
    $.ajax({
        url: url,
        data: data,
        type: 'POST',
        dataType: 'json',
        success: function (res) {
            if (res.status === 'success'){
                window.location.href = '/admin/home';
            } else {
                alert('Tài khoản hoặc mật khẩu không đúng!');
                form[0].reset();
                return false;
            }
        }
    })
});

$(document).on('keypress', 'input[name="password"]',function(e) {
    if(e.which === 13) {
        $('.ajax-login').trigger('click');
    }
});

$(document).on('keypress', '.customValidity', function (e) {
    $(this).get(0).setCustomValidity("");
});

function checkEmptyCustomValidity() {
    $('.customValidity').each(function () {
        if (!$(this).val()) {
            $(this).get(0).setCustomValidity("Không được để trống!");
            $(this).get(0).reportValidity();
            return false;
        }
    });
    return true;
}

$(document).on('click', '.btn-change-password', function (e) {
    e.preventDefault();
    if (!checkEmptyCustomValidity()) return;
    let input_old_password = $('#changePassword input[name="old_password"]');
    let input_new_password = $('#changePassword input[name="new_password"]');
    let input_re_password = $('#changePassword input[name="re_password"]');
    let old_password = input_old_password.val();
    let new_password = input_new_password.val();
    let re_password = input_re_password.val();
    if (re_password !== new_password) {
        input_re_password.get(0).setCustomValidity("Mật khẩu nhập lại chưa đúng!");
        input_re_password.get(0).reportValidity();
        return false;
    }
    $.ajax({
        url: '/admin/ajax/changePassword',
        data: {
            old_password: old_password,
            new_password: new_password
        },
        type: 'POST',
        dataType: 'json',
        success: function (res) {
            if (res.status === 'success'){
                $('#changePassword').modal('hide');
                showToastr('success', res.message);
            } else {
                input_old_password.get(0).setCustomValidity("Mật khẩu cũ không đúng!");
                input_old_password.get(0).reportValidity();
            }
        }
    })
});

function upload_file(mode,control){
    let open_url = '/admin/libraries/elfinder/file-elfinder.php?mode='+mode+'&control='+control;
    window.open(open_url,'_blank',"location=0,left=200,width=800,height=500");
    return false;
}

if (document.getElementById('select-multi-tag')) {
    let post_id = $('#select-multi-tag').data('post-id');
    $.ajax({
        url: '/admin/ajax/loadTag',
        type: 'POST',
        data: {
            post_id: post_id
        },
        dataType: "json",
        success: function(data) {
            if (data.list_tag) {
                let options = data.list_tag;
                let select = document.getElementById('select-multi-tag');
                new coreui.MultiSelect(select, {
                    multiple: true,
                    selectionType: 'tags',
                    search: true,
                    options: options
                });
            }
            if (data.tag_selected) {
                data.tag_selected.forEach((item) => {
                    $('.c-multi-select-option[data-value="'+item+'"]').trigger('click');
                });
            }
        }
    });

    $('form').submit(function() {
        let container = $('#select-multi-tag').parent();
        $(container).find('.c-multi-select-selection .c-multi-select-tag').each(function () {
            let id = $(this).data('value');
            $(this).append('<input type="hidden" name="tag[]" value="'+id+'">');
        });
        return true;
    });
}

if (document.getElementById('select-multi-category')) {
    let post_id = $('#select-multi-category').data('post-id');
    $.ajax({
        url: '/admin/ajax/loadCategory',
        type: 'POST',
        data: {
            post_id: post_id
        },
        dataType: "json",
        success: function(data) {
            if (data.list_category) {
                let options = data.list_category;
                let select = document.getElementById('select-multi-category');
                new coreui.MultiSelect(select, {
                    multiple: true,
                    selectionType: 'tags',
                    search: true,
                    options: options
                });
            }
            if (data.category_selected) {
                data.category_selected.forEach((item) => {
                    $('#select-multi-category').closest('.form-group').find('.c-multi-select-option[data-value="'+item+'"]').trigger('click');
                });
            }
        }
    });

    $('form').submit(function() {
        let container = $('#select-multi-category').parent();
        $(container).find('.c-multi-select-selection .c-multi-select-tag').each(function () {
            let id = $(this).data('value');
            $(this).append('<input type="hidden" name="category[]" value="'+id+'">');
        });
        return true;
    });
}

$(document).on('change', '.sl-type-post', function () {
    let url = $(this).val();
    window.location.href = url;
});

$('.save-draft').on('click', function() {
    $('select[name=status]').val('0');
    $('button[type=submit]').trigger('click');
})
