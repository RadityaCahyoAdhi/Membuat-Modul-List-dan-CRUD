<?php
$servername = "localhost";
$username = "tesintegra";
$password = "integra123";
$dbname = "kependudukandb";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

include 'SimpleXLSXGen.php';

// API endpoint
if (isset($_GET['action']) && $_GET['action'] == 'getAllData') {
    getAllData();
}

if (isset($_GET['action']) && $_GET['action'] == 'getDataByNomor' && isset($_GET['nomor'])) {
    getDataByNomor($_GET['nomor']);
}

if (isset($_GET['action']) && $_GET['action'] == 'addData') {
    adddata();
}

if (isset($_GET['action']) && $_GET['action'] === 'exportExcel') {
    exportExcel();
}

if (isset($_GET['action']) && $_GET['action'] == 'editData' && isset($_POST['nomor'])) {
    editData();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'deleteData') {
    deleteData($_POST['nomor']);
}

if (isset($_GET['action']) && $_GET['action'] == 'getPropinsi') {
    getPropinsi();
}

if (isset($_GET['action']) && $_GET['action'] == 'getKabupatenByPropId' && isset($_GET['id_prop'])) {
    getKabupatenByPropId($_GET['id_prop']);
}

if (isset($_GET['action']) && $_GET['action'] == 'getKecamatanByPropAndKabId' && isset($_GET['id_prop']) && isset($_GET['id_kab'])) {
    getKecamatanByPropAndKabId($_GET['id_prop'], $_GET['id_kab']);
}

