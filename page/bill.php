<!-- function_bill.php -->
<?php
require_once '../config/condb.php';
require_once '../functions/bill_functions.php'; // รวมไฟล์ฟังก์ชัน

// เริ่ม session เพื่อใช้สำหรับข้อความแสดงผล (success/error)
session_start();

// ดึงข้อมูลลูกค้าเพื่อใช้ใน dropdown และตรวจสอบว่ามี customer_id จาก GET หรือไม่
$customer_id = isset($_GET['customer_id']) ? intval($_GET['customer_id']) : null;

// เพิ่มการรับค่าจากฟอร์มค้นหาด้วย bill_number
$bill_number = isset($_GET['bill_number']) ? trim($_GET['bill_number']) : null;

try {
    // หากมี customer_id ให้ดึงชื่อลูกค้าก่อน
    $customer_name = 'ไม่พบข้อมูลลูกค้า';
    if ($customer_id) {
        $customer_name = get_customer_name($pdo, $customer_id);
    }

    // เริ่มสร้าง SQL Query
    $sql = "
    SELECT 
        b.bill_id,
        b.bill_number,
        b.bill_status,
        b.bill_type,
        b.all_price,
        b.start_date,      -- เพิ่ม start_date
        b.end_date,        -- เพิ่ม end_date
        c.customer_name,
        bg.group_name,
        bg.group_type,
        bg.group_price,
        bg.group_price_a,
        bg.group_price_b,
        GROUP_CONCAT(bgi.group_info_name SEPARATOR ', ') as group_info_name,
        bn.note_text,
        s.service_type,      -- เลือก service_type จากตาราง services
        s.equipment_service  -- เลือก equipment_service จากตาราง services
    FROM 
        bill b
    LEFT JOIN 
        customer c ON b.customer_id = c.customer_id
    LEFT JOIN 
        bill_group bg ON b.bill_id = bg.bill_id
    LEFT JOIN 
        bill_group_info bgi ON bg.bill_group_id = bgi.bill_group_id
    LEFT JOIN 
        bill_notes bn ON b.bill_id = bn.bill_id
    LEFT JOIN 
        services s ON b.bill_id = s.bill_id  -- JOIN กับตาราง services
";

       // เตรียมเงื่อนไขและพารามิเตอร์สำหรับ WHERE
       $conditions = [];
       $params = [];
   
       if ($customer_id) {
           $conditions[] = "b.customer_id = :customer_id";
           $params[':customer_id'] = $customer_id;
       }
   
       if ($bill_number) {
           $conditions[] = "b.bill_number LIKE :bill_number";
           $params[':bill_number'] = '%' . $bill_number . '%';
       }
   
       // **ย้ายโค้ดส่วนนี้ขึ้นมา**
       if (isset($_GET['bill_status']) && !empty($_GET['bill_status'])) {
           $conditions[] = "b.bill_status = :bill_status";
           $params[':bill_status'] = $_GET['bill_status'];
       }
   
       if (isset($_GET['bill_type']) && !empty($_GET['bill_type'])) {
           $conditions[] = "b.bill_type = :bill_type";
           $params[':bill_type'] = $_GET['bill_type'];
       }
   
       // ถ้ามีเงื่อนไข ให้เพิ่มเข้าไปใน SQL Query
       if (!empty($conditions)) {
           $sql .= " WHERE " . implode(" AND ", $conditions);
       }
   
       $sql .= " GROUP BY b.bill_id, bg.bill_group_id
               ORDER BY b.bill_create_at DESC, bg.bill_group_id";
   
       // รัน SQL Query
       $stmt = $pdo->prepare($sql);
       foreach ($params as $key => $value) {
           if ($key === ':customer_id') {
               $stmt->bindValue($key, $value, PDO::PARAM_INT);
           } else {
               $stmt->bindValue($key, $value, PDO::PARAM_STR);
           }
       }
       $stmt->execute();
       $bills = $stmt->fetchAll(PDO::FETCH_ASSOC);
   
   } catch (Exception $e) {
       die("เกิดข้อผิดพลาด: " . $e->getMessage());
   }
