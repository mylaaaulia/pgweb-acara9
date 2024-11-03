<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Peta Penduduk</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
        integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            display: flex;
            flex-direction: column;
        }

        #map {
            width: 100%;
            height: 700px;
            /* Sesuaikan tinggi peta di sini */
            margin-bottom: 10px;
            /* Jarak kecil antara peta dan tabel */
        }

        #data-table {
            top: 10px;
            right: 10px;
            width: 300px;
            background-color: rgba(255, 255, 255, 0.9);
            /* Semi-transparan */
            border-radius: 8px;
            overflow-y: auto;
            max-height: 200px;
            padding: 0px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            z-index: 1000;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><i class="fa-solid fa-map-location-dot"></i> Peta Penduduk </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="https://github.com/mylaaaulia/pgweb-acara8.git"
                            target="_blank"><i class="fa-solid fa-layer-group"></i> Sumber Data</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#infoModal"><i
                                class="fa-solid fa-circle-info"></i> Info</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Info Modal -->
    <div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="infoModalLabel">Info Pembuat</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <tr>
                            <th>Nama</th>
                            <td>Myla Aulia Syahida</td>
                        </tr>
                        <tr>
                            <th>NIM</th>
                            <td>23/522046/SV/23577</td>
                        </tr>
                        <tr>
                            <th>Kelas</th>
                            <td>B</td>
                        </tr>
                        <tr>
                            <th>Github</th>
                            <td> <a href="https://github.com/mylaaaulia" target="_blank"
                                    rel="noopener noreference">https://github.com/mylaaaulia</a></td>
                        </tr>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Feature Modal -->
    <div class="modal fade" id="featureModal" tabindex="-1" aria-labelledby="featureModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="featureModalTitle"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="featureModalBody">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

    <body>
        <div id="map"></div>

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

        // Hapus data jika ada parameter 'kecamatan'
        if (isset($_GET['kecamatan'])) {
            $kecamatan = $conn->real_escape_string($_GET['kecamatan']);
            $sql = "DELETE FROM pendudukk WHERE kecamatan = '$kecamatan'";

            if ($conn->query($sql) === TRUE) {
                header("Location: index.php");
                exit;
            } else {
                echo "Error deleting record: " . $conn->error;
            }
        }

        // Query untuk mengambil data dari tabel penduduk
        $sql = "SELECT * FROM pendudukk";
        $result = $conn->query($sql);

        // Menyimpan data sebagai array untuk digunakan pada JavaScript
        $data = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        // Menutup koneksi
        $conn->close();
        ?>

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script>
            // Inisialisasi peta
            var map = L.map("map").setView([-7.7748176, 110.3744245], 13);

            // Tile Layer Base Map
            var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            });

            var OpenStreetMap_Mapnik = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">Myla Aulia & OpenStreetMap</a> contributors'
            });

            // Menambahkan basemap ke dalam peta
            OpenStreetMap_Mapnik.addTo(map);

            // Data PHP to JavaScript
            var data = <?php echo json_encode($data); ?>;

            // Add markers based on database data
            data.forEach(function(row) {
                var marker = L.marker([row.latitude, row.longitude])
                    .addTo(map)
                    .bindPopup("<b>" + row.kecamatan + "</b><br>Luas: " + row.luas + "<br>Longitude: " + row.longitude + "<br>Latitude: " + row.latitude + "<br>Jumlah Penduduk: " + row.jumlah_penduduk);
            });

            // Menambahkan layer kontrol
            var overlayMaps = {
                "Markers": markersLayer
            };

            L.control.layers(baseMaps, overlayMaps, {
                collapsed: false
            }).addTo(map);
            L.control.scale({
                position: "bottomright",
                imperial: false
            }).addTo(map);
        </script>

        <!-- Tabel Data -->
        <div>
            <?php
            // Menampilkan data sebagai tabel
            if (!empty($data)) {
                echo "<table class='table table-sm table-striped'>
                    <tr>
                        <th>Kecamatan</th>
                        <th>Longitude</th>
                        <th>Latitude</th>
                        <th>Luas</th>
                        <th>Jumlah Penduduk</th>
                        <th>Aksi</th>
                    </tr>";
                foreach ($data as $row) {
                    echo "<tr>
                        <td>" . htmlspecialchars($row["kecamatan"]) . "</td>
                        <td>" . htmlspecialchars($row["longitude"]) . "</td>
                        <td>" . htmlspecialchars($row["latitude"]) . "</td>
                        <td>" . htmlspecialchars($row["luas"]) . "</td>
                        <td align='left'>" . htmlspecialchars($row["jumlah_penduduk"]) . "</td>
                        <td>
                            <a href='edit.php?kecamatan=" . urlencode($row["kecamatan"]) . "'>Edit</a>
                            <a href='index.php?kecamatan=" . urlencode($row["kecamatan"]) . "' 
                            onclick=\"return confirm('Yakin ingin menghapus data ini?');\">Hapus</a>
                        </td>
                    </tr>";
                }
                echo "</tbody></table>";
            } else {
                echo "Tidak ada data.";
            }
            ?>
        </div>
    </body>

</html>