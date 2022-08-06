<?php

use LDAP\Result;

function koneksi()
{
  return mysqli_connect('localhost', 'root', 'root', 'pw_08012003');
}

function query($query)
{
  $conn = koneksi();
  $result = mysqli_query($conn, $query);

  // Jika Hasilnya hanya 1 data
  if (mysqli_num_rows($result) == 1) {
    return mysqli_fetch_assoc($result);
  }


  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }

  return $rows;
}

function upload()
{
  $namaFile = $_FILES['gambar']['name'];
  $tipeFile = $_FILES['gambar']['type'];
  $ukuranFile = $_FILES['gambar']['size'];
  $error = $_FILES['gambar']['error'];
  $tmpFile = $_FILES['gambar']['tmp_name'];

  // Ketika tidak ada gambar yang dipilih
  if ($error == 4) {
    echo "<script>
            alert('Pilih gambar terlebih dahulu');
          </script>";
    return false;
  }

  // Cek Ekstensi file
  $daftarGambar =  ['jpg', 'jpeg', 'png'];
  $ekstensiFile = explode('.', $namaFile);
  $ekstensiFile = strtolower(end($ekstensiFile));
  if (!in_array($ekstensiFile, $daftarGambar)) {
    echo "<script>
            alert('Yang anda pilih bukan gambar');
          </script>";
    return false;
  }

  // cek tipe file
  if ($tipeFile != 'image/jpeg' && $tipeFile != 'image/png') {
    echo "<script>
            alert('Yang anda pilih bukan gambar');
          </script>";
    return false;
  }

  // Cek ukuran file
  // Maksimal 5 mb == 5000000
  if ($ukuranFile > 5000000) {
    echo "<script>
            alert('ukuran terlalu besar');
          </script>";
    return false;
  }

  // Lolos pengecekkan
  // siap upload file
  // generate nama file baru
  $namaFileBaru = uniqid();
  $namaFileBaru .= ".";
  $namaFileBaru .= $ekstensiFile;

  move_uploaded_file($tmpFile, 'img/' . $namaFileBaru);

  return $namaFileBaru;
}

function tambah($data)
{
  $conn = koneksi();

  $nama = htmlspecialchars($data["nama"]);
  $nrp = htmlspecialchars($data["nrp"]);
  $email = htmlspecialchars($data["email"]);
  $jurusan = htmlspecialchars($data["jurusan"]);
  // $gambar = htmlspecialchars($data["gambar"]);

  // upload gambar
  $gambar = upload();
  if (!$gambar) {
    return false;
  }

  $query = "INSERT INTO
            mahasiswa
            VALUES
            (NULL , '$nama', '$nrp', '$email', '$jurusan', '$gambar' );
            ";
  mysqli_query($conn, $query) or die(mysqli_error($conn));;
  return mysqli_affected_rows($conn);
}

function hapus($id)
{
  $conn = koneksi();
  mysqli_query($conn, "DELETE FROM mahasiswa WHERE id=$id") or die(mysqli_error($conn));
  return mysqli_affected_rows($conn);
}
function ubah($data)
{
  $conn = koneksi();

  $id = $data["id"];
  $nama = htmlspecialchars($data["nama"]);
  $nrp = htmlspecialchars($data["nrp"]);
  $email = htmlspecialchars($data["email"]);
  $jurusan = htmlspecialchars($data["jurusan"]);
  $gambar = htmlspecialchars($data["gambar"]);


  $query = "UPDATE mahasiswa SET
            nama = '$nama',
            nrp = '$nrp',
            email = '$email', 
            jurusan = '$jurusan',
            gambar = '$gambar'
            WHERE id=$id";
  mysqli_query($conn, $query) or die(mysqli_error($conn));
  return mysqli_affected_rows($conn);
}

function cari($keywoard)
{
  $conn = koneksi();

  $query = "SELECT * FROM mahasiswa
            WHERE nama LIKE '%$keywoard%' OR
            nrp LIKE '%$keywoard%'";
  $result = mysqli_query($conn, $query);

  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }

  return $rows;
}

function login($data)
{
  $conn = koneksi();
  // Set Session
  $username = htmlspecialchars($data["username"]);
  $password = htmlspecialchars($data["password"]);

  // cek dulu username
  if ($user = query("SELECT * FROM user WHERE username = '$username'")) {
    if (password_verify($password, $user["password"])) {
      // set session
      $_SESSION['login'] = true;
      header("Location: index.php");
      exit;
    }
  }
  return [
    'error' => true,
    'pesan' => 'Username/Paswword salah!'
  ];
}

function registrasi($data)
{

  $conn = koneksi();
  $username = htmlspecialchars(strtolower($data["username"]));
  $password1 = mysqli_real_escape_string($conn, $data["password1"]);
  $password2 = mysqli_real_escape_string($conn, $data["password2"]);

  // Jika username dan password kosong
  if (empty($username) || empty($password1) || empty($password2)) {
    echo "
          <script>
          alert('Username / Password tidak boleh kosong');
          document.location.href = 'registrasi.php';
          </script> 
    ";
    return false;
  }

  // Jika Username sudah terdaftar
  if (query("SELECT * FROM user WHERE username = '$username'")) {
    echo "
    <script>
    alert('Username sudah terdaftar');
    document.location.href = 'registrasi.php';
    </script> ";
    return false;
  }

  // Jika konfirmasi password tidak sesuai
  if ($password1 !== $password2) {
    echo "
    <script>
    alert('Konfirmasi Password tidak sesuai');
    document.location.href = 'registrasi.php';
    </script> ";
    return false;
  }

  // Jika pasword < 5
  if (strlen($password1) < 5) {
    echo "
    <script>
    alert('Password terlalu pendek');
    document.location.href = 'registrasi.php';
    </script> ";
    return false;
  }

  // Jika Username dan password sudah sesuai
  // enkripsi atau acak password terlebih dahulu
  $passwordBaru = password_hash($password1, PASSWORD_DEFAULT);
  // Insert ke tabel user
  $query = "INSERT INTO user 
            VALUES
            (NULL, '$username', '$passwordBaru')
            ";
  mysqli_query($conn, $query) or die(mysqli_error($conn));
  return mysqli_affected_rows($conn);
}
