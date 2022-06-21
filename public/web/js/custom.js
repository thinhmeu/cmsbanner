const FUNC = {
    ajax_load_more: function() {
        $(document).on('click', '.load-more', function (e) {
            e.preventDefault();
            let page = $(this).data('page');
            if (!page) page = 2;
            $.ajax({
                type: 'get',
                url: '',
                dataType: 'html',
                data: {
                    page: page,
                },
                success: function (res) {
                    let selector_show_content = '#ajax_content';
                    let resultFind = $(res).find('#ajax_content').html();
                    if (resultFind) {
                        $(selector_show_content).append(resultFind);
                    }
                    $('.load-more').data('page', page + 1);
                }
            })
        })
    },
    init: function () {
        FUNC.ajax_load_more();
    }
};
$(document).ready(function () {
    FUNC.init();
});
