<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="card">
        <h2>Record a Sale</h2>
        <p class="sub">
            Pick a product, optionally attach a customer, and enter the quantity.
            Stock comes down automatically when the sale is saved.
        </p>

        <?php if (empty($products)): ?>

            <p class="empty">
                Nothing can be sold right now &mdash; every product is either archived
                or out of stock.
            </p>
            <p><a href="<?= site_url('products') ?>" class="btn btn-light">Go to products</a></p>

        <?php else: ?>

            <form action="<?= site_url('sales/store') ?>" method="post">
                <?= csrf_field() ?>

                <div class="field">
                    <label for="product_id">Product</label>
                    <?php $chosenProduct = old('product_id'); ?>
                    <select id="product_id" name="product_id">
                        <option value="">&mdash; choose a product &mdash;</option>
                        <?php foreach ($products as $product): ?>
                            <!-- only sellable products are listed: not archived, stock above zero -->
                            <option value="<?= esc($product['id'], 'attr') ?>"
                                <?= (string) $chosenProduct === (string) $product['id'] ? 'selected' : '' ?>>
                                <?= esc($product['name']) ?>
                                &mdash; &#8369;<?= number_format((float) $product['price'], 2) ?>
                                (<?= (int) $product['stock_quantity'] ?> in stock)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="field">
                    <label for="customer_id">Customer <span class="hint">(optional)</span></label>
                    <?php $chosenCustomer = old('customer_id'); ?>
                    <select id="customer_id" name="customer_id">
                        <option value="">Walk-in &mdash; no customer</option>
                        <?php foreach ($customers as $customer): ?>
                            <option value="<?= esc($customer['id'], 'attr') ?>"
                                <?= (string) $chosenCustomer === (string) $customer['id'] ? 'selected' : '' ?>>
                                <?= esc($customer['full_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <div class="hint">Leave as Walk-in if the buyer is not a registered customer.</div>
                </div>

                <div class="field">
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" name="quantity" min="1" step="1"
                           value="<?= esc(old('quantity', '1')) ?>">
                    <div class="hint">The sale is rejected if this is more than the stock on hand.</div>
                </div>

                <div class="field">
                    <label>Sold by</label>
                    <p class="muted" style="margin:0">
                        <?= esc(session()->get('full_name')) ?>
                        &mdash; taken from your session, not from this form.
                    </p>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-sell">Record Sale</button>
                    <a href="<?= site_url('sales') ?>" class="btn btn-light">Cancel</a>
                </div>
            </form>

        <?php endif; ?>
    </div>

<?= $this->endSection() ?>
