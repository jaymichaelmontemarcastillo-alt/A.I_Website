<?php
session_start();
require_once '../connect/config.php';

$pdo = getDBConnection();
$user_id = isset($_SESSION['user_id']) && !empty($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$session_id = session_id();
$quote_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$error = '';

if ($quote_id <= 0) {
    $error = 'Invalid quotation selected.';
} else {
    if ($user_id !== null) {
        $stmt = $pdo->prepare('SELECT id FROM quotations WHERE id = ? AND user_id = ?');
        $stmt->execute([$quote_id, $user_id]);
    } else {
        $stmt = $pdo->prepare('SELECT id FROM quotations WHERE id = ? AND session_id = ?');
        $stmt->execute([$quote_id, $session_id]);
    }

    $quote = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$quote) {
        $error = 'Quotation not found or access denied.';
    } else {
        $delete = $pdo->prepare('DELETE FROM quotations WHERE id = ?');
        if ($delete->execute([$quote_id])) {
            header('Location: ../quotations.php?notification=quotation_deleted');
            exit;
        }
        $error = 'Unable to delete quotation at this time.';
    }
}
?>

<?php if (!empty($error)): ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Quotation</title>
    <link rel="stylesheet" href="../assets/css/customer-site/style.css">
</head>
<body>
    <div class="create-quotation-container">
        <div class="page-header">
            <h2><i class="fas fa-file-invoice"></i> Delete Quotation</h2>
            <div class="page-header-actions">
                <a href="../quotations.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Quotations</a>
            </div>
        </div>
        <div class="form-section">
            <div class="section-body">
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php endif; ?>
