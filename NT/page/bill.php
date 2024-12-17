<?php
require_once '../config/condb.php';
session_start();

// ดึงฟังก์ชันเกี่ยวกับบิล
require_once '../functions/bill_functions.php';

// ตรวจสอบว่ามี customer_id หรือไม่
$customer_id = isset($_GET['customer_id']) ? $_GET['customer_id'] : null;

// หากมี customer_id ให้ดึงชื่อ
$customer_name = $customer_id ? getCustomerName($pdo, $customer_id) : null;

// ประมวลผลเมื่อกดฟอร์มเพิ่มบิล
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_bill') {
    try {
        if (empty($_POST['bill_number'])) {
            throw new Exception("กรุณากรอกหมายเลขบิล");
        }
        $bill_number = trim($_POST['bill_number']);
        $bill_type = $_POST['bill_type'];

        // รับ customer_id จาก GET ถ้ามี, ถ้าไม่มีให้รับจาก POST
        if ($customer_id) {
            // ตรวจสอบว่ามีลูกค้าจริงๆ
            $check_cus_stmt = $pdo->prepare("SELECT customer_id FROM customer WHERE customer_id = :customer_id");
            $check_cus_stmt->execute([':customer_id' => $customer_id]);
            if (!$check_cus_stmt->fetch()) {
                throw new Exception("ไม่พบลูกค้าที่ระบุ");
            }
        } else {
            if (empty($_POST['customer_id'])) {
                throw new Exception("กรุณาเลือกลูกค้า");
            }
            $customer_id = $_POST['customer_id'];
        }

        $groups = isset($_POST['groups']) ? $_POST['groups'] : [];
        addBill($pdo, $bill_number, $bill_type, $customer_id, $groups);

        $_SESSION['success'] = "สร้างบิลใหม่เรียบร้อยแล้ว";
        header("Location: bill.php" . ($customer_id ? "?customer_id=" . urlencode($customer_id) : ""));
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
}

// ดึงข้อมูลลูกค้าสำหรับ dropdown
$customers = getCustomers($pdo);

