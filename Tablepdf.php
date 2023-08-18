<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "table");
if ( mysqli_connect_errno() ) {
    // Jos yhteydessä on virhe, antaa errorin.
    exit('Yhdistäminen ei onnistunut: ' . mysqli_connect_error());
    }
    
    function fetch_customer_data($con) {
      $pdf_id = $_POST['pdf_id'];
      $query = "SELECT * FROM billings WHERE id='" . $pdf_id . "'";
      $query_run = mysqli_query($con, $query);
    
      $output = '
        <!DOCTYPE html>
        <html>
        <head>
        <style>
            ' . file_get_contents('table.css') . '
        </style>
        </head>
        <body>
         <section id="pdf2">
         <div class="container">
         <div class="row">
            <div class="col-md-12">
              <div class="table-responsive">
               <table class="table">
                
      ';
     
      while ($row = mysqli_fetch_assoc($query_run)) {
        $name = $row["name"];
        $lastname = $row["lastname"];
        $address = $row["address"];
        $phone = $row["phone"];
        $email = $row["email"];
        $choice = $row["choice"];
        $today = date("d/m/Y");
        
        if ($choice == "horizontal") {
        $output .= '
            <thead>
                <tr>
                  <th>Päiväys</th>
                  <th>Nimi</th>
                  <th>Osoite</th>
                  <th>Puhelin</th>
                  <th>Sähköposti</th>
                </tr>
              </thead>
              <tbody>
              ';
        $output .= '<tr>
                      <td> '.$today.' </td>
                      <td> '.$name.' '.$lastname.'</td>
                      <td> '.$address.' </td>
                      <td> '.$phone.' </td>
                      <td> '.$email.' </td>
                    </tr>
        ';
       }
       
    if ($choice == "upright") {
        $output .= '
            <tr>
                <th class="cell-header">Päiväys</th>
                <td class="cell-data">'.$today.'</td>
            </tr>
            <tr>
                <th class="cell-header">Nimi</th>
                <td class="cell-data">'.$name.' '.$lastname.'</td>
            </tr>
            <tr>
                <th class="cell-header">Osoite</th>
                <td class="cell-data">'.$address.'</td>
            </tr>
            <tr>
                <th class="cell-header">Puhelin</th>
                <td class="cell-data">'.$phone.'</td>
            </tr>
            <tr>
                <th class="cell-header">Sähköposti</th>
                <td class="cell-data">'.$email.'</td>
            </tr>
       ';
       }
      }
      
      $output .= '
              </tbody>
            </table>
           </div>
          </div>
          </div>
        </div>
        </section>
        </body>
        </html>
      ';
    
      return $output;
    }
    
    include "dompdf.php";
      // PDF:n nimi
      $filename = md5(rand()) . '.pdf';
      // Kutsuu funktiota ja lataa sisältöä HTML-tiedostosta
      $html_code = fetch_customer_data($con);
      $pdf = new Pdf();
      // Asettaa paperin koon ja suunnan
      $pdf->setPaper('A4', 'portrait');
      $pdf->load_html($html_code);
      // Muokkaa HTML:n PDF-muotoon
      $pdf->render(); 
      $file = $pdf->output();  
    
      if(isset($_POST['download_pdf'])){ 
        // Lataa PDF 
        $pdf->stream ($filename);
      }   
    
    if(isset($_POST["send_email"])){
      // Lataa tarvittavat kirjastot
      require 'phpmailer/autoload.php';
      require 'phpmailer/phpmailer/src/PHPMailer.php';
      require 'phpmailer/phpmailer/src/SMTP.php';
      require 'phpmailer/phpmailer/src/Exception.php';
      
      $from_name = 'Maiju Martikkala';
      $from_email = "martikkala.maiju@gmail.com";
      $password = 'eijilznbkwqbxyke';
      $mail = new PHPMailer\PHPMailer\PHPMailer();
    try {
      $mail->IsSMTP();        //Asettaa Mailerin lähettämään viestin SMTP:n kautta
      //$mail->SMTPDebug = 2; // testiä varten
      // $mail->Debugoutput = 'html'; // testiä varten
      $mail->SMTPAuth = true;       //Asettaa SMTP-todennuksen.
      $mail->SMTPSecure = 'tls';    //Asettaa yhteyden etuliitteen. Vaihtoehdot ovat "", "ssl" tai "tls"
      //$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // Ota TLS-salaus käyttöön; Myös `PHPMailer::ENCRYPTION_SMTPS` hyväksytään
      $mail->Host = 'smtp.gmail.com'; //Asettaa SMTP-isännät
      $mail->Port = '587';        //Asettaa oletusarvoisen SMTP-palvelimen portin
      $mail->Username = $from_email;  //Sähköposti
      $mail->Password = $password;     
      $mail->SetFrom("$from_email", "$from_name");   
      // $mail->AddReplyTo ("$youremail"); // Vastaa tänne
      // $mail->addBCC('$youremail');  // Piilokopio
      // $mail->addCC('xxxx@gmail.com'); // Kopio
      
      $email_id = $_POST['pdf_id'];
      $query = "SELECT * FROM billings WHERE id='".$email_id."'";
      $query_run = mysqli_query($con, $query);
      while($row = mysqli_fetch_array($query_run)){
        $name = $row['name'];
        $lastname = $row["lastname"];
        $notes = $row["notes"];
        $mail->AddAddress ($row["email"]); 
      }
    
      // Aseta sähköpostin muoto HTML:ksi
      // $mail->WordWrap = 50;    //Asettaa sanoman rivitystekstin tietylle määrälle merkkejä
      $mail->IsHTML(true);       //Asettaa viestin tyypiksi HTML 
      //Liite ja sisältö
      $mail->addStringAttachment($file, $filename); 
      $mail->Subject = iconv('UTF-8', 'ISO-8859-1//TRANSLIT','Yhteystiedot');   
      $mail->Body = nl2br(iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Hei '.$name.' '.$lastname.', '."\n\n".''.$notes.''));  
      
      //Lähetä sähköposti
      $mail->send();
       $_SESSION['status'] = "Pdf lähetetty!";
       header("Location: table.php");
    
    } catch (Exception $e) {
      $_SESSION['status'] = "Pdf ei voitu lähettää. Virhe: {$mail->ErrorInfo}";
      header("Location: table.php");
     }
    }
    ?>
    
    <?php
      echo fetch_customer_data($con);
    ?> 