<?php

function clean($string) {
    return htmlentities($string);
}


function redirect($location) {
    header("location: {$location}"); 
    exit();
}


function email_exist($email) {
    $email = filter_Var($email, FILTER_SANITIZE_EMAIL); //provjeravamo da li je e-mail
    $query = "SELECT id FROM users WHERE email = '$email'";
    $result = query($query);
    
    if($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }

}


function user_exist($user) {
    $user = filter_Var($user, FILTER_SANITIZE_STRING); //provjeravamo da li je user(string)
    $query = "SELECT id FROM users WHERE username = '$user'";
    $result = query($query);
    
    if($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}


function set_message($message) //dodajemo poruku u sesiju
{
    if (!empty($message)) {
        $_SESSION['message'] = $message;
    } else {
        $message = "";
    }
}


function display_message()
{
    if (isset($_SESSION['message'])) {
        echo $_SESSION['message'];
        unset($_SESSION['message']); //brišemo poruku iz sesije, da je ponovo ne ispisuje
    }
}


function validate_user_registration() {
    $errors = [];

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $first_name = clean($_POST['first_name']);
        $last_name = clean($_POST['last_name']);
        $username = clean($_POST['username']);
        $email = clean($_POST['email']);
        $password = clean($_POST['password']);
        $confirm_password = clean($_POST['confirm_password']);

        if(strlen($first_name) < 3) {
            $errors[] = "Your First Name cannot be less than 3 characters!";
        }

        if(strlen($last_name) < 3) {
            $errors[] = "Your Last Name cannot be less than 3 characters!";
        }

        if(strlen($username) < 3) {
            $errors[] = "Your Usermame cannot be less than 3 characters!";
        }

        if(strlen($first_name) > 20) {
            $errors[] = "Your Usermame cannot be bigger than 20 characters!";
        }

        if(email_exist($email)) {
            $errors[] = "Sorry, that Email is already used!";
        }

        if(user_exist($username)) {
            $errors[] = "Sorry, that Username is already used!";
        }

        if(strlen($password) < 8) {
            $errors[] = "Password cannot be less that 8 characters!";
        }

        if($password != $confirm_password) {
            $errors[] = "The password was not confirm correctly!";
        }

        if(!empty($errors)) {
            foreach ($errors as $error) {
                echo "<div class='alert'>" . $error . "</div>";
            }
        } else {

            $first_name = filter_var($first_name, FILTER_SANITIZE_STRING);
            $last_name = filter_var($first_name, FILTER_SANITIZE_STRING);
            $username = filter_var($first_name, FILTER_SANITIZE_STRING);
            $email = filter_var($first_name, FILTER_SANITIZE_EMAIL);
            $password = filter_var($first_name, FILTER_SANITIZE_STRING);
            create_user($first_name,$last_name,$username,$email,$password); //pozivamo funkciju da se korisnik kreira
        }
    }
}


function create_user($first_name,$last_name,$username,$email,$password) {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {

        $first_name = escape($_POST['first_name']);
        $last_name = escape($_POST['last_name']);
        $username = escape($_POST['username']);
        $email = escape($_POST['email']);
        $password = escape($_POST['password']);
        $password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (first_name,last_name,username,profile_photo,email,password)
        VALUES('$first_name','$last_name','$username','uploads/default.jpg','$email','$password')";

        confirm(query($sql));
        set_message('You have been successfully registered!');
        redirect("login.php");
    }
}


function validate_user_login() {
    $errors = [];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $email = clean($_POST['email']);
        $password = clean($_POST['password']);

        if(empty($email)) {
            $errors[] = "Email field cannot be empty!";
        }

        if(empty($password)) {
            $errors[] = "Password field cannot be empty!";
        }

        if(empty($errors)) {
            if(user_login($email,$password)) {
                redirect(location: 'index.php');
            } else {
                $errors[] = "Your email or password is incorrect. Please try again!"; 
            }
        }

        if(!empty($errors)) {
            foreach($errors as $error) {
                
                echo "<div class='alert'>" . $error .  "</div>";
            }
        }
    }
}


function user_login($email, $password) {

    $password = filter_var($password, FILTER_SANITIZE_STRING);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = query($query);

    if ($result->num_rows == 1) {
        $data = $result->fetch_assoc();

        if (password_verify($password, $data['password'])) {
            $_SESSION['email'] = $email;
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}


function get_user($id = NULL) {
    
    if ($id != NULL) {
        $query = "SELECT * FROM users WHERE id=" . $id;
        $result = query($query);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return "User not found.";
        }
    } else {
        $query = "SELECT * FROM users WHERE email='" . $_SESSION['email'] . "'";
        $result = query($query);

        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        } else {
            return "User not found.";
        }
    }
}


function user_profile_image_upload() {

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        $target_dir = "uploads/";
        $user = get_user(); //uzimamo ID usera
        $user_id = $user['id'];
        $target_file = $target_dir . $user_id . "." .pathinfo(basename($_FILES["profile_photo_file"]["name"]), PATHINFO_EXTENSION);;
        $uploadOk = 1; //za greške, moze biti i true
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $error = "";
        
        //niz provjera za sliku
        $check = getimagesize($_FILES["profile_photo_file"]["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            $error = "File is not an image.";
            $uploadOk = 0;
        }

        if ($_FILES["profile_photo_file"]["size"] > 5000000) { //vece od 5 mb
            $error = "Sorry, your file is too large.";
            $uploadOk = 0;
        }

        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $error = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            set_message('Error uploading file: '. $error);
        } else {

            $sql = "UPDATE users SET profile_photo='$target_file' WHERE id=$user_id";
            confirm(query($sql));
            set_message('Profile Image uploaded!');

            if (!move_uploaded_file($_FILES["profile_photo_file"]["tmp_name"], $target_file)) {
                set_message('Error uploading file: '. $error);
            }
        }

        redirect(location: 'profile.php');
    }
}


function user_restrictions() {
    if(!isset($_SESSION['email'])) {

        redirect(location: 'login.php');
    }
}


function login_check_pages() {
    if(isset($_SESSION['email'])) {

        redirect(location: 'index.php');
    }
}


function create_post() {
    $errors = [];
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $post_content = clean($_POST['post_content']);

        if (strlen($post_content) > 200) {
            $errors[] = "Your post is too long!";
        }

        if(!empty($errors)) {
            foreach($errors as $error) {
                echo '<div class="alert">' . $error . '</div>';
            }
        } else {

            //upisujume u bazu
            $post_content = filter_var($post_content, FILTER_SANITIZE_STRING);
            $post_content = escape($post_content);

            $user = get_user();
            $user_id = $user['id'];

            $sql = "INSERT INTO posts (user_id, content, likes) VALUES ('$user_id', '$post_content', 0)";

            confirm(query($sql));
            set_message('You added a post!');
            redirect(location: 'index.php'); 
        }
    }
}


function fetch_all_posts()
{
    $query = "SELECT * FROM posts ORDER BY created_time DESC";
    $result = query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $user = get_user($row['user_id']);

            echo "<div class='post'><p><img src='" . $user['profile_photo'] . "' alt=''><i><b>" . $user['first_name'] . " " . $user['last_name'] . "</b></i></p>
                    <p>" . $row['content'] . "</p>
                    <p><i>Date: <b>" . $row['created_time'] . "</b></i></p>
                    
                    <div class='likes'>Likes: <b id='likes_".$row['id']."'>" . $row['likes'] . "</b><button onclick='like_post(this)' data-post_id='".$row['id']."'>LIKE</button></div></div>";
        }
    }
}





    


