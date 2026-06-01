<?php
require_once 'auth.php';
require_once 'class.php';
$app = new Setoran();

if (isset($_GET['hapus'])) {
    if ($_SESSION['role'] == 'admin') {
        $app->hapus($_GET['hapus']);
    }
    header("Location: index.php");
    exit;
}

if (isset($_POST['simpan'])) {
    if ($_POST['id'] == "") {
       $app->tambah(
            $_POST['nama'],
            $_POST['surat'],
            $_POST['jenis'],
            $_POST['ayat_awal'],
            $_POST['ayat_akhir'],
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
            $_POST['ayat_awal'],
            $_POST['ayat_akhir'],
            $_POST['tanggal'],
            $_POST['nilai']
        );
    }

    header("Location: index.php");
    exit;
}

$id_edit = "";
$nama_edit = "";
$surat_edit = "";
$jenis_edit = "";
$ayat_awal_edit = "";
$ayat_akhir_edit = "";
$tanggal_edit = date('Y-m-d');
$nilai_edit = "";

if (isset($_GET['edit'])) {

    $edit = $app->ambilSatu($_GET['edit']);

    $id_edit = $edit['id'];
    $nama_edit = $edit['nama_murid'];
    $surat_edit = $edit['nama_surat'];
    $jenis_edit = $edit['jenis_setoran'];

    $ayat_awal_edit = $edit['ayat_awal'];
    $ayat_akhir_edit = $edit['ayat_akhir'];

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

                            <select name="surat" id="surat" class="form-select" required>
                        <option value="">Pilih Surat</option>
                        </select>
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

                       <div class="mb-3">

    <label class="fw-bold">
        Rentang Ayat
    </label>

    <div class="row">

        <div class="col-md-6">

            <label>Dari Ayat</label>

            <select
                name="ayat_awal"
                id="ayat_awal"
                class="form-select"
                required
            >
                <option value="">
                    Pilih
                </option>
            </select>

        </div>

        <div class="col-md-6">

            <label>Sampai Ayat</label>

            <select
                name="ayat_akhir"
                id="ayat_akhir"
                class="form-select"
                required
            >
                <option value="">
                    Pilih
                </option>
            </select>

        </div>

    </div>

</div>

<div class="mb-3">
    <div class="mb-3">

    <label>Jumlah Ayat</label>

    <input
        type="number"
        id="jumlah_ayat"
        class="form-control"
        readonly
    >

</div>

    <label>Nilai</label>

    <input
        type="number"
        name="nilai"
        class="form-control"
        value="<?= $nilai_edit ?>"
        required
    >
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
                            <th>Surat & Ayat</th>
                            <th>Jumlah Ayat</th>
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
                                    (<?= $row['ayat_awal']; ?> - <?= $row['ayat_akhir']; ?>)
                                </td>
                                <td>
                                    <?= $row['ayat_akhir'] - $row['ayat_awal'] + 1; ?>
                                    ayat
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
<script>

const daftarSurat = {
    "Al-Fatihah": 7,
    "Al-Baqarah": 286,
    "Ali 'Imran": 200,
    "An-Nisa": 176,
    "Al-Ma'idah": 120,
    "Al-An'am": 165,
    "Al-A'raf": 206,
    "Al-Anfal": 75,
    "At-Taubah": 129,
    "Yunus": 109,
    "Hud": 123,
    "Yusuf": 111,
    "Ar-Ra'd": 43,
    "Ibrahim": 52,
    "Al-Hijr": 99,
    "An-Nahl": 128,
    "Al-Isra": 111,
    "Al-Kahfi": 110,
    "Maryam": 98,
    "Ta-Ha": 135,
    "Al-Anbiya": 112,
    "Al-Hajj": 78,
    "Al-Mu'minun": 118,
    "An-Nur": 64,
    "Al-Furqan": 77,
    "Asy-Syu'ara": 227,
    "An-Naml": 93,
    "Al-Qashash": 88,
    "Al-'Ankabut": 69,
    "Ar-Rum": 60,
    "Luqman": 34,
    "As-Sajdah": 30,
    "Al-Ahzab": 73,
    "Saba": 54,
    "Fatir": 45,
    "Yasin": 83,
    "Ash-Shaffat": 182,
    "Shad": 88,
    "Az-Zumar": 75,
    "Ghafir": 85,
    "Fushshilat": 54,
    "Asy-Syura": 53,
    "Az-Zukhruf": 89,
    "Ad-Dukhan": 59,
    "Al-Jatsiyah": 37,
    "Al-Ahqaf": 35,
    "Muhammad": 38,
    "Al-Fath": 29,
    "Al-Hujurat": 18,
    "Qaf": 45,
    "Az-Zariyat": 60,
    "Ath-Thur": 49,
    "An-Najm": 62,
    "Al-Qamar": 55,
    "Ar-Rahman": 78,
    "Al-Waqi'ah": 96,
    "Al-Hadid": 29,
    "Al-Mujadilah": 22,
    "Al-Hasyr": 24,
    "Al-Mumtahanah": 13,
    "Ash-Shaff": 14,
    "Al-Jumu'ah": 11,
    "Al-Munafiqun": 11,
    "At-Taghabun": 18,
    "Ath-Thalaq": 12,
    "At-Tahrim": 12,
    "Al-Mulk": 30,
    "Al-Qalam": 52,
    "Al-Haqqah": 52,
    "Al-Ma'arij": 44,
    "Nuh": 28,
    "Al-Jin": 28,
    "Al-Muzzammil": 20,
    "Al-Muddatstsir": 56,
    "Al-Qiyamah": 40,
    "Al-Insan": 31,
    "Al-Mursalat": 50,
    "An-Naba": 40,
    "An-Nazi'at": 46,
    "'Abasa": 42,
    "At-Takwir": 29,
    "Al-Infithar": 19,
    "Al-Muthaffifin": 36,
    "Al-Insyiqaq": 25,
    "Al-Buruj": 22,
    "Ath-Thariq": 17,
    "Al-A'la": 19,
    "Al-Ghasyiyah": 26,
    "Al-Fajr": 30,
    "Al-Balad": 20,
    "Asy-Syams": 15,
    "Al-Lail": 21,
    "Adh-Dhuha": 11,
    "Asy-Syarh": 8,
    "At-Tin": 8,
    "Al-'Alaq": 19,
    "Al-Qadr": 5,
    "Al-Bayyinah": 8,
    "Az-Zalzalah": 8,
    "Al-'Adiyat": 11,
    "Al-Qari'ah": 11,
    "At-Takatsur": 8,
    "Al-'Ashr": 3,
    "Al-Humazah": 9,
    "Al-Fil": 5,
    "Quraisy": 4,
    "Al-Ma'un": 7,
    "Al-Kautsar": 3,
    "Al-Kafirun": 6,
    "An-Nashr": 3,
    "Al-Lahab": 5,
    "Al-Ikhlas": 4,
    "Al-Falaq": 5,
    "An-Nas": 6
};

const suratSelect = document.getElementById("surat");
const ayatAwal =
    document.getElementById("ayat_awal");

const ayatAkhir =
    document.getElementById("ayat_akhir");

const jumlahAyat =
    document.getElementById("jumlah_ayat");

for (let namaSurat in daftarSurat) {

    let option = document.createElement("option");

    option.value = namaSurat;
    option.textContent = namaSurat;

    suratSelect.appendChild(option);
}

// Saat surat dipilih
suratSelect.addEventListener(
    "change",
    function() {

        let totalAyat =
            daftarSurat[this.value];

        ayatAwal.innerHTML = "";
        ayatAkhir.innerHTML = "";

        for(let i = 1; i <= totalAyat; i++)
        {
            ayatAwal.innerHTML +=
                `<option value="${i}">
                    ${i}
                </option>`;

            ayatAkhir.innerHTML +=
                `<option value="${i}">
                    ${i}
                </option>`;
        }
});
function hitungJumlahAyat()
{
    let awal = parseInt(ayatAwal.value);
    let akhir = parseInt(ayatAkhir.value);

    if (!isNaN(awal) && !isNaN(akhir))
    {
        if (akhir >= awal)
        {
            jumlahAyat.value =
                akhir - awal + 1;
        }
        else
        {
            jumlahAyat.value = "";

            alert(
                "Ayat akhir tidak boleh lebih kecil dari ayat awal"
            );
        }
    }
}
ayatAwal.addEventListener(
    "change",
    hitungJumlahAyat
);

ayatAkhir.addEventListener(
    "change",
    hitungJumlahAyat
);
</script>

</body>
</html>