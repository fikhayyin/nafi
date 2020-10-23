<?php
ob_start();
//cek session
session_start();

if (empty($_SESSION['admin'])) {
    $_SESSION['err'] = '<center>Anda harus login terlebih dahulu!</center>';
    header("Location: ./");
    die();
} else {
?>
    <!doctype html>
    <html lang="en">

    <!-- Include Head START -->
    <?php include('include/head.php'); ?>
    <!-- Include Head END -->

    <!-- Body START -->

    <body class="bg">

        <!-- Header START -->
        <header>

            <!-- Include Navigation START -->
            <?php include('include/menu.php'); ?>
            <!-- Include Navigation END -->

        </header>
        <!-- Header END -->

        <!-- Main START -->
        <main>

            <!-- container START -->
            <div class="container">

                <?php
                if (isset($_REQUEST['page'])) {
                    $page = $_REQUEST['page'];
                    switch ($page) {
                        case 'ibm':
                            include "inventoribarang.php";
                            break;
                        case 'ilc':
                            include "laporanceklist.php";
                            break;
                        case 'clb':
                            include "cetakbarang.php";
                            break;
                        case 'kel':
                            include "kelola.php";
                            break;
                        case 'pro':
                            include "profil.php";
                            break;
                        case 'dlc':
                            include "dataceklist.php";
                            break;
                    }
                } else {
                ?>
                    <!-- Row START -->
                    <div class="row">

                        <!-- Include Header Instansi START -->
                        <?php include('include/headerpt.php'); ?>
                        <!-- Include Header Instansi END -->

                        <!-- Welcome Message START -->
                        <div class="col s12">
                            <div class="card">
                                <div class="card-content">
                                    <h4>Selamat Datang <?= echo $_SESSION['nama']; ?></h4>
                                    <p class="description">Anda login sebagai
                                        <?=
                                        if ($_SESSION['admin'] == 1) {
                                            <?= '<strong>Super Admin</strong>. Anda memiliki akses penuh terhadap sistem.' ?>;
                                        } elseif ($_SESSION['admin'] == 2) {
                                            <?= '<strong>Administrator</strong>. Berikut adalah statistik data yang tersimpan dalam sistem.' ?>;
                                        } else {
                                            <?= "<strong>Staff</strong>. Berikut adalah statistik data yang tersimpan dalam sistem.' ?>;
                                        } ?></p>
                                </div>
                            </div>
                        </div>
                        <!-- Welcome Message END -->

                        <?php
                        //menghitung jumlah barang masuk
                        $count1 = mysqli_num_rows(mysqli_query($config, "SELECT * FROM barang"));

                        //menghitung jumlah laporan checklist masuk
                        $count2 = mysqli_num_rows(mysqli_query($config, "SELECT * FROM checklist"));

                        //menghitung jumlah pengguna
                        $count5 = mysqli_num_rows(mysqli_query($config, "SELECT * FROM user"));
                        ?>

                        <!-- Info Statistic START -->
                        <div class="col s12 m4">
                            <div class="card cyan">
                                <div class="card-content">
                                    <span class="card-title white-text"><i class="material-icons md-36">mail</i> Jumlah Barang</span>
                                    <?= '<h5 class="white-text link">' . $count1 . ' Barang Masuk</h5>'; ?>
                                </div>
                            </div>
                        </div>

                        <div class="col s12 m4">
                            <div class="card lime darken-1">
                                <div class="card-content">
                                    <span class="card-title white-text"><i class="material-icons md-36">drafts</i> Jumlah Laporan</span>
                                    <?= '<h5 class="white-text link">' . $count2 . ' Laporan Masuk</h5>'; ?>
                                </div>
                            </div>
                        </div>

                        <?php
                        if ($_SESSION['id_user'] == 1 || $_SESSION['admin'] == 2) { ?>
                            <div class="col s12 m4">
                                <div class="card blue accent-2">
                                    <div class="card-content">
                                        <span class="card-title white-text"><i class="material-icons md-36">people</i> Jumlah Pengguna</span>
                                        <?= '<h5 class="white-text link">' . $count5 . ' Pengguna</h5>'; ?>
                                    </div>
                                </div>
                            </div>
                            <!-- Info Statistic START -->
                        <?php
                        }
                        ?>

                    </div>
                    <!-- Row END -->
                <?php
                }
                ?>
            </div>
            <!-- container END -->

        </main>
        <!-- Main END -->

        <!-- Include Footer START -->
        <?php include('include/footer.php'); ?>
        <!-- Include Footer END -->

    </body>
    <!-- Body END -->

    </html>

<?php
}
?>
