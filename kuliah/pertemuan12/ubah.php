<?php
session_start();

if (!isset($_SESSION['login'])) {
  header("Location: login.php");
  exit;
}
require 'function.php';

// Mengambil id dari url
$id = $_GET["id"];

// Jika tidak ada id di URL
if (!isset($_GET['id'])) {
  header("location: index.php");
  exit;
}

// query mahasiswa berdasarkan id
$mhs = query("SELECT * FROM mahasiswa WHERE id=$id");
// cek apakah tombol ubah sudah ditekan
if (isset($_POST["ubah"])) {
  if (ubah($_POST) > 0) {
    echo "
          <script>
          alert('Data berhasil diubah');
          document.location.href = 'index.php';
          </script>
    ";
  } else {
    echo "Data gagal diubah";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ubah Data Mahasiswa</title>
</head>

<body>
  <h3>Ubah Data Mahasiswa</h3>
  <form action="" method="POST">
    <ul>
      <!-- Menambahkan id yang tidak terlihat user -->
      <input type="hidden" name="id" value="<?= $mhs['id']; ?>">
      <li>
        <label>
          Nama :
          <input type="text" name="nama" autofocus required value="<?= $mhs['nama']; ?>">
        </label>
      </li>
      <li>
        <label>
          NRP :
          <input type="text" name="nrp" required value="<?= $mhs['nrp']; ?>">
        </label>
      </li>
      <li>
        <label>
          E-Mail :
          <input type="text" name="email" required value="<?= $mhs['email']; ?>">
        </label>
      </li>
      <li>
        <label>
          Jurusan :
          <input type="text" name="jurusan" required value="<?= $mhs['jurusan']; ?>">
        </label>
      </li>
      <li>
        <label>
          Gambar :
          <input type="text" name="gambar" required value="<?= $mhs['gambar']; ?>">
        </label>
      </li>
      <li>
        <button type="submit" name="ubah">Ubah Data</button>
      </li>
    </ul>
  </form>
</body>

</html>