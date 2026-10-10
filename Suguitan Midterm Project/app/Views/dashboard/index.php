<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="card">
        <h2>Dashboard</h2>
        <p class="sub">Where the system stands right now.</p>

        <div class="stats">
            <div class="stat"><b><?= $productCount ?></b><span>products on sale</span></div>
            <div class="stat"><b><?= $customerCount ?></b><span>customers</span></div>
            <div class="stat"><b><?= $staffCount ?></b><span>staff accounts</span></div>
            <div class="stat"><b><?= $units ?></b><span>units sold</span></div>
            <div class="stat"><b>&#8369;<?= number_format($revenue, 2) ?></b><span>total revenue</span></div>
        </div>
    </div>

    <div class="card">
        <div class="toolbar">
            <div>
                <h3>Running low</h3>
                <p class="sub">Products with 10 or fewer units left.</p>
            </div>
            <div class="toolbar-actions">
                <a href="<?= site_url('products') ?>" class="btn btn-light">All products</a>
            </div>
        </div>

        <?php if (empty($lowStock)): ?>
            <p class="empty">Nothing is running low.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr><th>Product</th><th class="num">Price</th><th class="num">In stock</th><th></th></tr>
                </thead>
                <tbody>
                    <?php foreach ($lowStock as $product): ?>
                        <tr>
                            <td><?= esc($product['name']) ?></td>
                            <td class="num">&#8369;<?= number_format((float) $product['price'], 2) ?></td>
                            <td class="num">
                                <?php $qty = (int) $product['stock_quantity']; ?>
                                <span class="badge <?= $qty === 0 ? 'badge-out' : 'badge-low' ?>">
                                    <?= $qty === 0 ? 'Out of stock' : $qty . ' left' ?>
                                </span>
                            </td>
                            <td>
                                <a class="btn btn-light btn-small"
                                   href="<?= site_url('products/edit/' . $product['id']) ?>">Restock</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="toolbar">
            <div>
                <h3>Recent sales</h3>
                <p class="sub">The last five transactions.</p>
            </div>
            <div class="toolbar-actions">
                <a href="<?= site_url('sales/new') ?>" class="btn btn-sell">Record Sale</a>
                <a href="<?= site_url('sales') ?>" class="btn btn-light">Full history</a>
            </div>
        </div>

        <?php if (empty($recentSales)): ?>
            <p class="empty">No sales recorded yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr><th>Date</th><th>Product</th><th>Customer</th><th class="num">Qty</th><th class="num">Total</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($recentSales as $sale): ?>
                        <tr>
                            <td><?= esc(date('M d, Y', strtotime($sale['created_at']))) ?></td>
                            <td><?= esc($sale['product_name']) ?></td>
                            <td>
                                <?php if ($sale['customer_name'] === null): ?>
                                    <span class="badge badge-none">Walk-in</span>
                                <?php else: ?>
                                    <?= esc($sale['customer_name']) ?>
                                <?php endif; ?>
                            </td>
                            <td class="num"><?= esc($sale['quantity']) ?></td>
                            <td class="num">&#8369;<?= number_format((float) $sale['total_price'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

<?= $this->endSection() ?>
