<?php
session_start();
session_unset();
session_destroy();
header("Location: /_dev/cinderella/admin/index.php");
?>