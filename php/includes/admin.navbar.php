<nav class="navbar navbar-expand-lg navbar-light">
    <a class="navbar-brand" href="<?php echo BASE_URL; ?>admin.welcome.php">Rij-college</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <a class="nav-item nav-link " href="<?php echo BASE_URL; ?>admin.index.php">Leerlingen</a>
            <a class="nav-item nav-link" href="<?php echo BASE_URL; ?>admin.reservation.php">Reserveringen</a>
        </div>
        <div class="nav-right">
            <a href="<?php echo BASE_URL; ?>php/logout.php" class="btn btn-danger">Uitloggen</a>
        </div>
    </div>
</nav>