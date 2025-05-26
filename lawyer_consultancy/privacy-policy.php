
<?php
// privacy-policy.php
require_once __DIR__ . '/includes/db_config.php';
require_once __DIR__ . '/includes/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h1 class="display-4 fw-bold text-primary mb-4 text-center">
                <i class="fas fa-shield-alt me-3"></i>Privacy Policy & Legal Framework
            </h1>
            
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <h2 class="text-primary mb-4">Data Protection & Privacy</h2>
                    <p class="lead">
                        LawyerHire is committed to protecting your privacy and ensuring the confidentiality of your legal consultations in accordance with Pakistani law and international best practices.
                    </p>
                    
                    <h3 class="text-secondary mt-5 mb-3">Legal Framework Compliance</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <h4 class="h5 text-primary">Pakistani Legal Acts</h4>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-check-circle text-success me-2"></i>Prevention of Electronic Crimes Act 2016 (PECA)</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Pakistan Telecommunication (Re-organization) Act 1996</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Legal Practitioners and Bar Councils Act 1973</li>
                                <li><i class="fas fa-check-circle text-success me-2"></i>Electronic Transactions Ordinance 2002</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h4 class="h5 text-primary">Professional Standards</h4>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-shield-alt text-info me-2"></i>Pakistan Bar Council Rules</li>
                                <li><i class="fas fa-shield-alt text-info me-2"></i>Attorney-Client Privilege Protection</li>
                                <li><i class="fas fa-shield-alt text-info me-2"></i>ISO 27001 Security Standards</li>
                                <li><i class="fas fa-shield-alt text-info me-2"></i>Data Encryption Protocols</li>
                            </ul>
                        </div>
                    </div>
                    
                    <h3 class="text-secondary mt-5 mb-3">Information We Collect</h3>
                    <div class="alert alert-light border-start border-primary border-4">
                        <h5 class="text-primary">Personal Information</h5>
                        <p>We collect only necessary information including your name, contact details, and case-related information required for legal consultation services.</p>
                    </div>
                    
                    <h3 class="text-secondary mt-4 mb-3">How We Protect Your Data</h3>
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-lock fa-2x text-primary mb-2"></i>
                                <h5>End-to-End Encryption</h5>
                                <p class="small">All communications are encrypted using AES-256 encryption standards.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-server fa-2x text-success mb-2"></i>
                                <h5>Secure Servers</h5>
                                <p class="small">Data stored on servers located within Pakistan, complying with local data residency laws.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="text-center p-3 border rounded">
                                <i class="fas fa-user-shield fa-2x text-warning mb-2"></i>
                                <h5>Access Control</h5>
                                <p class="small">Strict access controls ensure only authorized personnel can access your information.</p>
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="text-secondary mt-5 mb-3">Your Rights Under Pakistani Law</h3>
                    <div class="card bg-light">
                        <div class="card-body">
                            <h5 class="text-primary">Constitutional Rights (Article 14 - Privacy)</h5>
                            <p>Under the Constitution of Pakistan 1973, you have the fundamental right to privacy and protection of personal information.</p>
                            
                            <h6 class="text-secondary mt-3">Your Legal Rights Include:</h6>
                            <ul>
                                <li>Right to access your personal data</li>
                                <li>Right to correction of inaccurate information</li>
                                <li>Right to deletion of personal data (Right to be Forgotten)</li>
                                <li>Right to data portability</li>
                                <li>Right to object to processing</li>
                                <li>Right to file complaints with relevant authorities</li>
                            </ul>
                        </div>
                    </div>
                    
                    <h3 class="text-secondary mt-5 mb-3">Regulatory Compliance</h3>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-primary">
                                <tr>
                                    <th>Regulatory Body</th>
                                    <th>Compliance Area</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Pakistan Telecommunication Authority (PTA)</td>
                                    <td>Electronic Communications</td>
                                    <td><span class="badge bg-success">Compliant</span></td>
                                </tr>
                                <tr>
                                    <td>Federal Investigation Agency (FIA)</td>
                                    <td>Cybercrime Prevention</td>
                                    <td><span class="badge bg-success">Compliant</span></td>
                                </tr>
                                <tr>
                                    <td>Pakistan Bar Council</td>
                                    <td>Legal Practice Standards</td>
                                    <td><span class="badge bg-success">Compliant</span></td>
                                </tr>
                                <tr>
                                    <td>State Bank of Pakistan</td>
                                    <td>Financial Transactions</td>
                                    <td><span class="badge bg-success">Compliant</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <h3 class="text-secondary mt-5 mb-3">Contact Information</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-primary">Data Protection Officer</h5>
                            <p>
                                <i class="fas fa-envelope me-2"></i>privacy@lawyerhire.com<br>
                                <i class="fas fa-phone me-2"></i>+92-21-1234-5678<br>
                                <i class="fas fa-map-marker-alt me-2"></i>Karachi, Pakistan
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-primary">Legal Compliance</h5>
                            <p>
                                <i class="fas fa-envelope me-2"></i>legal@lawyerhire.com<br>
                                <i class="fas fa-fax me-2"></i>+92-21-8765-4321<br>
                                <i class="fas fa-clock me-2"></i>Mon-Fri: 9:00 AM - 6:00 PM
                            </p>
                        </div>
                    </div>
                    
                    <div class="alert alert-info mt-4">
                        <h5 class="text-primary">
                            <i class="fas fa-info-circle me-2"></i>Policy Updates
                        </h5>
                        <p class="mb-0">
                            This privacy policy is regularly updated to reflect changes in Pakistani law and international standards. 
                            Last updated: <?php echo date('F Y'); ?>. Users will be notified of any material changes via email.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require_once __DIR__ . '/includes/footer.php';
?>
