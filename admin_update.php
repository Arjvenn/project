<?php
@include 'config.php';

// Ambil ID dari parameter GET
$id = isset($_GET['edit']) ? $_GET['edit'] : null;
if (!$id) {
   die('ID tidak ditemukan.');
}

// Jika tombol update ditekan
if(isset($_POST['update_product'])){

   $product_nama = $_POST['product_nama'];
   $product_harga = $_POST['product_harga'];
   $product_foto = $_FILES['product_foto']['name'];
   $product_foto_tmp_name = $_FILES['product_foto']['tmp_name'];
   $product_foto_folder = 'uploaded_img/'.$product_foto;

   if(empty($product_nama) || empty($product_harga) || empty($product_foto)){
      $message[] = 'Mohon lengkapi semua data!';
   }else{
      $update_data = "UPDATE products SET nama='$product_nama', harga='$product_harga', foto='$product_foto' WHERE id = '$id'";
      $upload = mysqli_query($conn, $update_data);

      if($upload){
         move_uploaded_file($product_foto_tmp_name, $product_foto_folder);
         header('location:admin_page.php');
         exit;
      }else{
         $message[] = 'Gagal update produk!';
      }
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="css/style.css">
   <title>Update Produk</title>
</head>
<body>

<?php
if(isset($message)){
   foreach($message as $msg){
      echo '<span class="message">'.$msg.'</span>';
   }
}
?>

<div class="container">
   <div class="admin-product-form-container centered">

      <?php
         $select = mysqli_query($conn, "SELECT * FROM products WHERE id = '$id'");
         if(mysqli_num_rows($select) > 0){
            $row = mysqli_fetch_assoc($select);
      ?>

      <form action="" method="post" enctype="multipart/form-data">
         <h3 class="title">Update Produk</h3>
         <input type="text" class="box" name="product_nama" value="<?php echo htmlspecialchars($row['nama']); ?>" placeholder="Nama produk">
         <input type="number" min="0" class="box" name="product_harga" value="<?php echo htmlspecialchars($row['harga']); ?>" placeholder="Harga produk">
         <input type="file" class="box" name="product_foto" accept="image/png, image/jpeg, image/jpg">
         <input type="submit" value="Update Product" name="update_product" class="btn">
         <a href="admin_page.php" class="btn">Go Back!</a>
      </form>

      <?php } else {
         echo "<p class='message'>Produk tidak ditemukan.</p>";
      } ?>

   </div>
</div>

</body>
</html>
