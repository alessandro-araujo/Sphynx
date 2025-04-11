$.ajax({
    url: 'http://localhost:8000/logs',
    type: 'GET',
    dataType: 'text',  // Força o tratamento da resposta como texto
    success: function(response, status, xhr) {
        // Verifica o status da resposta
        if (xhr.status === 200) {
            // Se o status for 200, adiciona o item de navegação "Logs"
            $('.navbar-nav').append('<li class="nav-item"><a class="nav-link" href="xdebug.php">Logs</a></li>');
        }
    },
    error: function(xhr, status, error) {
        // Caso haja erro, não faz nada ou mostra uma mensagem, se necessário.
        console.log('Erro na requisição:', error);
    }
});