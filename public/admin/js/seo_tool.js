function toSlug(title) {
    let slug;
    slug = title.toLowerCase();
    slug = slug.replace(/\//mig, "-");
    slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, 'a');
    slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, 'e');
    slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, 'i');
    slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, 'o');
    slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, 'u');
    slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, 'y');
    slug = slug.replace(/đ/gi, 'd');
    // slug = slug.replace(/[^a-zA-Z0-9 ]/g, "");
    slug = slug.replace(/ /gi, "-");
    slug = slug.replace(/\-\-\-\-\-/gi, '-');
    slug = slug.replace(/\-\-\-\-/gi, '-');
    slug = slug.replace(/\-\-\-/gi, '-');
    slug = slug.replace(/\-\-/gi, '-');
    slug = '@' + slug + '@';
    slug = slug.replace(/\@\-|\-\@|\@/gi, '');
    slug = slug.replace(/\s/g, "-");
    return slug;
}
var SEO = {
    yoats_seo: function () {
        let main_keyword  = $('input[name="main_keyword"]');
        let meta_title = $('input[name="meta_title"]');
        let meta_desc = $('textarea[name="meta_description"]');
        let description = $('textarea[name="description"]');
        let slug = $('input[name="slug"]');
        let keyword = main_keyword.val().toLowerCase();
        let content = tinyMCE.editors[0].getContent({ format: "raw" });
        let content_html = $.parseHTML(content);

        //Link nội bộ trong bài viết
        let regex_href = new RegExp('(href=".*?")','g');
        if(regex_href.exec(content) !== null) {
            $('.link-in-content').html('<span class="m-badge bg-success small-badge"></span><span>Đã có đường dẫn nội bộ trong bài viết, tuyệt vời!!!</span>');
            $('.link-in-content').attr('data-score', 10);
        } else {
            $('.link-in-content').html('<span class="m-badge bg-warning small-badge"></span><span>Trong bài viết nên có link nội bộ. Chèn link nội bộ vào bài viết</span>');
            $('.link-in-content').attr('data-score', 0);
        }

        var keyword_in_content = 0;
        var keyword_in_sub_heading = 0;
        var count_heading = 0;
        var set_content = '';
        var check_alt = 0;
        $.each(content_html, function (key, val) {
            //Đếm mật độ từ khóa trong content
            if ($(val).text().toLowerCase().search(keyword) > -1){
                keyword_in_content = keyword_in_content + 1;
            }

            //Từ khóa trong thẻ tiêu đề phụ
            if ( $(val)[0].nodeName == 'H2' || $(val)[0].nodeName == 'H3'  || $(val)[0].nodeName == 'H4' || $(val)[0].nodeName == 'H5' || $(val)[0].nodeName == 'H6') {
                count_heading = 1;
                if ($(val).text().toLowerCase().search(keyword) > -1) {
                    keyword_in_sub_heading = 1;
                }
            }

            //Set alt cho img
            if ($(val)[0].nodeName !== '#text'){
                if ($(val).find('img').length > 0){
                    let img_content = $(val).find('img');
                    img_content.attr('alt',keyword);
                    check_alt = 1;
                }
                set_content += $(val)[0].outerHTML;
            }
        });

        //Set alt cho img
        if (set_content.length > 0) {
            tinyMCE.editors[0].setContent(set_content, {format: 'raw'});
        }

        if (check_alt == 1) {
            $('.alt-img').html('<span class="m-badge bg-success small-badge"></span><span>Alt đã có thuộc tính, tuyệt vời</span>');
            $('.alt-img').attr('data-score', 5);
        } else {
            $('.alt-img').html('<span class="m-badge bg-success small-badge"></span><span>Bài viết chưa có thuộc tính alt</span>');
            $('.alt-img').attr('data-score', 0);
        }

        //Độ dài tiêu đề
        if (meta_title.val().length == 0) {
            $('.title-length').html('<span class="m-badge bg-danger small-badge"></span><span>Chưa có tiêu đề</span>');
            $('.title-length').attr('data-score', 0);

        } else if (meta_title.val().length > 0 && meta_title.val().length < 40) {
            $('.title-length').html('<span class="m-badge bg-warning small-badge"></span><span>Tiêu đề SEO quá ngắn. Hãy tối ưu lại tiêu đề SEO</span>');
            $('.title-length').attr('data-score', 0);

        } else if (meta_title.val().length >= 40 && meta_title.val().length < 65) {
            $('.title-length').html('<span class="m-badge bg-success small-badge"></span><span>Độ dài tiêu đề chuẩn SEO. Tuyệt vời !!!</span>');
            $('.title-length').attr('data-score', 10);

        } else {
            $('.title-length').html('<span class="m-badge bg-warning small-badge"></span><span>Tiêu đề SEO quá dài. Hãy tối ưu lại tiêu đề SEO</span>');
            $('.title-length').attr('data-score', 0);
        }
        $('.count-title').html(meta_title.val().length);

        //Từ khoá trong mô tả
        if (meta_desc.val().length > 0) {
            if (meta_desc.val().toLowerCase().search(keyword) == -1){
                $('.keyword-in-desc').html('<span class="m-badge bg-danger small-badge"></span><span>Từ khoá SEO không xuất hiện ở phần mô tả. Thêm từ khoá SEO vào mô tả</span>');
                $('.keyword-in-desc').attr('data-score', 0);
            } else {
                $('.keyword-in-desc').html('<span class="m-badge bg-success small-badge"></span><span>Từ khoá SEO đã xuất hiện trong phần mô tả. Tuyệt vời !!!</span>');
                $('.keyword-in-desc').attr('data-score', 10);
            }
        } else {
            $('.keyword-in-desc').html('<span class="m-badge bg-success small-badge"></span><span>Chưa có từ khóa hoặc miêu tả, vui lòng kiểm tra lại</span>');
            $('.keyword-in-desc').attr('data-score', 0);
        }

        //Độ dài mô tả
        if (meta_desc.val().length == 0){
            $('.desc-length').html('<span class="m-badge bg-danger small-badge"></span><span>Chưa có mô tả</span>');
            $('.desc-length').attr('data-score', 0);

        } else if (meta_desc.val().length > 0 && meta_desc.val().length < 130) {
            $('.desc-length').html('<span class="m-badge bg-danger small-badge"></span><span>Độ dài mô tả quá ngắn</span>');
            $('.desc-length').attr('data-score', 0);
        } else if (meta_desc.val().length >= 130 && meta_desc.val().length < 175) {
            $('.desc-length').html('<span class="m-badge bg-success small-badge"></span><span>Độ dài mô tả đặt chuẩn quy định. Tuyệt vời !!!</span>');
            $('.desc-length').attr('data-score', 10);
        } else {
            $('.desc-length').html('<span class="m-badge bg-warning small-badge"></span><span>Độ dài miêu tả quá dài</span>');
            $('.desc-length').attr('data-score', 0);
        }
        $('.count-desc').html(meta_desc.val().length);


        if (keyword.length > 0) {
            //URL bài viết
            if (slug.val().length > 0){
                if ( slug.val().search(toSlug(keyword)) == -1 ){
                    $('.keyword-in-slug').html('<span class="m-badge bg-danger small-badge"></span><span>URL không chứa từ khoá SEO, hãy sửa lại url chứa từ khoá SEO</span>');
                    $('.keyword-in-slug').attr('data-score', 0);
                }  else {
                    $('.keyword-in-slug').html('<span class="m-badge bg-success small-badge"></span><span>Từ khoá có trong url. Tuyệt vời !!!</span>');
                    $('.keyword-in-slug').attr('data-score', 5);
                }
            } else {
                $('.keyword-in-slug').html('<span class="m-badge bg-danger small-badge"></span><span>Chưa có slug, vui lòng điền slug</span>');
                $('.keyword-in-slug').attr('data-score', 0);
            }

            //Từ khóa trong tiêu đề
            if (meta_title.val().length > 0) {
                if (meta_title.val().toLowerCase().search(keyword) == -1) {
                    $('.keyword-in-title').html('<span class="m-badge bg-warning small-badge"></span><span>Từ khoá không xuất hiện trong tiêu đề. Hãy kiểm tra lại</span>');
                    $('.keyword-in-title').attr('data-score', 0);

                } else {
                    $('.keyword-in-title').html('<span class="m-badge bg-success small-badge"></span><span>Từ khoá xuất hiện trong tiêu đề. Rất Tốt !!!</span>');
                    $('.keyword-in-title').attr('data-score', 10);
                }
            } else {
                $('.keyword-in-title').html('<span class="m-badge bg-danger small-badge"></span><span>Chưa có tiêu đề, vui lòng điền tiêu đề</span>');
                $('.keyword-in-title').attr('data-score', 0);
            }

            //vị trí từ khóa trong tiêu đề
            if (meta_title.val().length > 0){
                if (meta_title.val().toLowerCase().indexOf(keyword) == 0) {
                    $('.position-keyword-in-title').html('<span class="m-badge bg-success small-badge"></span><span>Cụm từ khoá SEO hoặc từ đồng nghĩa nằm trong tiêu đề. Rất tốt !!!</span>');
                    $('.position-keyword-in-title').attr('data-score', 5);
                } else {
                    $('.position-keyword-in-title').html('<span class="m-badge bg-warning small-badge"></span><span>Vị trí từ khoá SEO nên để phía bên trái, nằm đầu tiên của tiêu đề</span>');
                    $('.position-keyword-in-title').attr('data-score', 0);
                }
            } else {
                $('.position-keyword-in-title').html('<span class="m-badge bg-danger small-badge"></span><span>Chưa có từ khóa hoặc tiêu đề, vui lòng kiểm tra lại</span>');
                $('.position-keyword-in-title').attr('data-score', 0);
            }

            //Vị trí từ khóa trong mô tả
            if (description.val().toLowerCase().replace(/(<([^>]+)>)/gi, "").indexOf(keyword) == 0) {
                $('.position-keyword-in-desc').html('<span class="m-badge bg-success small-badge"></span><span>Từ khoá có vị trí tốt trong mô tả. Tuyệt vời !!!</span>');
                $('.position-keyword-in-desc').attr('data-score', 5);
            } else {
                $('.position-keyword-in-desc').html('<span class="m-badge bg-warning small-badge"></span><span>Từ khoá SEO nên xuất hiện đoạn đầu mô tả. Nên điều chỉnh lại</span>');
                $('.position-keyword-in-desc').attr('data-score', 0);
            }

            //Từ khóa trong tiêu đề phụ
            if (keyword_in_sub_heading == 1){
                $('.keyword-in-heading').html('<span class="m-badge bg-success small-badge"></span><span>Tiêu đề con đã xuất hiện từ khoá. Tuyệt vời !!!</span>');
                $('.keyword-in-heading').attr('data-score', 5);
            } else {
                $('.keyword-in-heading').html('<span class="m-badge bg-danger small-badge"></span><span>Từ khoá SEO nên xuất hiện ở tiêu đề phụ</span>');
                $('.keyword-in-heading').attr('data-score', 0);
            }

            //Đoạn văn có tiêu đề phụ
            if (count_heading == 1) {
                $('.count-heading').html('<span class="m-badge bg-success small-badge"></span><span>Nội dung đầy đủ thẻ H2, H3. Tuyệt vời !!!</span>');
                $('.count-heading').attr('data-score', 10);
            } else {
                $('.count-heading').html('<span class="m-badge bg-danger small-badge"></span><span>Đoạn văn bản không có thẻ H. Hãy dùng ít nhất các thẻ từ H2 đến H6</span>');
                $('.count-heading').attr('data-score', 0);
            }

            //Mật độ từ khóa trong nội dung
            if (keyword_in_content > 3 ) {
                $('.count-keyword-in-content').html('<span class="m-badge bg-success small-badge"></span><span>Cụm từ khoá đã xuất hiện '+keyword_in_content+' lần trong nội dung bài viết. Tuyệt vời !!!</span>');
                $('.count-keyword-in-content').attr('data-score', 10);
            } else {
                $('.count-keyword-in-content').html('<span class="m-badge bg-warning small-badge"></span><span>Từ khoá phải xuất hiện trong nội dung (0,3% ~ 3 từ khoá)</span>');
                $('.count-keyword-in-content').attr('data-score', 0);
            }
        } else {
            $.each($('.data-seo-score > li'), function (key, val) {
                $(val).html('<span class="m-badge bg-danger small-badge"></span><span>Chưa có từ khóa, vui lòng điền từ khóa</span>');
                $(val).attr('data-score', 0);
            });
        }

        //Kiểm tra độ dài của từ khoá > 3 từ
        if (keyword.length > 0 && keyword.length < 3) {
            $('.keyword-length').html('<span class="m-badge bg-warning small-badge"></span><span>Từ khóa này ít hơn 3 chữ, hãy chọn từ khóa có nghĩa hơn</span>');
            $('.keyword-length').attr('data-score', 0);
        } else if (keyword.length >= 3) {
            $('.keyword-length').html('<span class="m-badge bg-success small-badge"></span><span>Kiểm tra độ dài từ khóa: Từ khóa thỏa mãn tiêu chí SEO</span>');
            $('.keyword-length').attr('data-score', 5);
        } else {
            $('.keyword-length').html('<span class="m-badge bg-danger small-badge"></span><span>Chưa có từ khóa, vui lòng điền từ khóa</span>');
            $('.keyword-length').attr('data-score', 0);
        }

        var wordcount = tinymce.get('full-featured').plugins.wordcount;
        $('input[name="word_count"]').val(wordcount.body.getWordCount());
    },
    yoats_seo_score: function (){
        let sum = 0;
        $.each($('.data-seo-score > li'), function (key, val) {
            sum = sum + parseInt($(val).attr('data-score'));
        });
        $('#seo-score').html(sum);
        $('#seo-score').removeClass();
        if (sum  <= 60 ) {
            $('#seo-score').addClass('btn rounded-circle btn-outline-danger');
        } else if (sum >= 61 && sum <= 89) {
            $('#seo-score').addClass('btn rounded-circle btn-outline-warning');
        } else {
            $('#seo-score').addClass('btn rounded-circle btn-outline-success');
        }
        $('input[name="seo_score"]').val(sum);
    },
    init: function () {
        SEO.yoats_seo();
        SEO.yoats_seo_score();
        $('.form-post').change(function () {
            SEO.yoats_seo();
            SEO.yoats_seo_score();
        });
    }
};

