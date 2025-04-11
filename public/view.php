<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualização do Filme</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/view.css">
    <link rel="stylesheet" href="assets/css/nav.css">
    <link rel="stylesheet" href="assets/css/alert.css">
    <link rel="stylesheet" href="assets/css/comments.css">
    <link rel="icon" type="image/png" href="assets/images/favicon.ico">
    <script src="assets/js/functionLog.js"></script>
</head>
<body>
    <?php include "nav.php"; ?>
   <!-- Container para os alertas -->
   <div id="alertContainer"></div>
    <!-- Loading -->
    <div class="loading" id="loading">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Carregando...</span>
        </div>
    </div>

    <div class="container">
        <div class="movie-detail" id="movie-detail">
            <!-- Detalhes do filme serão carregados aqui -->
        </div>
    </div>

    <div class="container mt-4">
        <div id="comments" class="d-flex flex-column gap-3"></div>
    </div>

    <div class="container mt-4">
        <h2>Deixe seu Comentário</h2>
        <form id="commentForm" style="margin-bottom: 5rem;">
            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input type="text" class="form-control" id="name" required>
            </div>
            <div class="mb-3">
                <label for="comment" class="form-label">Comentário</label>
                <textarea class="form-control" id="comment" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Enviar Comentário</button>
        </form>
    </div>

    <!-- jQuery e Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/alert.js"></script>
    <script src="assets/js/view.js"></script>
</body>
</html>
