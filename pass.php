<?php
#Verificando se existe PHPSESSID (Sessão iniciada)
// if (isset($_COOKIE['PHPSESSID'])) {
//     #Se existe, retorna para a home do sistema
//     header("location: config/pages/");
// } else {
//     include "includes/global_variables.php"; #Incluindo variaveis globais
//     include "includes/functions.php"; #Incluindo arquivo de funções

    $password = '97W7JiqPpDXbjqyNkW9A==';
    $passHash = password_hash($password, PASSWORD_DEFAULT);
    var_dump($passHash);die;
    // $passDataBase = '$2y$10$kElpiBtBBFcLWU1TGGRX5un15i454MnXO55TumjVbeQs8wfzwoBUO';

//     if (password_verify($password, $passDataBase)) {
//         $user_input = '12+#æ345';
//         $pass = urlencode($user_input);
//         $pass_crypt = crypt($pass, PASSWORD_DEFAULT);

//         if ($pass_crypt == crypt($pass, $pass_crypt)) {
//             session_start();
//             generateSessionToken();
//             $_SESSION['nome'] = "Alessandro Araujo";
//             $_SESSION['id'] = session_id();
//             setcookie("cookie_token", $_SESSION['session_token']);
//             setcookie("cookie_token", $_SESSION['session_token'], time() + 86400);  #Cookie expira em 24 horas
//             setcookie("cookie_token", $_SESSION['session_token'], time() + 86400, "/~rasmus/", ".example.com", 1);

//             echo "<script>
//         			alert('Logado!!');
//         			location.href = 'config/pages/';
//      			</script>";

//         } else {
//             echo "<br>Invalid password";
//         }
//     } else {
//         echo "<br>Login Error";
//     }
// }