$(window).on('load', function () {
    if ($('.form-post').length > 0) {
        SEO.init();
    }

    function htmlEncode(value) {
        return $('<div/>').text(value).html();
    }

    function checkTitle() {
        let title = htmlEncode($("input[name=meta_title]").val());
        $('#title-count').text(title.length);
        if (title.length >= 25 && title.length < 40) {
            $('#title-count-text').removeClass('text-success').removeClass('text-danger').addClass('text-warning');
        } else if (title.length >= 40 && title.length < 66) {
            $('#title-count-text').removeClass('text-danger').removeClass('text-warning').addClass('text-success');
        } else {
            $('#title-count-text').removeClass('text-success').removeClass('text-warning').addClass('text-danger');
        }
        $('#title-count-text').show();
    }

    function checkDescription() {
        let description = htmlEncode($("textarea[name=meta_description]").val());
        $('#description-count').text(description.length);
        if (description.length >= 90 && description.length < 130) {
            $('#description-count-text').removeClass('text-success').removeClass('text-danger').addClass('text-warning');
        } else if (description.length >= 130 && description.length < 175) {
            $('#description-count-text').removeClass('text-danger').removeClass('text-warning').addClass('text-success');
        } else {
            $('#description-count-text').removeClass('text-success').removeClass('text-warning').addClass('text-danger');
        }
        $('#description-count-text').show();
    }

    $("input[name=meta_title]").keyup(function () {
        checkTitle();
    });

    $('textarea[name=meta_description]').keyup(function () {
        checkDescription();
    });

    if ($("input[name=meta_title]").length > 0) {
        checkTitle();
        checkDescription();
    }
});
