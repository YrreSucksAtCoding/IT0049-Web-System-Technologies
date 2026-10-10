<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="card">
        <div class="toolbar">
            <div>
                <h2>Customers</h2>
                <p class="sub">Total: <?= count($customers) ?>.</p>
            </div>
            <div class="toolbar-actions">
                <a href="<?= site_url('customers/new') ?>" class="btn btn-primary">+ New Customer</a>
                <a href="<?= site_url('customers/archived') ?>" class="btn btn-light">Archived</a>
            </div>
        </div>

        <?php if (empty($customers)): ?>

            <p class="empty">No customers yet.</p>

        <?php else: ?>

            <table>
                <thead>
                    <tr><th>#</th><th>Full Name</th><th>Email</th><th>Phone</th><th>Added</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $customer): ?>
                        <tr>
                            <td><?= esc($customer['id']) ?></td>
                            <td><?= esc($customer['full_name']) ?></td>
                            <td><?= esc($customer['email']) ?></td>
                            <td><?= esc($customer['phone']) ?></td>
                            <td><?= esc(date('M d, Y', strtotime($customer['created_at']))) ?></td>
                            <td>
                                <div class="row-actions">
                                    <a class="btn btn-light btn-small"
                                       href="<?= site_url('customers/edit/' . $customer['id']) ?>">Edit</a>
                                    <form action="<?= site_url('customers/delete/' . $customer['id']) ?>" method="post">
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
