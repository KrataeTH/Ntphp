<?php
session_start();
require_once '../config/config.php';

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit;
}

$evaluation_type = isset($_GET['evaluation_type']) ? $_GET['evaluation_type'] : '';

// ตรวจสอบการส่งข้อมูล
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $items = $_POST['items'] ?? [];
    $_SESSION['cost_table'] = $items; // เก็บข้อมูลในเซสชัน
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประมาณการค่าใช้จ่าย</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="bg-gray-100">
    <?php include './components/navbar.php'; ?>

    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">ประมาณการค่าใช้จ่าย</h1>
        
        <form method="POST" action="">
            <table class="table-auto w-full bg-white shadow-md rounded-lg">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="p-2">ลำดับ</th>
                        <th class="p-2">รายการ</th>
                        <th class="p-2">จำนวน</th>
                        <th class="p-2">ต้นทุน/หน่วย</th>
                        <th class="p-2">ราคาขาย/หน่วย</th>
                    </tr>
                </thead>
                <tbody id="itemTable">
                    <tr>
                        <td class="p-2">1</td>
                        <td class="p-2"><input type="text" name="items[0][name]" class="border p-1"></td>
                        <td class="p-2"><input type="number" name="items[0][quantity]" class="border p-1"></td>
                        <td class="p-2"><input type="number" name="items[0][cost]" class="border p-1"></td>
                        <td class="p-2"><input type="number" name="items[0][price]" class="border p-1"></td>
                    </tr>
                </tbody>
            </table>
            <button type="button" onclick="addRow()" class="mt-2 bg-blue-500 text-white p-2 rounded">เพิ่มแถว</button>
            <button type="submit" class="mt-2 bg-green-500 text-white p-2 rounded">บันทึกข้อมูล</button>
        </form>
        
        <?php if (!empty($_SESSION['cost_table'])): ?>
        <h2 class="text-xl font-bold mt-6">ตารางข้อมูลที่ป้อน</h2>
        <table class="table-auto w-full bg-white shadow-md rounded-lg mt-2">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2">ลำดับ</th>
                    <th class="p-2">รายการ</th>
                    <th class="p-2">จำนวน</th>
                    <th class="p-2">ต้นทุนรวม</th>
                    <th class="p-2">ราคาขายรวม</th>
                    <th class="p-2">กำไร</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cost_table'] as $index => $item): ?>
                <tr>
                    <td class="p-2"><?php echo $index + 1; ?></td>
                    <td class="p-2"><?php echo htmlspecialchars($item['name']); ?></td>
                    <td class="p-2"><?php echo htmlspecialchars($item['quantity']); ?></td>
                    <td class="p-2"><?php echo htmlspecialchars($item['quantity'] * $item['cost']); ?></td>
                    <td class="p-2"><?php echo htmlspecialchars($item['quantity'] * $item['price']); ?></td>
                    <td class="p-2"><?php echo htmlspecialchars(($item['quantity'] * $item['price']) - ($item['quantity'] * $item['cost'])); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>

    <script>
    function addRow() {
        let table = document.getElementById('itemTable');
        let rowCount = table.rows.length;
        let row = table.insertRow(-1);

        row.innerHTML = `
            <td class="p-2">\${rowCount + 1}</td>
            <td class="p-2"><input type="text" name="items[\${rowCount}][name]" class="border p-1"></td>
            <td class="p-2"><input type="number" name="items[\${rowCount}][quantity]" class="border p-1"></td>
            <td class="p-2"><input type="number" name="items[\${rowCount}][cost]" class="border p-1"></td>
            <td class="p-2"><input type="number" name="items[\${rowCount}][price]" class="border p-1"></td>
        `;
    }
    </script>
</body>
</html>ห
