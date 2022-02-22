<?php 
    session_start();
    if(isset($_SESSION['username']) && $_SESSION['username']!='' && $_SESSION['username']!=NULL) {
        header('location:index.php');
    }
    $name=$nameErr=$username=$usernameErr=$gender=$genderErr=$phone=$phoneErr=$email=$emailErr=$pass=$passErr=$cpass=$cpassErr="";
    $count=0;

    if($_SERVER["REQUEST_METHOD"]=="POST") {
        function test($input,$match) {
            if(empty($_POST[$input])) {
                return 1;
            } else {
                $test = test_input($_POST[$input]);
                if(preg_match($match,$test)) {
                    return 2;
                } else {
                    return 3;
                }
            }
        }

        $nameErr = test('Name','/^[a-zA-Z ]{3,15}$/');
        $usernameErr = test('Username','/^[a-zA-Z0-9._-]{3,16}$/');
        $emailErr = test('Email','/^[a-z0-9.]{5,}@[a-z]{3,10}.[a-z]{2,8}(.[a-z]{2,8})?$/');
        $phoneErr = test('Phone','/^[0-9]{10}$/');
        $passErr = test('pass','/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{4,16}$/');
        $cpassErr = test('pass','/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{4,16}$/');

        if($nameErr==2) {
            $name = $_POST['Name'];
            $nameErr="";
            $count++;
        }
        if($usernameErr==2) {
            $username = $_POST['Username'];
            $usernameErr="";
            $count++;
        }
        if($emailErr==2) {
            $email = $_POST['Email'];
            $emailErr="";
            $count++;
        }
        if($phoneErr==2) {
            $phone = $_POST['Phone'];
            $phoneErr="";
            $count++;
        }
        if($passErr==1) {
            $passErr = "Password cannot be empty.";
        } else if($passErr==3) {
            $passErr = "Password must contain atleast one character, number,capital letter, small letter.";
        } else if($passErr==2) {
            $pass=$_POST['pass'];
            $count++;
        }
        if($cpassErr==1) {
            $cpassErr = "Confirm password cannot be empty.";
        } else if($cpassErr==3) {
            $cpassErr = "Password must contain atleast one character, number,capital letter, small letter.";
        } else if($cpassErr==2 && $passErr==2) {
            $cpass=$_POST['cpass'];
            $cpassErr="";
            $passErr="";
            if($cpass!=$pass) {
                $cpass="";
                $cpassErr = "Confirm password should be same as password.";
            } else {
                $count++;
            }
        }
        function value($input,$field) {
            if($input==1) {
                return "$field cannot be empty.";
            } else if($input==3) {
                return "Please enter valid $field";
            }
        }

        $nameErr = value($nameErr,'Name');
        $usernameErr = value($usernameErr,'Username');
        $emailErr = value($emailErr,'Email');
        $phoneErr = value($phoneErr,'Phone');

        if($_POST['g']=='gen') {
            $genderErr="Please select gender";
        } else {
            $gender = $_POST['g'];
            $count++;
        }
        if($count==7) {
            $host='localhost';
            $user='root';
            $pass='';
            $dbname='toonindia';
            $conn=mysqli_connect($host,$user,$pass,$dbname);
            if(!$conn) {
                die("couldn't connect : ".mysqli_connect_error())."<br>";
            }
            if(isset($_POST['submit'])) {
                $sql = "INSERT INTO toonusers(name,username,gender,phone,email,password,profile) VALUES('$name','$username','$gender','$phone','$email','$cpass','default.jfif')";
                $sql2 = "SELECT username from toonusers where username='$username'";
                $result2 = mysqli_query($conn,$sql2);
                $num = mysqli_num_rows($result2);
                if($num) {
                    $usernameErr = "Username already exists";
                } else {
                    $result = mysqli_query($conn,$sql);
                    if(!$result) {
                        die('Query Failed! '.mysqli_error($conn));
                    }
                    $_SESSION['username']=$username;
                    header('location:index.php');
                }
            }
            mysqli_close($conn);
        }
    }

    function test_input($value) {
        $value=trim($value);
        return $value;
    }

