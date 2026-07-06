<?php
// === FUNGSI UTAMA (RESEP) ===
function sendNotification($headings, $contents, $filters = null, $player_id = null) {
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
        'Authorization: Basic os_v2_app_i4gf6u7lunfmrhozyujdx6ywx6gnc4cutslevnnqnpr3hh7flji2mox5t3enr5hleze3ofopsjizsvvgavepldd737womgedx36qg4q'
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

// === LOGIKA PEMICU (PROGRAM PENGIRIM) ===

// 1. Jika Aplikasi mengirim data POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['player_id'])) {
    echo "Respon Spesifik: " . sendNotification("Halo!", "Notif dari aplikasi kamu!", null, $_POST['player_id']);
} 
// 2. Jika diakses via Browser (Kirim Massal/Broadcast untuk Dosen)
else {
    $judul = "Pengumuman";
    $isi = "Besok libur hari raya idul fitri";
    
    $filter_aktif = array(array("field" => "last_session", "relation" => ">", "value" => "0"));
    
    $hasil = sendNotification($judul, $isi, $filter_aktif);
    echo "Respon Server OneSignal: " . $hasil;
}
?>