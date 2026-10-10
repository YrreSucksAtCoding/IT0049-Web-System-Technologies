<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="card">
        <h2><?= esc($heading) ?></h2>
        <p class="sub">Name, price and stock are required. The image is optional.</p>

        <!-- enctype is required for the file input; without it the browser
             sends only the filename as text and getFile() comes back empty -->
        <form action="<?= esc($action, 'attr') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>

            <div class="field">
                <label for="name">Product Name</label>
                <input type="text" id="name" name="name"
                       value="<?= esc(old('name', $product['name'] ?? '')) ?>">
            </div>

            <div class="field">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" step="0.01" min="0.01"
                       value="<?= esc(old('price', $product['price'] ?? '')) ?>">
                <div class="hint">In pesos, e.g. 249.00</div>
            </div>

            <div class="field">
                <label for="stock_quantity">Stock Quantity</label>
                <input type="number" id="stock_quantity" name="stock_quantity" step="1" min="0"
                       value="<?= esc(old('stock_quantity', $product['stock_quantity'] ?? '0')) ?>">
                <div class="hint">Zero is allowed; the product then cannot be sold until restocked.</div>
            </div>

            <div class="field">
                <label for="image">Product Image <span class="hint">(optional)</span></label>

                <?php if (! empty($product['image'])): ?>
                    <div class="current-image">
                        <img src="<?= esc(\App\Libraries\ImageUploader::url('products', $product['image'], 'product-placeholder.png'), 'attr') ?>" alt="Current image">
                        <span class="hint">Current image. Choosing a new file replaces it.</span>
                    </div>
                <?php endif; ?>

                <input type="file" id="image" name="image" accept=".jpg,.jpeg,.png,.webp">
                <div class="hint">JPG, PNG or WebP, 2 MB maximum. Resized to 400&times;400 for display.</div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <?= $product === null ? 'Add Product' : 'Save Changes' ?>
                </button>
                <a href="<?= site_url('products') ?>" class="btn btn-light">Cancel</a>
            </div>
        </form>
    </div>

<?= $this->endSection() ?>
