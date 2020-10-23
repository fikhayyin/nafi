<?php
//cek session
if (empty($_SESSION['admin'])) {
    $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
    header("Location: ./");
    die();
} else {

    $id_cek = mysqli_real_escape_string($config, $_REQUEST['id_cek']);
    $query = mysqli_query($config, "SELECT * FROM checklist WHERE id_cek='$id_cek'");
    if (mysqli_num_rows($query) > 0) {
        while ($row = mysqli_fetch_array($query)) {
            echo '
                    <div class="row jarak-form">
                        <ul class="collapsible white" data-collapsible="accordion">
                            <li>
                                <div class="collapsible-header white"><i class="material-icons md-prefix md-36">expand_more</i><span class="add">Detail data hasil laporan</span></div>
                                        <div class="col m12 white">
                                            <table>
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
                                                    </tr>
                                                    <tr>
                                                    <td width="13%">Nomor</td>
                                                    <td width="1%">:</td>
                                                    <td width="86%">' . $row['nama_cek'] . '</td>
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
                                                        <td width="1%">:</td><td width="86%">' . indoDate($row['tgl_lpr']) . '</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="13%">Keterangan</td>
                                                        <td width="1%">:</td>
                                                        <td width="86%">' . $row['keterangan'] . '</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                </div>

                        <button onclick="window.history.back()" class="btn-large blue waves-effect waves-light left"><i class="material-icons">arrow_back</i> KEMBALI</button>';

            if (empty($row['file'])) {
                echo '';
            } else {

                $ekstensi = array('pdf');
                $ekstensi2 = array('xls', 'xlsx');
                $file = $row['file'];
                $x = explode('.', $file);
                $eks = strtolower(end($x));

                if (in_array($eks, $ekstensi) == true) {
                    echo '<img class="gbr" data-caption="' . date('d M Y', strtotime($row['tgl_catat'])) . '" src="./upload/ceklist/' . $row['file'] . '"/>';
                } else {

                    if (in_array($eks, $ekstensi2) == true) {
                        echo '
                                    <div class="gbr">
                                        <div class="row">
                                            <div class="col s12">
                                                <div class="col s9 left">
                                                    <div class="card">
                                                        <div class="card-content">
                                                            <p>Data laporan hasil checklist ini bertipe <strong>XLS</strong>, klik link dibawah untuk melihat detail laporan.</p>
                                                        </div>
                                                        <div class="card-action">
                                                            <strong>Lihat file :</strong> <a class="blue-text" href="./upload/ceklist/' . $row['file'] . '" target="_blank">' . $row['file'] . '</a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col s3 right">
                                                    <img class="file" src="./asset/img/xls.png">
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                    } else {
                        echo '
                                    <div class="gbr">
                                        <div class="row">
                                            <div class="col s12">
                                                <div class="col s9 left">
                                                    <div class="card">
                                                        <div class="card-content">
                                                            <p>Data laporan hasil checklist ini bertipe <strong>PDF</strong>, klik link dibawah untuk melihat detail laporan.</p>
                                                        </div>
                                                        <div class="card-action">
                                                            <strong>Lihat file :</strong> <a class="blue-text" href="./upload/ceklist/' . $row['file'] . '" target="_blank">' . $row['file'] . '</a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col s3 right">
                                                    <img class="file" src="./asset/img/pdf.png">
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                    }
                }
            }
            echo '
                    </div>';
        }
    }
}
