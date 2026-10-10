<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="card">
        <div class="toolbar">
            <div>
                <h2>Archived Products</h2>
                <p class="sub">
                    These rows are still in the database with is_archived set to 1.
                    They are hidden from the product list and the sale form, but any
                    past sale that names them still reads correctly.
                </p>
            </div>
            <div class="toolbar-actions">
                <a href="<?= site_url('products') ?>" class="btn btn-light">Back to products</a>
            </div>
        </div>

        <?php if (empty($products)): ?>
            <p class="empty">Nothing archived.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr><th>#</th><th>Name</th><th class="num">Price</th><th class="num">Stock</th><th></th></tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= esc($product['id']) ?></td>
                            <td><?= esc($product['name']) ?></td>
                            <td class="num">&#8369;<?= number_format((float) $product['price'], 2) ?></td>
                            <td class="num"><?= esc($product['stock_quantity']) ?></td>
                            <td>
                                <form action="<?= site_url('products/restore/' . $product['id']) ?>" method="post">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-light btn-small">Restore</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

<?= $this->endSection() ?>
