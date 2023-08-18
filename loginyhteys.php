<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "table");
if ( mysqli_connect_errno() ) {
	// Jos yhteydessä on virhe, antaa errorin.
	exit('Yhdistäminen ei onnistunut: ' . mysqli_connect_error());
}

if ( !isset($_POST['username'], $_POST['password']) ) {
	exit('Anna käyttäjätunnus ja salasana!');
}

if ($stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?')) {
	$stmt->bind_param('s', $_POST['username']);
	$stmt->execute();
	$stmt->store_result();
    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $password);
        $stmt->fetch();
        // Account exists, now we verify the password.
        // Note: remember to use password_hash in your registration file to store the hashed passwords.
        if (password_verify($_POST['password'], $password)) {
            // Verification success! User has logged-in!
            // Create sessions, so we know the user is logged in, they basically act like cookies but remember the data on the server.
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['name'] = $_POST['username'];
            $_SESSION['id'] = $id;
            header('location: ./table.php');
        } else {
            // Jos salasana väärin
            $_SESSION['stat'] = "Salasana väärin!";
            $_SESSION['activeModal'] = "Login";
            header("Location: index.php");

        }
    } else {
        // Jos käyttäjätunnus väärin
        $_SESSION['stat'] = "Käyttäjätunnus väärin!";
        $_SESSION['activeModal'] = "Login";
        header("Location: index.php");

    }
    }


	$stmt->close();

?>