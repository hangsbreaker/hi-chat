<?php
session_start();
echo "<div align='center'><br><br><br><span>Waiting for logout...</span></div>";
session_destroy();
?>
<script>location.reload();</script>