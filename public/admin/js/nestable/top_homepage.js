$(document).ready(function () {
    if (typeof $('#nestable')[0] != 'undefined') {
        var container = $('#nestable');
        container.nestable({
            group: 1,
            maxDepth: 2
        }).change(function () {
            updateOutput(container);
        });
        $('.category_select').click(function(){
            var category = $('select[name=category_id]');
            var option = $('option:selected', category);
            var url = option.data('url');
            var title = option.data('title');
            title = title.replace(/-/g, '');
            appendEditMenuItem(container, title, url);
            updateOutput(container);
            toggleEditMenuItem();
            editMenuItem();
            deleteMenuItem();
        });
        $('.link_select').click(function(){
            var url = $('input[name=custom_link]').val();
            appendEditMenuItem(container, url, '#');
            updateOutput(container);
            toggleEditMenuItem();
            editMenuItem();
            deleteMenuItem();
        });
        var data = $('input[name=data]').val();
        if (data !== ''){
            listify(container, data);
            toggleEditMenuItem();
            editMenuItem();
            deleteMenuItem();
        }
    }
})
function toggleEditMenuItem() {
    $(document).on('click', '.nestleeditd', function () {
        $(this).parent().siblings('div.menublock').toggleClass('d-none');
    });
}
function editMenuItem() {
    $('.apply_item').on('click',function () {
        var container = $(this).closest('li.dd-item');
        var name = container.find('.name_item').first().val();
        var url = container.find('.link_item').first().val();
        container.data('name', name);
        container.data('url', url);
        container.find('.dd-handle').first().text(name);
        updateOutput($('#nestable'));
        container.find('.nestleeditd').first().trigger('click');
    });
}
function deleteMenuItem() {
    $('.nestledeletedd').on('click',function (e) {
        e.preventDefault();
        var item;
        item = $(this).closest('li.dd-item');
        if (confirm('Bạn có chắc chắn xóa?')) {
            item.remove();
        }
        /*$('#smallModal').modal('show');
        $('.confirm_yes').click(function() {
            item.remove();
            $('#smallModal').modal('hide');
        })*/
        updateOutput($('#nestable'));
    });
}
function appendEditMenuItem(container, title, url) {
    var item = "<li class='dd-item' data-name='' data-url=''>\n" +
        "    <div class='dd-handle'></div>\n" +
        "    <div class='action-item'>\n" +
        "        <span class='nestleeditd'>\n" +
        "            <svg class='c-icon'>\n" +
        "                <use xlink:href='/admin/images/icon-svg/free.svg#cil-pencil'></use>\n" +
        "            </svg>\n" +
        "        </span>\n" +
        "        <span class='nestledeletedd'>\n" +
        "            <svg class='c-icon'>\n" +
        "                <use xlink:href='/admin/images/icon-svg/free.svg#cil-trash'></use>\n" +
        "            </svg>\n" +
        "        </span>\n" +
        "    </div>\n" +
        "    <div class='menublock d-none'>\n" +
        "        <input type='text' class='form-control name_item' value='' placeholder='Name'>\n" +
        "        <input type='text' class='form-control link_item' value='' placeholder='Link'>\n" +
        "        <input type='button' class='mt-1 btn btn-theme apply_item border' value='Apply'>\n" +
        "    </div>\n" +
        "</li>";
    item = $.parseHTML(item);
    $(item).data('name', title);
    $(item).data('url', url);
    $(item).find('.dd-handle').text(title);
    $(item).find('.name_item').val(title);
    $(item).find('.link_item').val(url);
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
            var url = v.url;
            var parent = appendEditMenuItem(container,name,url);
            if (!!v.children){
                var div = "<ol class='dd-list'></ol>";
                $(parent).append(div);
                $.each(v.children, function(key, item) {
                    listify($(parent), JSON.stringify(item));
                });
            }
        }
    });
}
