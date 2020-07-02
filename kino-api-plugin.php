<?php
/*
Plugin Name: Kino API Plugin
Description: Kino API Plugin
Author: Kostia Kalinchuk
Version: 1.0
*/

//Подключаеем файли с ярда WordPress
require($_SERVER['DOCUMENT_ROOT'].'/wp-load.php');
require($_SERVER['DOCUMENT_ROOT'].'/wp-admin/includes/admin.php');

// Подключаем kap-functions.php, используя require_once, чтобы остановить скрипт, если kap-functions.php не найден
require_once plugin_dir_path(__FILE__) . 'includes/kap-functions.php';
require_once plugin_dir_path(__FILE__) . 'includes/kap-videocdn-api.php';
require_once plugin_dir_path(__FILE__) . 'includes/kap-kinopoisk-api.php';

//Подключение стилей и скриптов
add_action( 'admin_enqueue_scripts', function(){
    wp_enqueue_style('main-css', plugins_url('css/kap-main.css', __FILE__));
    wp_enqueue_script('main-js', plugins_url('js/kap-scripts.js', __FILE__));
}, 99 );


//Добавить пункт настройки на странице плагинов
add_filter( 'plugin_action_links', 'settings_link', 10, 2 );

function settings_link( $actions, $plugin_name ){
    if( false === strpos( $plugin_name, basename(__FILE__) ) )
        return $actions;

    $settings_link = '<a href="options-general.php?page='. basename(dirname(__FILE__)).'/includes/kap-admin-page.php' .'">'.__("Settings").'</a>';
    array_unshift( $actions, $settings_link );
    return $actions;

}

// Сохранить настройки
add_action('wp_ajax_save_settings', 'my_plugin_save_settings');

function my_plugin_save_settings() {

    $value = sanitize_text_field($_POST['my_message']);
    $my_page = sanitize_text_field($_POST['my_page']);
    $my_select = sanitize_text_field($_POST['my_select']);
    $kinoid = sanitize_text_field($_POST['kinoid']);
    $my_films_id = sanitize_text_field($_POST['my_films_id']);


    $up = update_option( 'no_login_message', $value , false);
    $up_page = update_option( 'my_page', $my_page , false);
    $up_select = update_option( 'my_select', $my_select , false);
    $up_kinoid = update_option( 'my_kinoid', $kinoid , false);
    $up_films_id = update_option( 'my_films_id', $my_films_id , false);


    echo ($up || $up_page || $up_select || $up_kinoid || $up_films_id) ? 1: 0;
    wp_die();

}

// Ajax
add_action('wp_ajax_start_videocdn', 'my_start_videocdn');

function my_start_videocdn() {

    $start = $_POST['start'];

    if ($start = 'start') {
        do_action( 'videocdn_action' );
    }

    wp_die();

}

add_action('wp_ajax_start_videocdn2', 'my_start_videocdn2');

function my_start_videocdn2() {

    $start = $_POST['start2'];

    if ($start = 'start2') {
        do_action( 'kinopoisk_action' );
    }

    wp_die();

}

add_action('wp_ajax_start_videocdn3', 'my_start_videocdn3');

function my_start_videocdn3() {

    $start = $_POST['start3'];

    if ($start = 'start3') {
        do_action( 'kinopoisk_staff' );
    }

    wp_die();

}


