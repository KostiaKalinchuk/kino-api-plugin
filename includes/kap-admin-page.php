<?php

$path = $_SERVER['DOCUMENT_ROOT'] . '/wp-content/plugins/kino-api-plugin/kino-api-plugin.php';
$data = get_plugin_data($path);
$message = get_option('no_login_message');
$page = get_option('my_page');
$selected = get_option('my_select');

$kinoid_checkbox = get_option('my_kinoid');
$kinoid = get_option('my_films_id');

//echo $kinoid_checkbox;
//echo $kinoid;

if ($selected == 'movies') {
    $select1 = 'selected';
} elseif ($selected == 'tv-series') {
    $select2 = 'selected';
} elseif ($selected == 'anime-tv-series') {
    $select3 = 'selected';
} elseif ($selected == 'animes') {
    $select4 = 'selected';
}

//echo $selected;
//var_dump($data);
?>

<div class="kap-wrapper-all">
    <div class="kap-wrapper">
        <h2 class="kap_plugin_header"><?php echo $data['Name'] . ' v' . $data["Version"] ?></h2>

        <div class="kap-fields">
            <label for="films">Количество</label>
            <input type="text" class="my_message" id="films" value="<?php echo $message; ?>">
        </div>

        <div class="kap-fields">
            <label for="page">Страница</label>
            <input type="text" class="my_page" id="page" value="<?php echo $page; ?>">
        </div>

        <div class="kap-fields">
            <label for="select">Категория</label>
            <select id="select">
                <option disabled>Выберите категорию</option>
                <option <?php echo $select1; ?> value="movies">Фильми</option>
                <option <?php echo $select2; ?> value="tv-series">Сериали</option>
                <option <?php echo $select3; ?> value="anime-tv-series">Аниме сериали</option>
                <option <?php echo $select4; ?> value="animes">Аниме</option>
            </select>
        </div>

        <div class="kap-fields checkbox-field">
            <label for="kinoid">Скачать по ID</label>
            <input type="checkbox" id="kinoid" name="kinoid" value="true">
        </div>

        <div class="kap-fields">
            <label for="films">ID кинопоиска</label>
            <input type="text" class="my_films_id" id="films-id" value="<?php echo $kinoid; ?>">
        </div>

        <button id="kap_plugin_save_button" class="button">Сохранить</button>

    </div>


    <button class="video-api button">Загрузить видео</button>

</div>

<progress id="progressbar" value="0" max="100"></progress>
