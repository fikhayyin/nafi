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
    if (isset($_REQUEST['act'])) {
        $act = $_REQUEST['act'];
        switch ($act) {
            case 'fcl':
                include "fileceklist.php";
                break;
        }
    } else {

        //pagging
        $limit = 8;
        $pg = @$_GET['pg'];
        if (empty($pg)) {
            $curr = 0;
            $pg = 1;
        } else {
            $curr = ($pg - 1) * $limit;
        }

        echo '
                    <!-- Row Start -->
                    <div class="row">
                        <!-- Secondary Nav START -->
                        <div class="col s12">
                            <div class="z-depth-1">
                                <nav class="secondary-nav">
                                    <div class="nav-wrapper blue-grey darken-1">
                                        <div class="col m12">
                                            <ul class="left">
                                                <li class="waves-effect waves-light"><a href="?page=dlc" class="judul"><i class="material-icons">image</i>Data Laporan</a></li>
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
                    <div class="row jarak-form">';

        if (isset($_REQUEST['submit'])) {

            $dari_tanggal = $_REQUEST['dari_tanggal'];
            $sampai_tanggal = $_REQUEST['sampai_tanggal'];

            if ($_REQUEST['dari_tanggal'] == "" || $_REQUEST['sampai_tanggal'] == "") {
                header("Location: ./admin.php?page=dlc");
                die();
            } else {

                $query = mysqli_query($config, "SELECT * FROM checklist WHERE tgl_lpr BETWEEN '$dari_tanggal' AND '$sampai_tanggal' ORDER By id_cek DESC LIMIT 10");

                echo '<!-- Row form Start -->
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
                                        <button type="submit" name="submit" class="btn-large blue waves-effect waves-light">FILTER <i class="material-icons">filter_list</i></button>&nbsp;&nbsp;

                                        <button type="reset" onclick="window.history.back()" class="btn-large deep-orange waves-effect waves-light">RESET <i class="material-icons">refresh</i></button>
                                    </div>
                                </form>
                            </div>
                            <!-- Row form END -->

                            <div class="row agenda">
                                <div class="col s12"><p class="warna agenda">Galeri laporan checklist antara tanggal <strong>' . indoDate($dari_tanggal) . '</strong> sampai dengan tanggal <strong>' . indoDate($sampai_tanggal) . '</strong></p>
                                </div>
                            </div>';

                if (mysqli_num_rows($query) > 0) {
                    while ($row = mysqli_fetch_array($query)) {
                        if (empty($row['file'])) {
                            echo '';
                        } else {

                            $ekstensi = array('jpg', 'png', 'jpeg');
                            $ekstensi2 = array('doc', 'docx');
                            $file = $row['file'];
                            $x = explode('.', $file);
                            $eks = strtolower(end($x));

                            if (in_array($eks, $ekstensi) == true) {
                                echo '
                                            <div class="col m3">
                                                <img class="galeri materialboxed" data-caption="' . indoDate($row['tgl_catat']) . '" src="./upload/ceklist/' . $row['file'] . '"/>
                                                <a class="btn light-green darken-1" href="?page=dlc&act=fcl&id_cek=' . $row['id_cek'] . '">Tampilkan Ukuran Penuh</a>
                                            </div>';
                            } else {

                                if (in_array($eks, $ekstensi2) == true) {
                                    echo '
                                                <div class="col m3">
                                                    <img class="galeri materialboxed" data-caption="' . indoDate($row['tgl_catat']) . '" src="./asset/img/pdf.png"/>
                                                    <a class="btn light-green darken-1" href="?page=dlc&act=fcl&id_cek=' . $row['id_cek'] . '">Lihat Detail File</a>
                                                </div>';
                                } else {
                                    echo '
                                                <div class="col m3">
                                                    <img class="galeri materialboxed" data-caption="' . indoDate($row['tgl_catat']) . '" src="./asset/img/xls.png"/>
                                                    <a class="btn light-green darken-1" href="?page=dlc&act=fcl&id_cek=' . $row['id_cek'] . '">Lihat Detail File</a>
                                                </div>';
                                }
                            }
                        }
                    }
                } else {
                    echo '<div class="col m12">
                                        <div class="card blue lighten-5">
                                            <div class="card-content notif">
                                                <span class="card-title lampiran"><center>Tidak ada data checklist yang ditemukan</center></span>
                                            </div>
                                        </div>
                                    </div>';
                }
                echo '
                                </div>';
            }
        } else {

            //script untuk menampilkan data
            $query = mysqli_query($config, "SELECT * FROM checklist ORDER BY id_cek DESC LIMIT $curr, $limit");
            if (mysqli_num_rows($query) > 0) {

                echo '
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
                                        <button type="submit" name="submit" class="btn-large blue waves-effect waves-light">FILTER <i class="material-icons">filter_list</i></button>
                                    </div>
                                </form>
                            </div>
                            <!-- Row form END -->';

                while ($row = mysqli_fetch_array($query)) {

                    if (empty($row['file'])) {
                        echo '';
                    } else {

                        $ekstensi = array('pdf');
                        $ekstensi2 = array('xls', 'xlsx');
                        $file = $row['file'];
                        $x = explode('.', $file);
                        $eks = strtolower(end($x));

                        if (in_array($eks, $ekstensi) == true) {
                            echo '
                                        <div class="col m3">
                                            <img class="galeri materialboxed" data-caption="' . indoDate($row['tgl_catat']) . '" src="./upload/ceklist/' . $row['file'] . '"/>
                                            <a class="btn light-green darken-1" href="?page=dlc&act=fcl&id_cek=' . $row['id_cek'] . '">Tampilkan Ukuran Penuh</a>
                                        </div>';
                        } else {

                            if (in_array($eks, $ekstensi2) == true) {
                                echo '
                                            <div class="col m3">
                                                <img class="galeri materialboxed" data-caption="' . indoDate($row['tgl_catat']) . '" src="./asset/img/xls.png"/>
                                                <a class="btn light-green darken-1" href="?page=dlc&act=fcl&id_cek=' . $row['id_cek'] . '">Lihat Detail File</a>
                                            </div>';
                            } else {
                                echo '
                                                <div class="col m3">
                                                    <img class="galeri materialboxed" data-caption="' . indoDate($row['tgl_catat']) . '" src="./asset/img/pdf.png"/>
                                                    <a class="btn light-green darken-1" href="?page=dlc&act=fcl&id_cek=' . $row['id_cek'] . '">Lihat Detail File</a>
                                                </div>';
                            }
                        }
                    }
                }
            } else {
                echo '<div class="col m12">
                                    <div class="card blue lighten-5">
                                        <div class="card-content notif">
                                            <span class="card-title lampiran"><center>Tidak ada data untuk ditampilkan</center></span>
                                        </div>
                                    </div>
                                </div>';
            }
            echo '
                        </div>';

            $query = mysqli_query($config, "SELECT * FROM checklist");
            $cdata = mysqli_num_rows($query);
            $cpg = ceil($cdata / $limit);

            echo '<!-- Pagination START -->
                              <ul class="pagination">';

            if ($cdata > $limit) {

                //first and previous pagging
                if ($pg > 1) {
                    $prev = $pg - 1;
                    echo '<li><a href="?page=dlc&pg=1"><i class="material-icons md-48">first_page</i></a></li>
                                  <li><a href="?page=dlc&pg=' . $prev . '"><i class="material-icons md-48">chevron_left</i></a></li>';
                } else {
                    echo '<li class="disabled"><a href=""><i class="material-icons md-48">first_page</i></a></li>
                                  <li class="disabled"><a href=""><i class="material-icons md-48">chevron_left</i></a></li>';
                }

                //looping pagging
                for ($i = 1; $i <= $cpg; $i++) {
                    if ((($i >= $pg - 3) && ($i <= $pg + 3)) || ($i == 1) || ($i == $cpg)) {
                        if ($i == $pg) echo '<li class="active waves-effect waves-dark"><a href="?page=dlc&pg=' . $i . '"> ' . $i . ' </a></li>';
                        else echo '<li class="waves-effect waves-dark"><a href="?page=dlc&pg=' . $i . '"> ' . $i . ' </a></li>';
                    }
                }

                //next and last pagging
                if ($pg < $cpg) {
                    $next = $pg + 1;
                    echo '<li><a href="?page=dlc&pg=' . $next . '"><i class="material-icons md-48">chevron_right</i></a></li>
                                  <li><a href="?page=dlc&pg=' . $cpg . '"><i class="material-icons md-48">last_page</i></a></li>';
                } else {
                    echo '<li class="disabled"><a href=""><i class="material-icons md-48">chevron_right</i></a></li>
                                  <li class="disabled"><a href=""><i class="material-icons md-48">last_page</i></a></li>';
                }
                echo '
                        </ul>
                        <!-- Pagination END -->';
            } else {
                echo '';
            }
        }
    }
}