if (isset($_GET['action']) && $_GET['action'] == 'search') {
    if (isset($_GET['query'])) {
        $query = $_GET['query'];
        searchFunction($query);
    } else {
        echo "Parameter query tidak ditemukan.";
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'sort') {
    if (isset($_GET['key'])) {
        $key = $_GET['key'];
        sortFunction($key);
    } else {
        echo "Parameter key tidak ditemukan.";
    }
}

if (isset($_GET['action']) && $_GET['action'] == 'rekapPendapatan' && isset($_GET['minPendapatan']) && isset($_GET['maxPendapatan'])) {
    rekapPendapatan($_GET['minPendapatan'], $_GET['maxPendapatan']);
}

if (isset($_GET['action']) && $_GET['action'] == 'rekapPendidikan' && isset($_GET['pendidikan'])) {
    rekapPendidikan($_GET['pendidikan']);
}

if (isset($_GET['action']) && $_GET['action'] == 'rekapWilayah' && isset($_GET['kecamatan'])) {
    rekapWilayah($_GET['kecamatan']);
}

function tanggal_indo($tanggal) {
    $bulan = array (
        1 =>   'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $split = explode('-', $tanggal);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}

function dataValidation() {
    $required_fields = ['nama', 'propinsi', 'kabupaten', 'kecamatan', 'alamat', 'telp_hp', 'tgl_lahir', 'pendapatan', 'tingkat_pendidikan', 'jenis_pekerjaan', 'keterangan'];
    foreach ($required_fields as $field) {
        if (!isset($_POST[$field]) || $_POST[$field] === '') {
            die("Error: $field is required.");
        }
    }
    $pendapatan = $_POST['pendapatan'];
    if (!is_numeric($pendapatan)) {
        die("Error: Pendapatan harus berupa angka.");
    }
    $telp_hp = $_POST['telp_hp'];
    if (strlen($telp_hp) < 8 || strlen($telp_hp) > 14) {
        die("Error: Nomor telepon harus antara 8 dan 14 karakter.");
    }
}

function getAllData() {
    global $conn;
    $sql = "SELECT * FROM penduduk";
    $result = $conn->query($sql);
    $data = array();
    while($row = $result->fetch_assoc()) {
        $tgl_lahir = new DateTime($row['tgl_lahir']);
        $tgl_sekarang = new DateTime('today');
        $usia = $tgl_lahir->diff($tgl_sekarang)->y;
        $row['usia'] = $usia;
        $data[] = $row;
    }
    echo json_encode($data);
}

function getDataByNomor($nomor) {
    global $conn;
    $sql = "SELECT * FROM penduduk WHERE nomor = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $nomor);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = array();
    while($row = $result->fetch_assoc()) {
        $tgl_lahir = new DateTime($row['tgl_lahir']);
        $tgl_sekarang = new DateTime('today');
        $usia = $tgl_lahir->diff($tgl_sekarang)->y;
        $row['usia'] = $usia;
        $data[] = $row;
    }
    echo json_encode($data);
}

function getKabupatenByPropId($id_prop) {
    global $conn;
    $sql = "SELECT * FROM kabupaten WHERE id_prop = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_prop);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = array();
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
}

function getKecamatanByPropAndKabId($id_prop, $id_kab) {
    global $conn;
    $sql = "SELECT * FROM kecamatan WHERE id_prop = ? AND id_kab = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_prop, $id_kab);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = array();
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
}

function getPropinsi() {
    global $conn;
    $sql = "SELECT * FROM propinsi";
    $result = $conn->query($sql);
    $data = array();
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    echo json_encode($data);
}

function addData() {
    global $conn;
    dataValidation();
    $nama = $_POST['nama'];
    $propinsi = $_POST['propinsi'];
    $kabupaten = $_POST['kabupaten'];
    $kecamatan = $_POST['kecamatan'];
    $alamat = $_POST['alamat'];
    $telp_hp = $_POST['telp_hp'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $pendapatan = $_POST['pendapatan'];
    $tingkat_pendidikan = $_POST['tingkat_pendidikan'];
    $jenis_pekerjaan = $_POST['jenis_pekerjaan'];
    $keterangan = $_POST['keterangan'];
    $sql = "INSERT INTO penduduk (nama, propinsi, kabupaten, kecamatan, alamat, telp_hp, tgl_lahir, pendapatan, tingkat_pendidikan, jenis_pekerjaan, keterangan)
    VALUES ('$nama', '$propinsi', '$kabupaten', '$kecamatan', '$alamat', '$telp_hp', '$tgl_lahir', '$pendapatan', '$tingkat_pendidikan', '$jenis_pekerjaan', '$keterangan')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

function editData() {
    global $conn;
    dataValidation();
    $nomor = $_POST['nomor'];
    $nama = $_POST['nama'];
    $propinsi = $_POST['propinsi'];
    $kabupaten = $_POST['kabupaten'];
    $kecamatan = $_POST['kecamatan'];
    $alamat = $_POST['alamat'];
    $telp_hp = $_POST['telp_hp'];
    $tgl_lahir = $_POST['tgl_lahir'];
    $pendapatan = $_POST['pendapatan'];
    $tingkat_pendidikan = $_POST['tingkat_pendidikan'];
    $jenis_pekerjaan = $_POST['jenis_pekerjaan'];
    $keterangan = $_POST['keterangan'];
    $sql = "UPDATE penduduk SET nama='$nama', propinsi='$propinsi', kabupaten='$kabupaten', kecamatan='$kecamatan', alamat='$alamat', telp_hp='$telp_hp', tgl_lahir='$tgl_lahir', pendapatan='$pendapatan', tingkat_pendidikan='$tingkat_pendidikan', jenis_pekerjaan='$jenis_pekerjaan', keterangan='$keterangan' WHERE nomor='$nomor'";
    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

function deleteData($nomor) {
    global $conn;
    $sql = "DELETE FROM penduduk WHERE nomor=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $nomor);
    if ($stmt->execute()) {
        echo "Record deleted successfully";
    } else {
        echo "Error deleting record: " . $stmt->error;
    }
}

function exportExcel() {
    
    global $conn;
    $sql = "SELECT * FROM penduduk";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $data = array();
        $headRow = ['Nomor', 'Nama', 'Propinsi', 'Kabupaten', 'Kecamatan', 'Alamat', 'Telp/HP', 'Tgl Lahir', 'Usia', 'Pendapatan', 'Tingkat Pendidikan', 'Jenis Pekerjaan', 'Keterangan'];
        $data[] = $headRow;
        while($row = $result->fetch_assoc()) {
            $tgl_lahir = new DateTime($row['tgl_lahir']);
            $tgl_sekarang = new DateTime('today');
            $usia = $tgl_lahir->diff($tgl_sekarang)->y . ' tahun';
            $pendapatan = 'Rp' . number_format($row['pendapatan'], 0, ',', '.');
            $newRow = [$row['nomor'], $row['nama'], $row['propinsi'], $row['kabupaten'], $row['kecamatan'], $row['alamat'], $row['telp_hp'], tanggal_indo($row['tgl_lahir']), $usia, $pendapatan, $row['tingkat_pendidikan'], $row['jenis_pekerjaan'], $row['keterangan']];
            $data[] = $newRow;
        }

        
        
        $xlsx = Shuchkin\SimpleXLSXGen::fromArray($data);
        $xlsx->downloadAs('data.xlsx');
    } else {
        echo "Tidak ada data untuk diekspor";
    }
}

function searchFunction($query) {
    global $conn;

    $sql = "SELECT * FROM penduduk WHERE nama LIKE ? OR alamat LIKE ? OR propinsi LIKE ? OR kabupaten LIKE ? OR kecamatan LIKE ? OR telp_hp LIKE ?";
    $stmt = $conn->prepare($sql);
    $param = "%" . $query . "%";
    $stmt->bind_param("ssssss", $param, $param, $param, $param, $param, $param);

    $stmt->execute();
    $result = $stmt->get_result();
    $rows = array();
    while($row = $result->fetch_assoc()) {
        $tgl_lahir = new DateTime($row['tgl_lahir']);
        $tgl_sekarang = new DateTime('today');
        $usia = $tgl_lahir->diff($tgl_sekarang)->y;
        $row['usia'] = $usia;
        $rows[] = $row;
    }
    
    $stmt->close();
    $conn->close();
    echo json_encode($rows);
}

function sortFunction($key) {
    global $conn;

    $sql = "SELECT * FROM penduduk ORDER BY ";
    switch ($key) {
        case "nama":
            $sql .= "nama";
            break;
        case "propinsi":
            $sql .= "propinsi";
            break;
        case "tanggal lahir":
            $sql .= "tgl_lahir";
            break;
        case "pendapatan":
            $sql .= "pendapatan";
            break;
        default:
            $sql .= "nomor";
            break;
    }
    $result = $conn->query($sql);
    $rows = array();
    while($row = $result->fetch_assoc()) {
        $tgl_lahir = new DateTime($row['tgl_lahir']);
        $tgl_sekarang = new DateTime('today');
        $usia = $tgl_lahir->diff($tgl_sekarang)->y;
        $row['usia'] = $usia;
        $rows[] = $row;
    }
    
    $conn->close();
    echo json_encode($rows);
}

function rekapPendapatan($minPendapatan, $maxPendapatan) {
    global $conn;
    $sql = "SELECT * FROM penduduk WHERE pendapatan BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $minPendapatan, $maxPendapatan);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = array();
    while($row = $result->fetch_assoc()) {
        $tgl_lahir = new DateTime($row['tgl_lahir']);
        $tgl_sekarang = new DateTime('today');
        $usia = $tgl_lahir->diff($tgl_sekarang)->y;
        $row['usia'] = $usia;
        $rows[] = $row;
    }
    $stmt->close();
    $conn->close();
    echo json_encode($rows);
}

function rekapPendidikan($pendidikan) {
    global $conn;
    $sql = "SELECT * FROM penduduk WHERE tingkat_pendidikan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $pendidikan);
    $stmt->execute();
    $result = $stmt->get_result();
    $rows = array();
    while($row = $result->fetch_assoc()) {
        $tgl_lahir = new DateTime($row['tgl_lahir']);
        $tgl_sekarang = new DateTime('today');
        $usia = $tgl_lahir->diff($tgl_sekarang)->y;
        $row['usia'] = $usia;
        $rows[] = $row;
    }
    $stmt->close();
    $conn->close();
    echo json_encode($rows);
}

function rekapWilayah($kecamatan) {
    global $conn;

    $sql = "SELECT * FROM penduduk WHERE kecamatan = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $kecamatan);

    $stmt->execute();
    $result = $stmt->get_result();
    $rows = array();
    while($row = $result->fetch_assoc()) {
        $tgl_lahir = new DateTime($row['tgl_lahir']);
        $tgl_sekarang = new DateTime('today');
        $usia = $tgl_lahir->diff($tgl_sekarang)->y;
        $row['usia'] = $usia;
        $rows[] = $row;
    }
    
    $stmt->close();
    $conn->close();
    echo json_encode($rows);
}

?>