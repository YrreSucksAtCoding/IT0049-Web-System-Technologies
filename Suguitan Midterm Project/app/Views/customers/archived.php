<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="card">
        <div class="toolbar">
            <div>
                <h2>Archived Customers</h2>
                <p class="sub">
                    Hidden from the customer list, but still in the table so that
                    sales naming them keep their record.
                </p>
            </div>
            <div class="toolbar-actions">
                <a href="<?= site_url('customers') ?>" class="btn btn-light">Back to customers</a>
            </div>
        </div>

        <?php if (empty($customers)): ?>
            <p class="empty">Nothing archived.</p>
        <?php else: ?>
            <table>
                <thead><tr><th>#</th><th>Full Name</th><th>Email</th><th>Phone</th><th></th></tr></thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><?= esc($customer['id']) ?></td>
                            <td><?= esc($customer['full_name']) ?></td>
                            <td><?= esc($customer['email']) ?></td>
                            <td><?= esc($customer['phone']) ?></td>
                            <td>
                                <form action="<?= site_url('customers/restore/' . $customer['id']) ?>" method="post">
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
