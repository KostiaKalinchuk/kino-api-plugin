<?php

// Основная инфа

add_action( 'kinopoisk_action', 'kap_kinopoisk_init' );

function kap_kinopoisk_init()
{

    $select_category = get_option('my_select');

    if ($select_category == 'movies') {
        $post_type = 'films';
        $prefix_fields = 'film';
    } elseif ($select_category == 'tv-series') {
        $post_type = 'tv_series';
        $prefix_fields = 'series';
    } elseif ($select_category == 'anime-tv-series' || $select_category == 'animes') {
        $post_type = 'anime';
        $prefix_fields = 'anime';
    }

    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'post_status' => 'draft'
    );

    $query = new WP_Query($args);

    while ($query->have_posts()) {
        $query->the_post();

        $post_id = get_the_ID();
        $kinopoisk_id = get_field('kinopoisk_id');

        $headers = array(
            "accept: application/json",
            "X-API-KEY: 9b71c565-1860-4ef7-a229-e461d0450fed"
        );

        $ch = curl_init('https://kinopoiskapiunofficial.tech/api/v2.1/films/'.$kinopoisk_id.'?append_to_response=EXTERNAL_ID');

        curl_setopt($ch, CURLOPT_COOKIEFILE, __DIR__ . '/cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEJAR, __DIR__ . '/cookie.txt');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, true);

        $html = curl_exec($ch);

        curl_close($ch);

        $dom = strstr($html, '{');

        $data_film = json_decode($dom);

        $countries = $data_film->data->countries;
        $countries_array = category_helper($countries, 'country');

        $genres = $data_film->data->genres;
        $genres_array = category_helper($genres, 'genre');

        $year = $data_film->data->year;

        // Подвязка категории
        wp_set_object_terms( $post_id, $countries_array, $post_type . '-country' );
        wp_set_object_terms( $post_id, $genres_array, $post_type . '-categories' );
        wp_set_object_terms( $post_id, substr($year, 0, 4), $post_type . '-years' );


         // Загрузить картинку
        $image = media_sideload_image($data_film->data->posterUrl, $post_id, null, 'id');
        update_field( $prefix_fields . '_image', $image, $post_id );


         // Описание и продолжительность
         update_post_meta($post_id, $prefix_fields . '_desc', $data_film->data->description);
         update_post_meta($post_id, $prefix_fields . '_duration', $data_film->data->filmLength);

        //Установить качество по умолчанию
        update_post_meta($post_id, $prefix_fields . '_quality', 'HDRip');

    }

    echo 'kinopoisk';

}



//Актери

add_action( 'kinopoisk_staff', 'kap_kinopoisk_staff' );

function kap_kinopoisk_staff()
{

    $select_category = get_option('my_select');

    if ($select_category == 'movies') {
        $post_type = 'films';
        $prefix_fields = 'film';
    } elseif ($select_category == 'tv-series') {
        $post_type = 'tv_series';
        $prefix_fields = 'series';
    } elseif ($select_category == 'anime-tv-series' || $select_category == 'animes') {
        $post_type = 'anime';
        $prefix_fields = 'anime';
    }

    $args = array(
        'post_type' => $post_type,
        'posts_per_page' => -1,
        'post_status' => 'draft'
    );

    $query = new WP_Query($args);

    while ($query->have_posts()) {
        $query->the_post();

        $post_id = get_the_ID();
        $kinopoisk_id = get_field('kinopoisk_id');

        $headers = array(
            "accept: application/json",
            "X-API-KEY: 9b71c565-1860-4ef7-a229-e461d0450fed"
        );

        $ch = curl_init('https://kinopoiskapiunofficial.tech/api/v1/staff?filmId='.$kinopoisk_id);

        curl_setopt($ch, CURLOPT_COOKIEFILE, __DIR__ . '/cookie.txt');
        curl_setopt($ch, CURLOPT_COOKIEJAR, __DIR__ . '/cookie.txt');
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, true);

        $html = curl_exec($ch);

        curl_close($ch);

        $dom = strstr($html, '[');

        $data_film = json_decode($dom);

        $actors = [];
        $directors = [];
        foreach ($data_film as $staff) {
            if ($staff->professionKey == 'ACTOR') {
                $actors[] = $staff->nameRu;
            } elseif ($staff->professionKey == 'DIRECTOR') {
                $directors[] = $staff->nameRu;
            }
        }

        $actors = array_slice($actors, 0, 15);
        $directors = array_slice($directors, 0, 5);

        // Актери и режисер
        update_post_meta($post_id, $prefix_fields . '_producer', implode(", ", $directors));
        update_post_meta($post_id, $prefix_fields . '_cast', implode(", ", $actors));

        // Опубликовать пост
        $post_publish = array(
            'ID' => $post_id,
            'post_status' => 'publish'
        );

        wp_update_post(wp_slash($post_publish));

    }

    echo 'staff';

}