// ดึงข้อมูลบิล
$bills = getBills($pdo, $customer_id);

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการบิล</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include '../components/header.php'; ?>
</head>
<body>
<div class="container mt-5">
    <h2>
    <?php 
    if ($customer_id) {
        $bill_count = count($bills);
        echo "บิลของลูกค้า: " . htmlspecialchars($customer_name) . " (ทั้งหมด $bill_count บิล)";
    } else {
        echo "รายการบิลทั้งหมด";
    }
    ?>
    </h2>

    <!-- แสดงข้อความสำเร็จหรือข้อผิดพลาด -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php 
            echo htmlspecialchars($_SESSION['success']); 
            unset($_SESSION['success']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="ปิด"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php 
            echo htmlspecialchars($_SESSION['error']); 
            unset($_SESSION['error']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="ปิด"></button>
        </div>
    <?php endif; ?>

    <!-- ปุ่มเปิดโมดัลสร้างบิลใหม่ -->
    <div class="mb-3">
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBillModal">
            สร้างบิลใหม่
        </button>
    </div>
    
    <!-- ปุ่มย้อนกลับ -->
    <div class="mb-3">
        <a href="customer.php" class="btn btn-secondary">ย้อนกลับ</a>
    </div>
    

    <!-- ตารางแสดงบิล -->
    <?php if (!empty($bills)): ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>รหัสบิล</th>
                    <th>เลขบิล</th>
                    <th>ประเภทบิล</th>
                    <th>วันที่สร้าง</th>
                    <th>วันที่อัปเดต</th>
                    <th>ยอดรวม (บาท)</th>
                    <th>ชื่อลูกค้า</th>
                    <th>ชื่อกลุ่ม</th>
                    <th>ราคากลุ่ม</th>
                    <th>ข้อมูลเพิ่มเติม</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($bills as $bill): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($bill['bill_id']); ?></td>
                        <td><?php echo htmlspecialchars($bill['bill_number']); ?></td>
                        <td><?php echo htmlspecialchars($bill['bill_type']); ?></td>
                        <td><?php echo htmlspecialchars($bill['bill_create_at']); ?></td>
                        <td><?php echo htmlspecialchars($bill['bill_update_at']); ?></td>
                        <td><?php echo htmlspecialchars(number_format($bill['all_price'], 2)); ?></td>
                        <td><?php echo htmlspecialchars($bill['customer_name'] ?? $customer_name); ?></td>
                        <td><?php echo htmlspecialchars($bill['group_name'] ?? '-'); ?></td>
                        <td><?php echo htmlspecialchars(number_format($bill['group_price'] ?? 0, 2)); ?></td>
                        <td><?php echo htmlspecialchars($bill['group_info_name'] ?? '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info text-center">
            <?php 
            if ($customer_id) {
                echo "ไม่มีบิลสำหรับลูกค้า: " . htmlspecialchars($customer_name);
            } else {
                echo "ไม่มีข้อมูลบิล";
            }
            ?>
        </div>
    <?php endif; ?>

    <?php include '../components/modals/bill_modal.php'; ?>

</div>

<!-- Bootstrap JS และ dependencies -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const groupCountSelect = document.getElementById('group_count');
    const groupContainer = document.getElementById('groupContainer');

    function generateGroups() {
        const groupCount = parseInt(groupCountSelect.value, 10);
        groupContainer.innerHTML = '';

        for (let i = 0; i < groupCount; i++) {
            const groupDiv = document.createElement('div');
            groupDiv.classList.add('card', 'mb-3', 'p-3');
            groupDiv.innerHTML = `
                <h5>กลุ่มที่ ${i + 1}</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ชื่อกลุ่ม</label>
                        <input type="text" name="groups[${i}][name]" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ประเภทกลุ่ม</label>
                        <select name="groups[${i}][type]" class="form-select" required>
                            <option value="1">ประเภท 1</option>
                            <option value="2">ประเภท 2</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ราคากลุ่ม A</label>
                        <input type="number" name="groups[${i}][price_a]" class="form-control" required min="0" step="0.01">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ราคากลุ่ม B</label>
                        <input type="number" name="groups[${i}][price_b]" class="form-control" required min="0" step="0.01">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">ข้อมูลเพิ่มเติม</label>
                        <div id="infoContainer_${i}">
                            <div class="input-group mb-2 info-group">
                                <input type="text" name="groups[${i}][info][]" class="form-control" placeholder="กรอกข้อมูลเพิ่มเติม">
                                <button type="button" class="btn btn-danger remove-info-btn">ลบ</button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary add-info-btn" data-group-index="${i}">เพิ่มข้อมูล</button>
                    </div>
                </div>
            `;
            groupContainer.appendChild(groupDiv);
        }

        const addInfoButtons = document.querySelectorAll('.add-info-btn');
        addInfoButtons.forEach(button => {
            button.addEventListener('click', function() {
                const groupIndex = this.getAttribute('data-group-index');
                addInfoField(groupIndex);
            });
        });

        const removeInfoButtons = document.querySelectorAll('.remove-info-btn');
        removeInfoButtons.forEach(button => {
            button.addEventListener('click', function() {
                this.parentElement.remove();
            });
        });
    }

    function addInfoField(groupIndex) {
        const infoContainer = document.getElementById(`infoContainer_${groupIndex}`);
        const infoGroupDiv = document.createElement('div');
        infoGroupDiv.classList.add('input-group', 'mb-2', 'info-group');
        infoGroupDiv.innerHTML = `
            <input type="text" name="groups[${groupIndex}][info][]" class="form-control" placeholder="ชื่อแพคเก็จ">
            <button type="button" class="btn btn-danger remove-info-btn">ลบ</button>
        `;
        infoContainer.appendChild(infoGroupDiv);

        const removeButton = infoGroupDiv.querySelector('.remove-info-btn');
        removeButton.addEventListener('click', function() {
            this.parentElement.remove();
        });
    }

    generateGroups();
    groupCountSelect.addEventListener('change', generateGroups);
});
</script>
</body>
</html>