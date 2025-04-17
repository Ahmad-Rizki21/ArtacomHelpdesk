<?php

namespace App\Helpers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailHelper
{
    public static function sendEmail($to, $subject, $body)
    {
        $mail = new PHPMailer(true);
        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; // Ganti dengan host SMTP Anda
            $mail->SMTPAuth = true;
            $mail->Username = env('ahmad2005rizki@gmail.com'); // Ambil dari .env
            $mail->Password = env('@AhmadRizki12345'); // Ambil dari .env
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            //Recipients
            $mail->setFrom(env('mendungriski135@gmail.com'), env('mendungriski135@gmail.com')); // Ambil dari .env
            $mail->addAddress($to); // Tambahkan penerima

            //Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;

            $mail->send();
            return true; // Mengembalikan true jika berhasil
        } catch (Exception $e) {
            return false; // Mengembalikan false jika gagal
        }
    }
}