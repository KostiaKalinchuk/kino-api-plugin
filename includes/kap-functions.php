<?php
/*
 * Добавляем новое меню в Админ Консоль
 */

// Хук событие 'admin_menu', запуск функции 'mfp_Add_My_Admin_Link()'
add_action( 'admin_menu', 'kap_Add_My_Admin_Link' );

// Добавляем новую ссылку в меню Админ Консоли
function kap_Add_My_Admin_Link()
{
    add_menu_page(
        'Kino API Plugin Page', // Название страниц (Title)
        'Kino API Plugin', // Текст ссылки в меню
        'manage_options', // Требование к возможности видеть ссылку
        'kino-api-plugin/includes/kap-admin-page.php' // 'slug' - файл отобразится по нажатию на ссылку
    );
}


function category_helper ($categories, $type) {
    $category_array = [];
    foreach ($categories as $category) {
        $category_array[] = $category->$type;
    }

    return $category_array;
}

