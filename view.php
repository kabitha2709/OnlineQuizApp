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
        <style>
            #entriesTable tbody tr:hover {
            background-color: #ebcacaff;
                }
    </style>
    </head>
    <body>
        <div class="container mt-5">
            <h2>All Entries</h2>
            <form method="GET" class="mb-3 d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search by name or email" value="<?php echo ($search); ?>">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="view.php" class="btn btn-secondary ms-2">Reset</a>
            </form>
            <div class="table-responsive"> 
                <table class="table table-bordered table-striped table-hover" id="entriesTable">
                    <thead>
                        <tr onmouseover="this.style.backgroundColor='#f2f2f2'" onmouseout="this.style.backgroundColor=''">
                            <th>ID</th>
                            <th onclick="sortTable(1)">Name</th> 
                            <th onclick="sortTable(2)">Email</th> 
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
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal"> Delete </button>
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
                        </div>
                        <script> 
                        function sortTable(n) { 
                            let table, rows, switching, i, x, y, shouldSwitch; 
                            table = document.getElementById("entriesTable"); 
                            switching = true; 
                            while (switching) {
                                switching = false; 
                                rows = table.rows; 
                                for (i = 1; i < (rows.length - 1); i++) {
                                    shouldSwitch = false; 
                                    x = rows[i].getElementsByTagName("TD")[n]; 
                                    y = rows[i + 1].getElementsByTagName("TD")[n]; 
                                    if (x.innerHTML.toLowerCase() > y.innerHTML.toLowerCase()) { 
                                        shouldSwitch = true; 
                                        break; 
                                    }
                                 } 
                                 if (shouldSwitch) { 
                                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]); 
                                    switching = true; 
                                } } 
                                } 
                                </script>
                                <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true"> 
                                    <div class="modal-dialog">
                                    <div class="modal-content"> 
                                    <div class="modal-header"> 
                                        <h5 class="modal-title">Confirm Delete</h5> 
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button> 
                                    </div> 
                                    <div class="modal-body"> Are you sure you want to delete this entry? </div> 
                                    <div class="modal-footer"> 
                                        <a href="delete.php?id=1" class="btn btn-danger">Yes, Delete</a> 
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button> 
                                    </div> 
                                </div> 
                            </div> 
                        </div>
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
                    </body>
                    </html>
