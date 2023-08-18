<?php
session_start();
// Luo yhteys
$con = mysqli_connect("localhost", "root", "", "table");

// Jos yhteydessä on virhe, antaa errorin.
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if(isset($_POST['add'])){
    $trn_date = date("Y-m-d H:i:s");
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $notes = $_POST['notes'];
    $choice = $_POST['choice'];
    $submittedby = $_SESSION["name"];

    $insert="INSERT INTO billings
    (`trn_date`,`name`, `lastname`, `address`, `phone`, `email`, `notes`, `choice`, `submittedby`)VALUES
    ('$trn_date', '$name', '$lastname', '$address', '$phone', '$email', '$notes', '$choice', '$submittedby')";
    $query_run = mysqli_query($con, $insert);

    if($query_run){
        $_SESSION['status'] = "Yhteystieto tallenettu!";
        header("Location: table.php");
    }
    else{
        $_SESSION['status'] = "Yhteystietoa ei voitu tallentaa, kokeile uudestaan!";
        header("Location: table.php");    
    }
}
   
if(isset($_POST["edit"])){
    $id = $_POST['update_id'];
    $trn_date = date("Y-m-d H:i:s");
    $name =$_POST['name'];
    $lastname = $_POST['lastname'];
    $address = $_POST['address'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $notes = $_POST['notes'];
    $choice = $_POST['choice'];
    $submittedby = $_SESSION["name"];

    $update="UPDATE billings SET name='".$name."', lastname='".$lastname."', address='".$address."', phone='".$phone."', email= '".$email."', notes= '".$notes."', choice='".$choice."', submittedby='".$submittedby."' WHERE id='".$id."'";
    $query_run = mysqli_query($con, $update);

    if($query_run){
        $_SESSION['status'] = "Yhteystieto päivitetty!";
        header("Location: table.php");
    }
    else{
        $_SESSION['status'] = "Yhteystietoa ei voitu päivittää, kokeile uudestaan!";
        header("Location: table.php");    
    }
}

if(isset($_POST["delete"])){
    $id=$_POST['delete_id'];

	$delete = "DELETE FROM billings WHERE id='".$id."'";
    $query_run = mysqli_query($con, $delete);

    if($query_run){
        $_SESSION['status'] = "Yhteystieto poistettu!";
        header("Location: table.php");
    }
    else{
        $_SESSION['status'] = "Yhteystietoa ei voitu poistaa, kokeile uudestaan!";
        header("Location: table.php");    
    }
}

if(isset($_POST['delete_row'])){ 
    $id=$_POST['delete_row_id'];

    $delete_row = "DELETE FROM billings WHERE id IN($id) ";
    $query_run = mysqli_query($con, $delete_row);

    if($query_run){
        $_SESSION['status'] = "Rivit poistettu!";
        header("Location: table.php");
    }
    else{
        $_SESSION['status'] = "Rivejä ei voitu poistaa, kokeile uudestaan!";
        header("Location: table.php");    
    }
}
?>




