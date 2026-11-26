<?php
require_once __DIR__ . '/includes/header.php';
?>
<header class="landing-nav">
  <a class="brand" href="index.php"><span>CLEAR</span>X</a>
  <button class="nav-toggle" aria-label="Toggle navigation"><i class="fa-solid fa-bars"></i></button>
  <nav>
    <a href="#home">Home</a><a href="#about">About</a><a href="#features">Features</a>
    <a href="#how">How It Works</a><a href="#contact">Contact</a>
  </nav>
  <a class="btn btn-primary" href="login.php">Login <i class="fa-solid fa-arrow-right"></i></a>
</header>
<main id="home">
  <section class="hero">
    <div class="hero-copy">
      <div class="eyebrow"><span></span> SMART CAMPUS AUTOMATION</div>
      <h1>Your Campus Clearance,<br><em>Reimagined.</em></h1>
      <p>Experience a smarter way to complete college No Due formalities. Track, verify and approve clearances through one centralized digital platform.</p>
      <div class="hero-actions">
        <a class="btn btn-primary" href="register.php">Get Started <i class="fa-solid fa-arrow-right"></i></a>
        <a class="btn btn-ghost" href="#features"><i class="fa-solid fa-circle-play"></i> Explore Features</a>
      </div>
    </div>
    <div class="hero-visual">
      <div class="orb orb-one"></div><div class="orb orb-two"></div>
      <div class="dashboard-preview">
        <div class="preview-top"><div class="preview-logo">CLEARX</div><div class="preview-avatar">AR</div></div>
        <div class="preview-grid">
          <div class="preview-sidebar"><i class="fa-solid fa-grid-2"></i><i class="fa-solid fa-route"></i><i class="fa-solid fa-bell"></i><i class="fa-solid fa-user"></i></div>
          <div class="preview-content">
            <div class="preview-title"><span>Clearance Overview</span><b>68%</b></div>
            <div class="preview-progress"><span></span></div>
            <div class="preview-cards"><div><i class="fa-solid fa-building"></i><b>4</b><span>Departments</span></div><div><i class="fa-solid fa-circle-check"></i><b>3</b><span>Approved</span></div><div><i class="fa-solid fa-clock"></i><b>1</b><span>Pending</span></div></div>
            <div class="preview-table"><div class="row"><span>Library</span><b class="status approved">Approved</b></div><div class="row"><span>Computer Science</span><b class="status pending">Pending</b></div></div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="stats" id="about">
    <div><b>5</b><span>Role-based Dashboards</span></div><div><b>100%</b><span>Digital Workflow</span></div><div><b>24/7</b><span>Request Tracking</span></div><div><b>QR</b><span>Certificate Verification</span></div>
  </section>
  <section class="section" id="features">
    <div class="section-heading"><span>PLATFORM FEATURES</span><h2>Clearance without complexity</h2></div>
    <div class="feature-grid">
      <article><i class="fa-solid fa-shield-halved"></i><h3>Secure & Authorized</h3><p>Role-based permissions, CSRF protection and encrypted password authentication.</p></article>
      <article><i class="fa-solid fa-route"></i><h3>Live Status Tracking</h3><p>Follow every department verification from submission to final approval.</p></article>
      <article><i class="fa-solid fa-certificate"></i><h3>Verified Certificates</h3><p>Generate final no-due certificates with unique IDs and QR verification.</p></article>
      <article><i class="fa-solid fa-chart-line"></i><h3>Actionable Reports</h3><p>Monitor requests and department performance with clear analytics.</p></article>
    </div>
  </section>
  <section class="section steps" id="how">
    <div class="section-heading"><span>HOW IT WORKS</span><h2>Four approvals. One seamless flow.</h2></div>
    <div class="step-grid"><article><b>01</b><h3>Submit</h3><p>Students create a no-due request.</p></article><article><b>02</b><h3>Verify</h3><p>Faculty and HOD validate departments.</p></article><article><b>03</b><h3>Approve</h3><p>The Principal grants final clearance.</p></article><article><b>04</b><h3>Download</h3><p>Students receive a verified certificate.</p></article></div>
  </section>
</main>
<footer class="landing-footer" id="contact"><div class="brand"><span>CLEAR</span>X</div><p>One Platform. Every Clearance. Zero Hassle.</p><p>&copy; <?= date('Y') ?> CLEARX</p></footer>
<script src="assets/js/script.js"></script>
</body></html>
