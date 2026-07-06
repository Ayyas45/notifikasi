<?php
// === FUNGSI UTAMA ===
function sendNotification($headings, $contents, $filters = null, $player_id = null) {
    $apiKey = "os_v2_app_i4gf6u7lunfmrhozyujdx6ywx52z5qghp6gelg5hw5f3iwvzubgdlsc4nmgp4gzcz6ucorczri2z4upwkxao2r2uastu7tu6c7c2xfi";
    $fields = array(
        'app_id' => "470c5f53-eba3-4ac8-9dd9-c5123bfb16bf",
        'contents' => array("en" => $contents),
        'headings' => array("en" => $headings)
    );

    if ($player_id) {
        $fields['include_player_ids'] = array($player_id);
    } else {
        $fields['filters'] = $filters;
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Bearer ' . $apiKey 
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

// === LOGIKA UTAMA ===
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['player_id'])) {
        // Jika ada player_id, kirim spesifik
        echo "Respon Spesifik: " . sendNotification("Halo!", "Notif dari aplikasi kamu!", null, $_POST['player_id']);
    } else {
        // Jika POST tapi tidak ada player_id
        echo "Error: player_id tidak ditemukan.";
    }
} else {
    // Jika diakses via browser (GET)
    $judul = "Pengumuman";
    $isi = "Besok libur hari raya idul fitri";
    $filter_aktif = array(array("field" => "last_session", "relation" => ">", "value" => "0"));
    
    echo "Respon Server OneSignal: " . sendNotification($judul, $isi, $filter_aktif);
}
?>
