<?php

require 'function.php';

// Mengambil id dari url
$id = $_GET["id"];
// jika tidak ada id di hapus
if (!isset($_GET['id'])) {
  header("location: index.php");
  exit;
}

if (hapus($id) > 0) {
  echo "
        <script>
        alert('Data berhasil dihapus');
        document.location.href = 'index.php';
        </script>
  ";
} else {
  echo "Data gagal ditambahkan";
}
