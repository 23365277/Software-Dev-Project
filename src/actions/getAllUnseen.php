<?php
    start_session();
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 0;
    $profile = getAllUnseen($userId, 20, $page * 20);
    echo json_encode($profiles);
?>