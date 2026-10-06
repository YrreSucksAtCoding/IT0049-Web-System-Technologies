<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <h2><?= esc($heading) ?></h2>

    <!--
        One form serves both Add and Edit.
        $customer is null when adding, so each old() call falls back to an
        empty string. When editing it falls back to the stored value.
        old() itself returns what the user typed if validation just failed,
        which is what stops a rejected form from wiping their work.
    -->
    <form action="<?= esc($action, 'attr') ?>" method="post">
        <?= csrf_field() ?>

        <div class="field">
            <label for="full_name">Full Name</label>
            <input type="text" id="full_name" name="full_name"
                   value="<?= esc(old('full_name', $customer['full_name'] ?? '')) ?>">
        </div>

        <div class="field">
            <label for="email">Email</label>
            <input type="email" id="email" name="email"
                   value="<?= esc(old('email', $customer['email'] ?? '')) ?>">
        </div>

        <div class="field">
            <label for="phone">Phone <span class="hint">(optional)</span></label>
            <input type="text" id="phone" name="phone"
                   value="<?= esc(old('phone', $customer['phone'] ?? '')) ?>">
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <?= $customer === null ? 'Add Customer' : 'Save Changes' ?>
            </button>
            <a href="<?= site_url('customers') ?>" class="btn btn-light">Cancel</a>
        </div>
    </form>

<?= $this->endSection() ?>
