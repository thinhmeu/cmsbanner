<?php
die();
$con_old = mysqli_connect("172.104.161.49", "doithuongo30e0", "931099b41bcba857", "doithuongthecao");
$con_new = mysqli_connect("172.104.178.94", "doithuongthecao", "doithuongthecao", "doithuongthecao");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

$list_post_old = mysqli_query($con_old, "SELECT * FROM wp_posts where post_status='publish' AND post_type='post'");
while ($row = $list_post_old->fetch_assoc()) {
    $id = $row['ID'];
    $check_exist = mysqli_query($con_new, 'SELECT id FROM post_copy where id = '.$id.' LIMIT 1');
    if ($check_exist->num_rows > 0) continue;
    echo $id.'<br>';
    $title = $row['post_title'];
    $slug = $row['post_name'];
    $description = '';
    $content = $row['post_content'];
//    $content = str_replace('https://doithuongthecao.com/wp-content/uploads', '/upload', $content);
//    $content = str_replace('https://i0.wp.com/doithuongthecao.com/wp-content/uploads', '/upload', $content);
//    $content = '';
    $meta_keyword = '';
    $created_time = $row['post_date'];
    $updated_time = $row['post_modified'];
    $displayed_time = $row['post_date'];
    $user_id = 3;
    $status = 1;
    $is_index = 1;
    #
    $post_meta = mysqli_query($con_old, 'select * from wp_postmeta where post_id='.$id.' AND meta_key="rank_math_primary_category"');
    if ($post_meta->num_rows > 0) {
        $category = $post_meta->fetch_assoc();
        $category_id = $category['meta_value'];
    } else {
        $category_id = 0;
    }
    #
    $post_meta = mysqli_query($con_old, 'select * from wp_postmeta where post_id='.$id.' AND meta_key="rank_math_description"');
    if ($post_meta->num_rows > 0) {
        $meta_description = $post_meta->fetch_assoc();
        $meta_description = $meta_description['meta_value'];
    } else {
        $meta_description = mb_substr(strip_tags($content), 0, 155);
    }
    $content = '';
    #
    $post_meta = mysqli_query($con_old, 'select * from wp_postmeta where post_id='.$id.' AND meta_key="_thumbnail_id"');
    $thumbnail = '';
    if ($post_meta->num_rows > 0) {
        $thumbnail_id = $post_meta->fetch_assoc();
        $thumbnail_id = $thumbnail_id['meta_value'];
        $thumbnail_item = mysqli_query($con_old, 'select * from wp_posts where ID='.$thumbnail_id);
        if ($thumbnail_item) {
            $thumbnail_item = $thumbnail_item->fetch_assoc();
            $thumbnail = $thumbnail_item['guid'];
            $thumbnail = str_replace('https://doithuongthecao.com/wp-content/uploads', '/upload', $thumbnail);
        }
    }
    #
    $post_meta = mysqli_query($con_old, 'select * from wp_postmeta where post_id='.$id.' AND meta_key="rank_math_title"');
    if ($post_meta->num_rows > 0) {
        $meta_title = $post_meta->fetch_assoc();
        $meta_title = $meta_title['meta_value'];
    } else {
        $meta_title = $title;
    }
    $query_insert = 'INSERT INTO post_copy(id, title, meta_title, slug, description, meta_description, content, thumbnail, meta_keyword, created_time, updated_time, displayed_time, category_id, user_id, is_index, status) VALUES ("'.$id.'", "'.addslashes($title).'", "'.addslashes($meta_title).'", "'.$slug.'", "'.addslashes($description).'", "'.addslashes($meta_description).'", "'.addslashes($content).'", "'.$thumbnail.'", "'.$meta_keyword.'", "'.$created_time.'", "'.$updated_time.'", "'.$displayed_time.'", "'.$category_id.'", "'.$user_id.'", "'.$is_index.'", "'.$status.'")';
    if (mysqli_query($con_new, $query_insert)) {
        $query_insert_post_category = 'INSERT INTO post_category_copy(post_id, category_id, is_primary) VALUES("'.$id.'", "'.$category_id.'", "1")';
        mysqli_query($con_new, $query_insert_post_category);
    } else {
        echo 'ERROR - '.$id.'<br>';
    }
}
?>
