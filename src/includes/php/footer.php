<?php if (isset($_SESSION['user_id']) && empty($skipChatbox)): ?>
<?php include_once $_SERVER['DOCUMENT_ROOT'] . '/includes/php/messaging.php'; ?>
<?php endif; ?>

</body>
</html>