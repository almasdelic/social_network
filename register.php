<?php
include 'inc/header.php';
?>

<div>
    <?php 
    validate_user_registration(); //pozivamo funkciju za create usera
    display_message();
    ?>
</div>

<h1>Register</h1>

<form method="POST">
    <input type="text" name="first_name" placeholder="First Name" required>
    <input type="text" name="last_name" placeholder="Last Name" required>
    <input type="text" name="username" placeholder="Username" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <input type="password" name="confirm_password" placeholder="Confirm Password" required>
    <input type="submit" name="register_submit" value="Register">
</form>

<?php
include 'inc/footer.php';
?>