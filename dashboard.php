<?php require "incl/parts/header.php";
if (!isset($_SESSION['username'])) {
    header('location: index.php');
}

?>
        <main class="px-3">

        
            
           
                        <h1><?= $_SESSION['username']?>'s Dashboard</h1>
                        <p class="pt-5">There is nothing you can do yet! So logout and see if you can access the dashboard after.</p>
         
           
            
        </main>
<?php require "incl/parts/footer.php"; ?>