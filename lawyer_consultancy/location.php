
<?php
// location.php
require_once __DIR__ . '/includes/db_config.php';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-10 mx-auto">
            <h1 class="text-primary mb-4"><i class="fas fa-map-marker-alt me-2"></i>Our Location</h1>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h3 class="text-secondary mb-3">Main Office</h3>
                            <div class="mb-3">
                                <h5>Address:</h5>
                                <p>123 Legal Street<br>Law District, LC 12345<br>United States</p>
                            </div>
                            <div class="mb-3">
                                <h5>Phone:</h5>
                                <p>+1 (555) 123-4567</p>
                            </div>
                            <div class="mb-3">
                                <h5>Email:</h5>
                                <p>info@lawyerhire.com</p>
                            </div>
                            <div class="mb-3">
                                <h5>Office Hours:</h5>
                                <p>Monday - Friday: 9:00 AM - 6:00 PM<br>Saturday: 10:00 AM - 4:00 PM<br>Sunday: Closed</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h3 class="text-secondary mb-3">How to Reach Us</h3>
                            <div class="mb-3">
                                <h5><i class="fas fa-car text-primary me-2"></i>By Car</h5>
                                <p>We are located in the heart of the Legal District with ample parking available. Take Exit 15 from Highway 101.</p>
                            </div>
                            <div class="mb-3">
                                <h5><i class="fas fa-subway text-primary me-2"></i>Public Transportation</h5>
                                <p>Metro Line 3 - Legal District Station (2 blocks away)<br>Bus Routes: 45, 67, 89</p>
                            </div>
                            <div class="mb-3">
                                <h5><i class="fas fa-parking text-primary me-2"></i>Parking</h5>
                                <p>Free parking available in our building garage. Visitor parking spaces are on Level 1.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h3 class="text-secondary mb-3">Interactive Map</h3>
                            <div class="bg-light p-5 text-center rounded">
                                <i class="fas fa-map fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Interactive map would be embedded here</p>
                                <p class="small">123 Legal Street, Law District, LC 12345</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <a href="<?php echo BASE_URL; ?>contact.php" class="btn btn-primary btn-lg me-3">
                    <i class="fas fa-envelope me-2"></i>Contact Us
                </a>
                <a href="<?php echo BASE_URL; ?>client/view_lawyers.php" class="btn btn-outline-primary btn-lg">
                    <i class="fas fa-gavel me-2"></i>Find a Lawyer
                </a>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
