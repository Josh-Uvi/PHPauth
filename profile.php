<?php 
    include("res/session.php");
    include("res/functions.php");

    if(!isset($_SESSION['username'])){
        redirectTo('index');
    }else{
        if(isset($_SESSION['username'])){
        }
    }
    
?>

<?php include_once("inc/header.php"); ?>
    <div class="container"> <br>
        <div class="jumbotron">
            <h1 class="display-4">Hello <?php if(isset($_SESSION['username'])) echo $_SESSION['username']; ?></h1>
            <p class="lead">This is a simple hero unit, a simple jumbotron-style component for calling extra attention to featured content or information.</p>
            <hr class="my-4">
            <p>It uses utility classes for typography and spacing to space content out within the larger container.</p><br>
            <a class="btn btn-primary btn-lg" href="logout.php" role="button">Logout</a>
        </div>
    </div>

<?php include_once("inc/footer.php"); ?> 