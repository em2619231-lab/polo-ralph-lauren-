<?php
session_start();

// Configuración de la aplicación
define('APP_NAME', 'POLO Ralph Lauren');
define('APP_VERSION', '1.0');
define('IVA_PORCENTAJE', 0.16);

// Configuración de email
define('EMAIL_CONTACTO', 'contacto@ralphlauren.com');

// Configuración de envío
define('ENVIO_GRATIS_MINIMO', 500.00);
define('COSTO_ENVIO', 50.00);

// Roles de usuario
define('ROLE_ADMIN', 'admin');
define('ROLE_CLIENT', 'cliente');

// Funciones de usuario
function isLoggedIn() {
    return isset($_SESSION['usuario_id']);
}

function isAdmin() {
    return isset($_SESSION['usuario_tipo']) && $_SESSION['usuario_tipo'] == ROLE_ADMIN;
}

function getUserName() {
    return $_SESSION['usuario_nombre'] ?? 'Invitado';
}

function getUserId() {
    return $_SESSION['usuario_id'] ?? 0;
}

// Funciones de formato
function formatPrice($precio) {
    return '$' . number_format($precio, 2);
}

function calcularIVA($subtotal) {
    return $subtotal * IVA_PORCENTAJE;
}

function calcularTotal($subtotal) {
    return $subtotal + calcularIVA($subtotal);
}

// Proteger rutas
function requireLogin() {
    if(!isLoggedIn()) {
        header('Location: /login.php');
        exit();
    }
}

function requireAdmin() {
    if(!isAdmin()) {
        header('Location: /acceso-denegado.php');
        exit();
    }
}
?>