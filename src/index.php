<?php
require_once "TweetHook.php";
header('Content-Type:application/json');

$hook = new TweetHook('../Anime-Quotes-ID.sqlite');

if (isset($_POST['tweet'])) {
    $tweet = $_POST['tweet'];
    if ($hook->model1($tweet)) {
        echo $hook->update();
    }
    elseif ($hook->model2($tweet)) {
        echo $hook->update();
    }
    elseif ($hook->model3($tweet)) {
        echo $hook->update();
    }
    else {
        echo json_encode(array('msg' => 'nothing to update!'));
    }
} else {
    echo json_encode(array('msg' => 'listen for request!', 'total' => $hook->count()));
}
    