// ประมวลผลการส่งฟอร์มสร้างบิลใหม่
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add_bill') {
    try {
        // ตรวจสอบและรับข้อมูลจากฟอร์ม
        if (empty($_POST['bill_number'])) {
            throw new Exception("กรุณากรอกหมายเลขบิล");
        }
        $bill_number_post = trim($_POST['bill_number']);

        $bill_type = $_POST['bill_type'] ?? null;
        $customer_id_post = $_POST['customer_id'] ?? null; // รับ customer_id จากฟอร์ม
        $groups = $_POST['groups'] ?? [];

        // ตรวจสอบข้อมูลที่จำเป็น
        if (empty($bill_type)) {
            throw new Exception("กรุณาเลือกประเภทบิล");
        }

        if (empty($customer_id_post)) {
            throw new Exception("กรุณาเลือกลูกค้า");
        }

        // สร้างบิลใหม่
        create_bill($pdo, $bill_number_post, $bill_type, $customer_id_post, $groups);

        // ตั้งข้อความแสดงความสำเร็จ
        $_SESSION['success'] = "สร้างบิลใหม่เรียบร้อยแล้ว";

        // รีไดเรกไปยังหน้า bill.php พร้อมกับ customer_id หากมี
        if ($customer_id_post) {
            header("Location: bill.php?customer_id=" . $customer_id_post);
        } else {
            header("Location: bill.php");
        }
        exit();
    } catch (Exception $e) {
        // ตั้งข้อความแสดงข้อผิดพลาด
        $_SESSION['error'] = "เกิดข้อผิดพลาด: " . $e->getMessage();
    }
}

// ดึงข้อมูลลูกค้าเพื่อใช้ใน dropdown
try {
    $customers = get_customers($pdo);
} catch (Exception $e) {
    die("เกิดข้อผิดพลาดในการดึงข้อมูลลูกค้า: " . $e->getMessage());
}

//  เพิ่มส่วนสำหรับบันทึกหมายเหตุ
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_note') {
    try {
        $bill_id = $_POST['bill_id'];
        $note_text = $_POST['note_text'];

        // ตรวจสอบว่ามีหมายเหตุสำหรับ bill_id นี้หรือไม่
        $sql = "SELECT COUNT(*) FROM bill_notes WHERE bill_id = :bill_id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':bill_id', $bill_id, PDO::PARAM_INT);
        $stmt->execute();
        $noteExists = $stmt->fetchColumn() > 0;

        if ($noteExists) {
            //  อัพเดตหมายเหตุที่มีอยู่แล้ว
            $sql = "UPDATE bill_notes SET note_text = :note_text WHERE bill_id = :bill_id";
        } else {
            //  สร้างหมายเหตุใหม่
            $sql = "INSERT INTO bill_notes (bill_id, note_text) VALUES (:bill_id, :note_text)";
        }

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':bill_id', $bill_id, PDO::PARAM_INT);
        $stmt->bindParam(':note_text', $note_text, PDO::PARAM_STR);
        $stmt->execute();

        //  ส่ง response กลับเป็น JSON
        echo json_encode(['status' => 'success', 'message' => 'บันทึกหมายเหตุเรียบร้อยแล้ว']);

    } catch (Exception $e) {
        //  ส่ง response กลับเป็น JSON
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $e->getMessage()]);
    }
    exit(); //  หยุดการทำงานของ script หลังจากบันทึกหมายเหตุ
}

