<?php

session_start();
require 'function.php';


// cek cookie
if (isset($_COOKIE['id']) && isset($_COOKIE['key'])) {
  $id = $_COOKIE['id'];
  $key = $_COOKIE['key'];

  // ambil username berdasarkan id
  $conn = koneksi();
  $result = mysqli_query($conn, "SELECT username FROM user WHERE id = $id");
  $row = mysqli_fetch_assoc($result);

  // cek cookie dan username
  if ($key === hash('sha256', $row['username'])) {
    $_SESSION['login'] = true;
  }
}

if (isset($_SESSION['login'])) {
  header("Location: index.php");
  exit;
}

// Ketika tombol login ditekan
if (isset($_POST['login'])) {
  $login = login($_POST);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
</head>

<body>
  <h3>Login</h3>
  <?php if (isset($login['pesan'])) : ?>
    <p style="color: red; font-style: italic;"><?= $login['pesan']; ?></p>
  <?php endif; ?>
  <form action="" method="POST">
    <ul>
      <li>
        <label>
          Username :
          <input type="text" name="username" autofocus autocomplete="off" required>
        </label>
      </li>
      <li>
        <label>
          Password :
          <input type="password" name="password" required>
        </label>
      </li>
      <li>
        <input type="checkbox" name="remember">
        <label for="remember">Remember me</label>
      </li>
      <button type="submit" name="login">Login</button>
      </li>
      <li>
        <a href="registrasi.php">Tambah user baru</a>
      </li>
    </ul>
  </form>
</body>

</html>