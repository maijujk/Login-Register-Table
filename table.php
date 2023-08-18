<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "table");
    // Jos yhteydessä on virhe, antaa errorin.
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}
?>

<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Table </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="table.css?<?php echo time(); ?>" />
</head>
<body>
<!-- Ylöspäin-nappi -->
<button onclick="topFunction()" class="btn btn-primary" id="ylös" title="Sivun ylälaitaan"><span class="bi bi-caret-up-fill"></span></button>    
      <script>
        var mybutton = document.getElementById("ylös");
        
        window.onscroll = function() {scrollFunction()};
        
        function scrollFunction() {
          if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            mybutton.style.display = "block";
          } else {
            mybutton.style.display = "none";
          }
        }
        function topFunction() {
          document.body.scrollTop = 0;
          document.documentElement.scrollTop = 0;
        }
      </script>

    <!-- ADD POP UP FORM (Bootstrap MODAL) -->
    <div class="modal fade" id="addmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> Lisää yhteystieto </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="tableyhteys.php" method="POST">
                    
                    <div class="modal-body">
                        <div class="form-group">
                            <label> Etunimi </label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label> Sukunimi </label>
                            <input type="text" name="lastname" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label> Osoite </label>
                            <input type="text" name="address" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label> Puhelin </label>
                            <input type="text" name="phone" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label> Sähköposti </label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        
                         <div class="form-group">
                            <label> Viesti </label>
                            <textarea type="text" name="notes" class="form-control" required></textarea>
                        </div>
                        
                        <br>
                        <div class="form-group">
                            <select class="custom-select" name="choice" required="required">
                                <option value="">Näytä yhteysyiedot</option>
                                <option value="horizontal">Vaakasuunta</option>
                                <option value="upright">Pystysuunta</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Peruuta</button>
                        <button type="submit" name="add" class="btn btn-secondary">Lisää</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EDIT POP UP FORM (Bootstrap MODAL) -->
    <div class="modal fade" id="editmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> Muokkaa yhteystietoa </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="tableyhteys.php" method="POST">

                    <div class="modal-body">

                        <input type="hidden" name="update_id" id="update_id">
                        
                        <div class="form-group">
                            <label> Etunimi </label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label> Sukunimi </label>
                            <input type="text" name="lastname" id="lastname" class="form-control" required>
                        </div>

                        
                        <div class="form-group">
                            <label> Osoite </label>
                            <input type="text" name="address" id="address" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label> Puhelin </label>
                            <input type="text" name="phone" id="phone" class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label> Sähköposti </label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        
                         <div class="form-group">
                            <label> Viesti </label>
                            <textarea type="text" name="notes" id="notes" class="form-control" required></textarea>
                        </div>
                        
                        <br>
                        <div class="form-group">
                            <select class="custom-select" name="choice" required="required">
                                <option value="">Näytä yhteysyiedot</option>
                                <option value="horizontal">Vaakasuunta</option>
                                <option value="upright">Pystysuunta</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Peruuta</button>
                        <button type="submit" name="edit" class="btn btn-secondary">Päivitä</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- DELETE POP UP FORM (Bootstrap MODAL) -->
    <div class="modal fade" id="deletemodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> Poista yhteystieto </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="tableyhteys.php" method="POST">

                    <div class="modal-body">

                        <input type="hidden" name="delete_id" id="delete_id">

                            Haluatko varmasti poistaa yhteystiedon?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Ei </button>
                        <button type="submit" name="delete" class="btn btn-secondary"> Kyllä </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- DELETE MULTIPLE ROW POP UP FORM (Bootstrap MODAL) -->
    <div class="modal fade" id="deleterowmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> Poista rivi </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="tableyhteys.php" method="POST">

                    <div class="modal-body">

                    <input type="hidden" name="delete_row_id" id="delete_row_id">

                            Haluatko varmasti poistaa yhteystiedon?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Ei </button>
                        <button type="submit" name="delete_row" class="btn btn-secondary""> Kyllä </button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- EMPTY ROW POP UP FORM (Bootstrap MODAL) -->
    <div class="modal fade" id="emptyrowmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">

                        Valitse vähintään yksi rivi!

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Ok </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- CREATE PDF POP UP FORM (Bootstrap MODAL) -->
    <div class="modal fade modal-lg" id="createmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> Pdf esikatselu </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="tablepdf.php" method="POST">

                    <!--Tähän tulostuu pdf.php sivun html -->
                    <div class="modal-body" id="pdf_content">          
                    </div>

                    <div class="modal-footer">     
                         <button type="button" name="cancel" class="btn btn-secondary" data-bs-dismiss="modal"> Peruuta </button>
                         <input type="hidden"  name="pdf_id" id="pdf_id">	
                         <!--<input type="hidden" name="invoice_id" id="invoice_id">-->
                         <button type="submit" name="download_pdf" class="btn btn-secondary" title="Lataa PDF"> Lataa</button>
                         <button type="submit" name="send_email" class="btn btn-secondary" title="Lähetä PDF"> Lähetä</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
     <!-- SEND MULTIPLE EMAIL POP UP FORM (Bootstrap MODAL) -->
    <div class="modal fade" id="emailmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"> Lähetä rivi </h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="send_multiple.php" method="POST">

                    <div class="modal-body">
                        
                    <input type="hidden" name="multiple_email_id" id="multiple_email_id">
                    <!--<input type="hidden" name="multiple_invoice_id" id="multiple_invoice_id">-->

                            Haluatko varmasti lähettää pdf sähköpostiin?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Ei </button>
                        <button type="submit" name="send_multiple_email" class="btn btn-primary"> Kyllä </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- EMPTY EMAIL POP UP FORM (Bootstrap MODAL) -->
    <div class="modal fade" id="emptyemailmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                    <div class="modal-body">

                        Valitse vähintään yksi rivi!

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"> Ok </button>
                    </div>
            </div>
        </div>
    </div>

    <div class="container">                    
        <div class="card">
                <div class="card-header">    
                    <?php 
                        if(isset($_SESSION['status'])){
                    ?>                            
                            <div id="test" aria-live="polite" aria-atomic="true" class="position-relative">
                                <div class="toast-container top-0 start-50 translate-middle-x">
                                    <div class="toast rounded-3" role="alert" aria-live="assertive" aria-atomic="true">
                                        <div class="toast-header rounded-3">
                                            <span class="bi bi-info-circle"></span>&nbsp;
                                            <div class="me-auto"><?php echo $_SESSION['status']; ?></div>
                                            <!--<button type="button" class="btn-close" data-bs-dismiss="toast"></button>-->
                                            <span id="toast-close" class="btn-close" data-bs-dismiss="toast"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        unset($_SESSION['status']);
                        }
                    ?>
                    <!--<i class="bi bi-arrow-90deg-down"></i>&nbsp-->
                    <button type="button" class="btn rowbtn" title="Poista yhteystieto" style="display:none"><span class="bi bi-trash"></span> Poista</button>
                    <button class="btn emailbtn" title="Lähetä PDF" style="display:none"><span class="bi bi-envelope"></span> Lähetä</button>
                      <button type="button" class="btn" data-bs-toggle="modal" data-bs-target="#addmodal"><span class="bi bi-person-plus"></span> Lisää yhteystieto</button>
                    <button class="btn float-end" onclick="document.location='./logout.php'">Kirjaudu ulos<span class="bi bi-chevron-right"></span></button>
                     <button id="mode-button" class="btn float-end move">
                        <i id="mode-icon" class="bi bi-brightness-high"></i>
                        <span id="mode-text"></span>
                    </button>
                  </div>
                   <?php
                        $con = mysqli_connect("localhost", "root", "", "table");
                        $count=1;
                        $query = "SELECT * FROM billings";
                        $query_run = mysqli_query($con, $query);
                    ?>
                    <div class="table-responsive">  <!-- taulukko on vaaka-akselilla vieritettävissä, jos se on liian leveä näytölle -->
                     <table id="datatableid" class="table table-bordered">
                        <thead> 
                            <tr>
                                <th style="width: 30px;">
                                <input type="checkbox" id="selectAll" title="Valitse">
                                <i class="bi bi-caret-down-fill"></i>
                                </th>
                                <th style="display:none;"> ID </th>
                                <th style="width: 20px; scope="col"> # </th>
                                <th scope="col"> NIMI </th>
                                <th style="display:none;"> SUKUNIMI </th>
                                <th scope="col"> OSOITE </th>
                                <th scope="col"> PUHELIN </th>
                                <th scope="col"> SÄHKÖPOSTI </th>
                                <th scope="col"> VIESTI </th>
                                <th style="width: 0px;" scope="col"> MUOKKAA </th>
                                <th style="width: 0px;" scope="col"> POISTA </th>
                                <th style="width: 0px;" scope="col"> PDF </th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            if($query_run){
                                foreach($query_run as $row)
                                {
                        ?>
                            <tr>
                                <td>
						            <span class="custom-checkbox">
							        <input type="checkbox" name="multiple_id" value="<?php echo $row["id"]; ?>">
                                    <label for="checkbox"></label>
						            </span>
					            </td>
					            <td style="display:none;"><?php echo $row['id']; ?></td>
                                <td><?php echo $count; ?></td>
                                <td class="name"><?php echo $row['name'] . " " . $row['lastname'];  ?></td>
                                <td style="display:none;"><?php echo $row['lastname']; ?></td>
                                <td><?php echo $row['address']; ?></td>
                                <td><?php echo $row['phone']; ?></td>
                                <td><?php echo $row['email']; ?></td>
                                <td><?php echo $row['notes']; ?></td>
                                <td style="text-align: center;">
                                    <button type="button" class="btn editbtn" title="Muokkaa yhteystietoa"><span class="bi bi-pencil"></span></button>
                                </td>
                                <td style="text-align: center;">
                                    <button type="button" class="btn deletebtn" title="Poista yhteystieto"><span class="bi bi-trash"></span></button>
                                </td>
                                <td style="text-align: center;">
                                    <button type="submit" id="<?php echo $row['id']; ?>" class="btn pdfbtn" title="Lataa/Lähetä pdf"><span class="bi bi-envelope"></span></button>
                                </td>
                            </tr>
                            <?php $count++; } ?>
                            <?php
                            }
                            else{
                                echo "Ei yhteystietoja";
                            }
                        ?>
                        </tbody>   
                      </table>
                    </div>
        </div>
    </div>
    

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/dataTables.bootstrap5.min.js"></script>
    
    <script>
    // Suoritetaan kun käyttäjä painaa "Valise kaikki" -valintaruudut
    $(document).on('change', 'input[type=checkbox]', function () {
        // Jos valittuja rivejä on enemmän kuin 0
        if ($('input[type=checkbox]:checked').length > 0) {
        // Näyttää "Lähetä" -painikkeen
        $('.emailbtn').show();
      } else {
        // Piilottaa "Lähetä" -painikkeen
        $('.emailbtn').hide();
        }
    });
    </script>

    <script>
    // Suoritetaan kun käyttäjä painaa "Valise kaikki" -valintaruudut
    $(document).on('change', 'input[type=checkbox]', function () {
        // Jos valittuja rivejä on enemmän kuin 0
        if ($('input[type=checkbox]:checked').length > 0) {
        // Näyttää "Poista" -painikkeen
        $('.rowbtn').show();
     } else {
         // Piilottaa "Poista" -painikkeen
        $('.rowbtn').hide();
        }
    });
    </script>
    
    <script>
    // Suoritetaan kun käyttäjä painaa "Lähetä" -painiketta
       $(document).on('click', '.emailbtn', function () {
        var email_array = [];
        // Etsitään kaikki valitut rivit ja lisätään niiden arvot taulukkoon
        $("input:checkbox[name=multiple_id]:checked").each(function() {
        email_array.push($(this).val());
        });

        <!--var selected_ids = $('input[name="multiple_id"]:checked').map(function() {-->
        <!--return $(this).closest('tr').find('td:nth-child(3)').text();-->
        <!--}).get();-->
        <!--console.log(selected_ids);-->
        <!--alert(selected_ids);-->
        <!--$('#multiple_invoice_id').val(selected_ids); -->
        
          // Jos valittuja rivejä on enemmän kuin 0
          if(email_array.length > 0) {
            $('#multiple_email_id').val(email_array.join()); // Asettaa rivien ID:t stringinä modaalissa
            $('#emailmodal').modal('show'); // Avaa modaalin
        } else {
            // Avataan modaali, joka ilmoittaa että rivejä ei ole valittu
            $('#emptyemailmodal').modal('show');
        } 
    });
    </script>
    
    <script>  
        // Suoritetaan kun käyttäjä painaa "Pdf" -painiketta
        $(document).on('click', '.pdfbtn', function () {
        var pdf_id = $(this).attr("id"); // Asettaa ID:n modaalissa 
        $('#pdf_id').val(pdf_id);
        
        <!--$tr = $(this).closest('tr');-->
        <!--var invoice_id = $tr.children("td").map(function () {-->
        <!--return $(this).text();-->
        <!--}).get();-->
        <!--console.log(invoice_id);-->
        <!--$('#invoice_id').val(invoice_id[2]);-->
            $.ajax({  
                url:"tablepdf.php",  
                method:"post",  
                // Lähettää ID:n POST-pyynnön parametrina
                data:{pdf_id:pdf_id},
                success:function(data){  
                    $('#pdf_content').html(data); // Näyttää HTML-sisällön
                    $('#createmodal').modal("show"); // Avaa modaalin
                }
            })
        });
    </script>
    
    <!-- stateSave: true = pysyy samalla sivulla 
    responsive: true = taulukko ei mene yli?? -->
    <script>
    // Datatable -toiminnot
     $('#datatableid').DataTable({
    stateSave: true,
    rowReorder: true,
    filter: true,
    paging: true,
    info: false,
    ordering: false,
    lengthMenu: [
        [5, 10, 25],
        [5, 10, 25],
    ],
    language: {
        lengthMenu: 'Näytä _MENU_ yhteystietoa',
        search: '<i class="bi bi-search"></i>',
        searchPlaceholder: 'Etsi',
        zeroRecords: 'Yhteystietoja ei löytynyt',
        infoEmpty: '',
        infoFiltered: '',
        info: '',
        paginate: {
            next: '<i class="bi bi-chevron-right"></i>',
            previous: '<i class="bi bi-chevron-left"></i>',
        },
      }
    });
    $('#datatableid_info').remove();
    </script>

     <script>
        // Suoritetaan kun käyttäjä painaa "Valitse kaikki" -valintaruudut
        $(document).on('click', '#selectAll', function () {
            $('[data-bs-toggle="tooltip"]').tooltip();
            // Haetaan kaikki taulukon valintaruudut
            var checkbox = $('table tbody input[type="checkbox"]');
            if(this.checked) {
                // Asetetaan kaikki valintaruudut valituiksi
                checkbox.each(function(){
                    this.checked = true;                        
                });
            }
            else {
                // Poistetaan kaikkien valintaruutujen valinta
                checkbox.each(function(){
                    this.checked = false;                        
                });
            } 
        });
        $(document).on('click', 'table tbody input[type="checkbox"]', function() {
            // Poistetaan "Valitse kaikki" -ruudun valinta
            if(!this.checked) { 
                $("#selectAll").prop('checked', false);
            }
        });
    </script>

    <script>
        // Suoritetaan kun käyttäjä painaa "Poista rivit" -painiketta
        $(document).on('click', '.rowbtn', function () {
            var array = [];
            // Etsitään kaikki valitut rivit ja lisätään niiden arvot taulukkoon
            $("input:checkbox[name=multiple_id]:checked").each(function() {
            array.push($(this).val());
            });
            // Jos valittuja rivejä on enemmän kuin 0
            if(array.length > 0) {
                $('#deleterowmodal').modal('show'); // Avaa modaalin
                $('#delete_row_id').val(array); // Asettaa rivien ID:t modaalissa
            } 
            else {
                // Avataan modaali, joka ilmoittaa että rivejä ei ole valittu
                $('#emptyrowmodal').modal('show');
            } 
        });
    </script>
    
    <script>
        // Käynnistää "toast" viestin
        $(document).ready(function(){
            // Piilottaa automaatisesti 5s kuluttua
            $('.toast').toast({delay: 5000});
            $('.toast').toast('show');
        });
    </script>

    <script>
        // Suoritetaan kun käyttäjä painaa "Muokkaa" -painiketta
        $(document).on('click', '.editbtn', function () {
            // Avaa modaalin
            $('#editmodal').modal('show');
            // Hakee ID:n
            $tr = $(this).closest('tr');
            var data = $tr.children("td").map(function () {
            return $(this).text();
            }).get();
            // Asettaa muokattavat tiedot modaalissa
            $('#update_id').val(data[1]); 
            $('#name').val(data[3].split(" ")[0]); 
            $('#lastname').val(data[4]); 
            $('#address').val(data[5]);
            $('#phone').val(data[6]);
            $('#email').val(data[7]); 
            $('#notes').val(data[8]); 
            }); 
    </script>

    <script>
        // Suoritetaan kun käyttäjä painaa "Poista" -painiketta
        $(document).on('click', '.deletebtn', function () {
            // Avaa modaalin
            $('#deletemodal').modal('show');
            // Hakee ID:n
            $tr = $(this).closest('tr');
            var data = $tr.children("td").map(function () {
            return $(this).text();
            }).get();
            $('#delete_id').val(data[1]); // Asettaa poistettavan ID:n modaalissa
            });
    </script>
    
    <script>
    const modeBtn = document.getElementById('mode-button');
    const modeIcon = document.getElementById('mode-icon');
    const modeText = document.getElementById('mode-text');
    modeBtn.onclick = () => {
    if (modeBtn.classList.contains('dark-mode')) {
        document.documentElement.classList.remove('dark');
        document.documentElement.classList.add('light');
        window.localStorage.setItem('mode-icon', 'light');
        modeBtn.classList.remove('dark-mode');
        modeIcon.classList.remove('bi-brightness-high');
        modeIcon.classList.add('bi-moon');
        modeText.innerText = "Tumma tila";
    } else {
        document.documentElement.classList.remove('light');
        document.documentElement.classList.add('dark');
        window.localStorage.setItem('mode-icon', 'dark');
        modeBtn.classList.add('dark-mode');
        modeIcon.classList.remove('bi-moon');
        modeIcon.classList.add('bi-brightness-high');
        modeText.innerText = "Vaalea tila";
      }
    };

    const mode = window.localStorage.getItem('mode-icon');
    if (mode === 'dark') {
        modeBtn.classList.add('dark-mode');
        modeIcon.classList.remove('bi-moon');
        modeIcon.classList.add('bi-brightness-high');
        modeText.innerText = "Vaalea tila";
        document.documentElement.classList.remove('light');
        document.documentElement.classList.add('dark');
    } else {
        modeBtn.classList.remove('dark-mode');
        modeIcon.classList.remove('bi-brightness-high');
        modeIcon.classList.add('bi-moon');
        modeText.innerText = "Tumma tila";
        document.documentElement.classList.remove('dark');
        document.documentElement.classList.add('light');
    }
    </script>

</body>
</html>