<?php
// includes/header.php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'db_config.php'; // Ensure BASE_URL is defined
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LawyerHire - Find Your Legal Expert</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>css/style.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold" href="<?php echo BASE_URL; ?>">
                    <i class="fas fa-gavel me-2"></i>LawyerHire
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php if ($_SESSION['role_id'] == 1): // Client ?>
                                <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>client/view_lawyers.php">View Lawyers</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>client/view_appointments.php">My Appointments</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>client/profile.php">My Profile</a></li>
                            <?php elseif ($_SESSION['role_id'] == 2): // Admin ?>
                                <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>admin/manage_lawyers.php">Manage Lawyers</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>admin/manage_bookings.php">Manage Bookings</a></li>
                                <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>admin/manage_users.php">Manage Users</a></li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a class="nav-link btn btn-outline-light btn-sm ms-2" href="<?php echo BASE_URL; ?>logout.php">
                                    <i class="fas fa-sign-out-alt"></i> Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)
                                </a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_URL; ?>login.php">Login</a></li>
                            <li class="nav-item"><a class="nav-link btn btn-light text-primary ms-2" href="<?php echo BASE_URL; ?>register.php">Register</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="container my-4">