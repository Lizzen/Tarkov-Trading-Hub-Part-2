<head>
    <link rel="stylesheet" type="text/css" href="./css/pie.css">
</head>

<footer class="footer">
    <div class="container">
        <?php
        if (isset($_SESSION['rol']) && $_SESSION['rol'] == 1) {
            echo '<a href="admin.php" class="enlace">Admin</a>';
        }
        ?>
        <a href="faq.php" class="enlace">FAQ</a>
        <p>Derechos reservados &copy; 2023</p>
    </div>
</footer>