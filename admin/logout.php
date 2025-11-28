cat > admin/logout.php << 'EOF'
<?php
session_start();
session_destroy();
header("Location: login.php");
exit();
?>
EOF
