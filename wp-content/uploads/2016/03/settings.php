<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Razorbee keep-in-touch</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<script>
$(function() {


    name = localStorage.getItem('name');
    email = localStorage.getItem('email');
    subject =  localStorage.getItem('subject');
    message =  localStorage.getItem('message');
    sms =  localStorage.getItem('sms');

    if (!name){
          name = 'saleem kuder';
    }
    $("#name").val(name);


    if (!email){
          email = 'saleem@razorbee.com';
    }
    $("#email").val(email);



    if (!message){

          message = '<p>Hello,&nbsp;</p><p><br />Welcome to Razorbee Online Solution Pvt Ltd! Thanks so much for joining us.&nbsp;</p><p>RazorBee is a fast growing company, provides sharp-edge solutions for all types of software development and software products &nbsp;and IT services that enhances a business worldwide prosperity.</p> <p>Having the experience, it is consistently recognised for its fresh and innovative ideas and applications. It has two wings based on its activities&nbsp;<strong>&nbsp;Services and Products</strong></p> <p><strong>Services:</strong></p> <ul> <li>mobile application development</li> <li>responsive webdesign&nbsp;</li> <li>CMS &amp; ecommerse webdesign</li> <li>SEO&amp; digital marketing&nbsp;</li> <li>graphic &amp; LOGO design</li> </ul> <p><strong>Product:</strong></p> <ul> <li><span style="font-size: 12pt;">digital darpan</span></li> <li><span style="font-size: 12pt;">techyShiksha</span></li> <li><span style="font-size: 12pt;">business management softwawe&nbsp;</span></li> <li><span style="font-size: 12pt;">service ticket&nbsp;</span></li> </ul> <p>&nbsp;</p> <p><span style="font-size: 12pt;">Please visit: <a href="http://www.razorbee.com">http://www.razorbee.com</a></span></p> <p>Name: Mr. Saleem&nbsp;</p> <p>Contact: <a href="mailto:saleem@razorbee.com">saleem@razorbee.com</a>&nbsp;/ &nbsp;+91 9148688883</p> <p>&nbsp;</p> <p>&nbsp;</p> <p>&nbsp;</p> <p style="margin-top: 0pt; margin-bottom: 0pt; margin-left: .31in; text-indent: -.31in; text-align: left; direction: ltr; unicode-bidi: embed; word-break: normal; punctuation-wrap: hanging;">&nbsp;</p>';

    }
    $("#message").val(message);

    if (!subject){

          subject = 'So nice to meet you, Razorbee Online Solutions Pvt Ltd';


    }
      $("#subject").val(subject);
    if (!sms){
          sms = 'So nice to meet you, Razorbee Online Solutions Pvt Ltd. connect: saleem@razorbee.com / +91 9148688883 / www.razorbe.com /';
    }
      $("#sms").val(sms);


  //var phonenumber = $.jStorage.get('name');

 });

 </script>
</head>
<body>
<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="./" class="navbar-brand">
              <img src="image/razorbee_logo.png"  width="110" >

            </a>
        </div>
        <!-- Collection of nav links and other content for toggling -->
        <div id="navbarCollapse" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active"><a href="./">Home</a></li>
                <li><a href="./history.php">History</a></li>
                <li><a href="./settings.php">Settings</a></li>
            </ul>

        </div>
    </div>
</nav>

<form>
  <div id="error"></div>

  <div class="container">
    <h1>settings</h1>
      <div class="row">
          <div class="col-md-8">
              <div class="well well-sm">
                  <form>
                  <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="name">
                                  Name</label>
                              <input type="text" class="form-control" id="name" placeholder="Enter name" />
                          </div>
                          <div class="form-group">
                              <label for="email">
                                  Email Address</label>
                              <div class="input-group">
                                  <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span>
                                  </span>
                                  <input type="email" class="form-control" id="email" placeholder="Enter email" /></div>
                          </div>
                          <div class="form-group">
                              <label for="subject">
                                  Subject</label>
                                  <input type="text" class="form-control" id="subject" placeholder="Enter subject" required="required" />

                          </div>
                      </div>
                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="name">
                                  Message</label>
                              <textarea name="message" id="message" class="form-control" rows="9" cols="25" required="required"
                                  placeholder="Message"></textarea>
                          </div>
                      </div>

                      <div class="col-md-6">
                          <div class="form-group">
                              <label for="name">
                                  SMS text(max 160 character)</label>
                              <textarea name="sms" id="sms" class="form-control" rows="9" cols="25" required="required"
                                  placeholder="SMS text"></textarea>
                          </div>
                      </div>

                      <div class="col-md-12">
                          <button type="submit" class="btn btn-primary pull-right" id="btnContactUs">
                              Send Message</button>
                      </div>
                  </div>
                  </form>
              </div>
          </div>

      </div>
  </div>

</form>

<script>
$( "form" ).submit(function( event ) {
  $("#error").html("");
debugger;
  event.preventDefault();

  name = localStorage.setItem('name', $("#name").val());
  email = localStorage.setItem('email',$("#email").val());
  subject =  localStorage.setItem('subject', $("#subject").val());
  message =  localStorage.setItem('message', $("#message").val());
  sms =  localStorage.setItem('sms', $("#sms").val());


    name = localStorage.getItem('name');
    email = localStorage.getItem('email');
    subject =  localStorage.getItem('subject');
    message =  localStorage.getItem('message');
    sms =  localStorage.getItem('sms');
if (!name){
 $("#error").html($("#error").html()+'<br/><div class="alert alert-danger"><strong>Alert!</strong> Please provide name.</div>');
}
if (!email){
 $("#error").html($("#error").html()+'<br/><div class="alert alert-danger"><strong>Alert!</strong> Please provide email.</div>');
}

if (!subject){
 $("#error").html($("#error").html()+'<br/><div class="alert alert-danger"><strong>Alert!</strong> Please provide subject.</div>');
}
window.scroll(0, 0)

if (name &&  email && subject){
  $("#error").html('<div class="alert alert-success"><strong>Success!</strong></div>');


}
});
</script>


</body>
</html>
