<?php
session_start();
require_once 'class.php';
$error = "";

if (isset($_POST['login'])) {
    $user = new User();
    $user->setUsername($_POST['username']);
    $user->setPassword($_POST['password']);
    $data = $user->login();

    if ($data) {
        $_SESSION['login'] = true;
        $_SESSION['username'] = $data['username'];
        $_SESSION['role'] = $data['role'];
        header("Location: index.php");
        exit;
    }
    else {
        $error = "Username atau Password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>

    <title>Login TPQ</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link rel="stylesheet" href="assets/style.css">

</head>

<body class="bg-login">

<div class="container">

    <div class="login-box">

        <h2 class="text-center mb-2">
            Sistem TPQ
        </h2>

        <p class="text-center text-muted mb-4">
            Silakan login terlebih dahulu
        </p>

        <?php if($error != ""): ?>

            <div class="alert alert-danger">
                <?= $error ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <div class="mb-3">

                <label>Username</label>

                <input
                    type="text"
                    name="username"
                    class="form-control"
                    required
                >

            </div>

            <div class="mb-4">

                <label>Password</label>

                <input
                    type="password"
                    name="password"
                    class="form-control"
                    required
                >

            </div>

            <button
                type="submit"
                name="login"
                class="btn btn-primary w-100"
            >
                Login
            </button>

        </form>

    </div>

</div>

</body>
</html>