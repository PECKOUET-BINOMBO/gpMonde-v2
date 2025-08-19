<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../../vendor/autoload.php';

/**
 * Envoie un email via PHPMailer
 * @param string $to Destinataire
 * @param string $subject Sujet
 * @param string $body Corps HTML
 * @param string $from Expéditeur
 * @return bool
 */
function sendMail($to, $subject, $body, $from = 'no-reply@cargotrack.local') {
    $mail = new PHPMailer(true);
    try {
        // Config SMTP (adapter selon ton hébergeur)
        $mail->isSMTP();
        $mail->Host = 'smtp.example.com'; // à adapter
        $mail->SMTPAuth = true;
        $mail->Username = 'user@example.com'; // à adapter
        $mail->Password = 'password'; // à adapter
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($from, 'CargoTrack');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log('Erreur PHPMailer: ' . $mail->ErrorInfo);
        return false;
    }
}