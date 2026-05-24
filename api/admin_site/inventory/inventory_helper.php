<?php
// ============================================================
//  includes/inventory_helper.php
//  Shared helper: log a stock change inside an open transaction
//  IMPORTANT: caller must open and commit/rollback the transaction
// ============================================================

/**
 * Insert one row into inventory_logs.
 * Call this INSIDE a transaction, after you've already updated products.stock.
 *
 * @param  PDO        $pdo
 * @param  int        $productId
 * @param  string     $changeType  add|subtract|order|return|adjust
 * @param  int        $quantity    absolute qty that changed
 * @param  int        $prevStock
 * @param  int        $newStock
 * @param  int|null   $adminId
 * @param  string     $note
 * @return void
 * @throws PDOException on failure
 */
function logInventoryChange(
    PDO    $pdo,
    int    $productId,
    string $changeType,
    int    $quantity,
    int    $prevStock,
    int    $newStock,
    ?int   $adminId = null,
    string $note    = ''
): void {
    $sql = "INSERT INTO inventory_logs
                (product_id, change_type, quantity, previous_stock, new_stock, admin_id, note)
            VALUES
                (:product_id, :change_type, :quantity, :prev, :new, :admin_id, :note)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':product_id'  => $productId,
        ':change_type' => $changeType,
        ':quantity'    => abs($quantity),
        ':prev'        => $prevStock,
        ':new'         => $newStock,
        ':admin_id'    => $adminId,
        ':note'        => $note ?: null,
    ]);
}
