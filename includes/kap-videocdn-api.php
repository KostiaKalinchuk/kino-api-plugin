<?php


add_action( 'videocdn_action', 'kap_videocdn_init' );

function kap_videocdn_init()
{

    $limit = get_option('no_login_message');
    $number_page = get_option('my_page');
    $select_category = get_option('my_select');

    $kinoid_checkbox = get_option('my_kinoid');
    $kinoid = get_option('my_films_id');

    if ($select_category == 'movies') {
        $post_type = 'films';
        $original_name = 'film_original_name';
        $movie_player = 'film_movie_player';
    } elseif ($select_category == 'tv-series') {
        $post_type = 'tv_series';
        $original_name = 'series_original_name';
        $movie_player = 'series_movie_player';
    } elseif ($select_category == 'anime-tv-series' || $select_category == 'animes') {
        $post_type = 'anime';
        $original_name = 'anime_original_name';
        $movie_player = 'anime_movie_player';
    }

    if ($kinoid_checkbox == 'true') {

        $json_url = "https://videocdn.tv/api/short?api_token=HtYOt6tf2l5neWUE6sxpZon1XK1P5nJ0&kinopoisk_id=" . $kinoid;
        $json = file_get_contents($json_url);

        $data = json_decode($json);

        foreach ($data->data as $video_data) {

            if (empty($video_data->kp_id)) {
                continue;
            }

            $post_data = array(
                'post_type' => $post_type,
                'post_title' => $video_data->title,
                'post_status' => 'draft',
                'comment_status' => 'open',
                'ping_status' => 'open'
            );

            $post_id = wp_insert_post($post_data, true);

            update_post_meta($post_id, $original_name, $video_data->orig_title);
            update_post_meta($post_id, $movie_player, '<iframe src="'. $video_data->iframe_src .'" width="640" height="480" frameborder="0" allowfullscreen></iframe>');
            update_post_meta($post_id, 'kinopoisk_id', $video_data->kp_id);

        }

    } else {

        $json_url = "http://videocdn.tv/api/" . $select_category . "?api_token=HtYOt6tf2l5neWUE6sxpZon1XK1P5nJ0&limit=" . $limit . "&page=" . $number_page;

        $json = file_get_contents($json_url);

        $data = json_decode($json);

        foreach ($data->data as $video_data) {

            if (empty($video_data->kinopoisk_id)) {
                continue;
            }

            $args = array(
                'post_type' => $post_type,
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key' => 'kinopoisk_id',
                        'value' => $video_data->kinopoisk_id,
                    )
                )
            );
            $query = new WP_Query($args);

            $kino_id = [];
            while ($query->have_posts()) {
                $query->the_post();

                $post_id = get_the_ID();
                $kino_id[] = get_field('kinopoisk_id', $post_id);

            }

            if (count($kino_id) == 1) {
                continue;
            }

            $post_data = array(
                'post_type' => $post_type,
                'post_title' => $video_data->ru_title,
                'post_status' => 'draft',
                'comment_status' => 'open',
                'ping_status' => 'open'
            );

            $post_id = wp_insert_post($post_data, true);

            update_post_meta($post_id, $original_name, $video_data->orig_title);
            update_post_meta($post_id, $movie_player, $video_data->iframe);
            update_post_meta($post_id, 'kinopoisk_id', $video_data->kinopoisk_id);

        }
    }
//    var_dump(count($kino_id));
    echo 'videocdn';

}





