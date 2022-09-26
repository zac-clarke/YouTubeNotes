<?php require "incl/parts/header.php"; ?>
        <main class="px-3">

        
            
                <?php if (isset($_SESSION['username'])) :?>
                        <h1>Hello <?= $_SESSION['username']?></h1>
                        <p class="pt-5">You are  logged in. Try to go to your DASHBOARD now.</p>
                        
                    <?php else :?>
                        <h1>Hello Stranger</h1>
                        <p class="pt-5">You are  NOT logged in. Try to login now.</p>
                    <?php endif ?>
         
           
            
        </main>
<?php require "incl/parts/footer.php"; ?>