?>
<!DOCTYPE html>
<html lang="th">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>รายการบิล</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
  <?php include '../components/header.php'; ?>
  <style>
    body {
      padding-top: 80px;
      background-color: #f8f9fa;
      /* หรือสีเหลืองอ่อนอื่นๆ ที่คุณต้องการ */
    }

    .toggle-details svg {
      transition: transform 0.3s ease;
    }

    /* เพิ่มเส้นคั่นใต้ tr ที่มี class last-group-row */
    .last-group-row td {
      border-bottom: 1px solid #dee2e6;
      /* ใช้สีเดียวกับ border ของ table */
    }

    /* Custom table style */
    .custom-table {
      border-collapse: separate;
      border-spacing: 0 10px;
      border-radius: 10px;
      border-radius: 12px;
      overflow: hidden; 
    }

    .custom-table th,
    .custom-table td {
      border: none;
      padding: 10px 11px;
      font-size: 14px;
    }

    .custom-table th {
      background-color: #f5f5f5;
      font-weight: 600;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .custom-table tbody tr {
      background-color: white;
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .custom-table tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    .status-badge {
      padding: 5px 10px;
      border-radius: 5px;
      font-weight: 500;
      text-transform: uppercase;
    }

    .status-badge.success {
      background-color: #d4edda;
      color: #155724;
    }

    .status-badge.danger {
      background-color: #f8d7da;
      color: #721c24;
    }

    .btn {
      border-radius: 10px;
      /* ปรับค่าตามความโค้งมนที่ต้องการ */
    }
  </style>
</head>

<body>
  <div class="container mt-5">
    <h2 class="mb-4">
      <?php
      if ($customer_id) {
        $bill_count = count_unique_bills($bills);
        echo "บิลของลูกค้า: " . htmlspecialchars($customer_name) . " (ทั้งหมด $bill_count บิล)";
      } else {
        echo "รายการบิลทั้งหมด";
      }
      ?>
    </h2>

    <div class="d-flex flex-wrap align-items-center mb-4 gap-2">
        
      <form method="GET" action="bill.php" class="d-flex flex-wrap align-items-center gap-2">
        <?php if ($customer_id): ?>
          <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($customer_id); ?>">
        <?php endif; ?>
        <input type="text" name="bill_number" class="form-control rounded-pill" placeholder="ค้นหาเลขบิล" value="<?php echo htmlspecialchars($bill_number); ?>" style="max-width: 200px;">

        <select name="bill_status" class="form-select rounded-pill" style="max-width: 150px;">
          <option value="">สถานะทั้งหมด</option>
          <option value="Active" <?php echo isset($_GET['bill_status']) && $_GET['bill_status'] == 'Active' ? 'selected' : ''; ?>>Active</option>
          <option value="Inactive" <?php echo isset($_GET['bill_status']) && $_GET['bill_status'] == 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
        </select>

        <select name="bill_type" class="form-select rounded-pill" style="max-width: 150px;">
          <option value="">ประเภททั้งหมด</option>
          <option value="CIP+" <?php echo isset($_GET['bill_type']) && $_GET['bill_type'] == 'CIP+' ? 'selected' : ''; ?>>CIP+</option>
          <option value="Special bill" <?php echo isset($_GET['bill_type']) && $_GET['bill_type'] == 'Special bill' ? 'selected' : ''; ?>>Special bill</option>
          <option value="N1" <?php echo isset($_GET['bill_type']) && $_GET['bill_type'] == 'N1' ? 'selected' : ''; ?>>N1</option>
        </select>

        <button type="submit" class="btn btn-primary rounded-pill">ค้นหา</button>
        <?php if ($bill_number || $customer_id || isset($_GET['bill_status']) || isset($_GET['bill_type'])): ?>
          <a href="bill.php<?php echo $customer_id ? '?customer_id=' . htmlspecialchars($customer_id) : ''; ?>" class="btn btn-light rounded-pill">ล้าง</a>
        <?php endif; ?>
      </form>

      <div class="ms-auto">
        <button type="button" class="btn btn-warning rounded-pill" data-bs-toggle="modal" data-bs-target="#addBillModal">
          <i class="fa-regular fa-pen-to-square"></i> สร้างบิลใหม่
        </button>
        <a href="customer.php" class="btn btn-outline-secondary rounded-pill">ย้อนกลับ</a>
      </div>
    </div>

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

    <?php if (!empty($bills)): ?>
      <table class="table table-sm table-bordered custom-table">
        <thead>
          <tr>
            <th>เลขบิล</th>
            <th>สถานะ</th>
            <th>ประเภทบิล</th>
            <th>ประเภทบริการ</th>
            <th>ลักษณะการบริการ</th>
            <th>ยอดรวม (บาท)</th>
            <th>ชื่อลูกค้า</th>
            <th>ข้อมูลกลุ่ม</th>
            <th>หมายเหตุ</th>
            <th>วันที่เริ่มสัญญา</th>
            <th>วันที่สิ้นสุดสัญญา</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $current_bill = null;
          $group_data = [];

          foreach ($bills as $bill) {
            if ($current_bill !== $bill['bill_number']) {
              $current_bill = $bill['bill_number'];
              $group_data[$current_bill] = [];
            }
            $group_data[$current_bill][] = $bill;
          }

          foreach ($group_data as $bill_number => $bills_in_group) :
            $first_bill = reset($bills_in_group);
          ?>
            <tr class="main-row" data-bill-number="<?php echo htmlspecialchars($first_bill['bill_number']); ?>">
              <td>
                <div class="d-flex align-items-center gap-2">
                  <button class="btn btn-sm btn-outline-secondary toggle-details" data-bill-number="<?php echo htmlspecialchars($first_bill['bill_number']); ?>" data-show="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                      <path d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z" />
                    </svg>
                  </button>
                  <?php echo htmlspecialchars($first_bill['bill_number']); ?>
                </div>
              </td>

              <td class="bill-details" style="display: table-cell;">
                <span class="status-badge <?php echo $first_bill['bill_status'] == 'Active' ? 'success' : 'danger'; ?>">
                  <?php echo htmlspecialchars($first_bill['bill_status']); ?>
                </span>
              </td>
              <td class="bill-details" style="display: table-cell;">
                <?php echo htmlspecialchars($first_bill['bill_type']); ?>
              </td>
              <td class="bill-details" style="display: table-cell;">
                <?php echo htmlspecialchars($first_bill['service_type']); ?>
              </td>
              <td class="bill-details" style="display: table-cell;">
                <?php echo htmlspecialchars($first_bill['equipment_service']); ?>
              </td>
              <td class="bill-details" style="display: table-cell;">
                <?php echo number_format($first_bill['all_price'], 2); ?>
              </td>
              <td class="bill-details" style="display: table-cell;">
                <?php echo htmlspecialchars($first_bill['customer_name']); ?>
              </td>

              <td class="bill-details" style="display: table-cell;">
                <table class="table table-borderless">
                  <tbody>
                    <?php
                    $group_count = 0;
                    foreach ($bills_in_group as $index => $bill) :
                      $group_count++;
                      $is_last_group = ($index == count($bills_in_group) - 1);
                    ?>
                      <tr>
                        <td><strong>กลุ่มลำดับที่:</strong></td>
                        <td>
                          <?php echo isset($bill['group_type']) ? htmlspecialchars($bill['group_type']) : '-'; ?>
                        </td>
                      </tr>
                      <tr>
                        <td><strong>ชื่อกลุ่ม:</strong></td>
                        <td>
                          <?php echo htmlspecialchars($bill['group_name']); ?>
                        </td>
                      </tr>
                      <tr>
                        <td><strong>ราคา:</strong></td>
                        <td>
                          <?php echo number_format($bill['group_price'], 2); ?> บาท
                        </td>
                      </tr>
                      <tr>
                        <td><strong>แพ็คเกจหลัก:</strong></td>
                        <td>
                          <?php echo number_format($bill['group_price_a'], 2); ?> บาท
                        </td>
                      </tr>
                      <tr>
                        <td><strong>ICT Solution:</strong></td>
                        <td>
                          <?php echo number_format($bill['group_price_b'], 2); ?> บาท
                        </td>
                      </tr>
                      <tr <?php if ($is_last_group) echo 'class="last-group-row"'; ?>>
                        <td colspan="2">
                          <?php echo htmlspecialchars($bill['group_info_name']); ?>
                        </td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </td>

              <td class="bill-details" style="display: table-cell;">
                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill" data-bs-toggle="modal" data-bs-target="#noteModal-<?php echo $first_bill['bill_id']; ?>">
                  หมายเหตุ
                </button>

                <div class="modal fade" id="noteModal-<?php echo $first_bill['bill_id']; ?>" tabindex="-1" aria-labelledby="noteModalLabel-<?php echo $first_bill['bill_id']; ?>" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="noteModalLabel-<?php echo $first_bill['bill_id']; ?>">หมายเหตุสำหรับบิล <?php echo htmlspecialchars($first_bill['bill_number']); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <textarea class="form-control" rows="5"><?php echo htmlspecialchars($first_bill['note_text'] ?? ''); ?></textarea>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        <button type="button" class="btn btn-primary" onclick="saveNote(<?php echo $first_bill['bill_id']; ?>)">บันทึก</button>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
              <td class="bill-details date-details" style="display: ;">
                <?php echo htmlspecialchars($first_bill['start_date']); ?>
              </td>
              <td class="bill-details date-details" style="display: ;">
                <?php echo htmlspecialchars($first_bill['end_date']); ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    <?php else : ?>
      <div class="alert alert-info">ไม่พบข้อมูล</div>
    <?php endif; ?>

    <?php include '../components/modals/add_bill_modal.php'; ?>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../assets/js/bills.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.toggle-details').forEach(button => {
        button.addEventListener('click', function() {
          const billNumber = this.dataset.billNumber;
          const isShowing = this.dataset.show === 'true';

          const mainRow = document.querySelector(`tr.main-row[data-bill-number="${billNumber}"]`);

          const icon = this.querySelector('svg');

          const billDetails = mainRow.querySelectorAll('.bill-details');
          billDetails.forEach(detail => {
            detail.style.display = isShowing ? 'none' : 'table-cell';
          });

          icon.style.transform = isShowing ? 'rotate(180deg)' : '';
          this.dataset.show = (!isShowing).toString();
        });
      });
    });

    function saveNote(billId) {
      const modal = document.getElementById(`noteModal-${billId}`);
      const noteText = modal.querySelector('textarea').value;

      fetch('bill.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: `action=save_note&bill_id=${billId}&note_text=${encodeURIComponent(noteText)}`
        })
        .then(response => response.json())
        .then(data => {
          if (data.status === 'success') {
            alert(data.message);

            const textarea = modal.querySelector('textarea');
            textarea.value = noteText;

            modal.querySelector('.btn-close').click();
          } else {
            alert(data.message);
          }
        })
        .catch(error => {
          alert('บันทึกหมายเหตุ');
        });
    }
  </script>
</body>

</html>