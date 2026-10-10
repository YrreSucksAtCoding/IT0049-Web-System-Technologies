<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="card">
        <div class="toolbar">
            <div>
                <h2>Sales History</h2>
                <p class="sub">
                    Every transaction, newest first. Sales are never edited or deleted &mdash;
                    changing one would make the stock figures stop matching the history.
                </p>
            </div>
            <div class="toolbar-actions">
                <a href="<?= site_url('sales/new') ?>" class="btn btn-sell">Record Sale</a>
            </div>
        </div>

        <div class="stats" style="margin-bottom:18px">
            <div class="stat"><b><?= count($sales) ?></b><span>transactions</span></div>
            <div class="stat"><b><?= $units ?></b><span>units sold</span></div>
            <div class="stat"><b>&#8369;<?= number_format($revenue, 2) ?></b><span>total revenue</span></div>
        </div>

        <?php if (empty($sales)): ?>

            <p class="empty">No sales recorded yet.</p>

        <?php else: ?>

            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Product</th>
                        <th>Customer</th>
                        <th>Sold by</th>
                        <th class="num">Qty</th>
                        <th class="num">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- product, customer and staff names come from joins in SaleModel,
                         so this loop runs no extra queries -->
                    <?php foreach ($sales as $sale): ?>
                        <tr>
                            <td><?= esc($sale['id']) ?></td>
                            <td><?= esc(date('M d, Y g:i A', strtotime($sale['created_at']))) ?></td>
                            <td><?= esc($sale['product_name']) ?></td>
                            <td>
                                <?php if ($sale['customer_name'] === null): ?>
                                    <!-- customer_id is nullable, which is why the join is a LEFT join -->
                                    <span class="badge badge-none">Walk-in</span>
                                <?php else: ?>
                                    <?= esc($sale['customer_name']) ?>
                                <?php endif; ?>
                            </td>
                            <td><?= esc($sale['staff_name']) ?></td>
                            <td class="num"><?= esc($sale['quantity']) ?></td>
                            <td class="num">&#8369;<?= number_format((float) $sale['total_price'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>
    </div>

<?= $this->endSection() ?>
