$(document).ready(function() {
    // Array com IDs dos filmes e caminhos das imagens
    const movieImages = {
        1: 'assets/images/a_new_hope.jpg',
        2: 'assets/images/the_empire_strikes_back.jpg',
        3: 'assets/images/return_of_the_jedi.jpg',
        4: 'assets/images/the_phantom_menace.jpg',
        5: 'assets/images/attack_of_the_clones.jpg',
        6: 'assets/images/revenge_of_the_sith.jpg',
    };

    // Captura o ID do filme pela URL
    const urlParams = new URLSearchParams(window.location.search);
    const movieId = urlParams.get('id');

    // Se não houver ID, exibe mensagem e encerra
    if (!movieId) {
        $('#movie-detail').html('<p class="text-danger">Nenhum filme encontrado.</p>');
        $('#loading').fadeOut();
        return;
    }

    // Requisição para o endpoint do filme específico
    $.get(`http://localhost:8000/films/${movieId}`, function(data) {
        logRequest("GET", `/films/${movieId}`);
        if (!data || !data.title) {
            $('#movie-detail').html('<p class="text-danger">Nenhum filme encontrado.</p>');
            $('#loading').fadeOut();
            return;
        }

        $('#loading').fadeOut();
        const movieImage = movieImages[movieId] || 'assets/images/default.jpg';

        const movieDetail = `
            <div class="row">
                <div class="col-md-6">
                    <img src="${movieImage}" alt="${data.title}" class="img-fluid">
                </div>
                <div class="col-md-6">
                    <div class="movie-info">
                        <h2>${data.title}</h2>
                        <p><strong>Episode: </strong> ${data.episode_id}</p>
                        <p><strong>Release Date:</strong> ${data.release_date}</p>
                        <p> <strong>The film has ${data.film_age}.</strong></p>
                        <p><strong>Director:</strong> ${data.director}</p>
                        <p><strong>Producer(s):</strong> ${data.producer}</p>
                        <h5><strong>Sinopse:</strong></h5>
                        <p style="font-size: 80%;">${data.opening_crawl}</p>
                        <h5>Personagens:</h5>
                        <ul>
                            ${data.characters.map(character => `<li>${character}</li>`).join('')}
                        </ul>
                    </div>
                </div>
            </div>
        `;

        // Exibe os detalhes do filme
        $('#movie-detail').html(movieDetail);

        // Requisição para carregar comentários
        logRequest("GET", `/comment/${movieId}`);
        $.ajax({
            url: `http://localhost:8000/comment/${movieId}`,
            method: 'GET',
            success: function (data) {
                let commentsHtml = '';
                data.forEach(comment => {
                    commentsHtml += `
                        <div class="comment-card">
                            <h5 class="comment-author">${comment.name}</h5>
                            <p class="comment-text">${comment.comment}</p>
                        </div>`;
                });
                $('#comments').html(commentsHtml);
            },
            error: function () {
                $('#comments').html('<p class="text-danger text-center"></p>');
            }
        });

        // Lógica de envio de comentário via AJAX
        $('#commentForm').on('submit', function(e) {
            logRequest("POST", `/comment/${movieId}`);
            e.preventDefault();

            var name = $('#name').val();
            var comment = $('#comment').val();
            var id_film = movieId;

            var data = {
                name: name,
                id_film: id_film,
                comment: comment
            };

            $.ajax({
                url: 'http://localhost:8000/comment',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(data),
                success: function(response) {
                    showAlert('Comentário enviado com sucesso!');
                    $('#commentForm')[0].reset(); // Limpar formulário
                    $('#comments').append(`
                        <div class="comment-card">
                            <h5 class="comment-author">${name}</h5>
                            <p class="comment-text">${comment}</p>
                        </div>
                    `); // Adiciona o comentário à lista
                },
                error: function(xhr, status, error) {
                    showAlert('Erro ao enviar comentário.', 'alert-danger');
                }
            });
        });

    }).fail(function() {
        $('#movie-detail').html('<p class="text-danger">Erro ao carregar os detalhes do filme.</p>');
        $('#loading').fadeOut();
    });
});