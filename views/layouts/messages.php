<?php
if (isset($_SESSION['messages'])) {
    foreach ($_SESSION['messages'] as $msg) {
        echo '<div class="alert ' . htmlspecialchars($msg['type']) . ' alert-dismissible" role="alert">';
        echo '<button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>';
        echo htmlspecialchars($msg['text']);
        echo '</div>';
    }
    unset($_SESSION['messages']);
}
