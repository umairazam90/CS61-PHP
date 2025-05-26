<?php
// admin/manage_lawyers.php
require_once __DIR__ . '/../includes/db_config.php';
require_once __DIR__ . '/../includes/header.php'; // Handles session_start()

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 2) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

$message = '';
$message_type = '';
$upload_dir_relative = 'uploads/profile_pictures/'; // Relative to BASE_URL
$upload_dir_absolute = __DIR__ . '/../uploads/profile_pictures/'; // Absolute path for PHP file operations

// Ensure upload directory exists
if (!is_dir($upload_dir_absolute)) {
    mkdir($upload_dir_absolute, 0777, true);
}


// Handle Add/Edit Lawyer
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_POST['action'] ?? '';
    $lawyer_id = $_POST['lawyer_id'] ?? null;
    $name = trim($_POST['name']);
    $specialization = trim($_POST['specialization']);
    $experience = (int)$_POST['experience'];
    $hourly_rate = (float)$_POST['hourly_rate'];
    $bio = trim($_POST['bio']);
    $contact_email = trim($_POST['contact_email']);
    $contact_phone = trim($_POST['contact_phone']);

    $profile_picture = ''; // Default to empty
    $new_file_uploaded = false;

    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $file_name = $_FILES['profile_picture']['name'];
        $file_tmp = $_FILES['profile_picture']['tmp_name'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        $allowed_ext = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($file_ext, $allowed_ext)) {
            $new_file_name = uniqid('lawyer_', true) . '.' . $file_ext;
            $destination = $upload_dir_absolute . $new_file_name;
            if (move_uploaded_file($file_tmp, $destination)) {
                $profile_picture = $new_file_name;
                $new_file_uploaded = true;
            } else {
                $message = "Failed to upload profile picture.";
                $message_type = "danger";
            }
        } else {
            $message = "Invalid file type. Only JPG, JPEG, PNG, GIF are allowed.";
            $message_type = "danger";
        }
    }

    if (!$message) { // Only proceed if no upload errors
        if ($action == 'add') {
            $stmt = $conn->prepare("INSERT INTO lawyers (name, specialization, experience, hourly_rate, bio, contact_email, contact_phone, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssidsiss", $name, $specialization, $experience, $hourly_rate, $bio, $contact_email, $contact_phone, $profile_picture);
            if ($stmt->execute()) {
                $message = "Lawyer added successfully.";
                $message_type = "success";
            } else {
                $message = "Error adding lawyer: " . $stmt->error;
                $message_type = "danger";
            }
            $stmt->close();
        } elseif ($action == 'edit' && $lawyer_id) {
            $current_lawyer_data = null;
            $stmt_current = $conn->prepare("SELECT profile_picture FROM lawyers WHERE lawyer_id = ?");
            $stmt_current->bind_param("i", $lawyer_id);
            $stmt_current->execute();
            $result_current = $stmt_current->get_result();
            if ($result_current->num_rows > 0) {
                $current_lawyer_data = $result_current->fetch_assoc();
            }
            $stmt_current->close();

            $sql = "UPDATE lawyers SET name = ?, specialization = ?, experience = ?, hourly_rate = ?, bio = ?, contact_email = ?, contact_phone = ?";
            $params = "ssidsis";
            $values = [&$name, &$specialization, &$experience, &$hourly_rate, &$bio, &$contact_email, &$contact_phone];

            if ($new_file_uploaded) {
                $sql .= ", profile_picture = ?";
                $params .= "s";
                $values[] = &$profile_picture;
            }
            $sql .= " WHERE lawyer_id = ?";
            $values[] = &$lawyer_id;

            $stmt = $conn->prepare($sql);
            $stmt->bind_param($params, ...$values);

            if ($stmt->execute()) {
                $message = "Lawyer updated successfully.";
                $message_type = "success";
                // Delete old picture if a new one was uploaded and an old one existed
                if ($new_file_uploaded && !empty($current_lawyer_data['profile_picture']) && file_exists($upload_dir_absolute . $current_lawyer_data['profile_picture'])) {
                    unlink($upload_dir_absolute . $current_lawyer_data['profile_picture']);
                }
            } else {
                $message = "Error updating lawyer: " . $stmt->error;
                $message_type = "danger";
            }
            $stmt->close();
        }
    }
}

