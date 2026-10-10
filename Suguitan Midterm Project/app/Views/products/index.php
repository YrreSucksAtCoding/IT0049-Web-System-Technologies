<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="card">
        <div class="toolbar">
            <div>
                <h2>Products</h2>
                <p class="sub">Total on sale: <?= count($products) ?>.</p>
            </div>
            <div class="toolbar-actions">
                <a href="<?= site_url('products/new') ?>" class="btn btn-primary">+ New Product</a>
                <a href="<?= site_url('products/archived') ?>" class="btn btn-light">Archived</a>
            </div>
        </div>

        <?php if (empty($products)): ?>

            <p class="empty">No products yet. Use the New Product button to add one.</p>

        <?php else: ?>

            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>#</th>
                        <th>Name</th>
                        <th class="num">Price</th>
                        <th class="num">Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td>
                                <!-- the database stores a filename; the folder and the
                                     placeholder fallback are added by the helper -->
                                <img class="thumb"
                                     src="<?= esc(\App\Libraries\ImageUploader::url('products', $product['image'], 'product-placeholder.png'), 'attr') ?>"
                                     alt="<?= esc($product['name'], 'attr') ?>">
                            </td>
                            <td><?= esc($product['id']) ?></td>
                            <td><?= esc($product['name']) ?></td>
                            <td class="num">&#8369;<?= number_format((float) $product['price'], 2) ?></td>
                            <td class="num">
                                <?php $qty = (int) $product['stock_quantity']; ?>
                                <?php if ($qty === 0): ?>
                                    <span class="badge badge-out">Out of stock</span>
                                <?php elseif ($qty <= 10): ?>
                                    <span class="badge badge-low"><?= $qty ?> left</span>
                                <?php else: ?>
                                    <span class="badge badge-ok"><?= $qty ?></span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="row-actions">
                                    <a class="btn btn-light btn-small"
                                       href="<?= site_url('products/edit/' . $product['id']) ?>">Edit</a>

                                    <!-- Delete is a POST form with a CSRF token, and it
                                         archives rather than removing the row -->
                                    <form action="<?= site_url('products/delete/' . $product['id']) ?>" method="post">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn btn-danger btn-small">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>
    </div>

<?= $this->endSection() ?>
