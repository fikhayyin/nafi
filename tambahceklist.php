<?php
//cek session
if (empty($_SESSION['admin'])) {
    $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
    header("Location: ./");
    die();
} else {

    if (isset($_REQUEST['submit'])) {

        //validasi form kosong
        if (
            $_REQUEST['no_cek'] == "" || $_REQUEST['area'] == "" || $_REQUEST['hasil'] == "" || $_REQUEST['nama_cek'] == ""
            || $_REQUEST['tipe'] == "" || $_REQUEST['tgl_lpr'] == ""  || $_REQUEST['keterangan'] == ""
        ) {
            $_SESSION['errEmpty'] = 'ERROR! Semua form wajib diisi';
            echo '<script language="javascript">window.history.back();</script>';
        } else {

            $no_cek = $_REQUEST['no_cek'];
            $area = $_REQUEST['area'];
            $hasil = $_REQUEST['hasil'];
            $nama_cek = $_REQUEST['nama_cek'];
            $tipe = substr($_REQUEST['tipe'], 0, 30);
            $ntipe = trim($tipe);
            $tgl_lpr = $_REQUEST['tgl_lpr'];
            $keterangan = $_REQUEST['keterangan'];
            $id_user = $_SESSION['id_user'];

            //validasi input data
            if (!preg_match("/^[0-9]*$/", $no_cek)) {
                $_SESSION['no_cekk'] = 'Form Nomor checklist harus diisi angka!';
                echo '<script language="javascript">window.history.back();</script>';
            } else {

                if (!preg_match("/^[a-zA-Z0-9.\/ -]*$/", $area)) {
                    $_SESSION['areak'] = 'Form area hanya boleh mengandung karakter huruf, angka, spasi, titik(.), minus(-) dan garis miring(/)';
                    echo '<script language="javascript">window.history.back();</script>';
                } else {

                    if (!preg_match("/^[a-zA-Z0-9.,() \/ -]*$/", $hasil)) {
                        $_SESSION['hasil'] = 'Form hasil hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-), kurung() dan garis miring(/)';
                        echo '<script language="javascript">window.history.back();</script>';
                    } else {

                        if (!preg_match("/^[a-zA-Z0-9.,_()%&@\/\r\n -]*$/", $nama_cek)) {
                            $_SESSION['nama_cekk'] = 'Form nama_cek Ringkas hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-), garis miring(/), kurung(), underscore(_), dan(&) persen(%) dan at(@)';
                            echo '<script language="javascript">window.history.back();</script>';
                        } else {

                            if (!preg_match("/^[a-zA-Z0-9., ]*$/", $ntipe)) {
                                $_SESSION['tipek'] = 'Form tipe  hanya boleh mengandung karakter huruf, angka, spasi, titik(.) dan koma(,)';
                                echo '<script language="javascript">window.history.back();</script>';
                            } else {

                                if (!preg_match("/^[0-9.-]*$/", $tgl_lpr)) {
                                    $_SESSION['tgl_lprk'] = 'Form Tanggal checklist hanya boleh mengandung angka dan minus(-)';
                                    echo '<script language="javascript">window.history.back();</script>';
                                } else {

                                    if (!preg_match("/^[a-zA-Z0-9.,()\/ -]*$/", $keterangan)) {
                                        $_SESSION['keterangank'] = 'Form Keterangan hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-), garis miring(/), dan kurung()';
                                        echo '<script language="javascript">window.history.back();</script>';
                                    } else {

                                     
                                        $result = mysqli_num_rows($cek);

                                        if ($result > 0) {
                                            $_SESSION['errDup'] = 'Nomor checklist sudah terpakai, gunakan yang lain!';
                                            echo '<script language="javascript">window.history.back();</script>';
                                        } else {

                                            $ekstensi = array('xls', 'xlsx', 'pdf');
                                            $file = $_FILES['file']['name'];
                                            $x = explode('.', $file);
                                            $eks = strtolower(end($x));
                                            $ukuran = $_FILES['file']['size'];
                                            $target_dir = "upload/ceklist/";

                                            if (!is_dir($target_dir)) {
                                                mkdir($target_dir, 0755, true);
                                            }

                                            //jika form file tidak kosong akan mengekse
                                            if ($file != "") {

                                                $rand = rand(1, 10000);
                                                $nfile = $rand . "-" . $file;
                                                if (in_array($eks, $ekstensi) == true) {
                                                    if ($ukuran < 2500000) {

                                                        move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . $nfile);

                                                        $query = mysqli_query($config, "INSERT INTO checklist(no_cek,hasil,area,nama_cek,tipe,tgl_lpr,
                                                                tgl_catat,file,keterangan,id_user)
                                                                VALUES('$no_cek','$hasil','$area','$nama_cek','$ntipe','$tgl_lpr',NOW(),'$nfile','$keterangan','$id_user')");

                                                        if ($query == true) {
                                                            $_SESSION['succAdd'] = 'SUKSES! Laporan berhasil ditambahkan';
                                                            header("Location: ./admin.php?page=ilc");
                                                            die();
                                                        } else {
                                                            $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                                                            echo '<script language="javascript">window.history.back();</script>';
                                                        }
                                                    } else {
                                                        $_SESSION['errSize'] = 'Ukuran file yang diupload terlalu besar!';
                                                        echo '<script language="javascript">window.history.back();</script>';
                                                    }
                                                } else {
                                                    $_SESSION['errFormat'] = 'Format file yang diperbolehkan hanya *.XLS, *.XLSX atau *.PDF!';
                                                    echo '<script language="javascript">window.history.back();</script>';
                                                }
                                            } else {
                                                $query = mysqli_query($config, "INSERT INTO checklist(no_cek,hasil,area,nama_cek,tipe,tgl_lpr,
                                                        tgl_catat,file,keterangan,id_user)
                                                        VALUES('$no_cek','$hasil','$area','$nama_cek','$ntipe','$tgl_lpr',NOW(),'','$keterangan','$id_user')");

                                                if ($query == true) {
                                                    $_SESSION['succAdd'] = 'SUKSES! Laporan berhasil ditambahkan';
                                                    header("Location: ./admin.php?page=ilc");
                                                    die();
                                                } else {
                                                    $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                                                    echo '<script language="javascript">window.history.back();</script>';
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    } else { ?>

        <!-- Row Start -->
        <div class="row">
            <!-- Secondary Nav START -->
            <div class="col s12">
                <nav class="secondary-nav">
                    <div class="nav-wrapper blue-grey darken-1">
                        <ul class="left">
                            <li class="waves-effect waves-light"><a href="?page=ilc&act=add" class="judul"><i class="material-icons">drafts</i> Tambah Laporan Checklist</a></li>
                        </ul>
                    </div>
                </nav>
            </div>
            <!-- Secondary Nav END -->
        </div>
        <!-- Row END -->

        <?php
        if (isset($_SESSION['errQ'])) {
            $errQ = $_SESSION['errQ'];
            echo '<div id="alert-message" class="row">
                            <div class="col m12">
                                <div class="card red lighten-5">
                                    <div class="card-content notif">
                                        <span class="card-title red-text"><i class="material-icons md-36">clear</i> ' . $errQ . '</span>
                                    </div>
                                </div>
                            </div>
                        </div>';
            unset($_SESSION['errQ']);
        }
        if (isset($_SESSION['errEmpty'])) {
            $errEmpty = $_SESSION['errEmpty'];
            echo '<div id="alert-message" class="row">
                            <div class="col m12">
                                <div class="card red lighten-5">
                                    <div class="card-content notif">
                                        <span class="card-title red-text"><i class="material-icons md-36">clear</i> ' . $errEmpty . '</span>
                                    </div>
                                </div>
                            </div>
                        </div>';
            unset($_SESSION['errEmpty']);
        }
        ?>

        <!-- Row form Start -->
        <div class="row jarak-form">

            <!-- Form START -->
            <form class="col s12" method="POST" action="?page=ilc&act=add" enctype="multipart/form-data">

                <!-- Row in form START -->
                <div class="row">
                    <div class="input-field col s6">
                        <?php
                        echo '<input id="no_cek" type="number" class="validate" name="no_cek" value="';
                        $sql = mysqli_query($config, "SELECT no_cek FROM checklist");
                        $no_cek = "1";
                        if (mysqli_num_rows($sql) == 0) {
                            echo $no_cek;
                        }

                        $result = mysqli_num_rows($sql);
                        $counter = 0;
                        while (list($no_cek) = mysqli_fetch_array($sql)) {
                            if (++$counter == $result) {
                                $no_cek++;
                                echo $no_cek;
                            }
                        }
                        echo '" required>';

                        if (isset($_SESSION['no_cekk'])) {
                            $no_cekk = $_SESSION['no_cekk'];
                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $no_cekk . '</div>';
                            unset($_SESSION['no_cekk']);
                        }
                        ?>
                        <label for="no_cek">Nomor</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="tipe" type="text" class="validate" name="tipe" required>
                        <?php
                        if (isset($_SESSION['tipek'])) {
                            $tipek = $_SESSION['tipek'];
                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $tipek . '</div>';
                            unset($_SESSION['tipek']);
                        }
                        ?>
                        <label for="tipe">Tipe</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="hasil" type="text" class="validate" name="hasil" required>
                        <?php
                        if (isset($_SESSION['hasil'])) {
                            $hasil = $_SESSION['hasil'];
                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $hasil . '</div>';
                            unset($_SESSION['hasil']);
                        }
                        ?>
                        <label for="hasil">Hasil laporan</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="area" type="text" class="validate" name="area" required>
                        <?php
                        if (isset($_SESSION['areak'])) {
                            $areak = $_SESSION['areak'];
                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $areak . '</div>';
                            unset($_SESSION['areak']);
                        }
                        if (isset($_SESSION['errDup'])) {
                            $errDup = $_SESSION['errDup'];
                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $errDup . '</div>';
                            unset($_SESSION['errDup']);
                        }
                        ?>
                        <label for="area">Area</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="tgl_lpr" type="text" name="tgl_lpr" class="datepicker" required>
                        <?php
                        if (isset($_SESSION['tgl_lprk'])) {
                            $tgl_lprk = $_SESSION['tgl_lprk'];
                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $tgl_lprk . '</div>';
                            unset($_SESSION['tgl_lprk']);
                        }
                        ?>
                        <label for="tgl_lpr">Tanggal laporan</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="keterangan" type="text" class="validate" name="keterangan" required>
                        <?php
                        if (isset($_SESSION['keterangank'])) {
                            $keterangank = $_SESSION['keterangank'];
                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $keterangank . '</div>';
                            unset($_SESSION['keterangank']);
                        }
                        ?>
                        <label for="keterangan">Keterangan</label>
                    </div>
                    <div class="input-field col s6">
                        
                        <textarea id="nama_cek" class="materialize-textarea validate" name="nama_cek" required></textarea>
                        <?php
                        if (isset($_SESSION['nama_cekk'])) {
                            $nama_cekk = $_SESSION['nama_cekk'];
                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $nama_cekk . '</div>';
                            unset($_SESSION['nama_cekk']);
                        }
                        ?>
                        <label for="nama_cek">Nama</label>
                    </div>
                    <div class="input-field col s6">
                        <div class="file-field input-field">
                            <div class="btn light-green darken-1">
                                <span>File</span>
                                <input type="file" id="file" name="file">
                            </div>
                            <div class="file-path-wrapper">
                                <input class="file-path validate" type="text" placeholder="Upload file/scan checklist">
                                <?php
                                if (isset($_SESSION['errSize'])) {
                                    $errSize = $_SESSION['errSize'];
                                    echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $errSize . '</div>';
                                    unset($_SESSION['errSize']);
                                }
                                if (isset($_SESSION['errFormat'])) {
                                    $errFormat = $_SESSION['errFormat'];
                                    echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $errFormat . '</div>';
                                    unset($_SESSION['errFormat']);
                                }
                                ?>
                                <small class="red-text">*Format file yang diperbolehkan *.XLSX, *.XLS, *.PDF dan ukuran maksimal file 2 MB!</small>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row in form END -->

                <div class="row">
                    <div class="col 6">
                        <button type="submit" name="submit" class="btn-large blue waves-effect waves-light">SIMPAN <i class="material-icons">done</i></button>
                    </div>
                    <div class="col 6">
                        <a href="?page=ilc" class="btn-large deep-orange waves-effect waves-light">BATAL <i class="material-icons">clear</i></a>
                    </div>
                </div>

            </form>
            <!-- Form END -->

        </div>
        <!-- Row form END -->

<?php
    }
}
?>