// Handle Delete Lawyer
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $lawyer_id_to_delete = $_GET['id'];

    // Get picture path before deleting lawyer record
    $profile_picture_to_delete = '';
    $stmt_pic = $conn->prepare("SELECT profile_picture FROM lawyers WHERE lawyer_id = ?");
    $stmt_pic->bind_param("i", $lawyer_id_to_delete);
    $stmt_pic->execute();
    $result_pic = $stmt_pic->get_result();
    if ($result_pic->num_rows > 0) {
        $profile_picture_to_delete = $result_pic->fetch_assoc()['profile_picture'];
    }
    $stmt_pic->close();

    // Delete associated appointments first (if not using ON DELETE CASCADE in DB)
    $stmt_delete_appointments = $conn->prepare("DELETE FROM appointments WHERE lawyer_id = ?");
    $stmt_delete_appointments->bind_param("i", $lawyer_id_to_delete);
    $stmt_delete_appointments->execute();
    $stmt_delete_appointments->close();


    $stmt = $conn->prepare("DELETE FROM lawyers WHERE lawyer_id = ?");
    $stmt->bind_param("i", $lawyer_id_to_delete);
    if ($stmt->execute()) {
        $message = "Lawyer deleted successfully.";
        $message_type = "success";
        // Delete the actual profile picture file
        if (!empty($profile_picture_to_delete) && file_exists($upload_dir_absolute . $profile_picture_to_delete)) {
            unlink($upload_dir_absolute . $profile_picture_to_delete);
        }
    } else {
        $message = "Error deleting lawyer: " . $stmt->error;
        $message_type = "danger";
    }
    $stmt->close();
}

// Fetch all lawyers for display
$lawyers = [];
$stmt = $conn->prepare("SELECT lawyer_id, name, specialization, experience, hourly_rate, bio, contact_email, contact_phone, profile_picture FROM lawyers ORDER BY name");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $lawyers[] = $row;
}
$stmt->close();
$conn->close();

// For Edit mode, load lawyer data into form
$edit_lawyer = null;
if (isset($_GET['action']) && $_GET['action'] == 'edit' && isset($_GET['id'])) {
    $edit_id = $_GET['id'];
    $conn_edit = new mysqli($servername, $username, $password, $dbname); // Re-open connection for this context
    $stmt_edit = $conn_edit->prepare("SELECT lawyer_id, name, specialization, experience, hourly_rate, bio, contact_email, contact_phone, profile_picture FROM lawyers WHERE lawyer_id = ?");
    $stmt_edit->bind_param("i", $edit_id);
    $stmt_edit->execute();
    $result_edit = $stmt_edit->get_result();
    if ($result_edit->num_rows == 1) {
        $edit_lawyer = $result_edit->fetch_assoc();
    }
    $stmt_edit->close();
    $conn_edit->close();
}
?>

<h2 class="mb-4 text-success"><i class="fas fa-balance-scale me-2"></i>Manage Lawyers</h2>

<?php if ($message): ?>
    <div class="alert <?php echo ($message_type == 'success') ? 'alert-success' : 'alert-danger'; ?>" role="alert">
        <?php echo htmlspecialchars($message); ?>
    </div>
<?php endif; ?>

