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
                                <p>123 Legal Street<br>Law District<br>Lahore</p>
                            </div>
                            <div class="mb-3">
                                <h5>Phone:</h5>
                                <p>+92 123-4567</p>
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
                                <p>We are located in the heart of the Legal District with ample parking available. Sida NAir o Nair.</p>
                            </div>
                            <div class="mb-3">
                                <h5><i class="fas fa-subway text-primary me-2"></i>Public Transportation</h5>
                                <p>Metro Line 3 - Legal District Station (2 blocks away from Kacheri)<br>Bus Routes: 45, 67, 89</p>
                            </div>
                            <div class="mb-3">
                                <h5><i class="fas fa-parking text-primary me-2"></i>Parking</h5>
                                <p>Free parking available in our building garage.</p>
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
                            <div class="map-container">
                                <iframe 
                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3401.8746329064944!2d74.35874631512238!3d31.520370781393986!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x391904d9ad4e7a47%3A0x9c8b1c8e8e8e8e8e!2sLahore%2C%20Punjab%2C%20Pakistan!5e0!3m2!1sen!2s!4v1234567890123!5m2!1sen!2s"
                                    width="100%" 
                                    height="400" 
                                    style="border:0; border-radius: 10px;" 
                                    allowfullscreen="" 
                                    loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade">
                                </iframe>
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