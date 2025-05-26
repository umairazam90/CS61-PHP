<?php
require_once __DIR__ . '/../includes/db_config.php';
require_once __DIR__ . '/../includes/header.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$lawyer_id = $_GET['lawyer_id'] ?? null;
$lawyer_name = '';
$message = '';
$message_type = '';

if ($lawyer_id) {
    $stmt = $conn->prepare("SELECT name, specialization, profile_picture FROM lawyers WHERE lawyer_id = ?");
    $stmt->bind_param("i", $lawyer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 1) {
        $lawyer_data = $result->fetch_assoc();
        $lawyer_name = $lawyer_data['name'];
        $lawyer_specialization = $lawyer_data['specialization'];
        $lawyer_profile_picture = $lawyer_data['profile_picture'];
    } else {
        $message = "Lawyer not found.";
        $message_type = "error";
        $lawyer_id = null;
    }
    $stmt->close();
} else {

    header("Location: " . BASE_URL . "client/view_lawyers.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && $lawyer_id) {
    $selected_lawyer_id = $_POST['lawyer_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $description = trim($_POST['description']);
    if (empty($appointment_date) || empty($appointment_time)) {
        $message = "Appointment date and time are required.";
        $message_type = "error";
    } elseif (strtotime($appointment_date . ' ' . $appointment_time) < time()) {
        $message = "Appointment date and time cannot be in the past.";
        $message_type = "error";
    } else {
        $stmt_check = $conn->prepare("SELECT appointment_id FROM appointments WHERE lawyer_id = ? AND appointment_date = ? AND appointment_time = ? AND (status = 'pending' OR status = 'confirmed')");
        $stmt_check->bind_param("iss", $selected_lawyer_id, $appointment_date, $appointment_time);
        $stmt_check->execute();
        $stmt_check->store_result();

        if ($stmt_check->num_rows > 0) {
            $message = "This lawyer is already booked at the selected date and time. Please choose another time.";
            $message_type = "error";
        } else {
            $stmt_insert = $conn->prepare("INSERT INTO appointments (user_id, lawyer_id, appointment_date, appointment_time, description, status) VALUES (?, ?, ?, ?, ?, 'pending')");
            $stmt_insert->bind_param("iisss", $user_id, $selected_lawyer_id, $appointment_date, $appointment_time, $description);

            if ($stmt_insert->execute()) {
                $message = "Appointment booked successfully! It is pending confirmation.";
                $message_type = "success";
                $appointment_date = '';
                $appointment_time = '';
                $description = '';
            } else {
                $message = "Error booking appointment: " . $stmt_insert->error;
                $message_type = "error";
            }
            $stmt_insert->close();
        }
        $stmt_check->close();
    }
}
$conn->close();
?>

<h2 class="mb-4 text-primary"><i class="fas fa-calendar-plus me-2"></i>Book Appointment</h2>

<?php if ($message): ?>
    <div class="alert <?php echo ($message_type == 'success') ? 'alert-success' : 'alert-danger'; ?>" role="alert">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<?php if ($lawyer_id && $lawyer_name): ?>
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h3 class="card-title text-center text-secondary mb-3">Booking for: <?php echo htmlspecialchars($lawyer_name); ?></h3>
            <p class="card-text text-center text-muted"><?php echo htmlspecialchars($lawyer_specialization); ?></p>
            <?php if (!empty($lawyer_profile_picture)): ?>
                <div class="text-center mb-3">
                    <img src="<?php echo BASE_URL . 'uploads/profile_pictures/' . htmlspecialchars($lawyer_profile_picture); ?>" alt="<?php echo htmlspecialchars($lawyer_name); ?> Profile" class="img-fluid rounded-circle" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid #007bff;">
                </div>
            <?php endif; ?>

            <form action="book_appointment.php?lawyer_id=<?php echo htmlspecialchars($lawyer_id); ?>" method="POST">
                <input type="hidden" name="lawyer_id" value="<?php echo htmlspecialchars($lawyer_id); ?>">

                <div class="mb-3">
                    <label for="appointment_date" class="form-label">Date:</label>
                    <input type="date" class="form-control" id="appointment_date" name="appointment_date" required min="<?php echo date('Y-m-d'); ?>">
                </div>
                <div class="mb-3">
                    <label for="appointment_time" class="form-label">Time:</label>
                    <input type="time" class="form-control" id="appointment_time" name="appointment_time" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="form-label">Reason for Appointment (optional):</label>
                    <textarea class="form-control" id="description" name="description" rows="5"></textarea>
                </div>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg">Confirm Booking</button>
                </div>
            </form>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-warning" role="alert">
        <h4 class="alert-heading"><i class="fas fa-exclamation-triangle me-2"></i>No Lawyer Selected</h4>
        <p>Please select a lawyer from the <a href="<?php echo BASE_URL; ?>client/view_lawyers.php" class="alert-link">View Lawyers</a> page to book an appointment.</p>
    </div>
<?php endif; ?>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>