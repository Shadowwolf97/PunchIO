<?php

function getNavbar() {
    ?>
    
    <!-- Static navbar -->
    <div class="navbar navbar-default navbar-static-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="http://punchio.shdwlf.com/">PunchIO</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#">Home</a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="/register.php">Login</a></li>
            <li><a href="/login.php">Register</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>

    <?php
}

function getMySQL() {
    //MySQL creds are ignored from the repo, but they are pretty self explanatory.
    return new MySQLi(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
}

function hashPassword($password) {
    $opt = [
        'cost' => 11
    ];
    return password_hash($password, PASSWORD_BCRYPT, $opt);
}