<?php
require_once '../config/condb.php';

// Handle form submission for adding/editing customer
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Start transaction
        $pdo->beginTransaction();

        // Insert or update address first
        $address_stmt = $pdo->prepare("
            INSERT INTO address (Address_text, amphure_id, thambon_id) 
            VALUES (:address_text, :amphure_id, :thambon_id)
            ON DUPLICATE KEY UPDATE 
            Address_text = :address_text, 
            amphure_id = :amphure_id, 
            thambon_id = :thambon_id
        ");
        
        $address_stmt->execute([
            ':address_text' => $_POST['address_text'],
            ':amphure_id' => $_POST['amphure_id'],
            ':thambon_id' => $_POST['thambon_id']
        ]);
        
        // Get the last inserted or existing address ID
        $address_id = $pdo->lastInsertId();

        // Prepare customer insert/update statement
        if (isset($_POST['customer_id']) && !empty($_POST['customer_id'])) {
            // Update existing customer
            $customer_stmt = $pdo->prepare("
                UPDATE customer 
                SET customer_name = :customer_name, 
                    customer_type = :customer_type, 
                    customer_phone = :customer_phone, 
                    customer_status = :customer_status, 
                    address_id = :address_id, 
                    update_at = CURRENT_DATE 
                WHERE customer_id = :customer_id
            ");
            $customer_stmt->execute([
                ':customer_name' => $_POST['customer_name'],
                ':customer_type' => $_POST['customer_type'],
                ':customer_phone' => $_POST['customer_phone'],
                ':customer_status' => $_POST['customer_status'],
                ':address_id' => $address_id,
                ':customer_id' => $_POST['customer_id']
            ]);
        } else {
            // Insert new customer
            $customer_stmt = $pdo->prepare("
                INSERT INTO customer 
                (customer_name, customer_type, customer_phone, customer_status, address_id, create_at, update_at) 
                VALUES 
                (:customer_name, :customer_type, :customer_phone, :customer_status, :address_id, CURRENT_DATE, CURRENT_DATE)
            ");
            $customer_stmt->execute([
                ':customer_name' => $_POST['customer_name'],
                ':customer_type' => $_POST['customer_type'],
                ':customer_phone' => $_POST['customer_phone'],
                ':customer_status' => $_POST['customer_status'],
                ':address_id' => $address_id
            ]);
        }

        // Commit transaction
        $pdo->commit();

        // Redirect with success message
        header("Location: customer.php?success=1");
        exit();
    } catch (Exception $e) {
        // Rollback transaction in case of error
        $pdo->rollBack();
        
        // Log error (in a real application, use proper logging)
        error_log("Customer save error: " . $e->getMessage());
        
        // Redirect with error
        header("Location: customer.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}

// Handle customer deletion
if (isset($_GET['delete_customer_id']) && !empty($_GET['delete_customer_id'])) {
    $customer_id = $_GET['delete_customer_id'];

    try {
        // Start transaction
        $pdo->beginTransaction();

        // Delete customer address first (cascade delete may occur depending on foreign key constraints)
        $address_stmt = $pdo->prepare("SELECT address_id FROM customer WHERE customer_id = ?");
        $address_stmt->execute([$customer_id]);
        $address = $address_stmt->fetch(PDO::FETCH_ASSOC);

        if ($address) {
            $address_id = $address['address_id'];
            // Delete the customer
            $delete_customer_stmt = $pdo->prepare("DELETE FROM customer WHERE customer_id = ?");
            $delete_customer_stmt->execute([$customer_id]);

            // Optionally, delete the address if it's no longer associated with any customer
            $delete_address_stmt = $pdo->prepare("DELETE FROM address WHERE address_id = ? AND NOT EXISTS (SELECT 1 FROM customer WHERE address_id = ?)");
            $delete_address_stmt->execute([$address_id, $address_id]);
        }

        // Commit transaction
        $pdo->commit();

        // Redirect to the customer list page with success message
        header("Location: customer.php?success=2");
        exit();

    } catch (Exception $e) {
        // Rollback transaction in case of error
        $pdo->rollBack();

        // Log error (in a real application, use proper logging)
        error_log("Customer delete error: " . $e->getMessage());

        // Redirect to customer list page with error message
        header("Location: customer.php?error=" . urlencode($e->getMessage()));
        exit();
    }
}

// ฟิลเตอร์สำหรับการค้นหาอำเภอและตำบล
$filter_amphure = isset($_GET['amphure']) ? $_GET['amphure'] : null; // รับค่ากรองอำเภอ
$filter_thambon = isset($_GET['thambon']) ? $_GET['thambon'] : null; // รับค่ากรองตำบล

// Query หลักสำหรับกรองข้อมูล
$query = "
    SELECT c.customer_id, c.customer_name, c.customer_type, c.customer_phone, c.customer_status, 
           c.create_at, c.update_at, a.address_text, a.amphure_id, a.thambon_id, 
           am.amphure_name, t.thambon_name
    FROM customer c
    JOIN address a ON c.address_id = a.address_id
    JOIN amphure am ON a.amphure_id = am.amphure_id
    JOIN thambon t ON a.thambon_id = t.thambon_id
    WHERE 1=1
"; // เริ่ม Query แบบไม่มีเงื่อนไข

$params = []; // เตรียมอาร์เรย์สำหรับเก็บพารามิเตอร์

// เพิ่มเงื่อนไขถ้ามีการกรองอำเภอ
if ($filter_amphure) {
    $query .= " AND am.amphure_id = :amphure";
    $params[':amphure'] = $filter_amphure; // ผูกค่าพารามิเตอร์
}

// เพิ่มเงื่อนไขถ้ามีการกรองตำบล
if ($filter_thambon) {
    $query .= " AND t.thambon_id = :thambon";
    $params[':thambon'] = $filter_thambon; // ผูกค่าพารามิเตอร์
}

$query .= " ORDER BY c.customer_id"; // จัดเรียงผลลัพธ์

// เตรียมและรัน Query
$stmt = $pdo->prepare($query);
$stmt->execute($params); // ส่งพารามิเตอร์ไปใน Query
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC); // ดึงข้อมูลผลลัพธ์


$filter_amphure = isset($_GET['amphure']) ? $_GET['amphure'] : null;
$filter_thambon = isset($_GET['thambon']) ? $_GET['thambon'] : null;

$query = "
    SELECT c.customer_id, c.customer_name, c.customer_type, c.customer_phone, c.customer_status, 
           c.create_at, c.update_at, a.address_text, a.amphure_id, a.thambon_id, 
           am.amphure_name, t.thambon_name
    FROM customer c
    JOIN address a ON c.address_id = a.address_id
    JOIN amphure am ON a.amphure_id = am.amphure_id
    JOIN thambon t ON a.thambon_id = t.thambon_id
    WHERE 1=1
";

$params = [];

if ($filter_amphure) {
    $query .= " AND am.amphure_id = :amphure";
    $params[':amphure'] = $filter_amphure;
}

if ($filter_thambon) {
    $query .= " AND t.thambon_id = :thambon";
    $params[':thambon'] = $filter_thambon;
}

$query .= " ORDER BY c.customer_id";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch amphures for dropdown
$amphures_stmt = $pdo->query("SELECT * FROM amphure ORDER BY amphure_name");
$amphures = $amphures_stmt->fetchAll(PDO::FETCH_ASSOC);

// Function to get thambons by amphure_id
function getThambonsByAmphure($pdo, $amphure_id) {
    $stmt = $pdo->prepare("SELECT * FROM thambon WHERE amphure_id = ? ORDER BY thambon_name");
    $stmt->execute([$amphure_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
 
$search = isset($_GET['search']) ? $_GET['search'] : '';

// สร้าง Query สำหรับการค้นหา
$query = "
    SELECT c.customer_id, c.customer_name, c.customer_type, c.customer_phone, c.customer_status, 
           c.create_at, c.update_at, a.address_text, a.amphure_id, a.thambon_id, 
           am.amphure_name, t.thambon_name
    FROM customer c
    JOIN address a ON c.address_id = a.address_id
    JOIN amphure am ON a.amphure_id = am.amphure_id
    JOIN thambon t ON a.thambon_id = t.thambon_id
    WHERE c.customer_name LIKE :search
       OR c.customer_type LIKE :search
       OR c.customer_phone LIKE :search
       OR a.address_text LIKE :search
       OR am.amphure_name LIKE :search
       OR t.thambon_name LIKE :search
    ORDER BY c.customer_id
";

// เตรียมและรัน Query
$stmt = $pdo->prepare($query);
$stmt->execute([':search' => "%$search%"]);
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการข้อมูลลูกค้า</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <?php include '../components/header.php'; ?>
</head>
<body>
    <div class="container mt-5">
        <h2>รายชื่อลูกค้า</h2>

         <!-- ฟอร์มกรองข้อมูลอำเภอและตำบล -->
         <form method="get" action="customer.php" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <!-- Dropdown อำเภอ -->
                    <select name="amphure" id="filterAmphure" class="form-select">
                        <option value="">เลือกอำเภอ</option>
                        <?php foreach ($amphures as $amphure): ?>
                            <option value="<?= $amphure['amphure_id']; ?>" <?= $filter_amphure == $amphure['amphure_id'] ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($amphure['amphure_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <!-- Dropdown ตำบล -->
                    <select name="thambon" id="filterThambon" class="form-select">
                        <option value="">เลือกตำบล</option>
                        <!-- ตำบลจะโหลดผ่าน JavaScript -->
                    </select>
                </div>

                <div class="col-md-4 d-flex">
                    <!-- ปุ่มค้นหา -->
                    <button type="submit" class="btn btn-success me-2">ค้นหา</button>
                    <a href="customer.php" class="btn btn-secondary">ล้างตัวกรอง</a>
                </div>
            </div>
        </form>

        <!-- Success/Error Messages -->
        <?php if(isset($_GET['success'])): ?>
            <script>
                alert('บันทึกข้อมูลสำเร็จ');
            </script>
        <?php endif; ?>
        <?php if(isset($_GET['error'])): ?>
            <script>
                alert('เกิดข้อผิดพลาด: <?php echo htmlspecialchars($_GET['error']); ?>');
            </script>
        <?php endif; ?>

       <!-- ฟอร์มค้นหา -->
<form action="customer.php" method="get" class="d-flex align-items-center mb-3">
    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search..." class="form-control me-2">
    <button type="submit" class="btn btn-success me-2" style="width: 100px;">Search</button>
    <a href="customer.php" class="btn btn-secondary" style="width: 100px;">Back</a>
</form>

<!-- ปุ่มเพิ่มลูกค้า และ ฟอร์ม Import Excel -->
<div class="row mb-4">
    <div class="col-md-8">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#customerModal">เพิ่มลูกค้า</button>
    </div>
    <div class="col-md-4">
        <form action="#" method="post" enctype="multipart/form-data" class="d-flex align-items-center">
            <input type="file" name="excelFile" id="excelFile" accept=".xlsx, .xls" class="form-control me-2" required>
            <button type="submit" class="btn btn-primary">Import Excel</button>
        </form>
    </div>
</div>

        <!-- Customer Table -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ชื่อลูกค้า</th>
                    <th>ประเภท</th>
                    <th>เบอร์โทรศัพท์</th>
                    <th>สถานะ</th>
                    <th>ที่อยู่</th>
                    <th>อำเภอ</th>
                    <th>ตำบล</th>
                    <th>วันที่เพิ่ม</th>
                    <th>วันที่แก้ไข</th>
                    <th>การดำเนินการ</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($customers as $customer): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($customer['customer_name']); ?></td>
                        <td><?php echo htmlspecialchars($customer['customer_type']); ?></td>
                        <td><?php echo htmlspecialchars($customer['customer_phone']); ?></td>
                        <td><?php echo htmlspecialchars($customer['customer_status']); ?></td>
                        <td><?php echo htmlspecialchars($customer['address_text']); ?></td>
                        <td><?php echo htmlspecialchars($customer['amphure_name']); ?></td>
                        <td><?php echo htmlspecialchars($customer['thambon_name']); ?></td>
                        <td><?php echo htmlspecialchars($customer['create_at']); ?></td>
                        <td><?php echo htmlspecialchars($customer['update_at']); ?></td>
                        <td>
                        <button class="btn btn-sm btn-warning edit-customer" 
                          data-bs-toggle="modal" 
                          data-bs-target="#customerModal"
                          data-customer-id="<?= $customer['customer_id']; ?>"
                          data-customer-name="<?= htmlspecialchars($customer['customer_name']); ?>"
                          data-customer-type="<?= htmlspecialchars($customer['customer_type']); ?>"
                          data-customer-phone="<?= htmlspecialchars($customer['customer_phone']); ?>"
                          data-customer-status="<?= htmlspecialchars($customer['customer_status']); ?>"
                          data-address-text="<?= htmlspecialchars($customer['address_text']); ?>"
                          data-amphure-id="<?= htmlspecialchars($customer['amphure_id']); ?>"
                          data-thambon-id="<?= htmlspecialchars($customer['thambon_id']); ?>"
                      >
                          แก้ไข
                      </button>

                            <a href="?delete_customer_id=<?php echo $customer['customer_id']; ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('คุณต้องการลบข้อมูลลูกค้าหรือไม่?')">ลบ</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Customer Modal -->
    <div class="modal fade" id="customerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่ม/แก้ไขข้อมูลลูกค้า</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="customerForm" method="POST">
                    <div class="modal-body">
                        <!-- Hidden input for customer ID (for edit) -->
                        <input type="hidden" name="customer_id" id="customerId">

                        <!-- Customer Name -->
                        <div class="mb-3">
                            <label class="form-label">ชื่อลูกค้า</label>
                            <input type="text" class="form-control" name="customer_name" id="customerName" required>
                        </div>

                        <!-- Customer Type -->
                        <div class="mb-3">
                            <label class="form-label">ประเภท</label>
                            <select class="form-select" name="customer_type" id="customerType" required>
                                <option value="เทศบาลเมือง">เทศบาลเมือง</option>
                                <option value="เทศบาลตำบล">เทศบาลตำบล</option>
                                <option value="อบจ">อบจ</option>
                                <option value="อบต">อบต</option>
                                <option value="บริษัทเอกชน">บริษัทเอกชน</option>
                                <option value="โรงพยาบาล">โรงพยาบาล</option>
                                <option value="วัด">วัด</option>
                                <option value="มูลนิธิ">มูลนิธิ</option>
                                <option value="โรงเรียนประถม">โรงเรียนประถม</option>
                                <option value="โรงเรียนมัธยม">โรงเรียนมัธยม</option>
                                <option value="โรงเรียนเอกชน">โรงเรียนเอกชน</option>
                                <option value="วิทยาลัย">วิทยาลัย</option>
                                <option value="มหาวิทยาลัย">มหาวิทยาลัย</option>
                            </select>
                        </div>

                        <!-- Customer Phone -->
                        <div class="mb-3">
                            <label class="form-label">เบอร์โทรศัพท์</label>
                            <input type="text" class="form-control" name="customer_phone" id="customerPhone" required>
                        </div>

                        <!-- Customer Status -->
                        <div class="mb-3">
                            <label class="form-label">สถานะ</label>
                            <select class="form-select" name="customer_status" id="customerStatus" required>
                                <option value="Active">ใช้งาน</option>
                                <option value="Inactive">ไม่ใช้งาน</option>
                            </select>
                        </div>

                        <!-- Address Text -->
                        <div class="mb-3">
                            <label class="form-label">ที่อยู่</label>
                            <textarea class="form-control" name="address_text" id="addressText" required></textarea>
                        </div>

                        <!-- Amphure Dropdown -->
                        <div class="mb-3">
                            <label class="form-label">อำเภอ</label>
                            <select class="form-select" name="amphure_id" id="amphureId" required>
                                <option value="">เลือกอำเภอ</option>
                                <?php foreach($amphures as $amphure): ?>
                                    <option value="<?php echo $amphure['amphure_id']; ?>">
                                        <?php echo htmlspecialchars($amphure['amphure_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Thambon Dropdown -->
                        <div class="mb-3">
                            <label class="form-label">ตำบล</label>
                            <select class="form-select" name="thambon_id" id="thambonId" required>
                                <option value="">เลือกตำบล</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                        <button type="submit" class="btn btn-primary">บันทึก</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
    // กดปุ่ม Edit แล้วโหลดข้อมูลอำเภอและตำบล
    const editButtons = document.querySelectorAll('.edit-customer');
    editButtons.forEach(button => {
        button.addEventListener('click', function () {
            // ตั้งค่าข้อมูลใน modal
            document.getElementById('customerId').value = this.dataset.customerId;
            document.getElementById('customerName').value = this.dataset.customerName;
            document.getElementById('customerType').value = this.dataset.customerType;
            document.getElementById('customerPhone').value = this.dataset.customerPhone;
            document.getElementById('customerStatus').value = this.dataset.customerStatus;
            document.getElementById('addressText').value = this.dataset.addressText;

            const amphureId = this.dataset.amphureId;
            const thambonId = this.dataset.thambonId;

            // ตั้งค่าอำเภอใน dropdown
            document.getElementById('amphureId').value = amphureId;

            // ดึงข้อมูลตำบลสำหรับอำเภอที่เลือก
            fetchThambons(amphureId, thambonId);
        });
    });

    // เมื่อเปลี่ยนอำเภอใน modal
    document.getElementById('amphureId').addEventListener('change', function () {
        const amphureId = this.value;
        fetchThambons(amphureId);
    });

    // ฟังก์ชันดึงข้อมูลตำบลจากเซิร์ฟเวอร์
    function fetchThambons(amphureId, selectedThambonId = null) {
        if (!amphureId) {
            document.getElementById('thambonId').innerHTML = '<option value="">เลือกตำบล</option>';
            return;
        }

        // ดึงข้อมูลตำบลจาก get_thambons.php
        fetch(`get_thambons.php?amphure_id=${amphureId}`)
            .then(response => response.json())
            .then(data => {
                const thambonSelect = document.getElementById('thambonId');
                thambonSelect.innerHTML = '<option value="">เลือกตำบล</option>';

                data.forEach(thambon => {
                    const option = document.createElement('option');
                    option.value = thambon.thambon_id;
                    option.textContent = thambon.thambon_name;

                    // ถ้าตำบลถูกเลือกไว้ก่อนหน้า ให้ตั้งค่า selected
                    if (selectedThambonId && thambon.thambon_id === selectedThambonId) {
                        option.selected = true;
                    }

                    thambonSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching thambons:', error));
    }

    // ฟิลเตอร์ตำบลในหน้าแรก
    document.getElementById('filterAmphure').addEventListener('change', function () {
        const amphureId = this.value;

        fetch(`get_thambons.php?amphure_id=${amphureId}`)
            .then(response => response.json())
            .then(data => {
                const thambonSelect = document.getElementById('filterThambon');
                thambonSelect.innerHTML = '<option value="">เลือกตำบล</option>';

                data.forEach(thambon => {
                    const option = document.createElement('option');
                    option.value = thambon.thambon_id;
                    option.textContent = thambon.thambon_name;
                    thambonSelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching thambons:', error));
    });
});
document.addEventListener('DOMContentLoaded', function () {
            const amphureSelect = document.getElementById('filterAmphure');
            const thambonSelect = document.getElementById('filterThambon');

            // โหลดตำบลเมื่อเลือกอำเภอ
            amphureSelect.addEventListener('change', function () {
                const amphureId = this.value;
                fetch(`get_thambons.php?amphure_id=${amphureId}`)
                    .then(response => response.json())
                    .then(data => {
                        thambonSelect.innerHTML = '<option value="">เลือกตำบล</option>';
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.thambon_id;
                            option.textContent = item.thambon_name;
                            thambonSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error loading thambons:', error));
            });

            // ตั้งค่า dropdown ตำบลในกรณีแก้ไข (ถ้ามี)
            <?php if ($filter_amphure): ?>
                fetch(`get_thambons.php?amphure_id=<?= $filter_amphure ?>`)
                    .then(response => response.json())
                    .then(data => {
                        thambonSelect.innerHTML = '<option value="">เลือกตำบล</option>';
                        data.forEach(item => {
                            const option = document.createElement('option');
                            option.value = item.thambon_id;
                            option.textContent = item.thambon_name;
                            if (item.thambon_id === '<?= $filter_thambon ?>') {
                                option.selected = true;
                            }
                            thambonSelect.appendChild(option);
                        });
                    });
            <?php endif; ?>
        });
</script>

</body>
</html>