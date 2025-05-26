<?php
// client/view_lawyers.php
require_once __DIR__ . '/../includes/db_config.php';
require_once __DIR__ . '/../includes/header.php'; // Handles session_start()

// Check if user is logged in and is a client
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
    header("Location: " . BASE_URL . "login.php");
    exit();
}

// Get search parameters
$search_name = isset($_GET['search_name']) ? trim($_GET['search_name']) : '';
$search_specialization = isset($_GET['search_specialization']) ? trim($_GET['search_specialization']) : '';

// Build the query with search filters
$sql = "SELECT lawyer_id, name, specialization, experience, hourly_rate, bio, profile_picture FROM lawyers WHERE is_available = TRUE";
$params = [];
$types = "";

if (!empty($search_name)) {
    $sql .= " AND name LIKE ?";
    $params[] = "%" . $search_name . "%";
    $types .= "s";
}

if (!empty($search_specialization)) {
    $sql .= " AND specialization LIKE ?";
    $params[] = "%" . $search_specialization . "%";
    $types .= "s";
}

$sql .= " ORDER BY name";

$lawyers = [];
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $lawyers[] = $row;
    }
}

// Get unique specializations for filter dropdown
$specializations = [];
$spec_stmt = $conn->prepare("SELECT DISTINCT specialization FROM lawyers WHERE is_available = TRUE ORDER BY specialization");
$spec_stmt->execute();
$spec_result = $spec_stmt->get_result();
while ($spec_row = $spec_result->fetch_assoc()) {
    $specializations[] = $spec_row['specialization'];
}
$spec_stmt->close();

$stmt->close();
$conn->close();
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="mb-4 text-primary"><i class="fas fa-search me-2"></i>Find Your Lawyer</h2>

        <!-- Search Form -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="search_name" class="form-label">Search by Name</label>
                            <input type="text" class="form-control" id="search_name" name="search_name" 
                                   placeholder="Enter lawyer name..." value="<?php echo htmlspecialchars($search_name); ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="search_specialization" class="form-label">Filter by Specialization</label>
                            <select class="form-select" id="search_specialization" name="search_specialization">
                                <option value="">All Specializations</option>
                                <?php foreach ($specializations as $spec): ?>
                                    <option value="<?php echo htmlspecialchars($spec); ?>" 
                                            <?php echo ($search_specialization == $spec) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($spec); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="fas fa-search me-2"></i>Search
                            </button>
                            <a href="view_lawyers.php" class="btn btn-outline-secondary">
                                <i class="fas fa-refresh me-2"></i>Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Results Summary -->
        <div class="mb-3">
            <span class="text-muted">
                <?php if (!empty($search_name) || !empty($search_specialization)): ?>
                    Showing <?php echo count($lawyers); ?> lawyer(s) 
                    <?php if (!empty($search_name)): ?>
                        matching "<?php echo htmlspecialchars($search_name); ?>"
                    <?php endif; ?>
                    <?php if (!empty($search_specialization)): ?>
                        in <?php echo htmlspecialchars($search_specialization); ?>
                    <?php endif; ?>
                <?php else: ?>
                    Showing all <?php echo count($lawyers); ?> available lawyers
                <?php endif; ?>
            </span>
        </div>
    </div>
</div>

<?php if (!empty($lawyers)): ?>
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php foreach ($lawyers as $lawyer): ?>
            <div class="col">
                <div class="lawyer-card d-flex flex-column h-100">
                    <?php if (!empty($lawyer['profile_picture'])): ?>
                        <div class="text-center mb-3">
                            <img src="<?php echo BASE_URL . 'uploads/profile_pictures/' . htmlspecialchars($lawyer['profile_picture']); ?>" 
                                 alt="<?php echo htmlspecialchars($lawyer['name']); ?> Profile" 
                                 class="profile-pic"
                                 onerror="this.src='<?php echo BASE_URL; ?>uploads/profile_pictures/default-lawyer.jpg'">
                        </div>
                    <?php else: ?>
                        <div class="text-center mb-3">
                            <img src="<?php echo BASE_URL; ?>uploads/profile_pictures/default-lawyer.jpg" 
                                 alt="Default Profile" class="profile-pic">
                        </div>
                    <?php endif; ?>

                    <h3 class="card-title text-center mb-2"><?php echo htmlspecialchars($lawyer['name']); ?></h3>
                    <p class="card-text text-center text-muted mb-3">
                        <i class="fas fa-briefcase me-1"></i>
                        <span class="badge bg-primary"><?php echo htmlspecialchars($lawyer['specialization']); ?></span>
                    </p>
                    <hr>
                    <p class="card-text mb-1">
                        <i class="fas fa-graduation-cap me-2 text-success"></i>
                        <strong>Experience:</strong> <?php echo htmlspecialchars($lawyer['experience']); ?> years
                    </p>
                    <p class="card-text mb-1">
                        <i class="fas fa-money-bill-wave me-2 text-warning"></i>
                        <strong>Hourly Rate:</strong> PKR <?php echo number_format($lawyer['hourly_rate'], 0); ?>
                    </p>
                    <p class="card-text mb-3">
                        <i class="fas fa-info-circle me-2 text-info"></i>
                        <strong>Bio:</strong> 
                        <span class="bio-text"><?php echo nl2br(htmlspecialchars(substr($lawyer['bio'], 0, 120))); ?><?php echo strlen($lawyer['bio']) > 120 ? '...' : ''; ?></span>
                    </p>
                    <div class="mt-auto text-center">
                        <a href="<?php echo BASE_URL; ?>client/book_appointment.php?lawyer_id=<?php echo $lawyer['lawyer_id']; ?>" 
                           class="btn btn-primary btn-lg w-75">
                            <i class="fas fa-calendar-plus me-2"></i>Book Appointment
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php else: ?>
    <div class="alert alert-info" role="alert">
        <h4 class="alert-heading"><i class="fas fa-info-circle me-2"></i>No Lawyers Found</h4>
        <?php if (!empty($search_name) || !empty($search_specialization)): ?>
            <p>No lawyers match your search criteria. Try adjusting your search terms or <a href="view_lawyers.php">view all lawyers</a>.</p>
        <?php else: ?>
            <p>It seems there are no lawyers currently listed. Please check back later!</p>
        <?php endif; ?>
    </div>
<?php endif; ?>

<!-- Add some custom styling -->
<style>
.lawyer-card {
    background: #fff;
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e9ecef;
}

.lawyer-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.profile-pic {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 4px solid #007bff;
    box-shadow: 0 2px 10px rgba(0, 123, 255, 0.3);
}

.bio-text {
    font-size: 0.9em;
    line-height: 1.4;
}

.card-title {
    color: #2c3e50;
    font-weight: 600;
    font-size: 1.1em;
}

.badge {
    font-size: 0.85em;
    padding: 0.5em 0.8em;
}

.btn-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #0056b3, #004085);
    transform: translateY(-2px);
}
</style>

<?php
require_once __DIR__ . '/../includes/footer.php';
?>