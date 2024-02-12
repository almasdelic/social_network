<?php
include 'inc/header.php';
?>

<div>
    <?php
    display_message();
    validate_user_login();
    ?>
</div>

<h1>Login</h1>

<form method="POST">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="submit" name="login_submit" value="Log In">
</form>

<?php
include 'inc/footer.php';
?>