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
                $_SESSION['no_brg'] = 'Form Nomor Equipment harus diisi angka!';
                echo '<script language="javascript">window.history.back();</script>';
            } else {

                if (!preg_match("/^[a-zA-Z0-9.,()\/ -]*$/", $lokasi_brg)) {
                    $_SESSION['lokasi_brg'] = 'Form lokasi hanya boleh mengandung karakter huruf, angka, spasi, titik(.), minus(-) dan garis miring(/)';
                    echo '<script language="javascript">window.history.back();</script>';
                } else {

                    if (!preg_match("/^[a-zA-Z0-9.,() \/ -]*$/", $jumlah_brg)) {
                        $_SESSION['jumlah_brg'] = 'Form jumlah hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-),kurung() dan garis miring(/)';
                        echo '<script language="javascript">window.history.back();</script>';
                    } else {

                        if (!preg_match("/^[a-zA-Z0-9.,_()%&@\/\r\n -]*$/", $nama_brg)) {
                            $_SESSION['nama_brg'] = 'Form nama_barang hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-), garis miring(/), kurung(), underscore(_), dan(&) persen(%) dan at(@)';
                            echo '<script language="javascript">window.history.back();</script>';
                        } else {

                            if (!preg_match("/^[a-zA-Z0-9., ]*$/", $nmerk_brg)) {
                                $_SESSION['merk_brg'] = 'Form merk_brg hanya boleh mengandung karakter huruf, angka, spasi, titik(.) dan koma(,)';
                                echo '<script language="javascript">window.history.back();</script>';
                            } else {

                                if (!preg_match("/^[a-zA-Z0-9., -]*$/", $tipe)) {
                                    $_SESSION['tipe'] = 'Form tipe hanya boleh mengandung karakter huruf, angka, spasi, titik(.) dan koma(,) dan minus (-)';
                                    echo '<script language="javascript">window.history.back();</script>';
                                } else {

                                    if (!preg_match("/^[0-9.-]*$/", $tgl_brg)) {
                                        $_SESSION['tgl_brg'] = 'Form Tanggal penerimaan Equipment hanya boleh mengandung angka dan minus(-)';
                                        echo '<script language="javascript">window.history.back();</script>';
                                    } else {

                                        if (!preg_match("/^[a-zA-Z0-9.,()\/ -]*$/", $keterangan)) {
                                            $_SESSION['keterangan'] = 'Form Keterangan hanya boleh mengandung karakter huruf, angka, spasi, titik(.), koma(,), minus(-), garis miring(/), dan kurung()';
                                            echo '<script language="javascript">window.history.back();</script>';
                                        } else {

                                            $cek = mysqli_query($config, "SELECT * FROM barang WHERE lokasi_brg='$lokasi_brg'");
                                            $result = mysqli_num_rows($cek);

                                            if ($result < 0) {
                                                $_SESSION['errDup'] = 'nama_brg sudah terpakai, gunakan yang lain!';
                                                echo '<script language="javascript">window.history.back();</script>';
                                            } else {

                                                $ekstensi = array('');
                                                $file = $_FILES['file']['name'];
                                                $x = explode('.', $file);
                                                $eks = strtolower(end($x));
                                                $ukuran = $_FILES['file']['size'];

                                                //jika form file tidak kosong akan mengeksekusi script dibawah ini
                                                if ($file != "") {

                                                    $rand = rand(1, 10000);
                                                    $nfile = $rand . "-" . $file;

                                                    //validasi file
                                                    if (in_array($eks, $ekstensi) == true) {
                                                        if ($ukuran < 2500000) {

                                                            move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . $nfile);

                                                            $query = mysqli_query($config, "INSERT INTO barang(no_brg,lokasi_brg,jumlah_brg,nama_brg,merk_brg,tipe,tgl_brg,
                                                                    tgl_terima,file,keterangan,id_user)
                                                                        VALUES('$no_brg','$lokasi_brg','$jumlah_brg','$nama_brg','$nmerk_brg','$tipe','$tgl_brg',NOW(),'$nfile','$keterangan','$id_user')");

                                                            if ($query == true) {
                                                                $_SESSION['succAdd'] = '';
                                                                header("Location: ./admin.php?page=ibm");
                                                                die();
                                                            } else {
                                                                $_SESSION['errQ'] = '';
                                                                echo '<script language="javascript">window.history.back();</script>';
                                                            }
                                                        } else {
                                                            $_SESSION['errSize'] = '';
                                                            echo '<script language="javascript">window.history.back();</script>';
                                                        }
                                                    } else {
                                                        $_SESSION['errFormat'] = '';
                                                        echo '<script language="javascript">window.history.back();</script>';
                                                    }
                                                } else {

                                                    //jika form file kosong akan mengeksekusi script dibawah ini
                                                    $query = mysqli_query($config, "INSERT INTO barang(no_brg,lokasi_brg,jumlah_brg,nama_brg,merk_brg,tipe,tgl_brg, tgl_terima,file,keterangan,id_user)
                                                            VALUES('$no_brg','$lokasi_brg','$jumlah_brg','$nama_brg','$nmerk_brg','$tipe','$tgl_brg',NOW(),'','$keterangan','$id_user')");

                                                    if ($query == true) {
                                                        $_SESSION['succAdd'] = 'SUKSES! Equipment berhasil ditambahkan';
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
        }
    } else { ?>

        <!-- Row Start -->
        <div class="row">
            <!-- Secondary Nav START -->
            <div class="col s12">
                <nav class="secondary-nav">
                    <div class="nav-wrapper blue-grey darken-1">
                        <ul class="left">
                            <li class="waves-effect waves-light"><a href="?page=ibm&act=tbh" class="judul"><i class="material-icons">event_available</i> Tambah Data Equipment Masuk</a></li>
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
            <form class="col s12" method="POST" action="?page=ibm&act=tbh" enctype="multipart/form-data">

                <!-- Row in form START -->
                <div class="row">
                    <div class="input-field col s6">
                        <?php
                        echo '<input id="no_brg" type="number" class="validate" name="no_brg" value="';
                        $sql = mysqli_query($config, "SELECT no_brg FROM barang");
                        $no_brg = "1";
                        if (mysqli_num_rows($sql) == 0) {
                            echo $no_brg;
                        }

                        $result = mysqli_num_rows($sql);
                        $counter = 0;
                        while (list($no_brg) = mysqli_fetch_array($sql)) {
                            if (++$counter == $result) {
                                $no_brg++;
                                echo $no_brg;
                            }
                        }
                        echo '" required>';

                        if (isset($_SESSION['no_brg'])) {
                            $no_brg = $_SESSION['no_brg'];
                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $no_brg . '</div>';
                            unset($_SESSION['no_brg']);
                        }
                        ?>
                        <label for="no_brg">Nomor</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="merk_brg" type="text" class="validate" name="merk_brg" required>
                        <?php
                        if (isset($_SESSION['merk_brg'])) {
                            $merk_brg = $_SESSION['merk_brg'];
                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $merk_brg . '</div>';
                            unset($_SESSION['merk_brg']);
                        }
                        ?>
                        <label for="merk_brg">Merk</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="jumlah_brg" type="text" class="validate" name="jumlah_brg" required>
                        <?php
                        if (isset($_SESSION['jumlah_brg'])) {
                            $jumlah_brg = $_SESSION['jumlah_brg'];
                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $jumlah_brg . '</div>';
                            unset($_SESSION['jumlah_brg']);
                        }
                        ?>
                        <label for="jumlah_brg">Serial Number</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="tipe" type="text" class="validate" name="tipe" required>
                        <?php
                        if (isset($_SESSION['tipe'])) {
                            $tipe = $_SESSION['tipe'];
                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $tipe . '</div>';
                            unset($_SESSION['tipe']);
                        }
                        ?>
                        <label for="tipe">Tipe</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="lokasi_brg" type="text" class="validate" name="lokasi_brg" required>
                        <?php
                        if (isset($_SESSION['lokasi_brg'])) {
                            $lokasi_brg = $_SESSION['lokasi_brg'];
                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $lokasi_brg . '</div>';
                            unset($_SESSION['lokasi_brg']);
                        }
                        if (isset($_SESSION['errDup'])) {
                            $errDup = $_SESSION['errDup'];
                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $errDup . '</div>';
                            unset($_SESSION['errDup']);
                        }
                        ?>
                        <label for="lokasi_brg">Lokasi</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="tgl_brg" type="text" name="tgl_brg" class="datepicker" required>
                        <?php
                        if (isset($_SESSION['tgl_brg'])) {
                            $tgl_brg = $_SESSION['tgl_brg'];
                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $tgl_brg . '</div>';
                            unset($_SESSION['tgl_brg']);
                        }
                        ?>
                        <label for="tgl_brg">Tanggal Penerimaan</label>
                    </div>
                    <div class="input-field col s6">
                        <textarea id="nama_brg" class="materialize-textarea validate" name="nama_brg" required></textarea>
                        <?php
                        if (isset($_SESSION['nama_brg'])) {
                            $nama_brg = $_SESSION['nama_brg'];
                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $nama_brg . '</div>';
                            unset($_SESSION['nama_brg']);
                        }
                        ?>
                        <label for="nama_brg">Nama Equipment</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="keterangan" type="text" class="validate" name="keterangan" required>
                        <?php
                        if (isset($_SESSION['keterangan'])) {
                            $keterangan = $_SESSION['keterangan'];
                            echo '<div id="alert-message" class="callout bottom z-depth-1 red lighten-4 red-text">' . $keterangan . '</div>';
                            unset($_SESSION['keterangan']);
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
?>