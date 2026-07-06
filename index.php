<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

function sendNotification($title, $message, $playerId = null)
{
    $appId = "470c5f53-eba3-4ac8-9dd9-c5123bfb16bf";
    $apiKey = "os_v2_app_i4gf6u7lunfmrhozyujdx6ywx52z5qghp6gelg5hw5f3iwvzubgdlsc4nmgp4gzcz6ucorczri2z4upwkxao2r2uastu7tu6c7c2xfi";

    $fields = [
        "app_id" => $appId,
        "headings" => [
            "en" => $title
        ],
        "contents" => [
            "en" => $message
        ]
    ];

    if (!empty($playerId)) {
        $fields["include_player_ids"] = [$playerId];
    } else {
        $fields["filters"] = [
            [
                "field" => "last_session",
                "relation" => ">",
                "value" => "0"
            ]
        ];
    }

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json; charset=utf-8",
        "Authorization: Bearer " . $apiKey
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (curl_errno($ch)) {
        $response = "cURL Error: " . curl_error($ch);
    }

    curl_close($ch);

    return [
        "http_code" => $httpCode,
        "response" => $response
    ];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!empty($_POST['player_id'])) {

        $result = sendNotification(
            "Halo!",
            "Notif khusus untuk kamu",
            $_POST['player_id']
        );

    } else {

        $result = sendNotification(
            "Pengumuman",
            "Besok libur hari raya idul fitri"
        );

    }

    echo "<pre>";
    echo "HTTP CODE : " . $result['http_code'] . PHP_EOL;
    echo "RESPONSE :" . PHP_EOL;
    echo $result['response'];
    echo "</pre>";

} else {

    echo "Server OneSignal siap.";

}
