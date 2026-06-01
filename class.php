<?php
class Database {
    protected $koneksi;
    public function __construct() {
        $this->koneksi = new mysqli(
            "localhost",
            "root",
            "",
            "db_tpq"
        );
        if ($this->koneksi->connect_error) {
            die("Koneksi gagal : " . $this->koneksi->connect_error);
        }
    }
}
class User extends Database {
    private $username;
    private $password;

    public function setUsername($username) {
        $this->username = trim($username);
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getUsername() {
        return $this->username;
    }

    public function login() {
        $stmt = $this->koneksi->prepare(
            "SELECT * FROM tb_users WHERE username=?"
        );
        $stmt->bind_param("s", $this->username);
        $stmt->execute();
        $hasil = $stmt->get_result();
        if ($hasil->num_rows > 0) {
            $data = $hasil->fetch_assoc();
            if (password_verify($this->password, $data['password'])) {
                return $data;
            }
        }
        return false;
    }
}

class Setoran extends Database {
    private $nilai;
    public function setNilai($nilai) {

        if ($nilai < 0) {
            $this->nilai = 0;
        }
        elseif ($nilai > 100) {
            $this->nilai = 100;
        }
        else {
            $this->nilai = $nilai;
        }
    }

    public function getNilai() {
        return $this->nilai;
    }

    public function tambah(
        $nama,
        $surat,
        $jenis,
        $ayat_awal,
        $ayat_akhir,
        $tanggal,
        $nilai_input
    ) {
        $this->setNilai($nilai_input);
        $nilai_akhir = $this->getNilai();
        $stmt = $this->koneksi->prepare(
            "INSERT INTO tb_setoran
             (
             nama_murid,
             nama_surat,
             jenis_setoran,
             ayat_awal,
             ayat_akhir,
             tanggal_setoran,
             nilai
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)"
            );

        $stmt->bind_param(
        "sssiisi",
         $nama,
         $surat,
         $jenis,
         $ayat_awal,
         $ayat_akhir,
         $tanggal,
         $nilai_akhir
         );
        return $stmt->execute();
    }

    public function tampilSemua() {
        $query = $this->koneksi->query(
            "SELECT * FROM tb_setoran
            ORDER BY id DESC"
        );
        $data = [];
        while($row = $query->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    public function hapus($id) {
        $stmt = $this->koneksi->prepare(
            "DELETE FROM tb_setoran WHERE id=?"
        );
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function ambilSatu($id) {
        $stmt = $this->koneksi->prepare(
            "SELECT * FROM tb_setoran WHERE id=?"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function ubah(
        $id,
        $nama,
        $surat,
        $jenis,
        $ayat_awal,
        $ayat_akhir,
        $tanggal,
        $nilai_input
    ) {

        $this->setNilai($nilai_input);

        $nilai_akhir = $this->getNilai();

        $stmt = $this->koneksi->prepare(
            "UPDATE tb_setoran SET
            nama_murid=?,
            nama_surat=?,
            jenis_setoran=?,
            ayat_awal=?,
            ayat_akhir=?,
            tanggal_setoran=?,
            nilai=?
            WHERE id=?"
        );

        $stmt->bind_param(
            "sssiiiii",
            $nama,
            $surat,
            $jenis,
            $ayat_awal,
            $ayat_akhir,
            $tanggal,
            $nilai_akhir,
            $id
        );
        return $stmt->execute();
    }
}
?>