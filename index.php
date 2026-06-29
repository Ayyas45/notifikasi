<?php
// Skrip ini menerima ID dari aplikasi dan mengirim notifikasi via OneSignal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $player_id = $_POST['player_id'];

    $fields = array(
        'app_id' => "6c040627-8141-4f67-a2a7-8ae690b626d6",
        'include_player_ids' => array($player_id),
        'contents' => array("en" => "Halo dari aplikasi kamu!")
    );

    $fields = json_encode($fields);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic os_v2_app_nqcamj4bifhwpivhrltjbnrg23vfy6gtisfe5tvmszku5uygut7tesr6hn423byxw2tl43h335juph456rmxjlift3okiki6k7ewmfy'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);
    
    echo "Notifikasi dikirim: " . $response;
}
?>
