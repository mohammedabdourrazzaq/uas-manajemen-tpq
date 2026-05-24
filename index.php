<?php

require_once 'auth.php';
require_once 'class.php';

$app = new Setoran();


// HAPUS
if (isset($_GET['hapus'])) {

    if ($_SESSION['role'] == 'admin') {

        $app->hapus($_GET['hapus']);
    }

    header("Location: index.php");
    exit;
}



// SIMPAN
if (isset($_POST['simpan'])) {

    if ($_POST['id'] == "") {

        $app->tambah(
            $_POST['nama'],
            $_POST['surat'],
            $_POST['jenis'],
            $_POST['ayat'],
            $_POST['tanggal'],
            $_POST['nilai']
        );
    }
    else {

        $app->ubah(
            $_POST['id'],
            $_POST['nama'],
            $_POST['surat'],
            $_POST['jenis'],
            $_POST['ayat'],
            $_POST['tanggal'],
            $_POST['nilai']
        );
    }

    header("Location: index.php");
    exit;
}



// DEFAULT
$id_edit = "";
$nama_edit = "";
$surat_edit = "";
$jenis_edit = "";
$ayat_edit = "";
$tanggal_edit = date('Y-m-d');
$nilai_edit = "";



// EDIT
if (isset($_GET['edit'])) {

    $edit = $app->ambilSatu($_GET['edit']);

    $id_edit = $edit['id'];
    $nama_edit = $edit['nama_murid'];
    $surat_edit = $edit['nama_surat'];
    $jenis_edit = $edit['jenis_setoran'];
    $ayat_edit = $edit['jumlah_ayat'];
    $tanggal_edit = $edit['tanggal_setoran'];
    $nilai_edit = $edit['nilai'];
}


$data = $app->tampilSemua();

?>

<!DOCTYPE html>
<html>
<head>

    <title>Dashboard TPQ</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="assets/style.css">

</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-primary shadow-sm">

    <div class="container">

        <span class="navbar-brand fw-bold">
            Aplikasi Manajemen TPQ
        </span>

        <div class="text-white">

            <?= $_SESSION['username']; ?>
            |
            <?= $_SESSION['role']; ?>

            <a
                href="logout.php"
                class="btn btn-light btn-sm ms-3"
            >
                Logout
            </a>

        </div>

    </div>

</nav>



<div class="container py-4">

    <div class="row mb-4">

        <div class="col-md-4">

            <div class="card statistik shadow">

                <div class="card-body">

                    <h5>Total Setoran</h5>

                    <h1>
                        <?= count($data); ?>
                    </h1>

                </div>

            </div>

        </div>

    </div>



    <div class="row">

        <!-- FORM -->
        <div class="col-md-4 mb-4">

            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white">

                    <?= ($id_edit == "") ?
                        "Tambah Setoran" :
                        "Edit Setoran"; ?>

                </div>

                <div class="card-body">

                    <form method="POST">

                        <input
                            type="hidden"
                            name="id"
                            value="<?= $id_edit ?>"
                        >

                        <div class="mb-3">

                            <label>Nama Murid</label>

                            <input
                                type="text"
                                name="nama"
                                class="form-control"
                                value="<?= $nama_edit ?>"
                                required
                            >

                        </div>

                        <div class="mb-3">

                            <label>Nama Surat</label>

                            <input
                                type="text"
                                name="surat"
                                class="form-control"
                                value="<?= $surat_edit ?>"
                                required
                            >

                        </div>

                        <div class="mb-3">

                            <label>Jenis Setoran</label>

                            <select
                                name="jenis"
                                class="form-select"
                            >

                                <option value="Hafalan Baru">
                                    Hafalan Baru
                                </option>

                                <option value="Muroja'ah">
                                    Muroja'ah
                                </option>

                            </select>

                        </div>

                        <div class="row">

                            <div class="col">

                                <div class="mb-3">

                                    <label>Jumlah Ayat</label>

                                    <input
                                        type="number"
                                        name="ayat"
                                        class="form-control"
                                        value="<?= $ayat_edit ?>"
                                        required
                                    >

                                </div>

                            </div>

                            <div class="col">

                                <div class="mb-3">

                                    <label>Nilai</label>

                                    <input
                                        type="number"
                                        name="nilai"
                                        class="form-control"
                                        value="<?= $nilai_edit ?>"
                                        required
                                    >

                                </div>

                            </div>

                        </div>

                        <div class="mb-4">

                            <label>Tanggal</label>

                            <input
                                type="date"
                                name="tanggal"
                                class="form-control"
                                value="<?= $tanggal_edit ?>"
                                required
                            >

                        </div>

                        <button
                            type="submit"
                            name="simpan"
                            class="btn btn-primary w-100"
                        >
                            Simpan Data
                        </button>

                    </form>

                </div>

            </div>

        </div>



        <!-- TABEL -->
        <div class="col-md-8">

            <div class="card shadow-sm">

                <div class="card-header bg-success text-white">

                    Riwayat Setoran

                </div>

                <div class="card-body p-0">

                    <table class="table table-hover mb-0">

                        <thead class="table-light">

                        <tr>

                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Surat</th>
                            <th>Jenis</th>
                            <th>Nilai</th>
                            <th>Aksi</th>

                        </tr>

                        </thead>

                        <tbody>

                        <?php foreach($data as $row): ?>

                            <tr>

                                <td>
                                    <?= $row['tanggal_setoran']; ?>
                                </td>

                                <td>
                                    <?= $row['nama_murid']; ?>
                                </td>

                                <td>
                                    <?= $row['nama_surat']; ?>
                                </td>

                                <td>
                                    <?= $row['jenis_setoran']; ?>
                                </td>

                                <td>
                                    <span class="badge bg-info">
                                        <?= $row['nilai']; ?>
                                    </span>
                                </td>

                                <td>

                                    <a
                                        href="?edit=<?= $row['id']; ?>"
                                        class="btn btn-warning btn-sm"
                                    >
                                        Edit
                                    </a>

                                    <?php if($_SESSION['role'] == 'admin'): ?>

                                    <a
                                        href="?hapus=<?= $row['id']; ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Hapus data?')"
                                    >
                                        Hapus
                                    </a>

                                    <?php endif; ?>

                                </td>

                            </tr>

                        <?php endforeach; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>