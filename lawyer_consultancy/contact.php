
<?php
// contact.php
require_once __DIR__ . '/includes/db_config.php';
require_once __DIR__ . '/includes/header.php';

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message_content = trim($_POST['message']);
    
    if (empty($name) || empty($email) || empty($subject) || empty($message_content)) {
        $message = "All fields are required.";
        $message_type = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Invalid email format.";
        $message_type = "error";
    } else {
        // Here you would typically send an email or save to database
        $message = "Thank you for your message! We'll get back to you soon.";
        $message_type = "success";
    }
}
?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="text-primary mb-4"><i class="fas fa-envelope me-2"></i>Contact Us</h1>
            
            <?php if ($message): ?>
                <div class="alert <?php echo ($message_type == 'success') ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h3 class="text-secondary mb-3">Get in Touch</h3>
                            <form action="contact.php" method="POST">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name" required>
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                                <div class="mb-3">
                                    <label for="subject" class="form-label">Subject</label>
                                    <input type="text" class="form-control" id="subject" name="subject" required>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">Message</label>
                                    <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-2"></i>Send Message
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">
                            <h3 class="text-secondary mb-3">Contact Information</h3>
                            <div class="mb-4">
                                <h5><i class="fas fa-map-marker-alt text-primary me-2"></i>Address</h5>
                                <p>123 Legal Street<br>Law District, LC 12345<br>United States</p>
                            </div>
                            <div class="mb-4">
                                <h5><i class="fas fa-phone text-primary me-2"></i>Phone</h5>
                                <p>+1 (555) 123-4567</p>
                            </div>
                            <div class="mb-4">
                                <h5><i class="fas fa-envelope text-primary me-2"></i>Email</h5>
                                <p>info@lawyerhire.com</p>
                            </div>
                            <div class="mb-4">
                                <h5><i class="fas fa-clock text-primary me-2"></i>Business Hours</h5>
                                <p>Monday - Friday: 9:00 AM - 6:00 PM<br>Saturday: 10:00 AM - 4:00 PM<br>Sunday: Closed</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
