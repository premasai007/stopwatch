<?php
$content = file_get_vontents("php://input");
$update =json_decode($content,true);

$chat_id = $update["message"]["chat"]["id"];
$message = $update["message"]["text"];

if($message =="/start" ){
    sendMessage($chat_id,"welcome to your bot!");
}

function sendMessage($chat_id,$message)(
    $apiToken = "8153170729:AAERI16M7HDYObKSFrvlpwhuuWGLUbJGxC8":
    $url = "https://api.telegram.org/bot" . $apiToken . "/sendMessage?chat_id=" . $chat_id . "&text=" . urlencode($message);
    file_get_contents($url);
)
?>
)
