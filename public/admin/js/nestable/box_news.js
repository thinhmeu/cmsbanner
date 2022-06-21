$(document).ready(function () {
    if (typeof $('#nestable')[0] != 'undefined') {
        var container = $('#nestable');
        container.nestable({
            group: 1,
            maxDepth: 1
        }).change(function () {
            updateOutput(container);
        });
        $('.category_select').click(function(){
            var category = $('select[name=category_id]');
            var option = $('option:selected', category);
            var id = option.val();
            var title = option.data('title');
            var url = option.data('url');
            title = title.replace(/-/g, '');
            appendEditMenuItem(container, title, id, url);
            updateOutput(container);
            deleteMenuItem();
        });
        var data = $('input[name=data]').val();
        if (data !== ''){
            listify(container, data);
            deleteMenuItem();
        }
    }
})
function deleteMenuItem() {
    $('.nestledeletedd').on('click',function (e) {
        e.preventDefault();
        var item;
        item = $(this).closest('li.dd-item');
        if (confirm('Bạn có chắc chắn xóa?')) {
            item.remove();
        }
        updateOutput($('#nestable'));
    });
}
function appendEditMenuItem(container, title, id, url) {
    var item = "<li class='dd-item' data-name='' data-id='' data-url=''>\n" +
        "    <div class='dd-handle'></div>\n" +
        "    <div class='action-item'>\n" +
        "        <span class='nestledeletedd'>\n" +
        "            <svg class='c-icon'>\n" +
        "                <use xlink:href='/admin/images/icon-svg/free.svg#cil-trash'></use>\n" +
        "            </svg>\n" +
        "        </span>\n" +
        "    </div>\n" +
        "</li>";
    item = $.parseHTML(item);
    $(item).data('name', title);
    $(item).data('id', id);
    $(item).data('url', url);
    $(item).find('.dd-handle').text(title);
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
function listify(container, strarr) {
    var obj = JSON.parse(strarr);
    if (!obj.length) obj = [obj];
    $.each(obj, function(i, v) {
        if (typeof v == 'object') {
            var name = v.name;
            var id = v.id;
            var url = v.url;
            appendEditMenuItem(container,name,id,url);
        }
    });
}
