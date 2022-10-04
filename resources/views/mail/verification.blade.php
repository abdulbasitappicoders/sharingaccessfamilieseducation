<!DOCTYPE html>
<html>
<head>
<title>Email Verification</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway">
<style>
body,h1 {font-family: "Raleway", sans-serif}
body, html {height: 100%}
.bgimg {
  background-image: url('public/safeBackground.jpg');
  min-height: 100%;
  background-position: center;
  background-size: cover;
}
</style>
</head>
<body>

<div class="bgimg w3-display-container w3-animate-opacity w3-text-white">
  <div class="w3-display-middle">
    <h1 class="w3-animate-top" style="font-size: 40px!important;">Confirm Account</h1>
    <hr class="w3-border-grey" style="margin:auto;width:40%">
    <form action="" method="POST">
    @csrf
        <input type="hidden" name = "code" value="ds">
        <div class="modal-footer d-flex justify-content-center" style="text-align: center;margin: 15px;">
            <button type="submit" class="w3-large w3-center" style="    padding-left: 30px;
    padding-right: 30px;
    color: black;
    background-color: #fff;cursor: pointer;" >Verify</button>
        </div>
    </form>
  </div>
</div>

</body>
</html>