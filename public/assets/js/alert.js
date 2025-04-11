function showAlert(message, type = 'alert-success') {
    const alertContainer = document.getElementById('alertContainer');

    // Cria um novo elemento de alert
    const newAlert = document.createElement('div');
    newAlert.classList.add('alert', 'alert-dismissible', 'fade');
    newAlert.setAttribute('role', 'alert');

    // Adiciona a classe do tipo de alerta (default é 'alert-success')
    newAlert.classList.add(type);

    // Adiciona o conteúdo do alert
    newAlert.innerHTML = `${message}`;

    // Adiciona o alert ao container
    alertContainer.appendChild(newAlert);

    // Exibe o alert
    newAlert.classList.add('show');

    // Remove o alert após 3 segundos
    setTimeout(function() {
        newAlert.classList.remove('show');
        alertContainer.removeChild(newAlert);
    }, 3000);
}