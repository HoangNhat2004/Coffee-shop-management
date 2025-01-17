<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   $delete_admin = $conn->prepare("DELETE FROM `admin` WHERE id = ?");
   $delete_admin->execute([$delete_id]);
   header('location:admin_accounts.php');
}

// Lọc theo tên admin nếu có tìm kiếm
if (isset($_POST['search_admin'])) {
   $search_name = $_POST['admin_name'];
} else {
   $search_name = '';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admins accounts</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <link rel="stylesheet" href="../css/dashboard_style.css">
   <link rel="stylesheet" href="../css/table.css">

</head>

<body>

   <?php include '../components/admin_header.php' ?>

   <!-- admins accounts section starts  -->

   <section class="accounts">

      <h1 class="heading">Admin Management</h1>

      <div class="table_header">
         <p>Admin Details</p>
         <div style="display: flex; flex-direction: row;">
            <!-- Form tìm kiếm admin theo tên -->
            <form method="post">
               <input type="text" name="admin_name" placeholder="Admin name" value="<?= htmlspecialchars($search_name); ?>">
               <button type="submit" name="search_admin" class="add_new">Search</button>
            </form>
            <div style="padding: 0 5px;">
               <a href="register_admin.php"><button class="add_new">Add Admin</button></a>
            </div>
            <!-- Nút Add Admin dẫn đến trang đăng ký admin -->
            
         </div>
      </div>

      <div>
         <table class="table">
            <thead>
               <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Action</th>
               </tr>
            </thead>
            <tbody>
               <?php
               // Truy vấn tìm kiếm theo tên admin nếu có
               if ($search_name != '') {
                  $select_account = $conn->prepare("SELECT * FROM `admin` WHERE name LIKE ?");
                  $select_account->execute(["%{$search_name}%"]);
               } else {
                  $select_account = $conn->prepare("SELECT * FROM `admin`");
                  $select_account->execute();
               }

               if ($select_account->rowCount() > 0) {
                  while ($fetch_accounts = $select_account->fetch(PDO::FETCH_ASSOC)) {
               ?>
                     <tr>
                        <td><span><?= $fetch_accounts['id']; ?></span></td>
                        <td><span><?= $fetch_accounts['name']; ?></span></td>
                        <td><a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" onclick="return confirm('Delete this account?');"><button><i class="fa-solid fa-trash"></i></button></a></td>
                     </tr>
               <?php
                  }
               } else {
                  echo '<p class="empty">no accounts available</p>';
               }
               ?>
            </tbody>
         </table>
      </div>

   </section>

   <!-- admins accounts section ends -->

   <!-- custom js file link  -->
   <script src="../js/admin_script.js"></script>

</body>

</html>
