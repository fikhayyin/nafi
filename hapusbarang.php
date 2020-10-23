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

    $id_brg = mysqli_real_escape_string($config, $_REQUEST['id_brg']);
    $query = mysqli_query($config, "SELECT * FROM barang WHERE id_brg='$id_brg'");

    if (mysqli_num_rows($query) > 0) {
        $no = 1;
        while ($row = mysqli_fetch_array($query)) {

            if ($_SESSION['id_user'] != $row['id_user'] and $_SESSION['id_user'] != 1 and $_SESSION['id_user'] != 2) {
                echo '<script language="javascript">
                        window.alert("ERROR! Anda tidak memiliki hak akses untuk menghapus data ini");
                        window.location.href="./admin.php?page=ibm";
                      </script>';
            } else {

                echo '
                <!-- Row form Start -->
				<div class="row jarak-card">
				    <div class="col m12">
                    <div class="card">
                        <div class="card-content">
				        <table>
				            <thead class="red lighten-5 red-text">
				                <div class="confir red-text"><i class="material-icons md-36">error_outline</i>
				                Apakah anda yakin ingin mengapus data barang berikut?</div>
				            </thead>

				            <tbody>
				                <tr>
				                    <td width="13%">Nomor</td>
				                    <td width="1%">:</td>
				                    <td width="86%">' . $row['no_brg'] . '</td>
                                </tr>                                
    			                <tr>
                                    <td width="13%">Tanggal Penerimaan</td>
                                    <td width="1%">:</td>
                                    <td width="86%">' . indoDate($row['tgl_brg']) . '</td>
                                </tr>
				                <tr>
				                    <td width="13%">Merk</td>
				                    <td width="1%">:</td>
				                    <td width="86%">' . $row['merk_brg'] . '</td>
                                </tr>
                                <tr>
				                    <td width="13%">Tipe</td>
				                    <td width="1%">:</td>
				                    <td width="86%">' . $row['tipe'] . '</td>
				                </tr>
    			                <tr>
    		                        <td width="13%">Nama Equipment</td>
    		                        <td width="1%">:</td>
    		                        <td width="86%">' . $row['nama_brg'] . '</td>
    			                    </tr>
    			                <tr>
    			                    <td width="13%">Serial Number</td>
    			                    <td width="1%">:</td>
    			                    <td width="86%">' . $row['jumlah_brg'] . '</td>
    			                </tr>
    			                <tr>
    			                    <td width="13%">Lokasi</td>
    			                    <td width="1%">:</td>
    			                    <td width="86%">' . $row['lokasi_brg'] . '</td>
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
        	                <a href="?page=ibm&act=del&submit=yes&id_brg=' . $row['id_brg'] . '" class="btn-large deep-orange waves-effect waves-light white-text">HAPUS <i class="material-icons">delete</i></a>
        	                <a href="?page=ibm" class="btn-large blue waves-effect waves-light white-text">BATAL <i class="material-icons">clear</i></a>
    	                </div>
    	            </div>
                </div>
            </div>
            <!-- Row form END -->';

                if (isset($_REQUEST['submit'])) {
                    $id_brg = $_REQUEST['id_brg'];

                    //jika ada file akan mengekseskusi script dibawah ini
                    if (!empty($row['file'])) {
                        unlink("upload/" . $row['file']);
                        $query = mysqli_query($config, "DELETE FROM barang WHERE id_brg='$id_brg'");
                        $query2 = mysqli_query($config, "DELETE FROM tbl_disposisi WHERE id_brg='$id_brg'");

                        if ($query == true) {
                            $_SESSION['succDel'] = 'SUKSES! Equipment berhasil dihapus<br/>';
                            header("Location: ./admin.php?page=ibm");
                            die();
                        } else {
                            $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                            echo '<script language="javascript">
                                window.location.href="./admin.php?page=ibm&act=del&id_brg=' . $id_brg . '";
                              </script>';
                        }
                    } else {

                        //jika tidak ada file akan mengekseskusi script dibawah ini
                        $query = mysqli_query($config, "DELETE FROM barang WHERE id_brg='$id_brg'");
                        $query2 = mysqli_query($config, "DELETE FROM tbl_disposisi WHERE id_brg='$id_brg'");

                        if ($query == true) {
                            $_SESSION['succDel'] = 'SUKSES! Equipment berhasil dihapus<br/>';
                            header("Location: ./admin.php?page=ibm");
                            die();
                        } else {
                            $_SESSION['errQ'] = 'ERROR! Ada masalah dengan query';
                            echo '<script language="javascript">
                                window.location.href="./admin.php?page=ibm&act=del&id_brg=' . $id_brg . '";
                              </script>';
                        }
                    }
                }
            }
        }
    }
}
