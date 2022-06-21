$(document).ready(function () {
    if (typeof $('#nestable')[0] != 'undefined') {
        var container = $('#nestable');
        container.nestable({
            group: 1,
            maxDepth: 2
        }).change(function () {
            updateOutput(container);
        });
        $(document).on('click', '.link_select', function () {
            let title = $(this).html();
            let id = $(this).attr('data-id');
            appendEditMenuItem(container, title, id);
            updateOutput(container);
        });

        $(document).on('click', '.nestledeletedd' ,function (e) {
            e.preventDefault();
            var item;
            item = $(this).closest('li.dd-item');
            if (confirm('Bạn có chắc chắn xóa?')) {
                item.remove();
            }
            updateOutput($('#nestable'));
        });
    }
});
function appendEditMenuItem(container, title, id) {
    var item = "<li class='dd-item'>\n" +
        "    <div class='dd-handle'>" + title + "</div>\n" +
        "    <div class='action-item'>\n" +
        "        <span class='nestledeletedd'>\n" +
        "            <svg class='c-icon'>\n" +
        "                <use xlink:href='/admin/images/icon-svg/free.svg#cil-trash'></use>\n" +
        "            </svg>\n" +
        "        </span>\n" +
        "    </div>\n" +
        "<input type='hidden' name='post_id[]' value='" + id + "'>" +
        "</li>\n";
    item = $.parseHTML(item);
    container.find('.dd-list').first().append(item);
    return item;
}
function updateOutput(e) {
    var list   = e.length ? e : $(e.target);
    if (window.JSON) {
        var data = window.JSON.stringify(list.nestable('serialize'));
        $('input[name=data]').val(data);
    }
}

let timer;
$(".nha-cai-search-keyword").bind('keyup', function () {
    clearTimeout(timer);
    timer = setTimeout(search_post, 500)
});

$(".nha-cai-search-keyword").on('focus', function () {
    search_post();
});

function search_post(){
    let keyword = $('.nha-cai-search-keyword').val();
    $.ajax({
        url: '/admin/ajax/ajax_search_post',
        data: {
            keyword: keyword
        },
        type: 'POST',
        dataType: 'json',
        success: function (res) {
            if (res.status === 'success') {
                let html = '';
                $.each(res.data, function (key, value) {
                    html += '<a class="dropdown-item link_select" data-id="' + value.id + '" href="javascript:;">' + value.title + '</a>'
                });
                $('.ajax-list-post').html(html).removeClass('d-none');
            }
        }
    });
}

$(document).on('change', '.sl-position', function (e) {
    let url = $('.sl-position option:selected').data('url');
    window.location.href = url;
});
