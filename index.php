<?php
// === FUNGSI UTAMA ===
function sendNotification($headings, $contents, $filters = null, $player_id = null) {
    // API Key dan App ID dari input Anda
    $apiKey = "os_v2_app_i4gf6u7lunfmrhozyujdx6ywx52z5qghp6gelg5hw5f3iwvzubgdlsc4nmgp4gzcz6ucorczri2z4upwkxao2r2uastu7tu6c7c2xfi";
    $appId = "470c5f53-eba3-4ac8-9dd9-c5123bfb16bf";
    
    $fields = array(
        'app_id' => $appId,
        'contents' => array("en" => $contents),
        'headings' => array("en" => $headings)
    );

    if ($player_id) {
        $fields['include_player_ids'] = array($player_id);
    } else {
        $fields['filters'] = $filters;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v2/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Bearer ' . $apiKey // Menggunakan skema Bearer
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    
    $response = curl_exec($ch);
    $error = curl_error($ch);
    curl_close($ch);

    return $error ? "cURL Error: " . $error : $response;
}

// === LOGIKA PEMICU ===
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
