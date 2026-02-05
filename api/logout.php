<?php
session_start();
session_unset();
session_destroy();

// segurança extra (opcional, mas top)
$_SESSION = [];

header('Location: ../login.html');
exit;
