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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard with Separate Announcement Frames</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Swiper.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
</head>
<body>
    <!-- Navbar -->
    <nav class="bg-gray-200 text-black shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <!-- Logo and Brand -->
                <div class="flex items-center">
                    <img src="https://lh5.googleusercontent.com/proxy/dFSvkaJ3s6GRq3Idd5YLpPVIKmOewgsaR0OrEg0-yXWnQO-HME3H4Yg8kRtfKPwD0UiIsObjAobdvx3bicht" 
                        alt="Logo" 
                        class="h-10 w-auto">
                    <a href="#" class="ml-2 text-xl font-bold">Dashboard</a>
                </div>
                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="index.php" class="text-black hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">Main</a>
                    <a href="customer.php" class="text-black hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">Customer</a>
                    <a href="bill.php" class="text-black hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">Billing</a>
                    <a href="report.php" class="text-black hover:text-gray-700 px-3 py-2 rounded-md text-sm font-medium">Report</a>
                    <a href="#" class="text-black hover:text-gray-700 px-3 py-2">
                        <i class="fas fa-bell"></i>
                    </a>
                    <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Logout
                    </a>
                </div>
            </div>
        </div>
        
    </nav>

    <!-- Swiper Section (Image Slider) -->
    <div class="bg-white border border-gray-300 p-6 mt-6 mx-4 rounded-lg shadow-md">
        <h3 class="text-lg font-bold text-gray-800 mb-4">ภาพตัวอย่าง</h3>
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <!-- Slide 1 -->
                <div class="swiper-slide">
                    <img src="https://via.placeholder.com/800x400?text=Image+1" alt="Image 1" class="rounded-lg w-full h-64 object-cover">
                </div>
                <!-- Slide 2 -->
                <div class="swiper-slide">
                    <img src="https://via.placeholder.com/800x400?text=Image+2" alt="Image 2" class="rounded-lg w-full h-64 object-cover">
                </div>
                <!-- Slide 3 -->
                <div class="swiper-slide">
                    <img src="https://via.placeholder.com/800x400?text=Image+3" alt="Image 3" class="rounded-lg w-full h-64 object-cover">
                </div>
            </div>
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <!-- Announcement Section -->
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-6 mt-6 mx-4 rounded-lg shadow-md">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-3xl mr-4"></i>
            <div>
                <h3 class="text-lg font-bold mb-2">ประกาศสำคัญ</h3>
                <p>
                    สัญญาเลขที่ 123335599 ของคุณจะสิ้นสุดในอีก <span class="font-bold text-yellow-800">60 วัน</span>.
                    โปรดต่ออายุสัญญาภายในเวลาที่กำหนดเพื่อหลีกเลี่ยงการหยุดชะงักในการให้บริการ.
                </p>
                <a href="renewal.php" class="mt-4 inline-block bg-yellow-600 hover:bg-yellow-700 text-white font-medium px-4 py-2 rounded">
                    ต่ออายุสัญญา
                </a>               
            </div>           
        </div>
    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-6 mt-6 mx-4 rounded-lg shadow-md">
      <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-3xl mr-4"></i>
            <div>
                <h3 class="text-lg font-bold mb-2">ประกาศสำคัญ</h3>
                <p>
                    สัญญาเลขที่ 222777888 ของคุณจะสิ้นสุดในอีก <span class="font-bold text-yellow-800">30 วัน</span>.
                    โปรดต่ออายุสัญญาภายในเวลาที่กำหนดเพื่อหลีกเลี่ยงการหยุดชะงักในการให้บริการ.
                </p>
                <a href="renewal.php" class="mt-4 inline-block bg-yellow-600 hover:bg-yellow-700 text-white font-medium px-4 py-2 rounded">
                    ต่ออายุสัญญา
                </a>               
            </div>           
        </div>
    </div>
  </div>
  <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-6 mt-6 mx-4 rounded-lg shadow-md">
        <div class="flex items-center">
            <i class="fas fa-exclamation-circle text-3xl mr-4"></i>
            <div>
                <h3 class="text-lg font-bold mb-2">ประกาศสำคัญ</h3>
                <p>
                    สัญญาเลขที่ 445556288 ของคุณจะสิ้นสุดในอีก <span class="font-bold text-yellow-800">60 วัน</span>.
                    โปรดต่ออายุสัญญาภายในเวลาที่กำหนดเพื่อหลีกเลี่ยงการหยุดชะงักในการให้บริการ.
                </p>
                <a href="renewal.php" class="mt-4 inline-block bg-yellow-600 hover:bg-yellow-700 text-white font-medium px-4 py-2 rounded">
                    ต่ออายุสัญญา
                </a>               
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
            loop: true, // Loop the slides
            autoplay: {
                delay: 3000, // Delay between transitions (in milliseconds)
                disableOnInteraction: false, // Continue autoplay after interaction
            },
        });
    </script>
</body>
</html>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <span class="text-gray-800">© 2025 Your Company. All rights reserved.</span> <!-- เปลี่ยนสีข้อความ Footer เป็นสีเทาเข้ม -->
        </div>
    </footer>
</body>
</html>