<div class="card shadow-sm p-4 mb-4">
    <h3 class="card-title text-secondary mb-3"><?php echo $edit_lawyer ? '<i class="fas fa-edit me-2"></i>Edit Lawyer' : '<i class="fas fa-plus-circle me-2"></i>Add New Lawyer'; ?></h3>
    <form action="manage_lawyers.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="<?php echo $edit_lawyer ? 'edit' : 'add'; ?>">
        <?php if ($edit_lawyer): ?>
            <input type="hidden" name="lawyer_id" value="<?php echo htmlspecialchars($edit_lawyer['lawyer_id']); ?>">
        <?php endif; ?>

        <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($edit_lawyer['name'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label for="specialization" class="form-label">Specialization:</label>
            <input type="text" class="form-control" id="specialization" name="specialization" value="<?php echo htmlspecialchars($edit_lawyer['specialization'] ?? ''); ?>" required>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="experience" class="form-label">Experience (Years):</label>
                <input type="number" class="form-control" id="experience" name="experience" value="<?php echo htmlspecialchars($edit_lawyer['experience'] ?? ''); ?>" required min="0">
            </div>
            <div class="col-md-6">
                <label for="hourly_rate" class="form-label">Hourly Rate ($):</label>
                <input type="number" class="form-control" id="hourly_rate" name="hourly_rate" step="0.01" value="<?php echo htmlspecialchars($edit_lawyer['hourly_rate'] ?? ''); ?>" required min="0">
            </div>
        </div>
        <div class="mb-3">
            <label for="bio" class="form-label">Biography:</label>
            <textarea class="form-control" id="bio" name="bio" rows="5"><?php echo htmlspecialchars($edit_lawyer['bio'] ?? ''); ?></textarea>
        </div>
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="contact_email" class="form-label">Contact Email:</label>
                <input type="email" class="form-control" id="contact_email" name="contact_email" value="<?php echo htmlspecialchars($edit_lawyer['contact_email'] ?? ''); ?>">
            </div>
            <div class="col-md-6">
                <label for="contact_phone" class="form-label">Contact Phone:</label>
                <input type="text" class="form-control" id="contact_phone" name="contact_phone" value="<?php echo htmlspecialchars($edit_lawyer['contact_phone'] ?? ''); ?>">
            </div>
        </div>
        <div class="mb-4">
            <label for="profile_picture" class="form-label">Profile Picture (optional):</label>
            <input type="file" class="form-control" id="profile_picture" name="profile_picture" accept="image/*">
            <?php if ($edit_lawyer && !empty($edit_lawyer['profile_picture'])): ?>
                <div class="mt-2">
                    <p class="mb-1">Current Picture:</p>
                    <img src="<?php echo BASE_URL . $upload_dir_relative . htmlspecialchars($edit_lawyer['profile_picture']); ?>" alt="Current Pic" class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                </div>
            <?php endif; ?>
        </div>
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-success btn-lg">
                <?php echo $edit_lawyer ? '<i class="fas fa-sync-alt me-2"></i>Update Lawyer' : '<i class="fas fa-plus-circle me-2"></i>Add Lawyer'; ?>
            </button>
            <?php if ($edit_lawyer): ?>
                <a href="manage_lawyers.php" class="btn btn-warning btn-lg"><i class="fas fa-ban me-2"></i>Cancel Edit</a>
            <?php endif; ?>
        </div>
    </form>
</div>

<h3 class="mb-3 text-secondary"><i class="fas fa-users-cog me-2"></i>Current Lawyers</h3>
<?php if (!empty($lawyers)): ?>
    <div class="table-responsive">
        <table class="table table-hover table-bordered shadow-sm">
            <thead class="table-success">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Specialization</th>
                    <th>Experience</th>
                    <th>Rate</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Picture</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lawyers as $lawyer): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($lawyer['lawyer_id']); ?></td>
                        <td><?php echo htmlspecialchars($lawyer['name']); ?></td>
                        <td><?php echo htmlspecialchars($lawyer['specialization']); ?></td>
                        <td><?php echo htmlspecialchars($lawyer['experience']); ?></td>
                        <td>$<?php echo number_format($lawyer['hourly_rate'], 2); ?></td>
                        <td><?php echo htmlspecialchars($lawyer['contact_email']); ?></td>
                        <td><?php echo htmlspecialchars($lawyer['contact_phone']); ?></td>
                        <td>
                            <?php if (!empty($lawyer['profile_picture'])): ?>
                                <img src="<?php echo BASE_URL . $upload_dir_relative . htmlspecialchars($lawyer['profile_picture']); ?>" alt="Pic" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="manage_lawyers.php?action=edit&id=<?php echo $lawyer['lawyer_id']; ?>" class="btn btn-warning btn-sm me-1">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="manage_lawyers.php?action=delete&id=<?php echo $lawyer['lawyer_id']; ?>" class="btn btn-danger btn-sm btn-delete-lawyer">
                                <i class="fas fa-trash-alt"></i> Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <div class="alert alert-info" role="alert">
        <h4 class="alert-heading"><i class="fas fa-info-circle me-2"></i>No Lawyers Added Yet!</h4>
        <p>Start by using the "Add New Lawyer" form above.</p>
    </div>
<?php endif; ?>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>