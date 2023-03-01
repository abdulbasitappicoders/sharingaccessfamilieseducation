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
  background-image: url('https://sharingaccessfamilieseducation.com/safeBackground.jpg');
  min-height: 100%;
  background-position: center;
  background-size: cover;
}
</style>
</head>
<body style="background-color: black; color:#fff">

<div class="">
  <div class="w3-display-middle">
    <h1 class="w3-animate-top" style="font-size: 40px!important; text-align: center;">Verify Account</h1>
    <hr class="w3-border-grey" style="margin:auto;width:40%">
    <form action="" method="POST">
        <div class="modal-footer d-flex justify-content-center" style="text-align: center;margin: 15px;">
            <a href="{{url('/verifyEmail').'/'.$data['user_id']}}/{{$data['code']}}" class="w3-large w3-center" style="padding-left: 50px;
            padding-right: 50px;
            color: #fff;
            background-color: rgb(0, 0, 0);
            cursor: pointer;
            /* padding: 29px; */
            padding-top: 10px;
            padding-bottom: 11px;
            text-decoration: none;" >Verify</a>
        </div>
    </form>
  </div>
</div>

</body>
</html>
