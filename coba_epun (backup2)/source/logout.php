<?php
// Mulai sesi dan hancurkan session
session_start();
session_destroy();
header("Location: ../login.php");
exit;
