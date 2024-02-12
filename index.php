<?php include 'inc/header.php'; ?>

<?php
if(isset($_SESSION['email'])) : ?>

    <?php create_post(); ?>
    <br>

    <form method="POST">
        <h2>Create new post</h2>
        <br>

        <textarea name="post_content" cols="60" rows="10" placeholder="Post content here"></textarea>
        <br><br>

        <input type="submit" value="Post" name="submit">
    </form>

    <hr>

    <div>
        <?php display_message(); ?>
    </div>

    <div class="posts">

        <?php fetch_all_posts(); ?>

    </div>

<?php else : ?>

    <div class="homepage">

        <h1>Welcome to the Retro Social Network</h1>
        <hr>
        <p>Welcome to our nostalgic corner of the internet, where the digital clocks turn back to the early 2000s.
           This social network is a haven for those who yearn for the simpler, more genuine connections of yesteryear.
        </p>
        <br>

        <h2>Click <a href="login.php">here</a> to login</h2>
        <br>

        <img src="css/img/social.jpg" alt="">

    </div>


<?php endif; ?>

<?php include 'inc/footer.php'; ?>
