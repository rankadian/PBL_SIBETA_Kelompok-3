<?php
include('lib/Session.php');

$session = new Session();

if ($session->get('is_login') === true) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - SIBETA</title>

    <!-- FAV ICON -->
    <link href="../assets/img/sibetaV3.png" rel="icon">
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/main.css">
</head>

<body>
    <div class="d-flex justify-content-center align-items-center vh-100 bg-light">
        <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
            <div style="text-align: center; margin-bottom: 2px;">
                <img src="../assets/img/sibetaV3.png" alt="icon" style="max-width: 150px; width: 100%; height: auto;">
            </div>
            <h2 class="text-center mb-4">Login - <strong>SIBETA</strong></h2>
            <p class="login-box-msg text-center mb-4">Sign in to start your session</p>
            <?php
            $status = $session->getFlash('status');
            if ($status === false) {
                $message = $session->getFlash('message');
                echo '<div class="alert alert-warning">' . $message . 
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
            }
            ?>
            <form action="action/auth.php?act=login" method="post" id="form-login">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Enter your username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" name="password" id="password" placeholder="Enter your password" required>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label for="remember" class="form-check-label">Remember Me</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100" style="background-color:#1b156a; border-color:#1b156a;">Sign In</button>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery Validation -->
    <script src="../adminlte/plugins/jquery/jquery.min.js"></script>
    <script src="../adminlte/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="../adminlte/plugins/jquery-validation/additional-methods.min.js"></script>
    <script src="../adminlte/plugins/jquery-validation/localization/messages_id.min.js"></script>

    <script>
        // validate form
        $(document).ready(function() {
            $('#form-login').validate({
                rules: {
                    username: {
                        required: true,
                        minlength: 3,
                        maxlength: 20
                    },
                    password: {
                        required: true,
                        minlength: 5,
                        maxlength: 255
                    }
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.mb-3').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
</body>

</html>
