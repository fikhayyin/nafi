<?php
//cek session
if (!empty($_SESSION['admin'])) {
?>

    <nav class="yellow darken-3">
        <div class="nav-wrapper">
            <a href="./" class="brand-logo center hide-on-large-only"><i class="material-icons md-36">person_pin</i>SIS-VER</a>
            <ul id="slide-out" class="side-nav" data-simplebar-direction="vertical">
                <li class="no-padding">
                    <div class="logo-side center blue-grey darken-3">
                        <?php
                        $query = mysqli_query($config, "SELECT * FROM perusahaan");
                        while ($data = mysqli_fetch_array($query)) {
                            if (!empty($data['logo'])) {
                                echo '<img class="logoside" src="./upload/' . $data['logo'] . '"/>';
                            } else {
                                echo '<img class="logoside" src="./asset/img/logo.png"/>';
                            }
                            if (!empty($data['nama'])) {
                                echo '<h5 class="smk-side">' . $data['nama'] . '</h5>';
                            } else {
                                echo '<h5 class="smk-side"></h5>';
                            }
                            if (!empty($data['alamat'])) {
                                echo '<p class="description-side">' . $data['alamat'] . '</p>';
                            } else {
                                echo '<p class="description-side"></p>';
                            }
                        }
                        ?>
                    </div>
                </li>
                <li class="no-padding blue-grey darken-4">
                    <ul class="collapsible collapsible-accordion">
                        <li>
                            <a class="collapsible-header"><i class="material-icons">account_circle</i><?php echo $_SESSION['nama']; ?></a>
                            <div class="collapsible-body">
                                <ul>
                                    <li><a href="?page=pro&sub=pass">Profil</a></li>
                                    <li><a href="logout.php">Logout</a></li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </li>
                <li><a href="./"><i class="material-icons middle">dashboard</i> Beranda</a></li>
                <li class="no-padding">
                    <?php
                    if ($_SESSION['admin'] == 1 || $_SESSION['admin'] == 3) { ?>
                        <ul class="collapsible collapsible-accordion">
                            <li>
                                <a class="collapsible-header"><i class="material-icons">repeat</i>Inventori</a>
                                <div class="collapsible-body">
                                    <ul>
                                        <li><a href="?page=ibm">Barang</a></li>
                                        <li><a href="?page=ilc">Laporan Checklist</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    <?php
                    }
                    ?>
                </li>
                <li class="no-padding">
                    <?php
                    if ($_SESSION['admin'] == 1 || $_SESSION['admin'] == 2) { ?>
                        <ul class="collapsible collapsible-accordion">
                            <li>
                                <a href="?page=clb"><i class="material-icons">assignment</i>Cetak Laporan Barang</a>
                            </li>
                        </ul>
                    <?php
                    }
                    ?>
                </li>
                <li class="no-padding">
                    <?php
                    if ($_SESSION['admin'] == 1 || $_SESSION['admin'] == 2) { ?>
                        <ul class="collapsible collapsible-accordion">
                            <li>
                                <a a href="?page=dlc"><i class="material-icons">image</i> Data Laporan Checklist</a>

                            </li>
                        </ul>
                    <?php
                    }
                    ?>
                </li>
                <li class="no-padding">
                    <?php
                    if ($_SESSION['admin'] == 1 || $_SESSION['admin'] == 2) { ?>
                        <ul class="collapsible collapsible-accordion">
                            <li>
                                <a class="collapsible-header"><i class="material-icons">settings</i> Pengaturan</a>
                                <div class="collapsible-body">
                                    <ul>
                                        <?php
                                        if ($_SESSION['admin'] == 1) { ?>
                                            <li><a href="?page=kel">Perusahaan</a></li>
                                        <?php
                                        }
                                        ?>
                                        <li><a href="?page=kel&sub=usr">Pengguna</a></li>
                                        <li class="divider"></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    <?php
                    }
                    ?>
                </li>
            </ul>
            <!-- Menu on medium and small screen END -->

            <!-- Menu on large screen START -->
            <ul class="center hide-on-med-and-down" id="nv">
                <li><a href="./" class="ams hide-on-med-and-down"><i class="material-icons md-36">person_pin</i>SIS-VER</a></li>
                <li>
                    <div class="grs">
                        </>
                </li>
                <li><a href="./"><i class="material-icons"></i>&nbsp; Beranda</a></li>
                <?php
                if ($_SESSION['admin'] == 1 || $_SESSION['admin'] == 3) { ?>
                    <li><a class="dropdown-button" href="#!" data-activates="dabar">Inventori <i class="material-icons md-18">arrow_drop_down</i></a></li>
                    <ul id='dabar' class='dropdown-content'>
                        <li><a href="?page=ibm">Barang</a></li>
                        <li><a href="?page=ilc">Laporan Checklist</a></li>
                    </ul>
                <?php
                }
                ?>
                <?php
                if ($_SESSION['admin'] == 1 || $_SESSION['admin'] == 2) { ?>
                    <li><a href="?page=clb" data-activates="agenda">Cetak Laporan Barang </a></li>
                <?php
                }
                ?>
                <?php
                if ($_SESSION['admin'] == 1 || $_SESSION['admin'] == 2) { ?>
                    <li><a href="?page=dlc" data-activates="agenda">Data Laporan Checklist </a></li>
                <?php
                }
                ?>
                <?php
                if ($_SESSION['admin'] == 1 || $_SESSION['admin'] == 2) { ?>
                    <li><a class="dropdown-button" href="#!" data-activates="pengaturan">Pengaturan <i class="material-icons md-18">arrow_drop_down</i></a></li>
                    <ul id='pengaturan' class='dropdown-content'>
                        <?php
                        if ($_SESSION['admin'] == 1) { ?>
                            <li><a href="?page=kel">Perusahaan</a></li>
                        <?php
                        }
                        ?>
                        <li><a href="?page=kel&sub=usr">Pengguna</a></li>
                        <li class="divider"></li>
                    </ul>
                <?php
                }
                ?>
                <li class="right" style="margin-right: 10px;"><a class="dropdown-button" href="#!" data-activates="logout"><i class="material-icons">account_circle</i> <?php echo $_SESSION['nama']; ?><i class="material-icons md-18">arrow_drop_down</i></a></li>
                <ul id='logout' class='dropdown-content'>
                    <li><a href="?page=pro&sub=pass">Profil</a></li>
                    <li class="divider"></li>
                    <li><a href="logout.php"><i class="material-icons">settings_power</i> Logout</a></li>
                </ul>
            </ul>
            <!-- Menu on large screen END -->
            <a href="#" data-activates="slide-out" class="button-collapse" id="menu"><i class="material-icons">menu</i></a>
        </div>
    </nav>

<?php
} else {
    header("Location: ../");
    die();
}
?>