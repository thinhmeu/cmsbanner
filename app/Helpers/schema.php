<?php
function getSchemaBreadCrumb($breadCrumb){
    $itemListElement = [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Trang chủ',
            'item' => 'https://doithuongthecao.com'
        ]
    ];
    $i=2;
    if (!empty($breadCrumb)) foreach ($breadCrumb as $key => $item) {
        if ($item['schema']) {
            $itemListElement[] = [
                '@type' => 'ListItem',
                'position' => $i,
                'name' => $item['name'],
                'item' => str_replace('/amp', '', $item['item'])
            ];
            $i++;
        }
    }
    $schema = '<script type="application/ld+json">';
    $schema .= json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $itemListElement
    ]);
    $schema .= '</script>';
    return $schema;
}
?>
