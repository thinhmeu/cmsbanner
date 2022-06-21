<?php
$con_old = mysqli_connect("172.104.161.49", "doithuongo30e0", "931099b41bcba857", "doithuongthecao");
$con_new = mysqli_connect("172.104.178.94", "doithuongthecao", "doithuongthecao", "doithuongthecao");

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

$get_id = isset($_GET['id']) ? $_GET['id'] : 0;
$post = mysqli_query($con_new, 'SELECT id, slug FROM post where id > '.$get_id.' ORDER BY id ASC LIMIT 1');
$row = $post->fetch_assoc();
$id = $row['id'];
$slug = $row['slug'];
$count_vote = mysqli_query($con_old, 'select * from wp_postmeta where post_id='.$id.' AND meta_key="_kksr_casts"');
if ($count_vote->num_rows) {
    $row = $count_vote->fetch_assoc();
    $count_vote = $row['meta_value'];
} else {
    $count_vote = 0;
}
$count_star = mysqli_query($con_old, 'select * from wp_postmeta where post_id='.$id.' AND meta_key="_kksr_ratings"');
if ($count_star->num_rows) {
    $row = $count_star->fetch_assoc();
    $count_star = $row['meta_value'];
} else {
    $count_star = 0;
}
$avg = mysqli_query($con_old, 'select * from wp_postmeta where post_id='.$id.' AND meta_key="_kksr_avg"');
if ($avg->num_rows) {
    $row = $avg->fetch_assoc();
    $avg = $row['meta_value'];
} else {
    $avg = 0;
}
if ($count_vote) {
    $query_update = 'INSERT INTO rating(url, count, avg_rating, sum_rating) VALUES("'.$slug.'", "'.$count_vote.'", "'.$avg.'", "'.$count_star.'")';
    mysqli_query($con_new, $query_update);
}
header("Refresh:0; url=/convert_rating.php?id=".$id);
?>
