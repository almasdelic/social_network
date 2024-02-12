<?php include 'inc/header.php'; ?>

<?php
if(isset($_SESSION['email'])) : ?>

    <form method="POST">
        <h2>Create new post</h2>
        <br>

        <textarea name="post_content" cols="60" rows="10" placeholder="Post content here"></textarea>
        <br><br>

        <input type="submit" value="Post" name="submit">
    </form>


<?php else : ?>


<?php endif; ?>

<?php include 'inc/footer.php'; ?>
