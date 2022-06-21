document.addEventListener('DOMContentLoaded', function() {
    let keyPopup = 'checkPopup';
    if (!sessionStorage.getItem(keyPopup)) {
        sessionStorage.setItem(keyPopup, 1);
        setTimeout(function () {
            $('#adsModal').modal('show');
        },3500);

        /*setTimeout(function () {
            $('#adsModal').modal('hide');
        },8500);*/

        let url = $("#adsModal").find('a').attr('href');

        $("#adsModal").on('hide.bs.modal', function(){
            window.open(url,"_blank");
        });
    }

    // remove banner closed
    /*let key_session = Object.keys(sessionStorage);
    $.each(key_session, function (key, val) {
        $('.ads-container[data-position="'+val+'"]').remove();
    });*/

    // load banner
    setTimeout(function () {
        $('.banners img:not([src])').each(function () {
            let _this = $(this);
            _this.attr('src', _this.data('src'));
            _this.closest('.ads-container').attr('data-loaded', 1);
        });
    }, 3500);
    /*$('#adsModal img:not([src])').each(function () {
        $(this).attr('src', $(this).data('src')).parent().attr('data-load', 1);
    });*/

    $('body').on('click','.banner-close .close-icon',function () {
        let key = $(this).parents('.ads-container').attr('data-position');
        if (!sessionStorage.getItem(key)) {
            sessionStorage.setItem(key, 1);
        }  else {
            let current = sessionStorage.getItem(key);
            let new_val = parseInt(current) + 1;
            sessionStorage.setItem(key, new_val);
        }

        if (key.includes("catfish")) {
            $(this).parents('.ads-container').parent('.fixed-bottom').remove();
        } else {
            $(this).parents('.ads-container').remove();
        }
    });
});