if(isset($_POST['signin'])) {

    $username = $_POST['loginuser'];
    $password = $_POST['loginpass'];

    $host='localhost';
    $user='root';
    $pass='';
    $dbname='toonindia';
    $conn=mysqli_connect($host,$user,$pass,$dbname);
    if(!$conn) {
        die("couldn't connect : ".mysqli_connect_error())."<br>";
    }
    if(isset($_POST['signin'])) {
        $sql = "SELECT username from toonusers where username='$username'";
        $result = mysqli_query($conn,$sql);
        $num = mysqli_num_rows($result);
        if($num) {
            $sql2 = "SELECT * from toonusers where username='$username' and password='$pass'";
            $result2 = mysqli_query($conn,$sql2);
            $num2 = mysqli_num_rows($result2);
            if($num2) {
                $_SESSION['username']=$username;
                header('location:index.php');
            } else {
                ?>
                <script>
                    alert("Username or Password is wrong");
                </script>
                <?php
            }
        } else {
            ?>
                <script>
                    alert("Username or Password is wrong");
                </script>
            <?php
        }
    }
    mysqli_close($conn);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>toonindia</title>
    <link 
     href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" 
     rel="stylesheet" 
     integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" 
     crossorigin="anonymous"
    />
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/signup.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed">
        <div class="container-fluid">
            <h4 id="logo">toonindia</h4>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav justify-content-end">
                    <?php 
                        if(isset($_SESSION['username']) && $_SESSION['username']!='' && $_SESSION['username']!=NULL) {
                            ?>
                                <li class="nav-item" id="active"><a href="index.php">Home</a></li>
                                <li class="nav-item"><a href="profile.php">Profile</a></li>
                            <?php
                        } else {
                            ?>
                                <li class="nav-item"><a href="index.php">Home</a></li>
                                <li class="nav-item" data-bs-toggle="modal" data-bs-target="#loginModal"><button class="nav-btn" type="submit"><i class="glyphicon glyphicon-log-in"></i>Login</button></li>
                                <li class="nav-item" id="active"><button class="nav-btn" type="submit" onclick="window.location.href='signup.php'"><i class="glyphicon glyphicon-user"></i>Signup</button></li>
                            <?php
                        }
                    ?>
                </ul>
            </div>
        </div>
    </nav>

     <!-- Modal login -->
     <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="main2">
                <p class="sign">Sign in</p>
                <div class="modal-body">
                <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                    <input class="un" name="loginuser" type="text" placeholder="Email">
                    <input class="pass" name="loginpass" type="password" placeholder="Password"> <br>
                    <p><button type="submit" name="signin" class="btn btn-secondary">Sign in</button></p>
                    <p class="forgot"><a href="#">Forgot Password?</a></p>
                </form>
                </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="main">
        <p class="sign">Sign up</p>
        <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" enctype="multipart/form-data">
            <input class="un" name="Name" type="text" placeholder="Name">
            <p><label style="color:red; font-size:13px;"><?php echo $nameErr; ?></label></p>
            <input class="un" name="Username" type="text" placeholder="Username">
            <p><label style="color:red; font-size:13px;"><?php echo $usernameErr; ?></label></p>
            <select name="g" class="un" style="padding-left: 30%;" required>
                <option value="gen" selected>Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select><br>
            <p><label style="color:red; font-size:13px;"><?php echo $genderErr; ?></label></p>
            <input class="un" name="Phone" type="tel" placeholder="Phone">
            <p><label style="color:red; font-size:13px;"><?php echo $phoneErr; ?></label></p>
            <input class="un" name="Email" type="text" placeholder="Email">
            <p><label style="color:red; font-size:13px;"><?php echo $emailErr; ?></label></p>
            <input class="pass" name="pass" type="password" placeholder="Password">
            <p><label style="color:red; font-size:13px;"><?php echo $passErr; ?></label></p>
            <input class="pass" name="cpass" type="password" placeholder="Confirm Password">
            <p><label style="color:red; font-size:13px;"><?php echo $cpassErr; ?></label></p>
            <p><button type="submit" name="submit" class="btn btn-secondary">Sign up</button></p>
        </form>
    </div>

    <script 
     src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" 
     integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" 
     crossorigin="anonymous">
    </script>

</body>
</html>