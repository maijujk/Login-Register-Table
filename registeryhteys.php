<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "table");

if ( mysqli_connect_errno() ) {
// Jos yhteydessä on virhe, antaa errorin.
exit('Yhdistäminen ei onnistunut: ' . mysqli_connect_error());
}
// Hakee käyttäjäIdn tietokannasta
$user_id = $_SESSION['id'];
if (isset($_POST["register"])){
    $username = $_POST['username'];
	$new_password = $_POST["new_password"];
    $confirm_password = $_POST["confirm_password"];

    if (empty($username) || empty($new_password) || empty($confirm_password)) {
        $_SESSION['statu'] = "Täytä kaikki kentät!";
        $_SESSION['activeModal'] = "Register";
        header("Location: login.php");
    }

    if (strlen($new_password) > 20 || strlen($new_password) < 5) {
        $_SESSION['statu'] = "Salasanan on oltava 5-20 merkkiä pitkä!";
        $_SESSION['activeModal'] = "Register";
        header("Location: login.php");
        exit();
    }

    if ($new_password !== $confirm_password) {
        $_SESSION['statu'] = "Salasanat eivät ole samat!";
        $_SESSION['activeModal'] = "Register";
        header("Location: login.php");
        exit();
    }

    // Tarkistaa löytyykö käyttäjää samalla käyttäjätunnuksella
    $stmt = $con->prepare('SELECT id, password FROM accounts WHERE username = ?');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['statu'] = "Käyttäjänimi on jo käytössä!";
        $_SESSION['activeModal'] = "Register";
        header("Location: login.php");
        exit();
    }

    // Ensimmäisessä rivissä valmistellaan tietokantakysely tallentamalla käyttäjänimi ja salasana. 
    $stmt = $con->prepare('INSERT INTO accounts (username, password) VALUES (?, ?)');
        // Tämän jälkeen salasana tiivistetään password_hash-funktiolla ennen tallentamista.  

    $password = password_hash($new_password, PASSWORD_DEFAULT);
    $stmt->bind_param('ss', $username, $password);
            // Lopuksi käyttäjän tiedot tallennetaan tietokantaan execute()-funktiolla.  

    $stmt->execute();
    $_SESSION['statu'] = "Käyttäjä rekisteröitiin onnistuneesti!";
    $_SESSION['activeModal'] = "Register";
    header("Location: login.php");
    exit();
}

$con->close();
?>