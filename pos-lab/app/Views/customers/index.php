<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

    <h2>Customer Accounts</h2>
    <p>Total customers: <?= count($customers) ?></p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Phone</th>
            </tr>
        </thead>
        <tbody>
            <?php $row = 1; ?>

            <!-- Loop through the static array sent by Customers::index() -->
            <?php foreach ($customers as $customer): ?>
                <tr>
                    <td><?= $row++ ?></td>
                    <td><?= esc($customer['full_name']) ?></td>
                    <td><?= esc($customer['email']) ?></td>
                    <td><?= esc($customer['phone']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

<?= $this->endSection() ?>
