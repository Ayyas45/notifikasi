<?php
// --- SETUP PORT RAILWAY ---
if (php_sapi_name() == 'cli-server') {
    $port = isset($_ENV['PORT']) ? $_ENV['PORT'] : 8080;
}

$status = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $appId = "470c5f53-eba3-4ac8-9dd9-c5123bfb16bf";
    $apiKey = "os_v2_app_i4gf6u7lunfmrhozyujdx6ywx52z5qghp6gelg5hw5f3iwvzubgdlsc4nmgp4gzcz6ucorczri2z4upwkxao2r2uastu7tu6c7c2xfi";

    $title = $_POST['judul'];
    $message = $_POST['pesan'];

    $fields = [
        "app_id" => $appId,
        "headings" => [
            "en" => $title
        ],
        "contents" => [
            "en" => $message
        ],
        "included_segments" => ["All Users"]
    ];

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json; charset=utf-8",
        "Authorization: Key " . $apiKey // Menggunakan 'Key ' untuk v2 API Key
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    $response = curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if($http == 200 || $http == 201 || $http == 202){
        $status = "<span style='color:green'>Status : Pesan Berhasil dikirim!</span>";
    } else {
        $status = "<span style='color:red'>Error (Code $http): ".$response."</span>";
    }

} else {
    $status = "<span style='color:green'>Status : OneSignal Siap</span>";
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Push Notif</title>

<style>

body{
    margin:0;
    font-family:Arial;
    background:#eef3fb;
}

.box{
    width:550px;
    margin:50px auto;
    background:white;
    border-radius:12px;
    padding:30px;
    box-shadow:0 0 20px rgba(0,0,0,.15);
}

h1{
    text-align:center;
}

.status{
    text-align:center;
    font-weight:bold;
    margin-bottom:25px;
}

label{
    font-weight:bold;
    display:block;
    margin-top:15px;
    margin-bottom:8px;
}

input,textarea{
    width:100%;
    padding:12px;
    border:1px solid #ccc;
    border-radius:6px;
    box-sizing:border-box;
    font-size:15px;
}

textarea{
    height:120px;
}

button{
    margin-top:20px;
    width:100%;
    padding:15px;
    border:none;
    background:#1485ff;
    color:white;
    font-size:18px;
    border-radius:6px;
    cursor:pointer;
}

button:hover{
    background:#006fe6;
}

</style>

</head>

<body>

<div class="box">

<h1>Sistem Push Notif Cerdas</h1>

<div class="status">
<?=$status;?>
</div>

<form method="post">

<label>Judul Notifikasi</label>

<input
type="text"
name="judul"
placeholder="Contoh : Pengumuman"
required>

<label>Isi Pesan</label>

<textarea
name="pesan"
placeholder="Tulis isi notifikasi..."
required></textarea>

<button type="submit">
🚀 Tembak Notifikasi!
</button>

</form>

</div>

</body>

</html>
