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
            $_REQUEST['no_brg'] == "" || $_REQUEST['lokasi_brg'] == "" || $_REQUEST['jumlah_brg'] == "" || $_REQUEST['nama_brg'] == ""
            || $_REQUEST['merk_brg'] == "" || $_REQUEST['tipe'] == "" || $_REQUEST['tgl_brg'] == ""  || $_REQUEST['keterangan'] == ""
        ) {
            $_SESSION['errEmpty'] = 'ERROR! Semua form wajib diisi';
            echo '<script language="javascript">window.history.back();</script>';
        } else {

            $no_brg = $_REQUEST['no_brg'];
            $lokasi_brg = $_REQUEST['lokasi_brg'];
            $jumlah_brg = $_REQUEST['jumlah_brg'];
            $nama_brg = $_REQUEST['nama_brg'];
            $merk_brg = substr($_REQUEST['merk_brg'], 0, 30);
            $nmerk_brg = trim($merk_brg);
            $tipe = $_REQUEST['tipe'];
            $tgl_brg = $_REQUEST['tgl_brg'];
            $keterangan = $_REQUEST['keterangan'];
            $id_user = $_SESSION['id_user'];

            //validasi input data
            if (!preg_match("/^[0-9]*$/", $no_brg)) {
                $_SESSION['eno_brg'] = 'Form Nomor Equipment harus diisi angka!';
                echo '<script language="javascript">window.history.back();</script>';
            } else {

                if (!preg_match("/^[a-zA-Z0-9.\/ , -]*$/", $lokasi_brg)) {
                    $_SESSION['elokasi_brg'] = 'Form lokasi Equipment hanya boleh mengandung karakter huruf, angka, spasi,koma(,), titik(.), minus(-) dan garis miring(/)';
                    echo '<script language="javascript">window.history.back();</script>';
                } else {

                    if (!preg_match("/^[a-zA-Z0-9.,() \/ -]*$/", $jumlah_brg)) {
                        $_SESSION['ejumlah_brg'] = 'Form Jumlah Equipment hanya boleh diisi huruf dan angka';
                        echo '<script language="javascript">window.history.back();</script>';
                    } else {

                        if (!preg_match("/^[a-zA-Z0-9.,_()%&@\/\r\n -]*$/", $nama_brg)) {
                            $_SESSION['enama_brg'] = 'Form nama_brg hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-), garis miring(/), kurung(), underscore(_), dan(&) persen(%) dan at(@)';
                            echo '<script language="javascript">window.history.back();</script>';
                        } else {

                            if (!preg_match("/^[a-zA-Z0-9., ]*$/", $nmerk_brg)) {
                                $_SESSION['emerk_brg'] = 'Form merk_brg hanya boleh mengandung karakter huruf, angka, spasi, titik(.) dan koma(,)';
                                echo '<script language="javascript">window.history.back();</script>';
                            } else {

                                if (!preg_match("/^[a-zA-Z0-9., -]*$/", $tipe)) {
                                    $_SESSION['etipe'] = 'Form tipe hanya boleh mengandung karakter huruf, angka, spasi, titik(.) dan koma(,) dan minus (-)';
                                    echo '<script language="javascript">window.history.back();</script>';
                                } else {

                                    if (!preg_match("/^[0-9.-]*$/", $tgl_brg)) {
                                        $_SESSION['etgl_brg'] = 'Form Tanggal penerimaan Equipment hanya boleh mengandung angka dan minus(-)';
                                        echo '<script language="javascript">window.history.back();</script>';
                                    } else {

                                        if (!preg_match("/^[a-zA-Z0-9.,()\/ -]*$/", $keterangan)) {
                                            $_SESSION['eketerangan'] = 'Form Keterangan hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-), garis miring(/), dan kurung()';
                                            echo '<script language="javascript">window.history.back();</script>';
                                        } else {


                                            if (!is_dir($target_dir)) {
                                                mkdir($target_dir, 0755, true);
                                            }

                                            //jika form file tidak kosong akan mengeksekusi script dibawah ini
                                            if ($file != "") {

                                                $rand = rand(1, 10000);
                                                $nfile = $rand . "-" . $file;

                                                //validasi file
                                                if (in_array($eks, $ekstensi) == true) {
                                                    if ($ukuran < 2300000) {

                                                        $id_brg = $_REQUEST['id_brg'];
                                                        $query = mysqli_query($config, "SELECT file FROM barang WHERE id_brg='$id_brg'");
                                                        list($file) = mysqli_fetch_array($query);

                                                        //jika file tidak kosong akan mengeksekusi script dibawah ini
                                                        if (!empty($file)) {
                                                            unlink($target_dir . $file);

                                                            move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . $nfile);

                                                            $query = mysqli_query($config, "UPDATE barang SET no_brg='$no_brg',lokasi_brg='$lokasi_brg',jumlah_brg='$jumlah_brg',nama_brg='$nama_brg',merk_brg='$nmerk_brg',tipe='$tipe',tgl_brg='$tgl_brg',file='$nfile',keterangan='$keterangan',id_user='$id_user' WHERE id_brg='$id_brg'");

                                                            if ($query == true) {
                                                                $_SESSION['succEdit'] = 'SUKSES! Data berhasil diupdate';
                                                                header("Location: ./admin.php?page=ibm");
                                                                die();
                                                            } else {
                                                                $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                                                                echo '<script language="javascript">window.history.back();</script>';
                                                            }
                                                        } else {

                                                            //jika file kosong akan mengeksekusi script dibawah ini
                                                            move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . $nfile);

                                                            $query = mysqli_query($config, "UPDATE barang SET no_brg='$no_brg',lokasi_brg='$lokasi_brg',jumlah_brg='$jumlah_brg',nama_brg='$nama_brg',merk_brg='$nmerk_brg',tipe='$tipe',tgl_brg='$tgl_brg',file='$nfile',keterangan='$keterangan',id_user='$id_user' WHERE id_brg='$id_brg'");

                                                            if ($query == true) {
                                                                $_SESSION['succEdit'] = 'SUKSES! Data berhasil diupdate';
                                                                header("Location: ./admin.php?page=ibm");
                                                                die();
                                                            } else {
                                                                $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                                                                echo '<script language="javascript">window.history.back();</script>';
                                                            }
                                                        }
                                                    } else {
                                                        $_SESSION['errSize'] = 'Ukuran file yang diupload terlalu besar!';
                                                        echo '<script language="javascript">window.history.back();</script>';
                                                    }
                                                } else {
                                                    $_SESSION['errFormat'] = 'Format file yang diperbolehkan hanya *.JPG, *.PNG, *.DOC, *.DOCX atau *.PDF!';
                                                    echo '<script language="javascript">window.history.back();</script>';
                                                }
                                            } else {

                                                //jika form file kosong akan mengeksekusi script dibawah ini
                                                $id_brg = $_REQUEST['id_brg'];

                                                $query = mysqli_query($config, "UPDATE barang SET no_brg='$no_brg',lokasi_brg='$lokasi_brg',jumlah_brg='$jumlah_brg',nama_brg='$nama_brg',merk_brg='$nmerk_brg',tipe='$tipe',tgl_brg='$tgl_brg',keterangan='$keterangan',id_user='$id_user' WHERE id_brg='$id_brg'");

                                                if ($query == true) {
                                                    $_SESSION['succEdit'] = 'SUKSES! Equipment berhasil diupdate';
                                                    header("Location: ./admin.php?page=ibm");
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
    } else {

        $id_brg = mysqli_real_escape_string($config, $_REQUEST['id_brg']);
        $query = mysqli_query($config, "SELECT id_brg, no_brg, lokasi_brg, jumlah_brg, nama_brg, merk_brg, tipe, tgl_brg, keterangan, id_user FROM barang WHERE id_brg='$id_brg'");
        list($id_brg, $no_brg, $lokasi_brg, $jumlah_brg, $nama_brg, $merk_brg, $tipe, $tgl_brg, $keterangan, $id_user) = mysqli_fetch_array($query);

        if ($_SESSION['id_user'] != $id_user and $_SESSION['id_user'] != 1 and $_SESSION['id_user'] != 2) {
            echo '<script language="javascript">
                    window.alert("ERROR! Anda tidak memiliki hak akses untuk mengedit data ini");
                    window.location.href="./admin.php?page=ibm";
                  </script>';
        } else { ?>

            <!-- Row Start -->
            <div class="row">
                <!-- Secondary Nav START -->
                <div class="col s12">
                    <nav class="secondary-nav">
                        <div class="nav-wrapper blue-grey darken-1">
                            <ul class="left">
                                <li class="waves-effect waves-light"><a href="#" class="judul"><i class="material-icons">edit</i>Edit Data Equipment Masuk</a></li>
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
                <form class="col s12" method="POST" action="?page=ibm&act=edit" enctype="multipart/form-data">

                    <!-- Row in form START -->
                    <div class="row">
                        <div class="input-field col s6">
                            <input type="hidden" name="id_brg" value="<?php echo $id_brg; ?>">
                            <input id="no_brg" type="number" class="validate" value="<?php echo $no_brg; ?>" name="no_brg" required>
                            <?php
                            if (isset($_SESSION['eno_brg'])) {
                                $eno_brg = $_SESSION['eno_brg'];
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $eno_brg . '</div>';
                                unset($_SESSION['eno_brg']);
                            }
                            ?>
                            <label for="no_brg">Nomor</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="merk_brg" type="text" class="validate" name="merk_brg" value="<?php echo $merk_brg; ?>" required>
                            <?php
                            if (isset($_SESSION['emerk_brg'])) {
                                $emerk_brg = $_SESSION['emerk_brg'];
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $emerk_brg . '</div>';
                                unset($_SESSION['emerk_brg']);
                            }
                            ?>
                            <label for="merk_brg">Merk</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="jumlah_brg" type="text" class="validate" name="jumlah_brg" value="<?php echo $jumlah_brg; ?>" required>
                            <?php
                            if (isset($_SESSION['ejumlah_brg'])) {
                                $ejumlah_brg = $_SESSION['ejumlah_brg'];
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $ejumlah_brg . '</div>';
                                unset($_SESSION['ejumlah_brg']);
                            }
                            ?>
                            <label for="jumlah_brg">Serial Number</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="tipe" type="text" class="validate" name="tipe" value="<?php echo $tipe; ?>" required>
                            <?php
                            if (isset($_SESSION['etipe'])) {
                                $etipe = $_SESSION['etipe'];
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $etipe . '</div>';
                                unset($_SESSION['etipe']);
                            }
                            ?>
                            <label for="tipe">Tipe</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="lokasi_brg" type="text" class="validate" name="lokasi_brg" value="<?php echo $lokasi_brg; ?>" required>
                            <?php
                            if (isset($_SESSION['elokasi_brg'])) {
                                $elokasi_brg = $_SESSION['elokasi_brg'];
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $elokasi_brg . '</div>';
                                unset($_SESSION['elokasi_brg']);
                            }
                            ?>
                            <label for="lokasi_brg">Lokasi</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="tgl_brg" type="text" name="tgl_brg" class="datepicker" value="<?php echo $tgl_brg; ?>" required>
                            <?php
                            if (isset($_SESSION['etgl_brg'])) {
                                $etgl_brg = $_SESSION['etgl_brg'];
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $etgl_brg . '</div>';
                                unset($_SESSION['etgl_brg']);
                            }
                            ?>
                            <label for="tgl_brg">Tanggal Penerimaan</label>
                        </div>
                        <div class="input-field col s6">
                            <textarea id="nama_brg" class="materialize-textarea validate" name="nama_brg" required><?php echo $nama_brg; ?></textarea>
                            <?php
                            if (isset($_SESSION['enama_brg'])) {
                                $enama_brg = $_SESSION['enama_brg'];
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $enama_brg . '</div>';
                                unset($_SESSION['enama_brg']);
                            }
                            ?>
                            <label for="nama_brg">Nama Equipment</label>
                        </div>
                        <div class="input-field col s6">
                            <input id="keterangan" type="text" class="validate" name="keterangan" value="<?php echo $keterangan; ?>" required>
                            <?php
                            if (isset($_SESSION['eketerangan'])) {
                                $eketerangan = $_SESSION['eketerangan'];
                                echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $eketerangan . '</div>';
                                unset($_SESSION['eketerangan']);
                            }
                            ?>
                            <label for="keterangan">Keterangan</label>
                        </div>
                    </div>
                    <!-- Row in form END -->

                    <div class="row">
                        <div class="col 6">
                            <button type="submit" name="submit" class="btn-large blue waves-effect waves-light">SIMPAN <i class="material-icons">done</i></button>
                        </div>
                        <div class="col 6">
                            <a href="?page=ibm" class="btn-large deep-orange waves-effect waves-light">BATAL <i class="material-icons">clear</i></a>
                        </div>
                    </div>

                </form>
                <!-- Form END -->

            </div>
            <!-- Row form END -->

<?php
        }
    }
}
?>