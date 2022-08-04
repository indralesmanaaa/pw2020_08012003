<?php

require 'function.php';

if (isset($_POST["registrasi"])) {
  if (registrasi($_POST) > 0) {
    echo     "<script>
    alert('Data berhasil ditambahkan, Silahkan kembali login');
    document.location.href = 'login.php';
    </script> ";
  } else {
    echo "User gagal ditambahkan!";
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi</title>
</head>

<body>
  <h3>Tambah User</h3>
  <form action="" method="POST">
    <ul>
      <li>
        <label>
          Username :
          <input type="text" name="username" autofocus autocomplete="off" required>
        </label>
      </li>
      <li><label>
          Password :
          <input type="password" name="password1" required>
        </label>
      </li>
      <li>
        <label>
          Konirmasi Password :
          <input type="password" name="password2" required>
        </label>
      </li>
      <li>
        <button type="submit" name="registrasi">Daftar</button>
      </li>
    </ul>
  </form>
  <a href="login.php">Login Kembali</a>
</body>

</html>