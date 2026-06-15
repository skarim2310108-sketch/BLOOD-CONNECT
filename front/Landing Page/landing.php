<?php
// landing.php - Blood Connect landing page
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Blood Connect</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="landing.css">
</head>
<body>

  <!-- Navbar -->
  <header class="navbar">
    <div class="container navbar-inner">
      <div class="logo">
        <i class="fa-solid fa-droplet"></i>
        <span>Blood Connect</span>
      </div>
      <nav class="nav-links">
        <a href="#home">Home</a>
        <a href="#features">Features</a>
        <a href="#how-it-works">How it Works</a>
      </nav>
      <div class="nav-actions">
        <a href="#emergency" class="btn btn-emergency">
          <i class="fa-solid fa-truck-medical"></i>
          Emergency
        </a>
        <a href="#login" class="btn btn-primary">Login</a>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero" id="home">
    <div class="container hero-inner">
      <div class="hero-content">
        <h1>Save Lives with Every Drop</h1>
        <p>
          Connect blood donors with those in need. A trusted platform making
          emergency blood requests simple, fast, and reliable.
        </p>
        <div class="hero-buttons">
          <a href="#donate" class="btn btn-primary">
            Become a Donor <i class="fa-solid fa-arrow-right"></i>
          </a>
          <a href="#request" class="btn btn-outline">Request Blood</a>
        </div>
        <div class="hero-stats">
          <div class="stat">
            <h3>10K+</h3>
            <p>Active Donors</p>
          </div>
          <div class="stat">
            <h3>5K+</h3>
            <p>Lives Saved</p>
          </div>
          <div class="stat">
            <h3>24/7</h3>
            <p>Support</p>
          </div>
        </div>
      </div>
      <div class="hero-image">
        <img src="https://images.unsplash.com/photo-1615461066841-6116e61058f4?q=80&w=1200&auto=format&fit=crop" alt="Blood donation">
      </div>
    </div>
  </section>

  <!-- Why Choose Section -->
  <section class="why-choose" id="features">
    <div class="container">
      <h2>Why Choose Blood Connect?</h2>
      <p class="section-subtitle">A platform built for speed, safety, and reliability</p>

      <div class="features-grid">
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fa-solid fa-bolt"></i>
          </div>
          <h3>Fast Response</h3>
          <p>Get connected with verified donors within minutes during emergencies.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fa-solid fa-shield-halved"></i>
          </div>
          <h3>Verified Donors</h3>
          <p>All donors are verified by us before they're available for safety and authenticity.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fa-solid fa-users"></i>
          </div>
          <h3>Large Network</h3>
          <p>Access to thousands of active blood donors across every blood type.</p>
        </div>
        <div class="feature-card">
          <div class="feature-icon">
            <i class="fa-solid fa-heart"></i>
          </div>
          <h3>Easy to Use</h3>
          <p>Simple, intuitive interface to request or donate blood in just a few steps.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- How It Works Section -->
  <section class="how-it-works" id="how-it-works">
    <div class="container">
      <h2>How It Works</h2>
      <p class="section-subtitle">Three simple steps to save a life</p>

      <div class="steps-grid">
        <div class="step-card">
          <div class="step-number">1</div>
          <h3>Register</h3>
          <p>Sign up as a blood donor with your details and blood type. Get notified when you're needed.</p>
          <a href="#register" class="step-link">Quick verification process <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="step-card">
          <div class="step-number">2</div>
          <h3>Get Notified</h3>
          <p>Receive notifications when someone nearby urgently needs your blood type.</p>
          <a href="#notify" class="step-link">Real-time alerts <i class="fa-solid fa-arrow-right"></i></a>
        </div>
        <div class="step-card">
          <div class="step-number">3</div>
          <h3>Save Lives</h3>
          <p>Connect directly with the recipient and hospital to donate blood and make a difference.</p>
          <a href="#donate2" class="step-link">Make a difference <i class="fa-solid fa-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="cta">
    <div class="container">
      <h2>Ready to Make a Difference?</h2>
      <p>Join thousands of donors who have already saved lives. Your blood can be someone's second chance at life.</p>
      <div class="cta-buttons">
        <a href="#donate3" class="btn btn-light">Become a Donor Today</a>
        <a href="#learn-more" class="btn btn-outline-light">Learn More</a>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container footer-inner">
      <div class="footer-brand">
        <div class="logo">
          <i class="fa-solid fa-droplet"></i>
          <span>Blood Connect</span>
        </div>
        <p>Connecting donors with those in need, faster and more efficiently than ever.</p>
      </div>
      <div class="footer-links">
        <h4>Quick Links</h4>
        <a href="#home">Home</a>
        <a href="#about">About Us</a>
        <a href="#features">Features</a>
        <a href="#contact">Contact</a>
      </div>
      <div class="footer-links">
        <h4>For Donors</h4>
        <a href="#register2">Register</a>
        <a href="#guidelines">Guidelines</a>
        <a href="#faq">FAQ</a>
        <a href="#benefits">Benefits</a>
      </div>
      <div class="footer-links">
        <h4>Support</h4>
        <a href="#help">Help Center</a>
        <a href="#privacy">Privacy Policy</a>
        <a href="#terms">Terms of Service</a>
        <a href="#emergency2">Emergency Aid</a>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; <?php echo date("Y"); ?> Blood Connect. All rights reserved.</p>
    </div>
  </footer>

</body>
</html>