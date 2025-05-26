<?php
// index.php
require_once __DIR__ . '/includes/db_config.php';
require_once __DIR__ . '/includes/header.php'; // Handles session_start()
?>

<div class="p-5 mb-4 bg-light rounded-3 shadow-sm">
    <div class="container-fluid py-5 text-center">
        <?php if (isset($_SESSION['user_id'])): ?>
            <?php if ($_SESSION['role_id'] == 1): // Client ?>
                <h1 class="display-5 fw-bold text-primary">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                <p class="col-md-8 fs-4 mx-auto">You are logged in as a client. Explore our legal experts or manage your existing appointments.</p>
                <div class="mt-4">
                    <a href="<?php echo BASE_URL; ?>client/view_lawyers.php" class="btn btn-primary btn-lg me-3">
                        <i class="fas fa-search me-2"></i>View Lawyers
                    </a>
                    <a href="<?php echo BASE_URL; ?>client/view_appointments.php" class="btn btn-outline-primary btn-lg">
                        <i class="fas fa-calendar-check me-2"></i>My Appointments
                    </a>
                </div>
            <?php elseif ($_SESSION['role_id'] == 2): // Admin ?>
                <h1 class="display-5 fw-bold text-success">Welcome, Admin <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                <p class="col-md-8 fs-4 mx-auto">You are logged in as an administrator. Take control of the consultancy.</p>
                <div class="mt-4">
                    <a href="<?php echo BASE_URL; ?>admin/manage_lawyers.php" class="btn btn-success btn-lg me-3">
                        <i class="fas fa-user-tie me-2"></i>Manage Lawyers
                    </a>
                    <a href="<?php echo BASE_URL; ?>admin/manage_bookings.php" class="btn btn-outline-success btn-lg">
                        <i class="fas fa-book me-2"></i>Manage Bookings
                    </a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <h1 class="display-5 fw-bold text-primary">Welcome to LawyerHire!</h1>
            <p class="col-md-8 fs-4 mx-auto">Your trusted partner for finding and hiring expert legal professionals for any need.</p>
            <div class="mt-4">
                <a href="<?php echo BASE_URL; ?>login.php" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-sign-in-alt me-2"></i>Login
                </a>
                <a href="<?php echo BASE_URL; ?>register.php" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-user-plus me-2"></i>Register
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title text-primary"><i class="fas fa-search me-2"></i>Find Your Lawyer</h5>
                <p class="card-text">Browse through our extensive list of highly qualified and experienced lawyers specializing in various fields.</p>
                <a href="<?php echo BASE_URL; ?>client/view_lawyers.php" class="btn btn-sm btn-outline-primary">Learn More</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title text-primary"><i class="fas fa-calendar-alt me-2"></i>Book Appointments Easily</h5>
                <p class="card-text">Schedule confidential consultations at your convenience with your chosen legal expert.</p>
                <a href="<?php echo BASE_URL; ?>client/book_appointment.php" class="btn btn-sm btn-outline-primary">Book Now</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title text-primary"><i class="fas fa-shield-alt me-2"></i>Secure & Private</h5>
                <p class="card-text">Your information and consultations are kept secure and private with our robust systems.</p>
                <a href="#" class="btn btn-sm btn-outline-primary">Read Policy</a>
            </div>
        </div>
    </div>
</div>


<?php
require_once __DIR__ . '/includes/footer.php';
?>