<?php
session_start();
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" /><title>Dashboard with Sidebar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css"/>
  <?php include '../components/header.php'; ?>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
  <style>
    .sidebar-expanded {
    }
    .sidebar-collapsed {
    }
    .sidebar-collapsed .sidebar-label {
      display: none;
    }
    .tooltip-right {
      position: absolute;
      left: 100%;
      top: 50%;
      transform: translateY(-50%);
      background-color: rgba(0, 0, 0, 0.8);
      color: #fff;
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 0.75rem;
      opacity: 0;
      pointer-events: none;
      transition: opacity 0.15s ease-in-out;
      white-space: nowrap;
    }
    .sidebar-item:hover .tooltip-right {
      opacity: 1;
    }
  </style>

</head>
  <!-- Sidebar -->
  <aside
  id="sidebar"
  class="fixed left-0 top-16 z-10 h-[calc(100vh-4rem)] overflow-hidden transition-all duration-300 flex flex-col sidebar-expanded bg-gradient-to-b from-yellow-50 to-yellow-200 shadow">
    <!-- ส่วนหัว / โลโก้ -->
    <div class="flex items-center justify-between px-2 py-4">
      <div class="flex items-center space-x-2">
        <i class="fas fa-dragon text-xl"></i>
        <span class="text-xl font-bold sidebar-label"></span>
      </div>
    <!-- ปุ่มย่อ/ขยาย -->
      <button onclick="toggleSidebar()" class="focus:outline-none"> <i class="fas fa-bars text-xl"></i></button>
    </div>
    <!-- Search Box -->
    <div class="px-4 py-2">
      <div class="bg-yellow-800 rounded-full flex items-center px-2 py-1 focus-within:bg-yellow-700 transition"><i class="fas fa-search pl-2"></i>
        <input type="text" placeholder="Search.." class="bg-transparent focus:outline-none ml-2 w-full sidebar-label"/>
      </div>
    </div>
    <!-- Menu Items -->
    <nav class="mt-4 flex-1 flex flex-col">
      <a href="index.php" class="sidebar-item flex items-center gap-3 px-4 py-3 hover:bg-yellow-800 transition relative">
        <i class="fas fa-home w-5 text-lg"></i>
      <span class="sidebar-label">Home</span>
      <span class="tooltip-right">Home</span></a>

      <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-3 hover:bg-yellow-800 transition relative">
        <i class="fas fa-tachometer-alt w-5 text-lg"></i>
        <span class="sidebar-label">Dashboard</span>
        <span class="tooltip-right">Dashboard</span></a>

      <a
        href="customer.php"
        class="sidebar-item flex items-center gap-3 px-4 py-3 hover:bg-yellow-800 transition relative">
        <i class="fas fa-user-friends w-5 text-lg"></i>
        <span class="sidebar-label">Customers</span>
        <span class="tooltip-right">Customers</span></a>

      <a
        href="bill.php"
        class="sidebar-item flex items-center gap-3 px-4 py-3 hover:bg-yellow-800 transition relative">
        <i class="fas fa-file-invoice-dollar w-5 text-lg"></i>
        <span class="sidebar-label">Billing</span>
        <span class="tooltip-right">Billing</span></a>

      <a
        href="#"
        class="sidebar-item flex items-center gap-3 px-4 py-3 hover:bg-yellow-800 transition relative">
        <i class="fas fa-tools w-5 text-lg"></i>
        <span class="sidebar-label">Tools</span>
        <span class="tooltip-right">Tools</span></a>

      <a
        href="#"
        class="sidebar-item flex items-center gap-3 px-4 py-3 hover:bg-yellow-800 transition relative">
        <i class="fas fa-chart-line w-5 text-lg"></i>
        <span class="sidebar-label">Reporting</span>
        <span class="tooltip-right">Reporting</span></a>

      <!-- กลุ่มเมนูอื่น ๆ -->
      <div class="mt-auto mb-4">
        <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-3 hover:bg-yellow-800 transition relative">
          <i class="fas fa-bell w-5 text-lg"></i>
          <span class="sidebar-label">Notification</span>
          <span class="tooltip-right">Notification</span></a>
        <a href="#" class="sidebar-item flex items-center gap-3 px-4 py-3 hover:bg-yellow-800 transition relative">
          <i class="fas fa-cog w-5 text-lg"></i>
          <span class="sidebar-label">Settings</span>
          <span class="tooltip-right">Settings</span></a>
      </div>
    </nav>
  </aside>
  <div id="main-content" class="ml-[260px] transition-all duration-300 flex-1 flex flex-col">

    <!-- ส่วน Header ของเนื้อหา (ซ่อน sidebar toggle เดิม) -->
    <header class="flex flex-col items-end space-y-1">
    <div >
        <span class="text-yellow-800">สวัสดี! คุณ <?php echo htmlspecialchars($_SESSION['name']); ?></span>
    </div>

    <!-- ปุ่ม Logout -->
    <a href="logout.php" class="bg-red-500 hover:bg-red-600 text-white text-sm font-medium px-3 py-1 rounded">Logout</a>
    </header>

    <!-- Container หลักของเนื้อหา -->
    <div class="mx-auto w-full max-w-6xl py-6 px-4 flex-1 flex flex-col">

    <!-- ส่วนหลัก แบ่งเป็น 2 คอลัมน์ใหญ่ -->
    <div class="flex flex-col lg:flex-row gap-4">

    <!-- Left Section -->
    <div class="w-full lg:w-2/3 flex flex-col gap-4">

    <!-- My Courses Section -->
    <section class="bg-white rounded-lg shadow p-4">
    <h2 class="text-xl font-bold mb-4 text-yellow-900">My Courses</h2>

    <!-- Swiper เป็นการสไลด์รูปภาพ -->
    <div class="swiper-container">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <img
            src="https://www.ntplc.co.th/images/default-source/promotions/2024/january/thunder-net/thumbnail.webp?sfvrsn=90995fa9_1"
            alt="Image 1"
            class="rounded-lg w-full h-64 object-cover"/>
        </div>
        <div class="swiper-slide">
          <img
            src="https://www.ntplc.co.th/images/default-source/august/nt-welcome/new/thumbnail.webp?sfvrsn=b3e0b66d_1"
            alt="Image 2"
            class="rounded-lg w-full h-64 object-cover"/>
        </div>
        <div class="swiper-slide">
          <img
            src="https://www.ntplc.co.th/images/default-source/new/thumbnail.webp?sfvrsn=df3de623_1"
            alt="Image 3"
            class="rounded-lg w-full h-64 object-cover"/>
        </div>
  </div>
    <!-- Pagination -->
    <div class="swiper-pagination"></div>
    </section>

    <!-- ประกาศสำคัญ (Announcements) -->
    <div id="announcements" class="flex flex-col gap-4">

    <!-- ประกาศสำคัญอันที่ 1 -->
      <section class="bg-yellow-100 border-l-4 border-yellow-400 text-yellow-800 p-6 rounded-lg shadow-md">
        <div class="flex items-start"><i class="fas fa-exclamation-circle text-3xl mr-4 mt-1"></i>
          <div>
            <h3 class="text-lg font-bold mb-2">ประกาศสำคัญ</h3>
            <p>
              สัญญาเลขที่ <span class="font-bold text-yellow-700">223335566</span> ของคุณ
              จะสิ้นสุดในอีก <span class="font-bold text-yellow-700">60 วัน</span>.
              กรุณาตรวจสอบเงื่อนไขสัญญา และดำเนินการต่ออายุให้เรียบร้อย.
            </p>
            <a href="renewal.php" class="mt-4 inline-block bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-medium px-4 py-2 rounded">ต่ออายุสัญญา</a>
          </div>
        </div>
      </section>

      <!-- ประกาศสำคัญอันที่ 2 -->
      <section class="bg-yellow-100 border-l-4 border-yellow-400 text-yellow-800 p-6 rounded-lg shadow-md">
        <div class="flex items-start">
          <i class="fas fa-exclamation-circle text-3xl mr-4 mt-1"></i>
          <div>
            <h3 class="text-lg font-bold mb-2">ประกาศสำคัญ</h3>
            <p>
              สัญญาเลขที่ <span class="font-bold text-yellow-700">223335599</span> ของคุณ
              จะสิ้นสุดในอีก <span class="font-bold text-yellow-700">30 วัน</span>.
              กรุณาตรวจสอบเงื่อนไขสัญญา และดำเนินการต่ออายุให้เรียบร้อย.
            </p>
            <a href="renewal.php" class="mt-4 inline-block bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-medium px-4 py-2 rounded">ต่ออายุสัญญา</a>
          </div>
        </div>
      </section>

      <!-- ประกาศสำคัญอันที่ 3 -->
      <section
        class="bg-yellow-100 border-l-4 border-yellow-400 text-yellow-800 p-6 rounded-lg shadow-md">
        <div class="flex items-start">
          <i class="fas fa-exclamation-circle text-3xl mr-4 mt-1"></i>
          <div>
            <h3 class="text-lg font-bold mb-2">ประกาศสำคัญ</h3>
            <p>
              สัญญาเลขที่ <span class="font-bold text-yellow-700">323335599</span> ของคุณ
              ใกล้จะครบกำหนดภายใน <span class="font-bold text-yellow-700">15 วัน</span>.
              โปรดดำเนินการต่ออายุล่วงหน้าเพื่อหลีกเลี่ยงปัญหาในการใช้งาน.
            </p>
            <a href="renewal.php" class="mt-4 inline-block bg-yellow-400 hover:bg-yellow-500 text-yellow-900 font-medium px-4 py-2 rounded">ต่ออายุสัญญา</a>
          </div>
        </div>
      </section>
    </div>
