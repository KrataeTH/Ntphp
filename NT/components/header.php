<?php
// components/header.php

// รับชื่อไฟล์ปัจจุบัน
$current_page = basename($_SERVER['PHP_SELF'], ".php");
?>
<header class="navbar navbar-expand-lg navbar-light bg-white shadow-sm py-3">
  <div class="container">
    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center" href="index.php">
      <img src="https://www.ntplc.co.th/ResourcePackages/ntplc/assets/dist/images/sub-header-logo.png" alt="NT Logo" class="header-logo me-2">
    </a>

    <!-- Toggle button for mobile view -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar Links -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'index') ? 'active' : ''; ?>" href="index.php">หน้าหลัก</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'customer') ? 'active' : ''; ?>" href="customer.php">ข้อมูลลูกค้า</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?php echo ($current_page == 'bill') ? 'active' : ''; ?>" href="bill.php">ข้อมูลบิลลูกค้า</a>
        </li>
      </ul>
    </div>
  </div>
  
</header>
<body>
<main class="container">
        <!-- เนื้อหาของหน้า -->
        <div class="text-center mt-4">
          <h2>ศูนย์ขายและวิศวกรรมบริการ</h2>
          <h3>ส่วนบริการลูกค้าจังหวัดกาญจนบุรี</h3>
          <h4>บริษัทโทรคมนาคมแห่งชาติ จำกัด (มหาชน)</h4>
        </div>
        <!-- เนื้อหาเพิ่มเติม -->
    </main>
</body>
