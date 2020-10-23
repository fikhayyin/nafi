<?php
//cek session
if (empty($_SESSION['admin'])) {
    $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
    header("Location: ./");
    die();
} else {

    if (isset($_SESSION['errQ'])) {
        $errQ = $_SESSION['errQ'];
        echo '<div id="alert-message" class="row jarak-card">
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

    $id_cek = mysqli_real_escape_string($config, $_REQUEST['id_cek']);
    $query = mysqli_query($config, "SELECT * FROM checklist WHERE id_cek='$id_cek'");

    if (mysqli_num_rows($query) > 0) {
        $no = 1;
        while ($row = mysqli_fetch_array($query)) {

            if ($_SESSION['id_user'] != $row['id_user'] and $_SESSION['id_user'] != 1 and $_SESSION['id_user'] != 2) {
                echo '<script language="javascript">
                        window.alert("ERROR! Anda tidak memiliki hak akses untuk menghapus laporan ini");
                        window.location.href="./admin.php?page=ilc";
                      </script>';
            } else {

                echo '<!-- Row form Start -->
				<div class="row jarak-card">
				    <div class="col m12">
                        <div class="card">
                            <div class="card-content">
        				        <table>
        				            <thead class="red lighten-5 red-text">
        				                <div class="confir red-text"><i class="material-icons md-36">error_outline</i>
        				                Apakah anda yakin ingin menghapus laporan ini?</div>
        				            </thead>

        				            <tbody>
        				                <tr>
        				                    <td width="13%">Nomor</td>
        				                    <td width="1%">:</td>
        				                    <td width="86%">' . $row['no_cek'] . '</td>
        				                </tr>
        				                <tr>
        				                    <td width="13%">Tipe</td>
        				                    <td width="1%">:</td>
        				                    <td width="86%">' . $row['tipe'] . '</td>
        				                </tr>
        				                <tr>
        				                    <td width="13%">Nama</td>
        				                    <td width="1%">:</td>
        				                    <td width="86%">' . $row['nama_cek'] . '</td>
        				                </tr>
        				                <tr>
        				                    <td width="13%">File</td>
        				                    <td width="1%">:</td>
                                            <td width="86%">';
                if (!empty($row['file'])) {
                    echo ' <a class="blue-text" href="?page=gsk&act=fsk&id_cek=' . $row['id_cek'] . '">' . $row['file'] . '</a>';
                } else {
                    echo ' Tidak ada file yang diupload';
                }
                echo '</td>
                                        </tr>
        				                <tr>
        				                    <td width="13%">Hasil laporan</td>
        				                    <td width="1%">:</td>
        				                    <td width="86%">' . $row['hasil'] . '</td>
        				                </tr>
        				                <tr>
        				                    <td width="13%">Area</td>
        				                    <td width="1%">:</td>
        				                    <td width="86%">' . $row['area'] . '</td>
        				                </tr>
        				                <tr>
        				                    <td width="13%">Tanggal laporan</td>
        				                    <td width="1%">:</td>
        				                    <td width="86%">' . indoDate($row['tgl_lpr']) . '</td>
        				                </tr>
                                        <tr>
                                            <td width="13%">Keterangan</td>
                                            <td width="1%">:</td>
                                            <td width="86%">' . $row['keterangan'] . '</td>
                                        </tr>
        				            </tbody>
    				   		    </table>
				            </div>
                            <div class="card-action">
        		                <a href="?page=ilc&act=del&submit=yes&id_cek=' . $row['id_cek'] . '" class="btn-large deep-orange waves-effect waves-light white-text">HAPUS <i class="material-icons">delete</i></a>
        		                <a href="?page=ilc" class="btn-large blue waves-effect waves-light white-text">BATAL <i class="material-icons">clear</i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Row form END -->';

                if (isset($_REQUEST['submit'])) {
                    $id_cek = $_REQUEST['id_cek'];

                    //jika ada file akan mengekseskusi script dibawah ini
                    if (!empty($row['file'])) {

                        unlink("upload/ceklist/" . $row['file']);
                        $query = mysqli_query($config, "DELETE FROM checklist WHERE id_cek='$id_cek'");

                        if ($query == true) {
                            $_SESSION['succDel'] = 'SUKSES! Data berhasil dihapus<br/>';
                            header("Location: ./admin.php?page=ilc");
                            die();
                        } else {
                            $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                            echo '<script language="javascript">
                                    window.location.href="./admin.php?page=ilc&act=del&id_cek=' . $id_cek . '";
                                  </script>';
                        }
                    } else {

                        //jika tidak ada file akan mengekseskusi script dibawah ini
                        $query = mysqli_query($config, "DELETE FROM checklist WHERE id_cek='$id_cek'");

                        if ($query == true) {
                            $_SESSION['succDel'] = 'SUKSES! Data berhasil dihapus<br/>';
                            header("Location: ./admin.php?page=ilc");
                            die();
                        } else {
                            $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                            echo '<script language="javascript">
                                    window.location.href="./admin.php?page=ilc&act=del&id_cek=' . $id_cek . '";
                                  </script>';
                        }
                    }
                }
            }
        }
    }
}
