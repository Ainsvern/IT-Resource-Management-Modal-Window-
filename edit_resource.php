<?php
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "it_resource_management";   

// Create Connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if ID is provided
if (isset($_GET['id'])) {
    echo "No ID provided.";
    exit();
}

$id = $_GET['id'];

// Fetch resource data based on ID
$sql = "SELECT * FROM resources WHERE id=$id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "Resource Not found.";
    exit();
}

$row = $result->fetch_assoc();

// Update Resource
if (isset($_POST ['update_resource'])) {
    $resource_name = $_POST['resource_name'];
    $type = $_POST['type'];
    $specification = $_POST['specification'] ?? '';
    $status = $_POST['status'];
    $allocated_to = $_POST['allocated_to'];
    $date_allocated = $_POST['date_allocated'];

    $sql = "UPDATE resources SET
            resource_name='$resource_name',
            type='$type',
            specification='$specification',
            status='$status',
            allocated_to='$allocated_to',
            date_allocated='$date_allocated'
            WHERE id=$id";

            if ($conn->query($sql) == TRUE) {
                header('Location: index.php');
                exit(); // Prevent further execution after redirect
            } else {
                echo "Error updating resource: " . $conn->error;
            }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Resource</title>
</head>
<body>
    <h3>Edit Resource</h3>
    <form action="edit_resource.php?id=<?php echo $id; ?>" method="post">
        <label for="resource_name">Resource Name:</label>
        <input type="text" name="resource_name" id="resource_name" value="<?php echo $row['resource_name']; ?>" required><br>

        <label for="type">Type:</label>
        <input type="text" name="type" id="type" value="<?php echo $row['type']; ?>" required><br>

        <label for="specification">Specification:</label>
        <input type="text" name="specification" id="specification" value="<?php echo $row['specification']; ?>"><br>

        <label for="status">Status:</label>
        <input type="text" name="status" id="status" value="<?php echo $row['status']; ?>" required><br>

        <label for="allocated_to">Allocated To:</label>
        <input type="text" name="allocated_to" id="allocated_to" value="<?php echo $row['allocated_to']; ?>"><br>

        <label for="date_allocated">Date Allocated:</label>
        <input type="date" name="date_allocated" id="date_allocated" value="<?php echo $row['date_allocated']; ?>"><br>

        <input type="submit" name="update_resource" value="Update Resource">
    </form>

    <p><a href="index.php">Back to Resource List</a></p>
</body>
</html>

<?php
$conn->close();
?>