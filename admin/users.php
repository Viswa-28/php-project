<?php
session_start();

if (!isset($_SESSION['role']) || 
    ($_SESSION['role'] !== 'super_admin' && $_SESSION['role'] !== 'admin')) {
    header('Location: ../login.php');
    exit;
}

include('../include/config.php');
include('../include/header.php');
include('./admin-nav.php');

// Fetch users and admins separately
$users = $conn->query("SELECT * FROM users WHERE role='user' ORDER BY id DESC");
$admins = $conn->query("SELECT * FROM users WHERE role IN ('admin','super_admin') ORDER BY id DESC");
?>

<div class="container mt-5">
    <h2 class="text-center mb-4">User Management</h2>

    <!-- Nav Tabs -->
    <ul class="nav nav-tabs" id="userTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="users-tab" data-bs-toggle="tab" data-bs-target="#users" type="button" role="tab">Users</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="admins-tab" data-bs-toggle="tab" data-bs-target="#admins" type="button" role="tab">Admins</button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content mt-4">
        <!-- Users Tab -->
        <div class="tab-pane fade show active" id="users" role="tabpanel">
            <?php if ($users && $users->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered text-white text-start">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Email</th>
                                <?php if ($_SESSION['role'] === 'super_admin'): ?>
                                    <th>Delete</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            while ($row = $users->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <?php if ($_SESSION['role'] === 'super_admin'): ?>
                                        <td><a href="delete-users.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a></td>
                                    <?php endif; ?>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center text-muted">No users found.</p>
            <?php endif; ?>
        </div>

        <!-- Admins Tab -->
        <div class="tab-pane fade" id="admins" role="tabpanel">
            <?php if ($admins && $admins->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered text-white text-start">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Role</th>
                                <th>Email</th>
                                <?php if ($_SESSION['role'] === 'super_admin'): ?>
                                    <th>Delete</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $i = 1;
                            while ($row = $admins->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $i++ ?></td>
                                    <td><?= htmlspecialchars($row['role']) ?></td>
                                    <td><?= htmlspecialchars($row['email']) ?></td>
                                    <?php if ($_SESSION['role'] === 'super_admin' && $row['id'] != $_SESSION['user_id']): ?>
                                        <td><a href="delete-users.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Delete</a></td>
                                    <?php else: ?>
                                        <td class="text-muted">--</td>
                                    <?php endif; ?>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <p class="text-center text-muted">No admins found.</p>
            <?php endif; ?>
        </div>
    </div>
</div>
