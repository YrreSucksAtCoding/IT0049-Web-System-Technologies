<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <div class="toolbar">
        <div>
            <h2>Customer Accounts</h2>
            <p>Total customers: <?= count($customers) ?></p>
        </div>
        <a href="<?= site_url('customers/new') ?>" class="btn btn-primary">+ New Customer</a>
    </div>

    <?php if (empty($customers)): ?>

        <p>No customer records yet. Use the New Customer button to add one.</p>

    <?php else: ?>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Date Added</th>
                    <th></th>
                </tr>
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
                            <a class="btn btn-light btn-small"
                               href="<?= site_url('customers/edit/' . $customer['id']) ?>">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

<?= $this->endSection() ?>
