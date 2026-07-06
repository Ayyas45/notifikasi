<?php

// ===============================
// Konfigurasi OneSignal
// ===============================
define("APP_ID", "6c040627-8141-4f67-a2a7-8ae690b626d6");

define("REST_API_KEY", "os_v2_app_nqcamj4bifhwpivhrltjbnrg2zb65m42d3aezleerzhjotvrpz6n4g5whohwvcmlfwi2ck56trr3wsjvcciuq4kdwaayhhocht5sypq");


// ===============================
// Fungsi umum kirim ke OneSignal
// ===============================
function sendNotification($fields)
{
    $fields = json_encode($fields);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Content-Type: application/json; charset=utf-8",
        "Authorization: Key " . REST_API_KEY
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);

    curl_close($ch);

    return $response;
}


// ===============================
// Kirim ke 1 Player ID
// ===============================
function sendNotificationToPlayer($playerId, $title, $message)
{
    $fields = array(
        "app_id" => APP_ID,
        "include_player_ids" => array($playerId),
        "headings" => array(
            "en" => $title
        ),
        "contents" => array(
            "en" => $message
        )
    );

    return sendNotification($fields);
}


// ===============================
// Kirim ke semua perangkat
// ===============================
function sendNotificationToAll($title, $message)
{
    $fields = array(
        "app_id" => APP_ID,
        "filters" => array(
            array(
                "field" => "last_session",
                "relation" => ">",
                "value" => "0"
            )
        ),
        "headings" => array(
            "en" => $title
        ),
        "contents" => array(
            "en" => $message
        )
    );

    return sendNotification($fields);
}



// =======================================
// Contoh Penggunaan
// =======================================

// 1. Kirim ke semua user
echo sendNotificationToAll(
    "Masuk Nihh BOSSS! 📢",
    "Notifikasi ini dikirim otomatis dari program PHP."
);


// 2. Kirim ke satu Player ID
/*
$playerId = "ISI_PLAYER_ID";

echo sendNotificationToPlayer(
    $playerId,
    "Halo",
    "Ini notif khusus untuk kamu."
);
*/

?>
