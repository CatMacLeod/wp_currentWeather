<?php
/* Template Name: Current Weather */

get_header(); ?>

<div class="wrap">

<?php
//OpenWeather API Key
$api_key = 'YOUR_API_KEY';

$url = 'http://api.openweathermap.org/data/2.5/weather?q=Vancouver,CA&units=metric&APPID='.$api_key;

try {
    $json = file_get_contents($url);
    $data = json_decode($json);
    $city = $data->name;
    $country = $data->sys->country;
    $temperature = $data->main->temp;
    $conditions = $data->weather[0]->description;
    $icon = $data->weather[0]->icon;

    echo '<h1>Current Weather in Vancouver,CA</h1>';
    echo 'City: <b>'.$city.', '.$country.'</b><br>';
    echo 'Temperature: <b>'.$temperature.'&deg;C</b><br>';
    // https://openweathermap.org/weather-conditions
    echo 'Weather Conditions: <b>'.$conditions.'</b><br><img src="http://openweathermap.org/img/w/'.$icon.'.png" alt="Weather Conditions" /><br>';
    
    global $current_user; //wp
    get_currentuserinfo(); //wp who's loged in
    $wpdb->insert( 
        'weather', 
        array( 
            'username' => $current_user->display_name,
            'visittime' => date("Y-m-d H:i:s"), /* the MySQL DATETIME format */
            'json' => $json,
            'city' => $city,
            'country' => $country,
            'temperature' => $temperature,
            'conditions' => $conditions,
            'icon' => $icon
        )
    );
} catch (Exception $e) {
    echo 'Failed to retrieve data. Error: '.$e->getMessage();
    exit();
}

?>

<h5><b>This example uses an API Key provided by openweathermap.org to 
    fetch the current weather conditions in Vancouver, BC. 
    The JSON string recieved from the data provider is decoded in the PHP 
    application to display the current weather with icon.</b></h5>

</div><!-- .wrap -->

<?php get_footer(); ?>