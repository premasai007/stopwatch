<?php
// Replace with your Telegram Bot Token
$token = "8153170729:AAERI16M7HDYObKSFrvlpwhuuWGLUbJGxC8";
$website = "https://api.telegram.org/bot" . $token;

// Store the current stopwatch status in a file
$stopwatchFile = "stopwatch.json";

// Read incoming updates from Telegram
$update = file_get_contents("php://input");
$updateArray = json_decode($update, TRUE);

// Get the message from Telegram
$message = $updateArray['message'];
$chatId = $message['chat']['id'];
$text = $message['text']; // The text message sent to the bot

// Function to send messages
function sendMessage($chatId, $message) {
    global $website;
    $url = $website . "/sendMessage?chat_id=" . $chatId . "&text=" . urlencode($message);
    file_get_contents($url);
}

// Function to read stopwatch data (stored as JSON)
function readStopwatchData() {
    global $stopwatchFile;
    if (file_exists($stopwatchFile)) {
        return json_decode(file_get_contents($stopwatchFile), true);
    } else {
        return [
            'running' => false,
            'start_time' => 0,
            'elapsed' => 0
        ];
    }
}

// Function to write stopwatch data (store as JSON)
function writeStopwatchData($data) {
    global $stopwatchFile;
    file_put_contents($stopwatchFile, json_encode($data));
}

// Start/stop the stopwatch
if ($text == "/startstop") {
    $data = readStopwatchData();
    if ($data['running']) {
        // Stop the stopwatch and calculate the elapsed time
        $data['elapsed'] += time() - $data['start_time'];
        $data['running'] = false;
        writeStopwatchData($data);
        $elapsedTime = gmdate("H:i:s", $data['elapsed']);
        sendMessage($chatId, "Stopwatch stopped! Elapsed time: $elapsedTime");
    } else {
        // Start the stopwatch
        $data['running'] = true;
        $data['start_time'] = time();
        $data['elapsed'] = isset($data['elapsed']) ? $data['elapsed'] : 0;
        writeStopwatchData($data);
        sendMessage($chatId, "Stopwatch started!");
    }
}

// Reset the stopwatch
elseif ($text == "/reset") {
    $data = [
        'running' => false,
        'start_time' => 0,
        'elapsed' => 0
    ];
    writeStopwatchData($data);
    sendMessage($chatId, "Stopwatch reset!");
}

// Display the current stopwatch time
elseif ($text == "/time") {
    $data = readStopwatchData();
    if ($data['running']) {
        $elapsedTime = gmdate("H:i:s", $data['elapsed'] + (time() - $data['start_time']));
        sendMessage($chatId, "Stopwatch running! Elapsed time: $elapsedTime");
    } else {
        $elapsedTime = gmdate("H:i:s", $data['elapsed']);
        sendMessage($chatId, "Stopwatch is stopped! Elapsed time: $elapsedTime");
    }
}

// Default reply if no command is recognized
else {
    sendMessage($chatId, "Commands available:\n/startstop - Start/Stop the stopwatch\n/reset - Reset the stopwatch\n/time - Show current time");
}

?>
