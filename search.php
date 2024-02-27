<?php
session_start();

if (!isset($_SESSION['isAdmin'])) {
    header("Location: create.php");
    exit();
}

$username = "root";
$password = "";
$database = "studentDB";
$servername = "localhost";

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$search = ""; // Initialize the search variable

// Check if a search query is provided in the URL
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    // Prepare the SQL query with the search condition
    $sql = "SELECT s.id, s.fullName, s.email, s.age, s.gpa, l.isAdmin 
            FROM student s
            INNER JOIN login l ON s.id = l.id
            WHERE s.fullName LIKE '%$search%' OR s.email LIKE '%$search%'";

    $result = $conn->query($sql);
} else {
    // If no search query is provided, set $result to false to indicate no records found
    $result = false;
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/view_style.css">

    <title>Search | <?php if ($_SESSION['isAdmin'] != 1) { echo"STUDENT";} else { echo"ADMIN";}?></title>
</head>
<body>
    <div class="container">
    <div class="background user-background" style="padding-bottom:70px;">
    <img src="img/CLSU.png" width="110" height="100" style="margin-left:80px;">
   
    <br><br>
    <?php 
    if($_SESSION['isAdmin']==1) {
        echo "<h2 style='margin-left:85px;'>ADMIN</h2> <br>";
        echo "<a href='register.php' style='text-decoration:none;'><div class='background' style='margin-left:5px; background-color:green; color:white; text-align:center;' onclick='createNew()'>
                <b>Create</b>
              </div> </a>"; 
              echo "";     

    }
    else {
        echo "<h2 style='margin-left:85px;'>Student</h2> ";
        }
    if($_SESSION['isAdmin']==1){
       echo" <a href='view.php' style='text-decoration:none;'><div class='background' style='margin-left:5px;     background-color: rgb(107, 154, 13);
 color:white; text-align:center; margin-top:10px;' onclick='createNew()'>
              <b>View All</b>
            </div> </a> ";
    }
    else {
       echo" <a href='view.php' style='text-decoration:none;'><div class='background' style='margin-left:5px;     background-color: green;
 color:white; text-align:center; margin-top:10px;' onclick='createNew()'>
              <b>View All</b>
            </div> </a> ";
    }
    ?>
    
    <a href="#" style="text-decoration: none;"><div class='background' style='margin-left:5px; background-color:red; color:white; margin-top:10px;text-align:center;' onclick='logout()'>
        <b>Logout</b>
    </div></a>
    <?php 
     echo "<br><hr><br>";
     echo "<p style='text-align:center;'>Date: ";
     echo date("M, d, Y");
     echo "</p>";
    ?>
</div>

    
    <div class="header">
        <h2 style="color:white;">OFFICE OF ADMISIONS</h2>
       </div>
       <br><br>
      
       </div>
        
       <div class="background">
       <form method="GET" action="<?php echo $_SERVER['PHP_SELF']; ?>" style="margin-left: 5px;">
            <input type="text" name="search" placeholder="Search by name or email" style="width: 900px; padding:10px;">
            <button type="submit" style="margin-left: 5px;">Search</button>
        </form>
        <br>
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Age</th>
                <th>GPA</th>
                <th>Role</th>
                <?php if ($_SESSION['isAdmin'] == 1) {
                    echo "<th>Options</th>";
                } ?>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result !== false) {
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["fullName"] . "</td>";
                        echo "<td>" . $row["email"] . "</td>";
                        echo "<td>" . $row["age"] . "</td>";
                        echo "<td>" . $row["gpa"] . "</td>";
                        echo "<td>";
                        if ($row["isAdmin"] == 1) {
                            echo "Admin";
                        } else {
                            echo "Student";
                        }
                        echo "</td>";
                        if ($_SESSION['isAdmin'] == 1) {
                            echo "<td>";
                            echo "<button onclick='updateRow(" . $row["id"] . ")' style='margin-left:10px; background-color:green; color:white; padding:5px;'>Update</button><br>";
                            echo "<button onclick='deleteRow(" . $row["id"] . ")'style='margin-left:12px;margin-top:5px;background-color:red; color:white; padding:5px;'>Delete</button>";
                            echo "</td>";
                        }
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No Records Found!</td></tr>";
                }
            } else {
                echo "<tr><td colspan='5'>Enter a search query above to find records.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
    <br>
            
    <script>
        function updateRow(id) {
            var confirmUpdate = confirm("Are you sure you want to update this record?");
            if (confirmUpdate) {
                window.location.href = 'update.php?id=' + id;
            }
        }

        function deleteRow(id) {
            var confirmDelete = confirm("Are you sure you want to delete this record?");
            if (confirmDelete) {
                window.location.href = 'delete.php?id=' + id;
            }
        }
        function logout(){
        window.location.href = 'logout.php';
    }
    function createNew(){
        windows.location.href='register.php';
    }
    </script>
</body>
</html>
