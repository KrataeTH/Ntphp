<?php
// components/header.php

// รับชื่อไฟล์ปัจจุบัน
$current_page = basename($_SERVER['PHP_SELF'], ".php");
?>
<header class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3 fixed-top">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="https://www.ntplc.co.th/ResourcePackages/ntplc/assets/dist/images/sub-header-logo.png" alt="NT Logo" class="header-logo me-2">
    </a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link btn btn-outline rounded-pill <?php echo ($current_page == 'index') ? 'active' : ''; ?>" href="index.php">หน้าหลัก</a>
        </li>
        <li class="nav-item">
          <a class="nav-link btn btn-outline rounded-pill <?php echo ($current_page == 'customer') ? 'active' : ''; ?>" href="customer.php">ข้อมูลลูกค้า</a>
        </li>
        <li class="nav-item">
          <a class="nav-link btn btn-outline rounded-pill <?php echo ($current_page == 'bill') ? 'active' : ''; ?>" href="bill.php">ข้อมูลบิลลูกค้า</a>
        </li>
        <li class="nav-item">
          <a class="nav-link btn btn-outline rounded-pill <?php echo ($current_page == 'report') ? 'active' : ''; ?>" href="report.php">ผลรายงานของลูกค้า</a>
        </li>
      </ul>
    </div>
  </div>
</header>

<style>
  body {
    padding-top: 80px;
  }

  .navbar-nav .nav-link {
    transition: all 0.3s ease;
    font-size: 1rem;
    text-decoration: none;
    color: #343a40;
  }

  .navbar-nav .nav-link:hover,
  .navbar-nav .nav-link:focus {
    background-color: #ffc107;
    transform: translateY(-2px);
  }

  .navbar-nav .nav-item {
    padding: 0.5rem 0.8rem;
  }

  .navbar {
    background-color: #fff;
  }

  /* เพิ่มเติม */
  .navbar-nav .nav-link.btn {
    margin: 0.2rem; /* ปรับระยะห่างระหว่างปุ่ม */
  }
</style>