<?php

// CLASS INDUK
class Database {

    protected $koneksi;

    // CONSTRUCTOR
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


// CLASS USER
class User extends Database {

    // ENCAPSULATION
    private $username;
    private $password;

    // SETTER
    public function setUsername($username) {
        $this->username = trim($username);
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    // GETTER
    public function getUsername() {
        return $this->username;
    }

    // LOGIN
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



// INHERITANCE
class Setoran extends Database {

    // ENCAPSULATION
    private $nilai;

    // SETTER
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

    // GETTER
    public function getNilai() {
        return $this->nilai;
    }


    // CREATE
    public function tambah(
        $nama,
        $surat,
        $jenis,
        $ayat,
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
                jumlah_ayat,
                tanggal_setoran,
                nilai
            )
            VALUES (?, ?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssisi",
            $nama,
            $surat,
            $jenis,
            $ayat,
            $tanggal,
            $nilai_akhir
        );

        return $stmt->execute();
    }



    // READ
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



    // DELETE
    public function hapus($id) {

        $stmt = $this->koneksi->prepare(
            "DELETE FROM tb_setoran WHERE id=?"
        );

        $stmt->bind_param("i", $id);

        return $stmt->execute();
    }



    // AMBIL SATU DATA
    public function ambilSatu($id) {

        $stmt = $this->koneksi->prepare(
            "SELECT * FROM tb_setoran WHERE id=?"
        );

        $stmt->bind_param("i", $id);

        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }



    // UPDATE
    public function ubah(
        $id,
        $nama,
        $surat,
        $jenis,
        $ayat,
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
            jumlah_ayat=?,
            tanggal_setoran=?,
            nilai=?
            WHERE id=?"
        );

        $stmt->bind_param(
            "sssisii",
            $nama,
            $surat,
            $jenis,
            $ayat,
            $tanggal,
            $nilai_akhir,
            $id
        );

        return $stmt->execute();
    }
}

?>