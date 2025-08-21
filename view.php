<?php
include 'config.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';
$limit = 5; 

$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;


if (!empty($search)) {
    $count_sql = "SELECT COUNT(*) AS total FROM entries 
                  WHERE name LIKE '%$search%' OR email LIKE '%$search%'";
} else {
    $count_sql = "SELECT COUNT(*) AS total FROM entries";
}
$count_result = $conn->query($count_sql);
$total_rows = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

if (!empty($search)) {
    $sql = "SELECT * FROM entries 
            WHERE name LIKE '%$search%' OR email LIKE '%$search%' 
            ORDER BY id DESC 
            LIMIT $limit OFFSET $offset";
} else {
    $sql = "SELECT * FROM entries ORDER BY id DESC LIMIT $limit OFFSET $offset";
}
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>View Entries - Online Quiz App</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>All Entries</h2>
    <form method="GET" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2"
               placeholder="Search by name or email"
               value="<?php echo ($search); ?>">
        <button type="submit" class="btn btn-primary">Search</button>
        <a href="view.php" class="btn btn-secondary ms-2">Reset</a>
    </form>

    <table class="table table-bordered table-striped table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Created At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo($row['name']); ?></td>
                    <td><?php echo($row['email']); ?></td>
                    <td><?php echo($row['phone']); ?></td>
                    <td><?php echo $row['created_at']; ?></td>
                    <td>
                        <a href="update.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?');">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6" class="text-center">No entries found</td></tr>
        <?php endif; ?>
        </tbody>
    </table>
    <nav>
        <ul class="pagination">
            <li class="page-item <?php if($page <= 1) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>">Previous</a>
            </li>
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?php if($page == $i) echo 'active'; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>
            <li class="page-item <?php if($page >= $total_pages) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>">Next</a>
            </li>
        </ul>
    </nav>
</div>
</body>
</html>
