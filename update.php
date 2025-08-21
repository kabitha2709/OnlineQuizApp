<?php
include 'config.php';

$error = "";
$row = [];

if (isset($_GET['id'])) {
    $id = ($_GET['id']);
    $sql = "SELECT * FROM entries WHERE id = $id";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
}

if (isset($_POST['update'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    if (empty($name) || empty($email) || empty($phone)) {
        $error = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format!";
    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {
        $error = "Phone number must be 10 digits!";
    } else {
   
        $sql = "UPDATE entries SET name='$name', email='$email', phone='$phone' WHERE 
        id=$id"; 
            if($conn->query($sql) === TRUE){ 
                header("Location: view.php"); 
            } else { 
                echo "Error updating record: " . $conn->error; 
            } 
    } }
?> 

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Record</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="mb-4">Update Record</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" value="<?php echo ($row['name']); ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="<?php echo ($row['email']); ?>" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" value="<?php echo ($row['phone']); ?>" class="form-control">
        </div>

        <button type="submit" name="update" class="btn btn-success">Update</button>
        <a href="view.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

</body>
</html>
