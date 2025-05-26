<?php
// client/view_lawyers.php
require_once __DIR__ . '/../includes/db_config.php';
require_once __DIR__ . '/../includes/header.php'; // Handles session_start()

// Check if user is logged in and is a client
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

$lawyers = [];
$stmt = $conn->prepare("SELECT lawyer_id, name, specialization, experience, hourly_rate, bio, profile_picture FROM lawyers WHERE is_available = TRUE ORDER BY name");
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $lawyers[] = $row;
    }
}
$stmt->close();
$conn->close();
?>

<h2 class="mb-4 text-primary"><i class="fas fa-search me-2"></i>Our Lawyers</h2>

<?php if (!empty($lawyers)): ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($lawyers as $lawyer): ?>
            <div class="col">
                <div class="lawyer-card d-flex flex-column h-100">
                    <?php if (!empty($lawyer['profile_picture'])): ?>
                        <div class="text-center mb-3">
                            <img src="<?php echo BASE_URL . 'uploads/profile_pictures/' . htmlspecialchars($lawyer['profile_picture']); ?>" alt="<?php echo htmlspecialchars($lawyer['name']); ?> Profile" class="profile-pic">
                        </div>
                    <?php endif; ?>
                    <h3 class="card-title text-center mb-2"><?php echo htmlspecialchars($lawyer['name']); ?></h3>
                    <p class="card-text text-center text-muted mb-3"><i class="fas fa-briefcase me-1"></i><?php echo htmlspecialchars($lawyer['specialization']); ?></p>
                    <hr>
                    <p class="card-text mb-1"><i class="fas fa-graduation-cap me-2"></i><strong>Experience:</strong> <?php echo htmlspecialchars($lawyer['experience']); ?> years</p>
                    <p class="card-text mb-1"><i class="fas fa-dollar-sign me-2"></i><strong>Hourly Rate:</strong> $<?php echo number_format($lawyer['hourly_rate'], 2); ?></p>
                    <p class="card-text mb-3"><i class="fas fa-info-circle me-2"></i><strong>Bio:</strong> <?php echo nl2br(htmlspecialchars($lawyer['bio'])); ?></p>
                    <div class="mt-auto text-center">
                        <a href="<?php echo BASE_URL; ?>client/book_appointment.php?lawyer_id=<?php echo $lawyer['lawyer_id']; ?>" class="btn btn-primary btn-lg w-75">
                            <i class="fas fa-calendar-plus me-2"></i>Book Appointment
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="alert alert-info" role="alert">
        <h4 class="alert-heading"><i class="fas fa-info-circle me-2"></i>No Lawyers Available</h4>
        <p>It seems there are no lawyers currently listed. Please check back later!</p>
    </div>
<?php endif; ?>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>