<?php
session_start();
include 'connect/config.php';
include 'includes/header.php';
$pdo = getDBConnection();

// Use session ID as user identifier
$user_id = session_id();
?>


<div class="quotations-container">
    <div class="quotations-header">
        <h2>My Quotations</h2>
        <a href="api/create_quotation.php" class="btn btn-primary">+ Create New Quotation</a>
    </div>

    <div class="quotations-list">
        <?php
        // Fetch user's quotations
        $sql = "SELECT * FROM quotations WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$user_id]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($result) {
            echo '<table class="quotations-table">
            <thead>
                <tr>
                    <th>Quote #</th>
                    <th>Client</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Expires</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>';

            foreach ($result as $row) {
                $status_class = $row['status'] == 'draft' ? 'draft' : ($row['status'] == 'sent' ? 'sent' : 'accepted');

                echo "<tr>
                <td><strong>#{$row['quote_number']}</strong></td>
                <td>{$row['client_name']}</td>
                <td>₱" . number_format($row['total'], 2) . "</td>
                <td><span class='status {$status_class}'>" . ucfirst($row['status']) . "</span></td>
                <td>" . ($row['expires_at'] ? date('M j', strtotime($row['expires_at'])) : 'N/A') . "</td>
                <td>" . date('M j, Y', strtotime($row['created_at'])) . "</td>
                <td>
                    <a href='api/view_quotation.php?id={$row['id']}' class='btn btn-sm btn-view'>View</a>
                    <a href='api/edit_quotation.php?id={$row['id']}' class='btn btn-sm btn-edit'>Edit</a>
                    <a href='api/delete_quotation.php?id={$row['id']}' class='btn btn-sm btn-delete' onclick='return confirm(\"Delete this quotation?\")'>Delete</a>
                </td>
              </tr>";
            }

            echo '</tbody></table>';
        } else {
            echo '<p class="no-quotations">No quotations yet. <a href="api/create_quotation.php">Create one now</a></p>';
        }
        ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>