<?php
session_start();
$DATABASE_HOST = 'localhost';
$DATABASE_USER = 'id20342285_kuivurintupa';
$DATABASE_PASS = 'gS8qqPL=8IrR~{8*';
$DATABASE_NAME = 'id20342285_kuivuri';

$con = mysqli_connect("localhost", "root", "", "kuivurintupa");

if ( mysqli_connect_errno() ) {
// Jos yhteydessä on virhe, antaa errorin.
exit('Yhdistäminen ei onnistunut: ' . mysqli_connect_error());
}

// Funktiolle syötettävät parametrit
function fetch_customer_data($con, $pdf_id_array) {
    $query = "SELECT * FROM billings WHERE id IN (" . implode(",", $pdf_id_array) . ")";
    $query_run = mysqli_query($con, $query);

    $output = '
      <!DOCTYPE html>
      <html>
      <head>
      <style>
      ' . file_get_contents('kuivuri2.css') . '
      </style>
      </head>
      <body>
      <section id="pdf">
      <div class="all">
      <div class="container style=float:left; margin-top:0px;">'
      ;
        $output .= ' 
        <div class="level">
        <p><strong>KUIVURINTUPA RY</strong><br>
        Uudispihantie 49<br>
        74150 Iisalmi</p>     
        <br> 
        <div class="tab">
        <div class="row justify-content-end mt-5" style="margin-left:315px;">
        <div class="col">
        <table id="table0" class="table float-left" style="width:90%">
          <thead>
          <tr>
            <th id="otsake"><strong>JÄSENMAKSU</strong></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          ';
          
     while ($row = mysqli_fetch_assoc($query_run)) {
       $name = $row['name'];
       $lastname = $row['lastname'];
       $address = $row['address'];
       $zip = $row['zip'];
       $district = $row['district'];
       // Hakee POST-pyynnön avulla lähetettyjä laskun numeroita
       $invoice_id = $row['id'];
       $today = date('d/m/Y');
       $future = date('d/m/Y', strtotime('+2 week'));
  
        $output .= '
        <tr>
          <td>Päiväys</td>
          <td>'.$today . '</td>
        </tr>
        ';
        $output .= ' 
        <tr>
          <td>Laskun numero</td>
          <td>'.$invoice_id . '</td>
          </tr>
        ';
  
        $output .= '
        <tr>
          <td>Eräpäivä</td>
          <td>'.$future . '</td>
        </tr>
        ';
  
        $output .= '
        <tr>
          <td>Viivästyskorko</td>
          <td>8.0%</td>
        </tr>
        <tr>
          <td>Tekstikenttä</td>
          <td>Nimi ja osoite</td>
        </tr>
        <tr>
          <td>Maksuehto</td>
          <td>14 päivää netto</td>
        </tr>
        <tr>
          <td>Pankki</td>
          <td>SP Optia</td>
        </tr>
        <tr>
          <td>Tilinumero</td>
          <td>FI87 4600 0010 435770</td>
        </tr>
        <tr>
          <td>Swift/BIC</td>
          <td>HELSFIHH</td>
        </tr>
        </tbody>
      </table>
    </div>
    </div>
  </div>
  </div>
  ';
        
        $output .= ' 
          <p>'.$name . ' '. $lastname .' <br>
          '.$address .' <br>
          '.$zip . ' '. $district .' </p>
        ';  
      
      $output .= '
      <br>
      <div class="tab">
      <table id="table2" class="table2">
            <thead>
            <tr>
                <th>Kuvaus</th>
                <th>Määrä</th>
                <th>Yksikkö</th>
                <th>à hinta</th>
                <th>Alv %</th>
                <th>Alv €</th>
                <th>Yhteensä</th>
              </tr>
            </thead>
            <tbody>
    ';
  
      $fee = $row["fee"];
      $fee_dec = number_format($fee, 2, '.', ' ');
      $year = date('Y');
  
      if ($fee == 20) {
      $output .= '
        <tr>
          <td> Kannatusmaksu '.$year.'</td>
          <td> 1 </td>
          <td> </td>
          <td> '.$fee_dec.' €</td>
          <td> </td>
          <td> </td>
        <td> '.$fee_dec.' €</td>
      </tr>
      ';
    }
    
    if ($fee == 50) {
      $output .= '
        <tr>
          <td> Kannatusmaksu '.$year.'</td>
          <td> 1 </td>
          <td> </td>
          <td> 20.00 €</td>
          <td> </td>
          <td> </td>
        <td> </td>
      </tr>
      ';
  
        $output .= '
          <tr>
          <td> Avainmaksu '.$year.'</td>
          <td> 1 </td>
            <td> </td>
            <td> 30.00 €</td>
            <td> </td>
            <td> </td>
            <td> '.$fee_dec.' €</td>        
          </tr>
        ';
      }
  
          $output .= '
            </tbody>
            <tfoot>
            <tr>
              <td colspan="5"></td>
              <td><strong>Veroton hinta yht</strong></td>
              <td> '.$fee_dec.' €</td>
            </tr>
            ';
          $output .= '
            <tr>
              <td colspan="5"></td>
              <td><strong>Arvonlisävero yht</strong></td>
              <td> 0.00 €</td>
            </tr>
            ';
          $output .= '
            <tr>
              <td colspan="5"></td>
              <td><strong>Yhteensä</strong></td>
              <td><strong>'.$fee_dec.' €</strong></td>
            </tr>
        </tfoot>
        </table>
      </div>
      </div> 
        ';
    }
      
 $output .= '
  <div class="content" style="margin-top: 250px;"></div>
  
   <div class="ala">
    <div class="row" style="display: flex; justify-content: space-between;">
    <div class="col-sm-4" style="flex-basis: 33%;"> 
    <div style="width:33%; float:left; position:absolute; bottom:30px;"> 
    <hr class="first" style="height:2px;border-width:0;color:#326300;background-color:#326300">
     <p class="row1">Kuivurintupa ry<br>
        Uudispihantie 49<br>
        74150 Iisalmi
      </p>
    </div>
    </div>
    <div class="col-sm-4" style="flex-basis: 33%;"> 
    <div style="width:40%; float:left; position:absolute; bottom:30px;"> 
    <hr style="height:2px;border-width:0;color:#326300;background-color:#326300">
      <p>Perttu Kauppinen<br>			
        Puh. 050-4361606<br>			
        perttu.kauppinen2@gmail.com	
      </p>
    </div>
    </div>
    <div class="col-sm-4" style="flex-basis: 30%; margin-left: auto;">
    <div style="width:40%; float:left; position:absolute; bottom:30px;"> 
    <hr class="last" style="height:2px;border-width:0;color:#326300;background-color:#326300">
      <p>SP Optia<br>	
        FI87 4600 0010 435770<br>	
        HELSFIHH
      </p>
    </div>
    </div>
  </div>
  </div>
  </div>
  </section>
  </body>
  </html>
  ';
    // Palautetaan HTML-koodi
    return $output;
  }
 

  if (isset($_POST['send_multiple_email'])) {
    // Lataa tarvittavat kirjastot
    include "dompdf.php";
    require 'phpmailer/autoload.php';
    require 'phpmailer/phpmailer/src/PHPMailer.php';
    require 'phpmailer/phpmailer/src/SMTP.php';
    require 'phpmailer/phpmailer/src/Exception.php';
    // Hakee POST-pyynnön avulla lähetettyjä sähköpostiosoitteita 
    $email_array = explode(",", $_POST['multiple_email_id']);
  
  // Käy läpi jokainen valittu sähköpostiosoite ja lähetä sille PDF-tiedosto
  foreach ($email_array as $email_id) {
    $query = "SELECT * FROM billings WHERE id = $email_id";
    $query_run = mysqli_query($con, $query);
    $row = mysqli_fetch_array($query_run);
        
    // Tarkista, että sähköposti löytyy tietokannasta
    if ($row) {
        $name = $row['name'];
        $lastname = $row['lastname'];
        $email = $row["email"];
            
        // PDF:n nimi
        $filename = 'Kuivurintuvan jasenmaksu.pdf';
        // Kutsuu funktiota ja lataa sisältöä HTML-tiedostosta
        // antaa sille parametreiksi muuttujat
        $html_code = fetch_customer_data($con, array($email_id));
        $pdf = new Pdf();
        // Asettaa paperin koon ja suunnan
        $pdf->setPaper('A4', 'portrait');
        $pdf->loadHtml($html_code);
        // Muokkaa HTML:n PDF-muotoon
        $pdf->render();
        $file = $pdf->output();
  
        // Lähetä sähköposti
        $from_name = 'Kuivurintupa Ry';
        $from_email = "martikkala.maiju@gmail.com";
        $password = 'eijilznbkwqbxyke'; 
        $mail = new PHPMailer\PHPMailer\PHPMailer();
       try {
        $mail->IsSMTP(); //Asettaa lähettämään viestin SMTP:n kautta
        $mail->SMTPAuth = true; //Asettaa SMTP-todennuksen
        $mail->SMTPSecure = 'tls';
        $mail->Host = 'smtp.gmail.com'; //Asettaa SMTP-isännät
        $mail->Port = '587'; //SMTP-palvelimen portin
        $mail->Username = $from_email;
        $mail->Password = $password;
        $mail->SetFrom("$from_email", "$from_name");
        $mail->addAddress($email);
        $mail->IsHTML(true);
    
        //Liite ja sisältö
        $mail->addStringAttachment($file, $filename);
        $mail->Subject = iconv('UTF-8', 'ISO-8859-1//TRANSLIT','Kuivurintuvan jäsenmaksu');
        $mail->Body = nl2br(iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Hei '.$name.' '.$lastname.', '."\n\n".'Liitteenä on laskusi Kuivurintupa ry:n jäsenmaksusta.'));
    
        //Lähetä sähköposti
        $mail->send();
         $_SESSION['status'] = "Laskut lähetetty!";
         header("Location: laskutustable.php");
    
      } catch (Exception $e) {
         $_SESSION['status'] = "Laskuja ei voitu lähettää. Virhe: {$mail->ErrorInfo}";
         header("Location: laskutustable.php");
        }
     }
   }
  }
?>