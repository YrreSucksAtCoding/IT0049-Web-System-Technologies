<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <h2>Customer Accounts</h2>
    <p>Total customers: <?= count($customers) ?> <span class="src">(from the database)</span></p>

    <?php if (empty($customers)): ?>

        <p>No customer records found. Make sure the database was imported.</p>

    <?php else: ?>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Date Added</th>
                </tr>
            </thead>
            <tbody>
                <!-- Same foreach as TFA1. Only the source of $customers changed. -->
                <?php foreach ($customers as $customer): ?>
                    <tr>
                        <td><?= esc($customer['id']) ?></td>
                        <td><?= esc($customer['full_name']) ?></td>
                        <td><?= esc($customer['email']) ?></td>
                        <td><?= esc($customer['phone']) ?></td>
                        <td><?= esc(date('M d, Y', strtotime($customer['created_at']))) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

<?= $this->endSection() ?>
