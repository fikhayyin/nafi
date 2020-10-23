<?php
//cek session
if (empty($_SESSION['admin'])) {
    $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
    header("Location: ./");
    die();
} else {

    if ($_SESSION['admin'] != 1 and $_SESSION['admin'] != 3) {
        echo '<script language="javascript">
                    window.alert("ERROR! Anda tidak memiliki hak akses untuk membuka halaman ini");
                    window.location.href="./logout.php";
                  </script>';
    } else {

        if (isset($_REQUEST['act'])) {
            $act = $_REQUEST['act'];
            switch ($act) {
                case 'add':
                    include "tambahceklist.php";
                    break;
                case 'edit':
                    include "editceklist.php";
                    break;
                case 'del':
                    include "hapusceklist.php";
                    break;
            }
        } else {

            $query = mysqli_query($config, "SELECT checklist FROM pengaturan");
            list($checklist) = mysqli_fetch_array($query);

            //pagging
            $limit = $checklist;
            $pg = @$_GET['pg'];
            if (empty($pg)) {
                $curr = 0;
                $pg = 1;
            } else {
                $curr = ($pg - 1) * $limit;
            } ?>

            <!-- Row Start -->
            <div class="row">
                <!-- Secondary Nav START -->
                <div class="col s12">
                    <div class="z-depth-1">
                        <nav class="secondary-nav">
                            <div class="nav-wrapper blue-grey darken-1">
                                <div class="col m7">
                                    <ul class="left">
                                        <li class="waves-effect waves-light hide-on-small-only"><a href="?page=ilc" class="judul"><i class="material-icons">drafts</i>Laporan Checklist</a></li>
                                        <li class="waves-effect waves-light">
                                            <a href="?page=ilc&act=add"><i class="material-icons md-24">add_circle</i> Tambah laporan</a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col m5 hide-on-med-and-down">
                                    <form method="post" action="?page=ilc">
                                        <div class="input-field round-in-box">
                                            <input id="search" type="search" name="cari" placeholder="Ketik dan tekan enter mencari data..." required>
                                            <label for="search"><i class="material-icons md-dark">search</i></label>
                                            <input type="submit" name="submit" class="hidden">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </nav>
                    </div>
                </div>
                <!-- Secondary Nav END -->
            </div>
            <!-- Row END -->

            <?php
            if (isset($_SESSION['succAdd'])) {
                $succAdd = $_SESSION['succAdd'];
                echo '<div id="alert-message" class="row">
                                <div class="col m12">
                                    <div class="card green lighten-5">
                                        <div class="card-content notif">
                                            <span class="card-title green-text"><i class="material-icons md-36">done</i> ' . $succAdd . '</span>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                unset($_SESSION['succAdd']);
            }
            if (isset($_SESSION['succEdit'])) {
                $succEdit = $_SESSION['succEdit'];
                echo '<div id="alert-message" class="row">
                                <div class="col m12">
                                    <div class="card green lighten-5">
                                        <div class="card-content notif">
                                            <span class="card-title green-text"><i class="material-icons md-36">done</i> ' . $succEdit . '</span>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                unset($_SESSION['succEdit']);
            }
            if (isset($_SESSION['succDel'])) {
                $succDel = $_SESSION['succDel'];
                echo '<div id="alert-message" class="row">
                                <div class="col m12">
                                    <div class="card green lighten-5">
                                        <div class="card-content notif">
                                            <span class="card-title green-text"><i class="material-icons md-36">done</i> ' . $succDel . '</span>
                                        </div>
                                    </div>
                                </div>
                            </div>';
                unset($_SESSION['succDel']);
            }
            ?>

            <!-- Row form Start -->
            <div class="row jarak-form">

    <?php
            if (isset($_REQUEST['submit'])) {
                $cari = mysqli_real_escape_string($config, $_REQUEST['cari']);
                echo '
                        <div class="col s12" style="margin-top: -18px;">
                            <div class="card blue lighten-5">
                                <div class="card-content">
                                <p class="description">Hasil pencarian untuk kata kunci <strong>"' . stripslashes($cari) . '"</strong><span class="right"><a href="?page=ilc"><i class="material-icons md-36" style="color: #333;">clear</i></a></span></p>
                                </div>
                            </div>
                        </div>

                        <div class="col m12" id="colres">
                            <table class="bordered" id="tbl">
                                <thead class="blue lighten-4" id="head">
                                    <tr>
                                        <th width="10%">Nomor<br/>Tipe</th>
                                        <th width="31%">Nama<br/>File</th>
                                        <th width="24%">Hasil laporan</th>
                                        <th width="19%">Area<br/>Tanggal laporan</th>
                                        <th width="16%">Tindakan <span class="right"><i class="material-icons" style="color: #333;">settings</i></span></th>
                                    </tr>
                                </thead>
                                <tbody>';

                //script untuk mencari data
                $query = mysqli_query($config, "SELECT * FROM checklist WHERE nama_cek LIKE '%$cari%' ORDER by id_cek DESC LIMIT $curr, 15");
                if (mysqli_num_rows($query) > 0) {
                    $no = 1;
                    while ($row = mysqli_fetch_array($query)) {
                        echo '
                                      <tr>
                                        <td>' . $row['no_cek'] . '<br/><hr/>' . $row['tipe'] . '</td>
                                        <td>' . substr($row['nama_cek'], 0, 200) . '<br/><br/><strong></strong>';

                        if (!empty($row['file'])) {
                            echo ' <strong><a href="?page=dlc&act=fcl&id_cek=' . $row['id_cek'] . '">' . $row['file'] . '</a></strong>';
                        } else {
                            echo ' <em>Tidak ada file yang diupload</em>';
                        }
                        echo '</td>
                                        <td>' . $row['hasil'] . '</td><td>' . $row['area'] . '<br/><hr/>' . indoDate($row['tgl_lpr']) . '</td>
                                        <td>';

                        if ($_SESSION['id_user'] != $row['id_user'] and $_SESSION['id_user'] != 1 and $_SESSION['id_user'] != 2) {
                            echo '<button class="btn small blue-grey waves-effect waves-light"><i class="material-icons">error</i> No Action</button>';
                        } else {
                            echo '<a class="btn small blue waves-effect waves-light" href="?page=ilc&act=edit&id_cek=' . $row['id_cek'] . '">
                                                    <i class="material-icons">edit</i> EDIT</a>
                                                <a class="btn small deep-orange waves-effect waves-light" href="?page=ilc&act=del&id_cek=' . $row['id_cek'] . '">
                                                    <i class="material-icons">delete</i> DEL</a>';
                        }
                        echo '
                                        </td>
                                    </tr>';
                    }
                } else {
                    echo '<tr><td colspan="5"><center><p class="add">Tidak ada data yang ditemukan</p></center></td></tr>';
                }
                echo '</tbody></table><br/><br/>
                            </div>
                        </div>
                        <!-- Row form END -->';
            } else {

                echo '
                        <div class="col m12" id="colres">
                        <table class="bordered" id="tbl">
                            <thead class="blue lighten-4" id="head">
                                <tr>
                                    <th width="10%">Nomor</th>
                                    <th width="17%">Tanggal laporan</th>
                                    <th width="5%">Tipe</th>
                                    <th width="8%">Area</th>
                                    <th width="10%">Nama</th>
                                    <th width="20%">File</th>
                                    <th width="10%">Hasil Laporan</th>
                                    <th width="20%">Tindakan <span class="right tooltipped" data-position="left" data-tooltip="Atur jumlah data yang ditampilkan"><a class="modal-trigger" href="#modal"><i class="material-icons" style="color: #333;">settings</i></a></span></th>

                                        <div id="modal" class="modal">
                                            <div class="modal-content white">
                                                <h5>Jumlah data yang ditampilkan per halaman</h5>';
                $query = mysqli_query($config, "SELECT id_atur,checklist FROM pengaturan");
                list($id_atur, $checklist) = mysqli_fetch_array($query);
                echo '
                                                <div class="row">
                                                    <form method="post" action="">
                                                        <div class="input-field col s12">
                                                            <input type="hidden" value="' . $id_atur . '" name="id_atur">
                                                            <div class="input-field col s1" style="float: left;">
                                                                <i class="material-icons prefix md-prefix">looks_one</i>
                                                            </div>
                                                            <div class="input-field col s11 right" style="margin: -5px 0 20px;">
                                                                <select class="browser-default validate" name="checklist" required>
                                                                    <option value="' . $checklist . '">' . $checklist . '</option>
                                                                    <option value="5">5</option>
                                                                    <option value="10">10</option>
                                                                    <option value="20">20</option>
                                                                    <option value="50">50</option>
                                                                    <option value="100">100</option>
                                                                </select>
                                                            </div>
                                                            <div class="modal-footer white">
                                                                <button type="submit" class="modal-action waves-effect waves-green btn-flat" name="simpan">Simpan</button>';
                if (isset($_REQUEST['simpan'])) {
                    $id_atur = "1";
                    $checklist = $_REQUEST['checklist'];
                    $id_user = $_SESSION['id_user'];

                    $query = mysqli_query($config, "UPDATE pengaturan SET checklist='$checklist',id_user='$id_user' WHERE id_atur='$id_atur'");
                    if ($query == true) {
                        header("Location: ./admin.php?page=ilc");
                        die();
                    }
                }
                echo '
                                                                <a href="#!" class=" modal-action modal-close waves-effect waves-green btn-flat">Batal</a>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

                                </tr>
                            </thead>

                            <tbody>';

                //script untuk mencari data
                $query = mysqli_query($config, "SELECT * FROM checklist ORDER by id_cek DESC LIMIT $curr, $limit");
                if (mysqli_num_rows($query) > 0) {
                    $no = 1;
                    $no_ceklist = 1;
                    while ($row = mysqli_fetch_array($query)) {
                        echo '
                                  <tr>
                                    <td>' . $no_ceklist . '</td>
                                    <td>' . indoDate($row['tgl_lpr']) . '</td>
                                    <td>' . $row['tipe'] . '</td>
                                    <td>' . $row['area'] . '</td>
                                    <td>' . substr($row['nama_cek'], 0, 200) . '</td>
                                    <td> <strong></strong>';

                        if (!empty($row['file'])) {
                            echo ' <strong><a href="?page=dlc&act=fcl&id_cek=' . $row['id_cek'] . '">' . $row['file'] . '</a></strong>';
                        } else {
                            echo ' <em>Tidak ada file yang diupload</em>';
                        }
                        echo '</td>
                                    <td>' . $row['hasil'] . '</td>
                                    
                                   
                                    <td>';

                        if ($_SESSION['id_user'] != $row['id_user'] and $_SESSION['id_user'] != 1 and $_SESSION['id_user'] != 2) {
                            echo '<button class="btn small blue-grey waves-effect waves-light"><i class="material-icons">error</i> No Action</button>';
                        } else {
                            echo '<a class="btn small blue waves-effect waves-light" href="?page=ilc&act=edit&id_cek=' . $row['id_cek'] . '">
                                                <i class="material-icons">edit</i> EDIT</a>
                                            <a class="btn small deep-orange waves-effect waves-light" href="?page=ilc&act=del&id_cek=' . $row['id_cek'] . '">
                                                <i class="material-icons">delete</i> DEL</a>';
                        }
                        echo '
                                    </td>
                                </tr>';
                        $no_ceklist++;
                    }
                } else {
                    echo '<tr><td colspan="5"><center><p class="add">Tidak ada data untuk ditampilkan. <u><a href="?page=ilc&act=add">Tambah data baru</a></u> </p></center></td></tr>';
                }
                echo '</tbody></table>
                        </div>
                    </div>
                    <!-- Row form END -->';

                $query = mysqli_query($config, "SELECT * FROM checklist");
                $cdata = mysqli_num_rows($query);
                $cpg = ceil($cdata / $limit);

                echo '<br/><!-- Pagination START -->
                          <ul class="pagination">';

                if ($cdata > $limit) {

                    //first and previous pagging
                    if ($pg > 1) {
                        $prev = $pg - 1;
                        echo '<li><a href="?page=ilc&pg=1"><i class="material-icons md-48">first_page</i></a></li>
                                  <li><a href="?page=ilc&pg=' . $prev . '"><i class="material-icons md-48">chevron_left</i></a></li>';
                    } else {
                        echo '<li class="disabled"><a href="#"><i class="material-icons md-48">first_page</i></a></li>
                                  <li class="disabled"><a href="#"><i class="material-icons md-48">chevron_left</i></a></li>';
                    }

                    //perulangan pagging
                    for ($i = 1; $i <= $cpg; $i++) {
                        if ((($i >= $pg - 3) && ($i <= $pg + 3)) || ($i == 1) || ($i == $cpg)) {
                            if ($i == $pg) echo '<li class="active waves-effect waves-dark"><a href="?page=ilc&pg=' . $i . '"> ' . $i . ' </a></li>';
                            else echo '<li class="waves-effect waves-dark"><a href="?page=ilc&pg=' . $i . '"> ' . $i . ' </a></li>';
                        }
                    }

                    //last and next pagging
                    if ($pg < $cpg) {
                        $next = $pg + 1;
                        echo '<li><a href="?page=ilc&pg=' . $next . '"><i class="material-icons md-48">chevron_right</i></a></li>
                                  <li><a href="?page=ilc&pg=' . $cpg . '"><i class="material-icons md-48">last_page</i></a></li>';
                    } else {
                        echo '<li class="disabled"><a href="#"><i class="material-icons md-48">chevron_right</i></a></li>
                                  <li class="disabled"><a href="#"><i class="material-icons md-48">last_page</i></a></li>';
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
}
    ?>