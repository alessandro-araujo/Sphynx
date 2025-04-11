<?php
    require $_SERVER['DOCUMENT_ROOT'] . '\core\env.php';
?>
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <a class="navbar-brand" href="index.php">SWAPI</a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" href="index.php">Home</a>
            </li>
            <?php if (strtolower($_ENV['XDEBUG']) === "true"): ?>
                <li class="nav-item"><a class="nav-link" href="xdebug.php">Logs</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
