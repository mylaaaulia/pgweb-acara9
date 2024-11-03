<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Edit Data Penduduk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Data Penduduk</h2>

        <?php
        // Konfigurasi MySQL
        $servername = "127.0.0.1:8111";
        $username = "root";
        $password = "";
        $dbname = "penduduk_db";

        // Membuat koneksi
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Cek koneksi
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Cek apakah ada data yang dikirimkan dari form
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $kecamatan = $conn->real_escape_string($_POST["kecamatan"]);
            $longitude = $conn->real_escape_string($_POST["longitude"]);
            $latitude = $conn->real_escape_string($_POST["latitude"]);
            $luas = $conn->real_escape_string($_POST["luas"]);
            $jumlah_penduduk = $conn->real_escape_string($_POST["jumlah_penduduk"]);

            // Update data ke dalam tabel
            $sql = "UPDATE pendudukk SET longitude='$longitude', latitude='$latitude', luas='$luas', jumlah_penduduk='$jumlah_penduduk' WHERE kecamatan='$kecamatan'";

            if ($conn->query($sql) === TRUE) {
                echo "<div class='alert alert-success'>Data berhasil diperbarui.</div>";
                echo "<a href='index.php' class='btn btn-primary'>Kembali ke Halaman Utama</a>";
                exit;
            } else {
                echo "Error updating record: " . $conn->error;
            }
        }

        // Ambil data yang akan diedit berdasarkan parameter kecamatan
        if (isset($_GET['kecamatan'])) {
            $kecamatan = $conn->real_escape_string($_GET['kecamatan']);
            $sql = "SELECT * FROM pendudukk WHERE kecamatan='$kecamatan'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
            } else {
                echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
                exit;
            }
        } else {
            echo "<div class='alert alert-danger'>Parameter kecamatan tidak ditemukan.</div>";
            exit;
        }

        // Menutup koneksi
        $conn->close();
        ?>

        <!-- Form untuk mengedit data -->
        <form method="POST" action="">
            <input type="hidden" name="kecamatan" value="<?php echo htmlspecialchars($row['kecamatan']); ?>">

            <div class="mb-3">
                <label for="longitude" class="form-label">Longitude</label>
                <input type="text" class="form-control" id="longitude" name="longitude" value="<?php echo htmlspecialchars($row['longitude']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="latitude" class="form-label">Latitude</label>
                <input type="text" class="form-control" id="latitude" name="latitude" value="<?php echo htmlspecialchars($row['latitude']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="luas" class="form-label">Luas</label>
                <input type="text" class="form-control" id="luas" name="luas" value="<?php echo htmlspecialchars($row['luas']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="jumlah_penduduk" class="form-label">Jumlah Penduduk</label>
                <input type="number" class="form-control" id="jumlah_penduduk" name="jumlah_penduduk" value="<?php echo htmlspecialchars($row['jumlah_penduduk']); ?>" required>
            </div>

            <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            <a href="index.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
