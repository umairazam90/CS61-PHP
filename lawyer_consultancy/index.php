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
                <p class="col-md-8 fs-4 mx-auto">Find the right legal expert for your needs. Connect with experienced professionals who understand your requirements.</p>
                <div class="mt-5">
                    <div class="row g-4 justify-content-center">
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center p-4 bg-white rounded-3 shadow-sm border">
                                <div class="mb-3">
                                    <i class="fas fa-user-tie text-primary" style="font-size: 2.5rem;"></i>
                                </div>
                                <h4 class="fw-bold text-primary">500+</h4>
                                <p class="text-muted mb-0">Expert Lawyers</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center p-4 bg-white rounded-3 shadow-sm border">
                                <div class="mb-3">
                                    <i class="fas fa-handshake text-success" style="font-size: 2.5rem;"></i>
                                </div>
                                <h4 class="fw-bold text-success">1000+</h4>
                                <p class="text-muted mb-0">Cases Solved</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center p-4 bg-white rounded-3 shadow-sm border">
                                <div class="mb-3">
                                    <i class="fas fa-star text-warning" style="font-size: 2.5rem;"></i>
                                </div>
                                <h4 class="fw-bold text-warning">4.9/5</h4>
                                <p class="text-muted mb-0">Client Rating</p>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6">
                            <div class="text-center p-4 bg-white rounded-3 shadow-sm border">
                                <div class="mb-3">
                                    <i class="fas fa-clock text-info" style="font-size: 2.5rem;"></i>
                                </div>
                                <h4 class="fw-bold text-info">24/7</h4>
                                <p class="text-muted mb-0">Support</p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php elseif ($_SESSION['role_id'] == 2): // Admin ?>
                <h1 class="display-5 fw-bold text-success">Welcome, Admin <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
                <p class="col-md-8 fs-4 mx-auto">You are logged in as an administrator. Here's your system overview:</p>
                <div class="mt-5">
                    <?php
                    // Get admin dashboard stats
                    $total_lawyers = 0;
                    $total_clients = 0;
                    $pending_bookings = 0;

                    $stmt_lawyers = $conn->prepare("SELECT COUNT(*) AS count FROM lawyers");
                    $stmt_lawyers->execute();
                    $result_lawyers = $stmt_lawyers->get_result();
                    $total_lawyers = $result_lawyers->fetch_assoc()['count'];
                    $stmt_lawyers->close();

                    $stmt_clients = $conn->prepare("SELECT COUNT(*) AS count FROM users WHERE role_id = 1");
                    $stmt_clients->execute();
                    $result_clients = $stmt_clients->get_result();
                    $total_clients = $result_clients->fetch_assoc()['count'];
                    $stmt_clients->close();

                    $stmt_pending = $conn->prepare("SELECT COUNT(*) AS count FROM appointments WHERE status = 'pending'");
                    $stmt_pending->execute();
                    $result_pending = $stmt_pending->get_result();
                    $pending_bookings = $result_pending->fetch_assoc()['count'];
                    $stmt_pending->close();
                    ?>
                    <div class="row g-4 justify-content-center mb-5">
                        <div class="col-md-4 col-sm-6">
                            <div class="text-center p-4 bg-white rounded-3 shadow-sm border">
                                <div class="mb-3">
                                    <i class="fas fa-user-tie text-success" style="font-size: 2.5rem;"></i>
                                </div>
                                <h4 class="fw-bold text-success"><?php echo $total_lawyers; ?></h4>
                                <p class="text-muted mb-0">Total Lawyers</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="text-center p-4 bg-white rounded-3 shadow-sm border">
                                <div class="mb-3">
                                    <i class="fas fa-users text-primary" style="font-size: 2.5rem;"></i>
                                </div>
                                <h4 class="fw-bold text-primary"><?php echo $total_clients; ?></h4>
                                <p class="text-muted mb-0">Total Clients</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="text-center p-4 bg-white rounded-3 shadow-sm border">
                                <div class="mb-3">
                                    <i class="fas fa-clock text-warning" style="font-size: 2.5rem;"></i>
                                </div>
                                <h4 class="fw-bold text-warning"><?php echo $pending_bookings; ?></h4>
                                <p class="text-muted mb-0">Pending Bookings</p>
                            </div>
                        </div>
                    </div>
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

<?php if (isset($_SESSION['user_id']) && $_SESSION['role_id'] == 2): // Admin cards ?>
<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title text-success"><i class="fas fa-user-tie me-2"></i>Manage Lawyers</h5>
                <p class="card-text">Add, edit, or remove lawyers from the system. Manage their profiles, specializations, and availability.</p>
                <a href="<?php echo BASE_URL; ?>admin/manage_lawyers.php" class="btn btn-sm btn-outline-success">Manage Lawyers</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title text-success"><i class="fas fa-calendar-check me-2"></i>Manage Bookings</h5>
                <p class="card-text">Review and manage all appointment bookings. Approve, reject, or modify client appointments.</p>
                <a href="<?php echo BASE_URL; ?>admin/manage_bookings.php" class="btn btn-sm btn-outline-success">Manage Bookings</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="card-title text-success"><i class="fas fa-users-cog me-2"></i>Manage Users</h5>
                <p class="card-text">View and manage all users in the system. Monitor client registrations and user activities.</p>
                <a href="<?php echo BASE_URL; ?>admin/manage_users.php" class="btn btn-sm btn-outline-success">Manage Users</a>
            </div>
        </div>
    </div>
</div>
<?php else: // Client/Guest cards ?>
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
                <a href="<?php echo BASE_URL; ?>privacy-policy.php" class="btn btn-sm btn-outline-primary">Read Policy</a>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>


<?php
require_once __DIR__ . '/includes/footer.php';
?>