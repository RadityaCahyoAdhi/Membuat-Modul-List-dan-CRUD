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
    <title>Data Kependudukan</title>
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
        <h3 class="judul my-4 pb-1 text-center">DAFTAR DATA KEPENDUDUKAN</h3>
    </div>

    <div class="cari-urut-container mb-2">
        <form class="cari-list" action="">
            <input class="" type="text" id="searchInput" placeholder="Cari data penduduk">
            <button type="button" onclick="searchFunction()">Search</button>
        </form>
    
        <div class="urut-list">
            <select class=".urut-list" id="sortCombo">
                <option value="no">Urutkan berdasarkan...</option>
                <option value="nama">Nama</option>
                <option value="propinsi">Propinsi</option>
                <option value="tanggal lahir">Tanggal lahir</option>
                <option value="pendapatan">Pendapatan</option>
            </select>
            <button type="button" onclick="sortFunction()">Sort</button>
        </div>
    </div>
    

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
                    <th colspan="3" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
    <div class="text-center">
        <button id="showAddModal" class="btn btn-secondary" onclick="showAddModal()">Add</button>
        <a class="btn btn-secondary" href="crud.php?action=exportExcel">Export</a>
        <button class="btn btn-secondary" onclick="refreshTable()">Refresh</button>
    </div>

    <div id="viewModal" class="modal">
        <div class="modal-content">
            <header style="display: flex; justify-content: space-between; align-items: center;">
                <h2>DETAIL DATA</h2>
                <button type="button" class="close no-style-btn" onclick="closeViewModal()">&times;</button>
            </header>
            
            <form id="viewDataForm">
                <label for="viewnama">Nama:</label><br/>
                <input type="text" id="viewnama" name="viewnama" placeholder="Nama" readonly><br/>
                
                <label for="viewpropinsi">Propinsi:</label><br/>
                <input type="text" id="viewpropinsi" name="viewpropinsi" placeholder="Propinsi" readonly><br/>

                <label for="viewkabupaten">Kabupaten:</label><br/>
                <input type="text" id="viewkabupaten" name="viewkabupaten" placeholder="Kabupaten" readonly><br/>

                <label for="viewkecamatan">Kecamatan:</label><br/>
                <input type="text" id="viewkecamatan" name="viewkecamatan" placeholder="Kecamatan" readonly><br/>

                <label for="viewalamat">Alamat:</label><br/>
                <input type="text" id="viewalamat" name="viewalamat" placeholder="Alamat" readonly><br/>

                <label for="viewtelp">Telp/HP:</label><br/>
                <input type="text" id="viewtelp" name="viewtelp" placeholder="Telp/HP" readonly><br/>

                <label for="viewtgl_lahir">Tgl Lahir:</label><br/>
                <input type="text" id="viewtgl_lahir" name="viewtgl_lahir" readonly><br/>

                <label for="viewusia">Usia:</label><br/>
                <input type="text" id="viewusia" name="viewusia" readonly><br/>

                <label for="viewpendapatan">Pendapatan:</label><br/>
                <input type="text" id="viewpendapatan" name="viewpendapatan" placeholder="Pendapatan" readonly><br/>

                <label for="viewpendidikan">Tingkat Pendidikan:</label><br/>
                <input type="text" id="viewpendidikan" name="viewpendidikan" placeholder="Tingkat Pendidikan" readonly><br/>

                <label for="viewpekerjaan">Jenis Pekerjaan:</label><br/>
                <input type="text" id="viewpekerjaan" name="viewpekerjaan" placeholder="Jenis Pekerjaan" readonly><br/>

                <label for="viewketerangan">Keterangan:</label><br/>
                <textarea id="viewketerangan" name="viewketerangan" placeholder="Keterangan" readonly></textarea><br/>
            </form>
        </div>
    </div>
    
    <div id="editModal" class="modal">
        <div class="modal-content">
            <header style="display: flex; justify-content: space-between; align-items: center;">
                <h2>EDIT DATA</h2>
                <button type="button" class="close no-style-btn" onclick="closeEditModal()">&times;</button>
            </header>
            
            <form id="editDataForm">
                <label for="editnama">Nama:</label><br/>
                <input type="text" id="editnama" name="editnama" placeholder="Nama" required><br/>
                
                <label for="editpropinsi">Propinsi:</label><br/>
                <select id="editpropinsi" name="editpropinsi" onchange="updateOptionEditKabupaten()" required>
                    <option value="">Propinsi</option>
                </select><br/>

                <label for="editkabupaten">Kabupaten:</label><br/>
                <select id="editkabupaten" name="editkabupaten" onchange="updateOptionEditKecamatan()" required>
                    <option value="">Kabupaten</option>
                </select><br/>

                <label for="editkecamatan">Kecamatan:</label><br/>
                <select id="editkecamatan" name="editkecamatan" required>
                    <option value="">Kecamatan</option>
                </select><br/>

                <label for="editalamat">Alamat:</label><br/>
                <input type="text" id="editalamat" name="editalamat" placeholder="Alamat" required><br/>

                <label for="edittelp">Telp/HP:</label><br/>
                <input type="text" id="edittelp" name="edittelp" placeholder="Telp/HP" required minlength=8 maxlength=14><br/>

                <label for="edittgl_lahir">Tgl Lahir:</label><br/>
                <input type="date" id="edittgl_lahir" name="edittgl_lahir" required><br/>

                <label for="editpendapatan">Pendapatan:</label><br/>
                <input type="number" id="editpendapatan" name="editpendapatan" placeholder="Pendapatan" required><br/>

                <label for="editpendidikan">Tingkat Pendidikan:</label><br/>
                <input type="text" id="editpendidikan" name="editpendidikan" placeholder="Tingkat Pendidikan" required><br/>

                <label for="editpekerjaan">Jenis Pekerjaan:</label><br/>
                <input type="text" id="editpekerjaan" name="editpekerjaan" placeholder="Jenis Pekerjaan" required><br/>

                <label for="editketerangan">Keterangan:</label><br/>
                <textarea id="editketerangan" name="editketerangan" placeholder="Keterangan" required></textarea><br/>

                <button id="editDataButton" onclick="editData()">Edit Data</button>
            </form>
        </div>
    </div>

    <div id="deleteModal" class="modal">
        <div class="modal-content">
            <header style="display: flex; justify-content: space-between; align-items: center;">
                <h2>HAPUS DATA</h2>
                <button type="button" class="close no-style-btn" onclick="closeDeleteModal()">&times;</button>
            </header>
            <form id="deleteDataForm">
                <p>Apakah Anda yakin ingin menghapus data ini?</p>
                <button id="confirmDeleteButton" onclick="deleteData()">OK</button>
                <button id="cancelDeleteButton" onclick="closeDeleteModal()">Cancel</button>
            </form>
        </div>
    </div>

    <div id="addModal" class="modal">
        <div class="modal-content">
            <header style="display: flex; justify-content: space-between; align-items: center;">
                <h2>TAMBAH DATA</h2>
                <button type="button" class="close no-style-btn" onclick="closeAddModal()">&times;</button>
            </header>
            
            <form id="addDataForm">
                <label for="addnama">Nama:</label><br/>
                <input type="text" id="addnama" name="addnama" placeholder="Nama" required><br/>
                
                <label for="addpropinsi">Propinsi:</label><br/>
                <select id="addpropinsi" name="addpropinsi" onchange="updateOptionAddKabupaten()" required>
                    <option value="">Propinsi</option>
                </select><br/>

                <label for="addkabupaten">Kabupaten:</label><br/>
                <select id="addkabupaten" name="addkabupaten" onchange="updateOptionAddKecamatan()" required>
                    <option value="">Kabupaten</option>
                </select><br/>

                <label for="addkecamatan">Kecamatan:</label><br/>
                <select id="addkecamatan" name="addkecamatan" required>
                    <option value="">Kecamatan</option>
                </select><br/>

                <label for="addalamat">Alamat:</label><br/>
                <input type="text" id="addalamat" name="addalamat" placeholder="Alamat" required><br/>

                <label for="addtelp">Telp/HP:</label><br/>
                <input type="text" id="addtelp" name="addtelp" placeholder="Telp/HP" required minlength=8 maxlength=14><br/>

                <label for="addtgl_lahir">Tgl Lahir:</label><br/>
                <input type="date" id="addtgl_lahir" name="addtgl_lahir" required><br/>

                <label for="addpendapatan">Pendapatan:</label><br/>
                <input type="number" id="addpendapatan" name="addpendapatan" placeholder="Pendapatan" required><br/>

                <label for="addpendidikan">Tingkat Pendidikan:</label><br/>
                <input type="text" id="addpendidikan" name="addpendidikan" placeholder="Tingkat Pendidikan" required><br/>

                <label for="addpekerjaan">Jenis Pekerjaan:</label><br/>
                <input type="text" id="addpekerjaan" name="addpekerjaan" placeholder="Jenis Pekerjaan" required><br/>

                <label for="addketerangan">Keterangan:</label><br/>
                <textarea id="addketerangan" name="addketerangan" placeholder="Keterangan" required></textarea><br/>

                <button id="addDataButton" onclick="addData()">Add Data</button>
            </form>
        </div>
    </div>
    
    <script>
        refreshTable();
        
        document.getElementById('addDataForm').addEventListener('submit', function(event) {
            event.preventDefault();
        });

        document.getElementById('editDataForm').addEventListener('submit', function(event) {
            event.preventDefault();
        });

        document.getElementById('deleteDataForm').addEventListener('submit', function(event) {
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
                            '<td colspan="3" class="text-center">' + 
                            '<button class="btn btn-secondary mb-3" onclick="viewDetails(' + row.nomor + ')">View</button>' +
                            '<button class="btn btn-secondary mb-3" onclick="showEditModal(' + row.nomor + ')">Edit</button>' +
                            '<button class="btn btn-secondary" onclick="showDeleteModal(' + row.nomor + ')">Delete</button></td>' +
                        '</tr>';
                    }).join('');
                }
            }
            xhr.send();
            document.getElementById("searchInput").value = "";
            document.getElementById("sortCombo").value = "no";
        }
        
        function viewDetails(nomor) {
            var modal = document.getElementById('viewModal');
            modal.style.display = "block";

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "crud.php?action=getDataByNomor&nomor=" + nomor, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var data = JSON.parse(xhr.responseText)[0];
                    document.getElementById('viewnama').value = data.nama;
                    document.getElementById('viewpropinsi').value = data.propinsi;
                    document.getElementById('viewkabupaten').value = data.kabupaten;
                    document.getElementById('viewkecamatan').value = data.kecamatan;
                    document.getElementById('viewalamat').value = data.alamat;
                    document.getElementById('viewtelp').value = data.telp_hp;
                    document.getElementById('viewtgl_lahir').value = new Date(data.tgl_lahir).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                    document.getElementById('viewusia').value = data.usia;
                    document.getElementById('viewpendapatan').value = 'Rp' + parseInt(data.pendapatan).toLocaleString();
                    document.getElementById('viewpendidikan').value = data.tingkat_pendidikan;
                    document.getElementById('viewpekerjaan').value = data.jenis_pekerjaan;
                    document.getElementById('viewketerangan').value = data.keterangan;
                }
            }
            xhr.send();
        }

        function closeViewModal() {
            var modal = document.getElementById('viewModal');
            modal.style.display = "none";
        }

        function showEditModal(nomor) {
            var modal = document.getElementById('editModal');
            modal.style.display = "block";

            var editDataButton = document.getElementById('editDataButton');
            editDataButton.setAttribute('onclick', 'editData(' + nomor + ')');

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "crud.php?action=getDataByNomor&nomor=" + nomor, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var data = JSON.parse(xhr.responseText)[0];
                    document.getElementById('editnama').value = data.nama;
                    document.getElementById('editalamat').value = data.alamat;
                    document.getElementById('edittelp').value = data.telp_hp;
                    document.getElementById('edittgl_lahir').value = data.tgl_lahir;
                    document.getElementById('editpendapatan').value = data.pendapatan;
                    document.getElementById('editpendidikan').value = data.tingkat_pendidikan;
                    document.getElementById('editpekerjaan').value = data.jenis_pekerjaan;
                    document.getElementById('editketerangan').value = data.keterangan;
                    var propinsi = document.getElementById('editpropinsi');
                    propinsi.innerHTML = "<option value=''>Propinsi</option>";
                    var kabupaten = document.getElementById('editkabupaten');
                    kabupaten.innerHTML = "<option value=''>Kabupaten</option>";
                    var kecamatan = document.getElementById('editkecamatan');
                    kecamatan.innerHTML = "<option value=''>Kecamatan</option>";
                    var xhr2 = new XMLHttpRequest();
                    xhr2.open("GET", "crud.php?action=getPropinsi", true);
                    xhr2.onreadystatechange = function() {
                        if (xhr2.readyState == 4 && xhr2.status == 200) {
                            var propinsi = JSON.parse(xhr2.responseText);
                            var propinsiOption = document.getElementById('editpropinsi');
                            propinsiOption.innerHTML = "<option value=''>Propinsi</option>";
                            propinsiOption.innerHTML += propinsi.map(function(prop) {
                                return '<option value="' + prop.id_prop + ',' + prop.propinsi + '">' + '(' + prop.id_prop + ') ' + prop.propinsi + '</option>';
                            }).join('');
                            split_propinsi = data.propinsi.replace(' ', ',').split(',');
                            id_prop = split_propinsi[0].replace('(', '').replace(')', '');
                            propinsiOption.value = id_prop + ',' + split_propinsi[1];
                            var xhr3 = new XMLHttpRequest();
                            xhr3.open("GET", "crud.php?action=getKabupatenByPropId&id_prop=" + id_prop, true);
                            xhr3.onreadystatechange = function() {
                                if (xhr3.readyState == 4 && xhr3.status == 200) {
                                    var kabupaten = JSON.parse(xhr3.responseText);
                                    var kabupatenOption = document.getElementById('editkabupaten');
                                    kabupatenOption.innerHTML = "<option value=''>Kabupaten</option>";
                                    for (var i = 0; i < kabupaten.length; i++) {
                                        kabupatenOption.innerHTML += "<option value='" + kabupaten[i].id_kab + ',' + kabupaten[i].kabupaten + "'>" + '(' + kabupaten[i].id_kab + ') ' + kabupaten[i].kabupaten + "</option>";
                                    }
                                    split_kabupaten = data.kabupaten.replace(' ', ',').split(',');
                                    id_kab = split_kabupaten[0].replace('(', '').replace(')', '');
                                    kabupatenOption.value = id_kab + ',' + split_kabupaten[1];
                                    var xhr4 = new XMLHttpRequest();
                                    xhr4.open("GET", "crud.php?action=getKecamatanByPropAndKabId&id_prop=" + id_prop + "&id_kab=" + id_kab, true);
                                    xhr4.onreadystatechange = function() {
                                        if (xhr4.readyState == 4 && xhr4.status == 200) {
                                            var kecamatan = JSON.parse(xhr4.responseText);
                                            var kecamatanOption = document.getElementById('editkecamatan');
                                            kecamatanOption.innerHTML = "<option value=''>Kecamatan</option>";
                                            for (var i = 0; i < kecamatan.length; i++) {
                                                kecamatanOption.innerHTML += "<option value='" + kecamatan[i].id_kec + ',' + kecamatan[i].kecamatan + "'>" + '(' + kecamatan[i].id_kec + ') ' + kecamatan[i].kecamatan + "</option>";
                                            }
                                            split_kecamatan = data.kecamatan.replace(' ', ',').split(',');
                                            id_kec = split_kecamatan[0].replace('(', '').replace(')', '');
                                            kecamatanOption.value = id_kec + ',' + split_kecamatan[1];
                                        }
                                    }
                                    xhr4.send();
                                }
                            }
                            xhr3.send();
                        }
                    }
                    xhr2.send();
                }
            }
            xhr.send();
        }

        function updateOptionEditKabupaten() {
            var propinsi = document.getElementById('editpropinsi').value;
            var id_prop = propinsi.split(',')[0];
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "crud.php?action=getKabupatenByPropId&id_prop=" + id_prop, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var kabupaten = JSON.parse(xhr.responseText);
                    var kabupatenOption = document.getElementById('editkabupaten');
                    kabupatenOption.innerHTML = "<option value=''>Kabupaten</option>";
                    for (var i = 0; i < kabupaten.length; i++) {
                        kabupatenOption.innerHTML += "<option value='" + kabupaten[i].id_kab + ',' + kabupaten[i].kabupaten + "'>" + '(' + kabupaten[i].id_kab + ') ' + kabupaten[i].kabupaten + "</option>";
                    }
                }
            }
            xhr.send();
        }

        function updateOptionEditKecamatan() {
            var propinsi = document.getElementById('editpropinsi').value;
            var id_prop = propinsi.split(',')[0];
            var kabupaten = document.getElementById('editkabupaten').value;
            var id_kab = kabupaten.split(',')[0];
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "crud.php?action=getKecamatanByPropAndKabId&id_prop=" + id_prop + "&id_kab=" + id_kab, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var kecamatan = JSON.parse(xhr.responseText);
                    var kecamatanOption = document.getElementById('editkecamatan');
                    kecamatanOption.innerHTML = "<option value=''>Kecamatan</option>";
                    for (var i = 0; i < kecamatan.length; i++) {
                        kecamatanOption.innerHTML += "<option value='" + kecamatan[i].id_kec + ',' + kecamatan[i].kecamatan + "'>" + '(' + kecamatan[i].id_kec + ') ' + kecamatan[i].kecamatan + "</option>";
                    }
                }
            }
            xhr.send();
        }

        function editData(nomor) {
            var form = document.getElementById('editDataForm');
            if (form.checkValidity()) {
                var propinsi_value = document.getElementById('editpropinsi').value.split(',');
                var kabupaten_value = document.getElementById('editkabupaten').value.split(',');
                var kecamatan_value = document.getElementById('editkecamatan').value.split(',');

                var nomor = nomor;
                var nama = document.getElementById('editnama').value;
                var propinsi = "(" + propinsi_value[0] + ")" + " " + propinsi_value[1];
                var kabupaten = "(" + kabupaten_value[0] + ")" + " " + kabupaten_value[1];
                var kecamatan = "(" + kecamatan_value[0] + ")" + " " + kecamatan_value[1];
                var alamat = document.getElementById('editalamat').value;
                var telp_hp = document.getElementById('edittelp').value;
                var tgl_lahir = document.getElementById('edittgl_lahir').value;
                var pendapatan = document.getElementById('editpendapatan').value;
                var tingkat_pendidikan = document.getElementById('editpendidikan').value;
                var jenis_pekerjaan = document.getElementById('editpekerjaan').value;
                var keterangan = document.getElementById('editketerangan').value;
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "crud.php?action=editData", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        console.log(xhr.responseText);
                    }
                }
                xhr.send(
                    "nomor=" + nomor + "&nama=" + nama + "&propinsi=" + propinsi + "&kabupaten=" + kabupaten + 
                    "&kecamatan=" + kecamatan + "&alamat=" + alamat + "&telp_hp=" + telp_hp + 
                    "&tgl_lahir=" + tgl_lahir + "&pendapatan=" + pendapatan + "&tingkat_pendidikan=" + tingkat_pendidikan + 
                    "&jenis_pekerjaan=" + jenis_pekerjaan + "&keterangan=" + keterangan
                );
                alert("Data berhasil diubah");
                closeEditModal();
                refreshTable();
            }
        }
        
        function closeEditModal() {
            var modal = document.getElementById('editModal');
            modal.style.display = "none";
        }

        function showDeleteModal(nomor) {
            var modal = document.getElementById('deleteModal');
            modal.style.display = "block";

            var confirmDeleteButton = document.getElementById('confirmDeleteButton');
            confirmDeleteButton.setAttribute('onclick', 'deleteData(' + nomor + ')');
        }

        function deleteData(nomor) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "crud.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    console.log(xhr.responseText);
                }
            }
            xhr.send("action=deleteData&nomor=" + nomor);
            alert("Data berhasil dihapus");
            closeDeleteModal();
            refreshTable();
        }

        function closeDeleteModal() {
            var modal = document.getElementById('deleteModal');
            modal.style.display = "none";
        }
        
        function showAddModal() {
            var modal = document.getElementById('addModal');
            modal.style.display = "block";

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "crud.php?action=getPropinsi", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var propinsi = JSON.parse(xhr.responseText);
                    var propinsiOption = document.getElementById('addpropinsi');
                    propinsiOption.innerHTML = "<option value=''>Propinsi</option>";
                    propinsiOption.innerHTML += propinsi.map(function(prop) {
                        return '<option value="' + prop.id_prop + ',' + prop.propinsi + '">' + '(' + prop.id_prop + ') ' + prop.propinsi + '</option>';
                    }).join('');
                }
            }
            xhr.send();
        }

        function updateOptionAddKabupaten() {
            var propinsi = document.getElementById('addpropinsi').value;
            var id_prop = propinsi.split(',')[0];
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "crud.php?action=getKabupatenByPropId&id_prop=" + id_prop, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var kabupaten = JSON.parse(xhr.responseText);
                    var kabupatenOption = document.getElementById('addkabupaten');
                    kabupatenOption.innerHTML = "<option value=''>Kabupaten</option>";
                    for (var i = 0; i < kabupaten.length; i++) {
                        kabupatenOption.innerHTML += "<option value='" + kabupaten[i].id_kab + ',' + kabupaten[i].kabupaten + "'>" + '(' + kabupaten[i].id_kab + ') ' + kabupaten[i].kabupaten + "</option>";
                    }
                }
            }
            xhr.send();
        }

        function updateOptionAddKecamatan() {
            var propinsi = document.getElementById('addpropinsi').value;
            var id_prop = propinsi.split(',')[0];
            var kabupaten = document.getElementById('addkabupaten').value;
            var id_kab = kabupaten.split(',')[0];
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "crud.php?action=getKecamatanByPropAndKabId&id_prop=" + id_prop + "&id_kab=" + id_kab, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var kecamatan = JSON.parse(xhr.responseText);
                    var kecamatanOption = document.getElementById('addkecamatan');
                    kecamatanOption.innerHTML = "<option value=''>Kecamatan</option>";
                    for (var i = 0; i < kecamatan.length; i++) {
                        kecamatanOption.innerHTML += "<option value='" + kecamatan[i].id_kec + ',' + kecamatan[i].kecamatan + "'>" + '(' + kecamatan[i].id_kec + ') ' + kecamatan[i].kecamatan + "</option>";
                    }
                }
            }
            xhr.send();
        }

        function closeAddModal() {
            var modal = document.getElementById('addModal');
            modal.style.display = "none";
        }
        
        function exportExcel() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "crud.php?action=exportExcel", true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    console.log(xhr.responseText);
                }
            }
            xhr.send();
        }

        function addData() {
            var form = document.getElementById('addDataForm');
            if (form.checkValidity()) {
                var propinsi_value = document.getElementById('addpropinsi').value.split(',');
                var kabupaten_value = document.getElementById('addkabupaten').value.split(',');
                var kecamatan_value = document.getElementById('addkecamatan').value.split(',');

                var nama = document.getElementById('addnama').value;
                var propinsi = "(" + propinsi_value[0] + ")" + " " + propinsi_value[1];
                var kabupaten = "(" + kabupaten_value[0] + ")" + " " + kabupaten_value[1];
                var kecamatan = "(" + kecamatan_value[0] + ")" + " " + kecamatan_value[1];
                var alamat = document.getElementById('addalamat').value;
                var telp_hp = document.getElementById('addtelp').value;
                var tgl_lahir = document.getElementById('addtgl_lahir').value;
                var pendapatan = document.getElementById('addpendapatan').value;
                var tingkat_pendidikan = document.getElementById('addpendidikan').value;
                var jenis_pekerjaan = document.getElementById('addpekerjaan').value;
                var keterangan = document.getElementById('addketerangan').value;
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "crud.php?action=addData", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        console.log(xhr.responseText);
                    }
                }
                
                xhr.send(
                    "nama=" + nama + "&propinsi=" + propinsi + "&kabupaten=" + kabupaten + 
                    "&kecamatan=" + kecamatan + "&alamat=" + alamat + "&telp_hp=" + telp_hp + 
                    "&tgl_lahir=" + tgl_lahir + "&pendapatan=" + pendapatan + "&tingkat_pendidikan=" + tingkat_pendidikan + 
                    "&jenis_pekerjaan=" + jenis_pekerjaan + "&keterangan=" + keterangan
                );
                alert("Data berhasil ditambahkan");
                closeAddModal();
                refreshTable();
            }
        }

        function searchFunction() {
            var input = document.getElementById("searchInput");
            var filter = input.value.toUpperCase();

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "crud.php?action=search&query=" + filter, true);
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
                            '<td colspan="3" class="text-center">' + 
                            '<button class="btn btn-secondary mb-3" onclick="viewDetails(' + row.nomor + ')">View</button>' +
                            '<button class="btn btn-secondary mb-3" onclick="showEditModal(' + row.nomor + ')">Edit</button>' +
                            '<button class="btn btn-secondary" onclick="showDeleteModal(' + row.nomor + ')">Delete</button></td>' +
                        '</tr>';
                    }).join('');
                }
            }
            xhr.send();
        }

        function sortFunction() {
            var select = document.getElementById("sortCombo");
            var key = select.options[select.selectedIndex].value;

            var xhr = new XMLHttpRequest();
            xhr.open("GET", "crud.php?action=sort&key=" + key, true);
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
                            '<td colspan="3" class="text-center">' + 
                            '<button class="btn btn-secondary mb-3" onclick="viewDetails(' + row.nomor + ')">View</button>' +
                            '<button class="btn btn-secondary mb-3" onclick="showEditModal(' + row.nomor + ')">Edit</button>' +
                            '<button class="btn btn-secondary" onclick="showDeleteModal(' + row.nomor + ')">Delete</button></td>' +
                        '</tr>';
                    }).join('');
                }
            }
            xhr.send();
        }
        
        // function sortFunction() {
        //     var input, filter, table, tr, td, i, txtValue;
        //     input = document.getElementById("urut-list");
        //     filter = input.value.toUpperCase();
        //     table = document.getElementsByTagName("table")[0];
        //     tr = table.getElementsByTagName("tr");
        //     for (i = 0; i < tr.length; i++) {
        //         td = tr[i].getElementsByTagName("td")[1];
        //         if (td) {
        //         txtValue = td.textContent || td.innerText;
        //         if (txtValue.toUpperCase().indexOf(filter) > -1) {
        //             tr[i].style.display = "";
        //         } 
        //         else {
        //             tr[i].style.display = "none";
        //         }
        //         }       
        //     }
        // }
       
    </script>
</body>
</html>