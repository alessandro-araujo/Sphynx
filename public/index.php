
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Filmes</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/index.css">
    <link rel="stylesheet" href="assets/css/nav.css">
    <link rel="stylesheet" href="assets/css/alert.css">
    <link rel="icon" type="image/png" href="assets/images/favicon.ico">
    <script src="assets/js/functionLog.js"></script>

</head>
<body>
    <?php include "nav.php"; ?>
    
    <!-- Loader -->
    <div class="spinner-container" id="loading-spinner">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Carregando...</span>
        </div>
    </div>

   <!-- Container para os alertas -->
   <div id="alertContainer"></div>

    <!-- Conteúdo -->
    <div class="container">
        <h1 class="text-center mt-5">Catálogo de Filmes</h1>
        <div class="movie-list" id="movie-list">
            <!-- Filmes serão carregados aqui -->
        </div>
    </div>

    <!-- jQuery e Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/alert.js"></script>
    <script src="assets/js/index.js"></script>
</body>
</html>
