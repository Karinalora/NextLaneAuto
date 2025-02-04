<?php
$host = 'smtp.gmail.com';
$port = 25; // Puedes probar también con 465 o 25


echo "Probando conexión SMTP a $host:$port...<br>";

$connection = fsockopen($host, $port, $errno, $errstr, 10);
if (!$connection) {
    echo "❌ No se pudo conectar: $errstr ($errno)";
} else {
    echo "✅ Conexión exitosa a $host en el puerto $port";
    fclose($connection);
}
?>
