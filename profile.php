<?php
include "inc/header.php";
user_restrictions();
?>

<div>
    <?php display_message(); ?>
</div>

<?php
$user = get_user();
echo "<img class='profile-photo' src='" . $user['profile_photo'] . "'>";

user_profile_image_upload();

?>

    <form method="POST" enctype="multipart/form-data">
        Select image to upload:
        <input type="file" name="profile_photo_file">
        <input type="submit" value="Upload Image" name="submit">
    </form>

<?php
include "inc/footer.php";
?>