</div>
  <div class="w-full lg:w-1/3 flex flex-col gap-4">
    <div class="bg-white rounded-lg shadow p-4">
      <!-- ส่วนควบคุมเดือน (Prev / Today / Next) -->
      <div class="flex justify-between items-center mb-4">
        <button id="prevMonth" class="bg-yellow-200 px-3 py-1 rounded hover:bg-yellow-300">Prev</button>

        <!-- หัวข้อเดือนปี -->
        <h2 class="text-lg font-bold text-yellow-900" id="calendarTitle"></h2>

        <!-- ปุ่ม Today -->
        <button id="todayBtn" class="bg-yellow-200 px-3 py-1 rounded hover:bg-yellow-300">Today</button>
        <button id="nextMonth" class="bg-yellow-200 px-3 py-1 rounded hover:bg-yellow-300">Next</button>
      </div>

      <!-- แสดงวันต่าง ๆ -->
      <div class="grid grid-cols-7 gap-2 text-center text-sm" id="calendarGrid"></div>
    </div>

      <!-- Online Users -->
      <div class="bg-white rounded-lg shadow p-4">
        <h2 class="text-lg font-bold mb-4 text-yellow-900">Online Users</h2>
        <div class="space-y-4">        
      <!-- รายการผู้ใช้งานตัวอย่าง -->
          <div class="flex items-center p-2 bg-yellow-50 rounded hover:bg-yellow-100 transition">
            <img class="w-10 h-10 rounded-full" src="https://via.placeholder.com/40" alt=""><div class="ml-3">
              <span class="block font-semibold">Maren Maureen</span>
              <span class="text-sm text-gray-500">1094882001</span>
            </div>
          </div>
          <div class="flex items-center p-2 bg-yellow-50 rounded hover:bg-yellow-100 transition">
            <img class="w-10 h-10 rounded-full" src="https://via.placeholder.com/40" alt=""><div class="ml-3">
              <span class="block font-semibold">Jennifer Jane</span>
              <span class="text-sm text-gray-500">1094672000</span>
            </div>
          </div>
          <div class="flex items-center p-2 bg-yellow-50 rounded hover:bg-yellow-100 transition">
            <img class="w-10 h-10 rounded-full" src="https://via.placeholder.com/40" alt=""><div class="ml-3">
              <span class="block font-semibold">Ryan Herwinds</span>
              <span class="text-sm text-gray-500">1094303423</span>
            </div>
          </div>
          <div class="flex items-center p-2 bg-yellow-50 rounded hover:bg-yellow-100 transition">
            <img class="w-10 h-10 rounded-full" src="https://via.placeholder.com/40" alt=""><div class="ml-3">
              <span class="block font-semibold">Kierra Culhane</span>
              <span class="text-sm text-gray-500">1094662002</span>
            </div>
          </div>
        </div>
      </div>
          <!-- ติดต่อแอดมิน -->
          <div class="space-y-4">
            <div class="bg-white rounded-lg shadow-md p-4">
              <div class="flex items-center">
                <img class="w-12 h-12 rounded-full" src="" alt="">
                <div class="ml-4">
                  <h2 class="text-lg font-semibold text-yellow-900">Jane Cooper</h2>
                  <span class="text-sm text-gray-500">Regional Paradigm Technician</span>
                </div>
                <span class="ml-auto bg-yellow-100 text-yellow-800 text-sm px-2 py-1 rounded-full">Admin</span>
              </div>
              <div class="mt-4 flex justify-between">
                <button class="flex items-center text-blue-500 hover:underline">
                  <i class="fa-solid fa-envelope mr-2"></i> Email</button>
                <button class="flex items-center text-blue-500 hover:underline">
                  <i class="fa-solid fa-phone mr-2"></i> Call</button>
              </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-4">
              <div class="flex items-center">
                <img class="w-12 h-12 rounded-full" src="" alt="">
                <div class="ml-4">
                  <h2 class="text-lg font-semibold text-yellow-900">Cody Fisher</h2>
                  <span class="text-sm text-gray-500">Product Directives Officer</span>
                </div>
                <span class="ml-auto bg-yellow-100 text-yellow-800 text-sm px-2 py-1 rounded-full">Admin</span>
              </div>
              <div class="mt-4 flex justify-between">
                <button class="flex items-center text-blue-500 hover:underline">
                  <i class="fa-solid fa-envelope mr-2"></i> Email</button>
                <button class="flex items-center text-blue-500 hover:underline">
                  <i class="fa-solid fa-phone mr-2"></i> Call</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Swiper.js Script -->
  <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
  <script>
    const swiper = new Swiper('.swiper-container', {
      loop: true,
      autoplay: {
        delay: 3000,
        disableOnInteraction: false
      },
      speed: 600,
      effect: 'cube',
      slidesPerView: 1,
      centeredSlides: false,
      spaceBetween: 20,
      pagination: {
        el: '.swiper-pagination',
        clickable: true
      }
    });
  </script>

  <!-- Dynamic Calendar Script -->
  <script>
    const thaiHolidays = {
      "2025-01-01": "วันขึ้นปีใหม่",
      "2025-02-14": "วันวาเลนไทน์",
      "2025-04-06": "วันจักรี",
      "2025-04-13": "สงกรานต์",
      "2025-04-14": "สงกรานต์",
      "2025-04-15": "สงกรานต์",
      "2025-05-01": "วันแรงงาน",
      "2025-05-05": "วันฉัตรมงคล",
      "2025-12-05": "วันพ่อ",
      "2025-12-10": "วันรัฐธรรมนูญ",
      "2025-12-25": "วันคริสต์มาส"
    };

    let userEvents = {};
    const dayNames = ["Mo", "Tu", "We", "Th", "Fr", "Sa", "Su"];
    const monthNames = ["January", "February", "March", "April", "May", "June","July", "August", "September", "October", "November", "December"];

    let currentYear = new Date().getFullYear();
    let currentMonth = new Date().getMonth();
    const today = new Date();
    const todayYear = today.getFullYear();
    const todayMonth = today.getMonth();
    const todayDate = today.getDate();

    function renderCalendar(year, month) {
    const calendarGrid = document.getElementById("calendarGrid");
    const calendarTitle = document.getElementById("calendarTitle");
    calendarGrid.innerHTML = "";
    calendarTitle.textContent = monthNames[month] + " " + year;

    dayNames.forEach((day) => {const dayHeader = document.createElement("div");dayHeader.textContent = day;
      dayHeader.classList.add(
        "font-semibold",
        "bg-yellow-100",
        "py-2",
        "border",
        "border-yellow-200"
      );
      calendarGrid.appendChild(dayHeader);
    });

      const firstDay = (new Date(year, month, 1).getDay() + 6) % 7;
      const lastDate = new Date(year, month + 1, 0).getDate();

      for (let i = 0; i < firstDay; i++) {
        const emptyCell = document.createElement("div");
        emptyCell.textContent = "";
        emptyCell.classList.add("border", "border-yellow-200", "bg-yellow-50");
        calendarGrid.appendChild(emptyCell);
      }

      for (let date = 1; date <= lastDate; date++) {
        const cell = document.createElement("div");
        cell.textContent = date;
        cell.classList.add(
          "border",
          "border-yellow-200",
          "py-2",
          "hover:bg-yellow-100",
          "cursor-pointer",
          "transition",
          "relative"
        );

        const dayIndex = (firstDay + (date - 1)) % 7;
        if (dayIndex === 5 || dayIndex === 6) {
          cell.classList.add("text-red-500", "font-semibold");
        }

        if (year === todayYear && month === todayMonth && date === todayDate) {
          cell.classList.add("bg-yellow-200", "rounded-full", "text-yellow-900");
        }

        const mm = String(month + 1).padStart(2, "0");
        const dd = String(date).padStart(2, "0");
        const dateStr = `${year}-${mm}-${dd}`;

        if (Object.keys(thaiHolidays).includes(dateStr)) {
          cell.classList.remove("text-red-500");
          cell.classList.add("text-red-700", "font-bold");
          const holidayLabel = document.createElement("div");
          holidayLabel.classList.add("text-[10px]", "text-red-700", "mt-1");
          holidayLabel.textContent = thaiHolidays[dateStr];
          cell.appendChild(holidayLabel);
        }

        if (userEvents[dateStr]) {
          const eventIcon = document.createElement("div");
          eventIcon.classList.add(
            "absolute", "top-0", "right-0",
            "text-red-500", "text-lg"
          );
          eventIcon.textContent = "•";
          eventIcon.title = userEvents[dateStr];
          cell.appendChild(eventIcon);
        }

        cell.addEventListener("click", () => {
          if (!date) return;
          const userNote = prompt(
            "Add your note for " + dateStr + ":",
            userEvents[dateStr] || ""
          );
          if (userNote !== null) {
            if (userNote.trim() !== "") {
              userEvents[dateStr] = userNote.trim();
            } else {
              delete userEvents[dateStr];
            }
            renderCalendar(currentYear, currentMonth);
          }
        });

        calendarGrid.appendChild(cell);
      }
    }

    document.addEventListener("DOMContentLoaded", () => {renderCalendar(currentYear, currentMonth);
    document.getElementById("prevMonth").addEventListener("click", () => {currentMonth--;
      if (currentMonth < 0) {
        currentMonth = 11;
        currentYear--;
      }
      renderCalendar(currentYear, currentMonth);
    });

    document.getElementById("nextMonth").addEventListener("click", () => {currentMonth++;
      if (currentMonth > 11) {currentMonth = 0; currentYear++;
      }
      renderCalendar(currentYear, currentMonth);
    });

    document.getElementById("todayBtn").addEventListener("click", () => {currentYear = todayYear; currentMonth = todayMonth;
      renderCalendar(currentYear, currentMonth);
    });
  });
    // ฟังก์ชัน Toggle Sidebar (ย่อ/ขยาย)
    function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    // ถ้า sidebar เป็น expanded -> ย่อ
    if (sidebar.classList.contains('sidebar-expanded')) {
      sidebar.classList.remove('sidebar-expanded');
      sidebar.classList.add('sidebar-collapsed');
      mainContent.classList.remove('ml-[260px]');
      mainContent.classList.add('ml-[72px]');
    } else {
      // ถ้า sidebar เป็น collapsed -> ขยาย
      sidebar.classList.remove('sidebar-collapsed');
      sidebar.classList.add('sidebar-expanded');
      mainContent.classList.remove('ml-[72px]');
      mainContent.classList.add('ml-[260px]');
    }
    }
    </script>
  </body>
</html>
