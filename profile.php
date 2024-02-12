<?php
include "inc/header.php";
?>

<?php

$user = get_user();
echo "<img class='profile-photo' src='" . $user['profile_photo'] . "'>";

user_profile_image_upload();

?>

    <form method="POST" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="profile_image_file">
        <input type="submit" value="Upload Image" name="submit">
    </form>

<?php
include "inc/footer.php";
?>