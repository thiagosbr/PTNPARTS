<?php
session_start();
header("Access-Control-Allow-Origin: *"); // Permite solicitações de qualquer origem

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
  header("Access-Control-Allow-Methods: POST, OPTIONS"); // Permitir apenas os métodos POST e OPTIONS
  header("Access-Control-Allow-Headers: Content-Type"); // Permitir apenas o cabeçalho Content-Type
  exit; // Encerrar o script PHP aqui para evitar que o restante do código seja executado
}

require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

// Verifica se o captcha digitado está correto
if ($_POST['captcha_input'] != $_POST['captcha']) {
  die('Código captcha incorreto.');
}

$destinatario = 'contato@ptnparts.com.br';

$nome = $_POST['name'];
$email = $_POST['email'];
$mensagem = $_POST['message'];
$assunto = $_POST['subject'];
$fone = $_POST['fone'];

$corpo = "Nome: $nome\n";
$corpo = "Telefone: $fone\n";
$corpo .= "Email: $email\n";
$corpo .= "Mensagem:\n$mensagem\n";

// Teste de conexão SMTP
if (!$sock = @fsockopen('smtp.kinghost.net', 587, $errno, $errstr, 30)) {
  die('Não foi possível conectar ao servidor SMTP: ' . $errstr);
}
fclose($sock);

// Instancia o objeto do PHPMailer
$mail = new PHPMailer(true); // true para habilitar exceções

// Configurações do servidor SMTP
$mail->isSMTP();
$mail->Host = 'smtp.kinghost.net';
$mail->Port = 465;
$mail->SMTPAuth = true;
$mail->Username = 'contato@ptnparts.com.br';
$mail->Password = 'S@muka07'; // Coloque aqui a senha do seu email
$mail->SMTPSecure = 'ssl';
$remetente = 'contato@ptnparts.com.br';

// Configurações do email a ser enviado
$mail->setFrom($remetente, $nome);
$mail->addAddress($destinatario);
$mail->Subject = 'Formulario de Contato ' . $assunto;
$mail->Body = $corpo;

try {
  // Tenta enviar o email
  $mail->send();

  echo 'Mensagem enviada com sucesso!';
} catch (Exception $e) {
  echo 'Ocorreu um erro ao enviar a mensagem: ' . $mail->ErrorInfo;
}
?>
