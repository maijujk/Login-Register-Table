<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "table");
if (mysqli_connect_errno()) {
  // Jos yhteydessä on virhe, antaa errorin.
  exit('Yhdistäminen ei onnistunut: ' . mysqli_connect_error());
}
?>

<!DOCTYPE html>
<html lang="fi">

<head>
  <title>Login/Register</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
  <style>
    body {
      background-color: darkslategray;
    }

    input[type="text"],
    input[type="password"] {
      width: 100px;
    }

    .nav-pills {
      background-color: white;
      border-radius: 1rem;
      padding: 0.5rem;
    }

    .nav-link.active,
    .nav-link:hover {
      background-color: #eeeeee;
      color: black;
    }

    .card {
      border-radius: 1rem;
      box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
    }

    .card-body {
      padding: 2rem;
    }

    .tab .active {
      /* background-color: #6c757d; */
      background-color: #ffffff;
    }

    button.btn-primary:hover {
      background-color: #0069d9;
      border-color: #0062cc;
    }

    .nav-pills .nav-link.active,
    .nav-pills .show>.nav-link {
      color: #fff;
      background-color: #607d8b;
    }

    .toast-header {
    background-color: #607d8b;
    border: 1px solid #000000;
  }
  
  .toast {
    width: fit-content;
    max-width: 100%;
    background-color: #607d8b;

  }
   .btn-close{
    font-size: 10px;
    cursor: pointer;
  }
  </style>
</head>

<body>
  <section class="vh-100">

    <!-- Pills navs -->
    <div class="container">
      <ul class="nav nav-pills nav-justified mb-4 w-50 mx-auto" id="ex1" role="tablist">
        <li class="nav-item" role="presentation">
          <a class="nav-link active" id="tab-login" data-bs-toggle="pill" href="#Login" role="tab" aria-controls="pills-login" aria-selected="true"><i class="fas "></i>Kirjaudu sisään</a>
        </li>
        <li class="nav-item" role="presentation">
          <a class="nav-link" id="tab-register" data-bs-toggle="pill" href="#Register" role="tab" aria-controls="pills-register" aria-selected="false"><i class="fas fa-chart-line fa-fw me-2"></i>Rekisteröidy</a>
        </li>
      </ul>
      <!-- Pills navs -->


      <!-- Login page -->
      <div class="tab-content">
        <div class="tab-pane fade show active" id="Login" role="tabpanel" aria-labelledby="tab-login">
          <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col-12 col-md-7 col-lg-5 col-xl-4">
              <div class="card shadow-2-strong card bg-light" style="border-radius: 1rem;">
                <?php
                if (isset($_SESSION['stat'])) {
                ?>
                  <div aria-live="polite" aria-atomic="true" class="position-relative">
                    <div class="toast-container top-0 start-50 translate-middle-x">
                      <div class="toast rounded-3" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header text-white rounded-3">
                          <span class="bi bi-info-circle"></span>&nbsp;
                          <strong class="me-auto"><?php echo $_SESSION['stat']; ?></strong>
                          <span id="toast-close" class="btn-close btn-close-white" data-bs-dismiss="toast"></span>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php
                  unset($_SESSION['stat']);
                }
                ?>

                <div class="card-body p-5">
                  <h3 class="text-center p-3">Kirjaudu sisään</h3>

                  <form action="./loginyhteys.php" method="post">

                    <!-- User input -->
                    <div class="input-group mb-3">
                      <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                      <label class="form-label" for="loginName"></label>
                      <input type="text" placeholder="Käyttäjätunnus" name="username" id="loginName" class="form-control" required />
                    </div>

                    <!-- Password input -->
                    <div class="input-group mb-3">
                      <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                      <label class="form-label" for="loginPassword"></label>
                      <input type="password" placeholder="Salasana" name="password" id="loginPassword" class="form-control" required />
                    </div>

                    <!-- Submit button -->
                    <div class="row">
                      <div class="col text-center">
                        <button type="submit" name="register" class="btn btn-secondary btn-block mb-3">Kirjaudu</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Register page -->
        <div class="tab-pane fade" id="Register" role="tabpanel" aria-labelledby="tab-register">
          <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col-12 col-md-7 col-lg-5 col-xl-4">
              <div class="card shadow-2-strong card card bg-light" style="border-radius: 1rem;">
                <?php
                if (isset($_SESSION['statu'])) {
                ?>
                  <div aria-live="polite" aria-atomic="true" class="position-relative">
                    <div class="toast-container top-0 start-50 translate-middle-x">
                      <div class="toast rounded-3" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header text-white rounded-3">
                          <span class="bi bi-info-circle"></span>&nbsp;
                          <strong class="me-auto"><?php echo $_SESSION['statu']; ?></strong>
                          <span id="toast-close" class="btn-close btn-close-white" data-bs-dismiss="toast"></span>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php
                  unset($_SESSION['statu']);
                }
                ?>
                <div class="card-body p-5">
                  <h3 class="text-center p-3">Rekisteröidy käyttäjäksi</h3>
                  <form action="./registeryhteys.php" method="post">

                    <!-- User input -->
                    <div class="input-group mb-3">
                      <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                      <label class="form-label" for="loginName"></label>
                      <input type="text" placeholder="Käyttäjätunnus" name="username" id="loginName" class="form-control" required />
                    </div>

                    <!-- Password input -->
                    <div class="input-group mb-3">
                      <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                      <label class="form-label" for="loginPassword"></label>
                      <input type="password" placeholder="Salasana" name="new_password" id="loginPassword" class="form-control" required />
                    </div>

                    <!-- Repeat Password input -->
                    <div class="input-group mb-3">
                      <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                      <label class="form-label" for="registerRepeatPassword"></label>
                      <input type="password" placeholder="Toista salasana" name="confirm_password" id="registerRepeatPassword" class="form-control" required />
                    </div>

                    <!-- Submit button -->
                    <div class="row">
                      <div class="col text-center">
                        <button type="submit" name="register" class="btn btn-secondary btn-block mb-3">Rekisteröidy</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    $(document).ready(function() {
      $('.toast').toast({delay: 5000});
      $('.toast').toast('show');
    });
  </script>

  <script>
    $(document).ready(function() {
      console.log('<?php echo $_SESSION["activeModal"]; ?>');

      let activeModal = "<?php echo $_SESSION['activeModal'] ?? ''; ?>";
      if (activeModal) {
        $(`#ex1 a[href="#${activeModal}"]`).tab('show');
      }
    });
  </script>
</body>
</html>