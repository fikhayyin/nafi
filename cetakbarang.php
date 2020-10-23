<?php
//cek session
if (empty($_SESSION['admin'])) {
    $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
    header("Location: ./");
    die();
} else {
    if ($_SESSION['admin'] != 1 and $_SESSION['admin'] != 2) {
        echo '<script language="javascript">
                    window.alert("ERROR! Anda tidak memiliki hak akses untuk membuka halaman ini");
                    window.location.href="./logout.php";
                  </script>';
    }
    echo '
            <style type="text/css">
                .hidd {
                    display: none
                }
                @media print{
                    body {
                        font-size: 12px!important;
                        color: #212121;
                    }
                    .disp {
                        text-align: center;
                        margin: -.5rem 0;
                        width: 100%;
                    }
                    nav {
                        display: none
                    }
                    .hidd {
                        display: block
                    }
                    .logodisp {
                        position: absolute;
                        width: 80px;
                        height: 80px;
                        left: 50px;
                        margin: 0 0 0 1.2rem;
                    }
                    .up {
                        font-size: 17px!important;
                        font-weight: normal;
                        margin-top: 45px;
                        text-transform: uppercase
                    }
                    #nama {
                        font-size: 20px!important;
                        text-transform: uppercase;
                        margin-top: 5px;
                        font-weight: bold;
                    }
                    .status {
                        font-size: 17px!important;
                        font-weight: normal;
                        margin-top: -1.5rem;
                    }
                    #alamat {
                        margin-top: -15px;
                        font-size: 13px;
                    }
                    .separator {
                        border-bottom: 2px solid #616161;
                        margin: 1rem 0;
                    }
                }
            </style>';

    if (isset($_REQUEST['submit'])) {

        $dari_tanggal = $_REQUEST['dari_tanggal'];
        $sampai_tanggal = $_REQUEST['sampai_tanggal'];

        if ($_REQUEST['dari_tanggal'] == "" || $_REQUEST['sampai_tanggal'] == "") {
            header("Location: ./admin.php?page=clb");
            die();
        } else {

            $query = mysqli_query($config, "SELECT * FROM barang WHERE tgl_brg BETWEEN '$dari_tanggal' AND '$sampai_tanggal'");

            $query2 = mysqli_query($config, "SELECT nama FROM perusahaan");
            list($nama) = mysqli_fetch_array($query2);

            echo '
                    <!-- SHOW DAFTAR AGENDA -->
                    <!-- Row Start -->
                    <div class="row">
                        <!-- Secondary Nav START -->
                        <div class="col s12">
                            <div class="z-depth-1">
                                <nav class="secondary-nav">
                                    <div class="nav-wrapper blue-grey darken-1">
                                        <div class="col 12">
                                            <ul class="left">
                                                <li class="waves-effect waves-light"><a href="?page=clb" class="judul"><i class="material-icons">print</i>Cetak laporan inventaris barang<a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </nav>
                            </div>
                        </div>
                        <!-- Secondary Nav END -->
                    </div>
                    <!-- Row END -->

                    <!-- Row form Start -->
                    <div class="row jarak-form black-text">
                        <form class="col s12" method="post" action="">
                            <div class="input-field col s3">
                                <i class="material-icons prefix md-prefix">date_range</i>
                                <input id="dari_tanggal" type="text" name="dari_tanggal" id="dari_tanggal" required>
                                <label for="dari_tanggal">Dari Tanggal</label>
                            </div>
                            <div class="input-field col s3">
                                <i class="material-icons prefix md-prefix">date_range</i>
                                <input id="sampai_tanggal" type="text" name="sampai_tanggal" id="sampai_tanggal" required>
                                <label for="sampai_tanggal">Sampai Tanggal</label>
                            </div>
                            <div class="col s6">
                                <button type="submit" name="submit" class="btn-large blue waves-effect waves-light"> TAMPILKAN <i class="material-icons">visibility</i></button>
                            </div>
                        </form>
                    </div>
                    <!-- Row form END -->

                    <div class="row agenda">
                    <div class="disp hidd">';
            $query2 = mysqli_query($config, "SELECT nama, cabang, divisi, alamat, logo FROM perusahaan");
            list($nama, $cabang, $divisi, $alamat, $logo) = mysqli_fetch_array($query2);
            echo "<img class="logodisp" src="./upload/' . $logo . '"/>";

            echo "<h6 class="up">" . $nama . "</h6>";

            echo "<h5 class="nama" id="nama">" . $cabang . "</h5><br/>";

            echo "<h6 class="status">" . $divisi . "</h6>";

            echo "<span id="alamat">" . $alamat . "</span>

                    </div>
                    <div class="separator"></div>
                    <h5 class="hid">Laporan Inventaris</h5>
                        <div class="col s10">
                            <p class="warna agenda">Laporan inventaris barang dari tanggal <strong>' . indoDate($dari_tanggal) . '</strong> sampai dengan tanggal <strong>' . indoDate($sampai_tanggal) . '</strong></p>
                        </div>
                        <div class="col s2">
                            <button type="submit" onClick="window.print()" class="btn-large deep-orange waves-effect waves-light right">CETAK <i class="material-icons">print</i></button>
                        </div>
                    </div>
                    <div id="colres" class="warna cetak">
                        <table class="bordered" id="tbl" width="80%">
                            <thead class="blue lighten-4">
                                <tr>
                                    <th width="3%">Nomor</th>
                                    <th width="8%">Tanggal Penerimaan</th>
                                    <th width="5%">Merk</th>
                                    <th width="6%">Tipe</th>
                                    <th width="15%">Nama Equipment</th>
                                    <th width="18%">Serial Number</th>
                                    <th width="15%">Lokasi Barang</th>
                                    <th width="10%">Penerima</th>
                                    <th width="15%">Paraf</th>
                                    <th width="5%">Keterangan</th>
                                </tr>
                            </thead>

                            <tbody>';

            if (mysqli_num_rows($query) > 0) {
                $no = 0;
                while ($row = mysqli_fetch_array($query)) {
                    echo '
                                 <tr>
                                        <td>' . $row['no_brg'] . '</td>
                                        <td>' . indoDate($row['tgl_brg']) . '</td>
                                        <td>' . $row['merk_brg'] . '</td>
                                        <td>' . $row['tipe'] . '</td>
                                        <td>' . $row['nama_brg'] . '</td>
                                        <td>' . $row['jumlah_brg'] . '</td>
                                        <td>' . $row['lokasi_brg'] . '</td>
                                        <td>';

                    $id_user = $row['id_user'];
                    $query3 = mysqli_query($config, "SELECT nama FROM user WHERE id_user='$id_user'");
                    list($nama) = mysqli_fetch_array($query3); {
                        $row['id_user'] = '' . $nama . '';
                    }

                    echo '' . $row['id_user'] . '</td>
                                        <td></td>
                                        <td>' . $row['keterangan'] . '';
                    echo '</td>
                                </tr>';
                }
            } else {
                echo '<tr><td colspan="9"><center><p class="add">Tidak ada nomor barang</p></center></td></tr>';
            }
            echo '
                        </tbody></table>
                    </div>';
        }
    } else {

        echo '
                <!-- Row Start -->
                <div class="row">
                    <!-- Secondary Nav START -->
                    <div class="col s12">
                        <div class="z-depth-1">
                            <nav class="secondary-nav">
                                <div class="nav-wrapper blue-grey darken-1">
                                    <div class="col 12">
                                        <ul class="left">
                                            <li class="waves-effect waves-light"><a href="?page=clb" class="judul"><i class="material-icons">print</i> Cetak laporan inventaris barang<a></li>
                                        </ul>
                                    </div>
                                </div>
                            </nav>
                        </div>
                    </div>
                    <!-- Secondary Nav END -->
                </div>
                <!-- Row END -->

                <!-- Row form Start -->
                <div class="row jarak-form black-text">
                    <form class="col s12" method="post" action="">
                        <div class="input-field col s3">
                            <i class="material-icons prefix md-prefix">date_range</i>
                            <input id="dari_tanggal" type="text" name="dari_tanggal" id="dari_tanggal" required>
                            <label for="dari_tanggal">Dari Tanggal</label>
                        </div>
                        <div class="input-field col s3">
                            <i class="material-icons prefix md-prefix">date_range</i>
                            <input id="sampai_tanggal" type="text" name="sampai_tanggal" id="sampai_tanggal" required>
                            <label for="sampai_tanggal">Sampai Tanggal</label>
                        </div>
                        <div class="col s6">
                            <button type="submit" name="submit" class="btn-large blue waves-effect waves-light"> TAMPILKAN <i class="material-icons">visibility</i></button>
                        </div>
                    </form>
                </div>
                <!-- Row form END -->';
    }
}
