<?php
// client/view_appointments.php
require_once __DIR__ . '/../includes/db_config.php';
require_once __DIR__ . '/../includes/header.php'; // Handles session_start()

// Check if user is logged in and is a client
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = '';
$message_type = '';

// Handle cancellation request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cancel_appointment_id'])) {
    $appointment_id_to_cancel = $_POST['cancel_appointment_id'];

    $stmt_check = $conn->prepare("SELECT status FROM appointments WHERE appointment_id = ? AND user_id = ?");
    $stmt_check->bind_param("ii", $appointment_id_to_cancel, $user_id);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();

    if ($result_check->num_rows == 1) {
        $appointment_data = $result_check->fetch_assoc();
        if ($appointment_data['status'] == 'pending') {
            $stmt_cancel = $conn->prepare("UPDATE appointments SET status = 'cancelled' WHERE appointment_id = ?");
            $stmt_cancel->bind_param("i", $appointment_id_to_cancel);
            if ($stmt_cancel->execute()) {
                $message = "Appointment cancelled successfully.";
                $message_type = "success";
            } else {
                $message = "Error cancelling appointment: " . $stmt_cancel->error;
                $message_type = "error";
            }
            $stmt_cancel->close();
        } else {
            $message = "Only pending appointments can be cancelled.";
            $message_type = "error";
        }
    } else {
        $message = "Appointment not found or you don't have permission to cancel it.";
        $message_type = "error";
    }
    $stmt_check->close();
}

$appointments = [];
$stmt = $conn->prepare("SELECT a.appointment_id, l.name AS lawyer_name, l.specialization, a.appointment_date, a.appointment_time, a.description, a.status
                        FROM appointments a
                        JOIN lawyers l ON a.lawyer_id = l.lawyer_id
                        WHERE a.user_id = ?
                        ORDER BY a.appointment_date DESC, a.appointment_time DESC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $appointments[] = $row;
    }
}
$stmt->close();
$conn->close();
?>

<h2 class="mb-4 text-primary"><i class="fas fa-calendar-check me-2"></i>My Appointments</h2>

<?php if ($message): ?>
    <div class="alert <?php echo ($message_type == 'success') ? 'alert-success' : 'alert-danger'; ?>" role="alert">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<?php if (!empty($appointments)): ?>
    <div class="table-responsive">
        <table class="table table-hover table-bordered shadow-sm">
            <thead class="table-primary">
                <tr>
                    <th>Lawyer</th>
                    <th>Specialization</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appointments as $appointment): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($appointment['lawyer_name']); ?></td>
                        <td><?php echo htmlspecialchars($appointment['specialization']); ?></td>
                        <td><?php echo date('M d, Y', strtotime($appointment['appointment_date'])); ?></td>
                        <td><?php echo date('h:i A', strtotime($appointment['appointment_time'])); ?></td>
                        <td><?php echo htmlspecialchars($appointment['description']); ?></td>
                        <td>
                            <span class="status-<?php echo strtolower($appointment['status']); ?>">
                                <?php echo htmlspecialchars(ucfirst($appointment['status'])); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($appointment['status'] == 'pending'): ?>
                                <form action="view_appointments.php" method="POST" class="d-inline-block">
                                    <input type="hidden" name="cancel_appointment_id" value="<?php echo $appointment['appointment_id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm btn-cancel-appointment">
                                        <i class="fas fa-times-circle me-1"></i>Cancel
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="text-muted">N/A</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info" role="alert">
        <h4 class="alert-heading"><i class="fas fa-info-circle me-2"></i>No Appointments Yet!</h4>
        <p>You have no appointments booked yet. Start by finding a lawyer.</p>
        <hr>
        <p class="mb-0"><a href="<?php echo BASE_URL; ?>client/view_lawyers.php" class="btn btn-primary"><i class="fas fa-gavel me-2"></i>Book an Appointment</a></p>
    </div>
<?php endif; ?>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>