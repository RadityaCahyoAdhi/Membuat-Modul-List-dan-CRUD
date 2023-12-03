<?php
require 'crud.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Rekap Data Kependudukan</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-primary px-3 py-2">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link text-white px-3" href="list.php">List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-white px-3" href="rekap.php">Rekap</a>
                </li>
            </ul>
        </div>
    </nav>

    <div>
        <h3 class="judul my-4 pb-1 text-center">REKAP DATA KEPENDUDUKAN</h3>
    </div>

    <div>
        <p>Rekap Berdasarkan:</p>
        <form id="rekapPendapatan" class="mb-3">
            <div style="display: flex; justify-content: flex-start; align-items: flex-start;">
                <div style="margin-right: 25px;">
                    <label for="minPendapatan">Pendapatan Minimum:</label><br/>
                    <input type="number" id="minPendapatan" name="minPendapatan" required><br/>
                </div>
            
                <div style="margin-right: 25px;">
                    <label for="maxPendapatan">Pendapatan Maksimum:</label><br/>
                    <input type="number" id="maxPendapatan" name="maxPendapatan" required><br/>
                </div>

                <div>
                    <br/>
                    <button id="rekapPendapatanButton" onclick="rekapPendapatan()">Rekap</button>
                </div>
            </div>
        </form>
        <form id="rekapPendidikan" class="mb-3">
            <div style="display: flex; justify-content: flex-start; align-items: flex-start;">
                <div style="margin-right: 25px;">
                    <label for="pendidikan">Tingkat Pendidikan:</label><br/>
                    <input type="text" id="pendidikan" name="pendidikan" required><br/>
                </div>
            
                <div>
                    <br/>
                    <button id="rekapPendidikanButton" onclick="rekapPendidikan()">Rekap</button>
                </div>
            </div>
        </form>
        <form id="rekapWilayah" class="mb-3">
            <div style="display: flex; justify-content: flex-start; align-items: flex-start;">
                <div style="margin-right: 25px;">
                    <label for="propinsi">Propinsi:</label><br/>
                    <select id="propinsi" name="propinsi" onchange="updateOptionRekapKabupaten()" required>
                        <option value="">Propinsi</option>
                    </select><br/>
                </div>
            
                <div style="margin-right: 25px;">
                    <label for="kabupaten">Kabupaten:</label><br/>
                    <select id="kabupaten" name="kabupaten" onchange="updateOptionRekapKecamatan()" required>
                        <option value="">Kabupaten</option>
                    </select><br/>
                </div>

                <div style="margin-right: 25px;">
                    <label for="kecamatan">Kecamatan:</label><br/>
                    <select id="kecamatan" name="kecamatan" required>
                        <option value="">Kecamatan</option>
                    </select><br/>
                </div>

                <div>
                    <br/>
                    <button id="rekapWilayahButton" onclick="rekapWilayah()">Rekap</button>
                </div>
            </div>
        </form>
    </div>

    <div id="jumlahData">Jumlah data penduduk:</div>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Nama</th>
                    <th>Propinsi</th>
                    <th>Kabupaten</th>
                    <th>Kecamatan</th>
                    <th>Alamat</th>
                    <th>Telp/HP</th>
                    <th>Tgl Lahir</th>
                    <th>Usia</th>
                    <th>Pendapatan</th>
                    <th>Tingkat Pendidikan</th>
                    <th>Jenis Pekerjaan</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <script>
        refreshTable();
        getPropinsi();

        document.getElementById('rekapPendapatan').addEventListener('submit', function(event) {
            event.preventDefault();
        });

        document.getElementById('rekapPendidikan').addEventListener('submit', function(event) {
            event.preventDefault();
        });

        document.getElementById('rekapWilayah').addEventListener('submit', function(event) {
            event.preventDefault();
        });

        function refreshTable() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "crud.php?action=getAllData", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var data = JSON.parse(xhr.responseText);
                    var table = document.getElementsByTagName('tbody')[0];
                    table.innerHTML = data.map(function(row) {
                        var usiaClass;
                        if (row.usia < 7) {
                            usiaClass = 'bg-pink';
                        } else if (row.usia <= 16) {
                            usiaClass = 'bg-warning';
                        } else if (row.usia <= 35) {
                            usiaClass = 'bg-success';
                        } else {
                            usiaClass = 'bg-primary';
                        }
                        return '<tr class="' + usiaClass + '">' +
                            '<td>' + row.nomor + '</td>' +
                            '<td>' + row.nama + '</td>' +
                            '<td>' + row.propinsi + '</td>' +
                            '<td>' + row.kabupaten + '</td>' +
                            '<td>' + row.kecamatan + '</td>' +
                            '<td>' + row.alamat + '</td>' +
                            '<td>' + row.telp_hp + '</td>' +
                            '<td>' + new Date(row.tgl_lahir).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) + '</td>' +
                            '<td>' + row.usia + ' tahun' + '</td>' +
                            '<td>' + 'Rp' + parseInt(row.pendapatan).toLocaleString() + '</td>' +
                            '<td>' + row.tingkat_pendidikan + '</td>' +
                            '<td>' + row.jenis_pekerjaan + '</td>' +
                            '<td>' + row.keterangan + '</td>' +
                        '</tr>';
                    }).join('');
                }
                countData();
            }
            xhr.send();
        }
        
        function getPropinsi() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "crud.php?action=getPropinsi", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var propinsi = JSON.parse(xhr.responseText);
                    var propinsiOption = document.getElementById('propinsi');
                    propinsiOption.innerHTML = "<option value=''>Propinsi</option>";
                    propinsiOption.innerHTML += propinsi.map(function(prop) {
                        return '<option value="' + prop.id_prop + ',' + prop.propinsi + '">' + '(' + prop.id_prop + ') ' + prop.propinsi + '</option>';
                    }).join('');
                }
            }
            xhr.send();
        }

        function updateOptionRekapKabupaten() {
            var propinsi = document.getElementById('propinsi').value;
            var id_prop = propinsi.split(',')[0];
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "crud.php?action=getKabupatenByPropId&id_prop=" + id_prop, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var kabupaten = JSON.parse(xhr.responseText);
                    var kabupatenOption = document.getElementById('kabupaten');
                    kabupatenOption.innerHTML = "<option value=''>Kabupaten</option>";
                    for (var i = 0; i < kabupaten.length; i++) {
                        kabupatenOption.innerHTML += "<option value='" + kabupaten[i].id_kab + ',' + kabupaten[i].kabupaten + "'>" + '(' + kabupaten[i].id_kab + ') ' + kabupaten[i].kabupaten + "</option>";
                    }
                }
            }
            xhr.send();
        }

        function updateOptionRekapKecamatan() {
            var propinsi = document.getElementById('propinsi').value;
            var id_prop = propinsi.split(',')[0];
            var kabupaten = document.getElementById('kabupaten').value;
            var id_kab = kabupaten.split(',')[0];
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "crud.php?action=getKecamatanByPropAndKabId&id_prop=" + id_prop + "&id_kab=" + id_kab, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var kecamatan = JSON.parse(xhr.responseText);
                    var kecamatanOption = document.getElementById('kecamatan');
                    kecamatanOption.innerHTML = "<option value=''>Kecamatan</option>";
                    for (var i = 0; i < kecamatan.length; i++) {
                        kecamatanOption.innerHTML += "<option value='" + kecamatan[i].id_kec + ',' + kecamatan[i].kecamatan + "'>" + '(' + kecamatan[i].id_kec + ') ' + kecamatan[i].kecamatan + "</option>";
                    }
                }
            }
            xhr.send();
        }

        function rekapPendapatan() {
            var form = document.getElementById('rekapPendapatan');
            if (form.checkValidity()) {
                var minPendapatan = document.getElementById('minPendapatan').value;
                var maxPendapatan = document.getElementById('maxPendapatan').value;
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "crud.php?action=rekapPendapatan&minPendapatan=" + minPendapatan + "&maxPendapatan=" + maxPendapatan, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var data = JSON.parse(xhr.responseText);
                        var table = document.getElementsByTagName('tbody')[0];
                        table.innerHTML = data.map(function(row) {
                            var usiaClass;
                            if (row.usia < 7) {
                                usiaClass = 'bg-pink';
                            } else if (row.usia <= 16) {
                                usiaClass = 'bg-warning';
                            } else if (row.usia <= 35) {
                                usiaClass = 'bg-success';
                            } else {
                                usiaClass = 'bg-primary';
                            }
                            return '<tr class="' + usiaClass + '">' +
                                '<td>' + row.nomor + '</td>' +
                                '<td>' + row.nama + '</td>' +
                                '<td>' + row.propinsi + '</td>' +
                                '<td>' + row.kabupaten + '</td>' +
                                '<td>' + row.kecamatan + '</td>' +
                                '<td>' + row.alamat + '</td>' +
                                '<td>' + row.telp_hp + '</td>' +
                                '<td>' + new Date(row.tgl_lahir).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) + '</td>' +
                                '<td>' + row.usia + ' tahun' + '</td>' +
                                '<td>' + 'Rp' + parseInt(row.pendapatan).toLocaleString() + '</td>' +
                                '<td>' + row.tingkat_pendidikan + '</td>' +
                                '<td>' + row.jenis_pekerjaan + '</td>' +
                                '<td>' + row.keterangan + '</td>' +
                            '</tr>';
                        }).join('');
                    }
                    countData();
                }
                xhr.send();
            }
        }

        function rekapPendidikan() {
            var form = document.getElementById('rekapPendidikan');
            if (form.checkValidity()) {
                var pendidikan = document.getElementById('pendidikan').value;
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "crud.php?action=rekapPendidikan&pendidikan=" + pendidikan, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var data = JSON.parse(xhr.responseText);
                        var table = document.getElementsByTagName('tbody')[0];
                        table.innerHTML = data.map(function(row) {
                            var usiaClass;
                            if (row.usia < 7) {
                                usiaClass = 'bg-pink';
                            } else if (row.usia <= 16) {
                                usiaClass = 'bg-warning';
                            } else if (row.usia <= 35) {
                                usiaClass = 'bg-success';
                            } else {
                                usiaClass = 'bg-primary';
                            }
                            return '<tr class="' + usiaClass + '">' +
                                '<td>' + row.nomor + '</td>' +
                                '<td>' + row.nama + '</td>' +
                                '<td>' + row.propinsi + '</td>' +
                                '<td>' + row.kabupaten + '</td>' +
                                '<td>' + row.kecamatan + '</td>' +
                                '<td>' + row.alamat + '</td>' +
                                '<td>' + row.telp_hp + '</td>' +
                                '<td>' + new Date(row.tgl_lahir).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) + '</td>' +
                                '<td>' + row.usia + ' tahun' + '</td>' +
                                '<td>' + 'Rp' + parseInt(row.pendapatan).toLocaleString() + '</td>' +
                                '<td>' + row.tingkat_pendidikan + '</td>' +
                                '<td>' + row.jenis_pekerjaan + '</td>' +
                                '<td>' + row.keterangan + '</td>' +
                            '</tr>';
                        }).join('');
                    }
                    countData();
                }
                xhr.send();
            }
        }

        function rekapWilayah() {
            var form = document.getElementById('rekapWilayah');
            if (form.checkValidity()) {
                var kecamatan_input = document.getElementById('kecamatan').value;
                var kecamatan_split = kecamatan_input.split(',');
                var kecamatan = "(" + kecamatan_split[0] + ") " + kecamatan_split[1];
                var xhr = new XMLHttpRequest();
                xhr.open("GET", "crud.php?action=rekapWilayah&kecamatan=" + kecamatan, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var data = JSON.parse(xhr.responseText);
                        var table = document.getElementsByTagName('tbody')[0];
                        table.innerHTML = data.map(function(row) {
                            var usiaClass;
                            if (row.usia < 7) {
                                usiaClass = 'bg-pink';
                            } else if (row.usia <= 16) {
                                usiaClass = 'bg-warning';
                            } else if (row.usia <= 35) {
                                usiaClass = 'bg-success';
                            } else {
                                usiaClass = 'bg-primary';
                            }
                            return '<tr class="' + usiaClass + '">' +
                                '<td>' + row.nomor + '</td>' +
                                '<td>' + row.nama + '</td>' +
                                '<td>' + row.propinsi + '</td>' +
                                '<td>' + row.kabupaten + '</td>' +
                                '<td>' + row.kecamatan + '</td>' +
                                '<td>' + row.alamat + '</td>' +
                                '<td>' + row.telp_hp + '</td>' +
                                '<td>' + new Date(row.tgl_lahir).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) + '</td>' +
                                '<td>' + row.usia + ' tahun' + '</td>' +
                                '<td>' + 'Rp' + parseInt(row.pendapatan).toLocaleString() + '</td>' +
                                '<td>' + row.tingkat_pendidikan + '</td>' +
                                '<td>' + row.jenis_pekerjaan + '</td>' +
                                '<td>' + row.keterangan + '</td>' +
                            '</tr>';
                        }).join('');
                    }
                    countData();
                }
                xhr.send();
            }
        }

        function countData() {
            var table = document.getElementsByTagName("table")[0];
            var rows = table.getElementsByTagName("tr");
            var count = rows.length - 1;
            document.getElementById("jumlahData").innerText = "Jumlah data penduduk: " + count;
        }

    </script>
</body>
</html>