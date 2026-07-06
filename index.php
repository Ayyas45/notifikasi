<?php
// Skrip ini menerima ID dari aplikasi dan mengirim notifikasi via OneSignal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $player_id = $_POST['player_id'];

    $fields = array(
        'app_id' => "470c5f53-eba3-4ac8-9dd9-c5123bfb16bf",
        'include_player_ids' => array($player_id),
        'contents' => array("en" => "Halo dari aplikasi kamu!")
    );

    $fields = json_encode($fields);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic os_v2_app_i4gf6u7lunfmrhozyujdx6ywx52z5qghp6gelg5hw5f3iwvzubgdlsc4nmgp4gzcz6ucorczri2z4upwkxao2r2uastu7tu6c7c2xfi'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    $response = curl_exec($ch);
    curl_close($ch);
    
    echo "Notifikasi dikirim: " . $response;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['player_id'])) {
    echo "Respon Spesifik: " . sendNotification("Halo!", "Notif dari aplikasi kamu!", null, $_POST['player_id']);
} else {
    $judul = "Pengumuman";
    $isi = "Besok libur hari raya idul fitri";
    // Filter untuk memastikan notifikasi terkirim ke perangkat yang aktif
    $filter_aktif = array(array("field" => "last_session", "relation" => ">", "value" => "0"));
    
    echo "Respon Server OneSignal: " . sendNotification($judul, $isi, $filter_aktif);
}
?>
