<?php
// Cek apakah form dikirim melalui POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Mengambil data dengan aman
    $kecamatan = $_POST['kecamatan'] ?? '';
    $luas = $_POST['luas'] ?? 0;
    $jumlah_penduduk = $_POST['jumlah_penduduk'] ?? 0;
    $longitude = $_POST['longitude'] ?? 0;
    $latitude = $_POST['latitude'] ?? 0;

    // Validasi input (bisa ditambahkan di sini jika perlu)

    // Sesuaikan dengan setting MySQL
    $servername = "127.0.0.1:8111";
    $username = "root";
    $password = ""; 
    $dbname = "penduduk_db"; 

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Query untuk memasukkan data
    $sql = "INSERT INTO pendudukk (kecamatan, luas, jumlah_penduduk, longitude, latitude)
    VALUES ('$kecamatan', $luas, $jumlah_penduduk, $longitude, $latitude)";

    // Eksekusi query dan cek hasil
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
        // Arahkan kembali ke index.html jika ingin
        header("Location: index.html");
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Menutup koneksi
    $conn->close();
} else {
    echo "Invalid request method.";
}
