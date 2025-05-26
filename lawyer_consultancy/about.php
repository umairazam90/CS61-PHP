
<?php
// about.php
require_once __DIR__ . '/includes/db_config.php';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container my-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="text-primary mb-4"><i class="fas fa-info-circle me-2"></i>About LawyerHire</h1>
            
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h3 class="text-secondary mb-3">Our Mission</h3>
                    <p class="lead">LawyerHire is dedicated to connecting clients with experienced legal professionals who can provide expert guidance and representation for all your legal needs.</p>
                    
                    <h3 class="text-secondary mb-3 mt-4">Why Choose Us?</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Verified Legal Professionals</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Easy Online Booking</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Transparent Pricing</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Multiple Specializations</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>Secure Platform</li>
                                <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i>24/7 Support</li>
                            </ul>
                        </div>
                    </div>
                    
                    <h3 class="text-secondary mb-3 mt-4">Our Team</h3>
                    <p>We work with a network of qualified lawyers specializing in various areas of law including corporate law, family law, criminal defense, personal injury, and more. All our legal professionals are thoroughly vetted and licensed to practice law.</p>
                    
                    <div class="text-center mt-4">
                        <a href="<?php echo BASE_URL; ?>client/view_lawyers.php" class="btn btn-primary btn-lg">
                            <i class="fas fa-gavel me-2"></i>Browse Our Lawyers
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
