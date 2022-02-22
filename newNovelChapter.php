<?php
session_start();

if(isset($_SESSION['username']) && $_SESSION['username']!='' && $_SESSION['username']!=NULL) {
    $cover=$coverErr=$title=$titleErr=$desc=$descErr=$genre=$genreErr="";
    $count=0;
    if($_SERVER["REQUEST_METHOD"]=="POST") {

    }

    function test_input($value) {
        $value=trim($value);
        return $value;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@username</title>
    <link rel="stylesheet" type="text/css" href="css/new.css">
</head>
<body>
    <div class="header">
		<h1>toonindia</h1>
	</div>
    <div class="main">
        <div class="">
            <div class="top left">

            </div>
            <div class="right bottom">
                <div>
                    <p>Create New Chapter</p>
                </div>
                <div name="novel">
                    <input type="text" name="title" id="title" placeholder="Title of Chapter" class="un"> <br>
                    <label for="">Content</label><br><textarea name="content" id="content" cols="200" rows="25" placeholder="Content of the chapter" class="un"></textarea> <br>
                    <label>Available Online</label><br><input type="date" name="date" placeholder="date" id="date"  class="un">
                </div>
                <div id="btns">
                    <br>
                    <button class="btn preview">preview</button>
                    <button class="btn create">create</button>
                    <button class="btn cancel">cancel</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>