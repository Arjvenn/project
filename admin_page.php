<?php

@include 'config.php';

if(isset($_POST['tambah_menu'])){

   $nama_menu = $_POST['nama_menu'];
   $harga_menu = $_POST['harga_menu'];
   $foto_menu = $_FILES['foto_menu']['name'];
   $foto_tmp = $_FILES['foto_menu']['tmp_name'];
   $folder_foto = 'uploaded_img/'.$foto_menu;

   if(empty($nama_menu) || empty($harga_menu) || empty($foto_menu)){
      $pesan[] = 'Silakan lengkapi semua data!';
   }else{
      $insert = "INSERT INTO products(nama, harga, foto) VALUES('$nama_menu', '$harga_menu', '$foto_menu')";
      $upload = mysqli_query($conn, $insert);
      if($upload){
         move_uploaded_file($foto_tmp, $folder_foto);
         $pesan[] = 'Menu baru berhasil ditambahkan!';
      }else{
         $pesan[] = 'Gagal menambahkan menu!';
      }
   }

};

if(isset($_GET['hapus'])){
   $id = $_GET['hapus'];
   mysqli_query($conn, "DELETE FROM products WHERE id = $id");
   header('location:admin_page.php');
};

?>

<!DOCTYPE html>
<html lang="id">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Halaman Admin</title>

   <!-- font awesome -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

   <!-- file CSS -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php
if(isset($pesan)){
   foreach($pesan as $msg){
      echo '<span class="message">'.$msg.'</span>';
   }
}
?>

<div class="container">

   <div class="admin-product-form-container">

      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" enctype="multipart/form-data">
         <h3>Tambahkan Menu Baru</h3>
         <input type="text" placeholder="Masukkan nama menu" name="nama_menu" class="box">
         <input type="number" placeholder="Masukkan harga menu" name="harga_menu" class="box">
         <input type="file" accept="image/png, image/jpeg, image/jpg" name="foto_menu" class="box">
         <input type="submit" class="btn" name="tambah_menu" value="Tambah Menu">
      </form>

   </div>

   <?php
   $select = mysqli_query($conn, "SELECT * FROM products");
   ?>

   <div class="product-display">
      <table class="product-display-table">
         <thead>
         <tr>
            <th>Gambar Menu</th>
            <th>Nama Menu</th>
            <th>Harga Menu</th>
            <th>Tindakan</th>
         </tr>
         </thead>
         <?php while($row = mysqli_fetch_assoc($select)){ ?>
         <tr>
            <td><img src="uploaded_img/<?php echo $row['foto']; ?>" height="100" alt=""></td>
            <td><?php echo $row['nama']; ?></td>
            <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
            <td>
               <a href="admin_update.php?edit=<?php echo $row['id']; ?>" class="btn"> <i class="fas fa-edit"></i> Ubah </a>
               <a href="admin_page.php?hapus=<?php echo $row['id']; ?>" class="btn"> <i class="fas fa-trash"></i> Hapus </a>
            </td>
         </tr>
         <?php } ?>
      </table>
   </div>

</div>

</body>
</html>
