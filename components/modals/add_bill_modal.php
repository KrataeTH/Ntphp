<?php
// ตรวจสอบว่ามี customer_id จาก GET หรือไม่
$modal_customer_id = isset($_GET['customer_id']) ? intval($_GET['customer_id']) : null;

// ถ้ามี customer_id ให้ดึงชื่อลูกค้า
if ($modal_customer_id) {
    try {
        $customer_name = get_customer_name($pdo, $modal_customer_id);
    } catch (Exception $e) {
        $customer_name = 'ไม่พบข้อมูลลูกค้า';
    }
}

// ดึงข้อมูล ENUM ของ service_type
$service_types = [];
try {
    $stmt = $pdo->query("SHOW COLUMNS FROM services LIKE 'service_type'");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $enum_values = str_replace(["enum(", ")", "'"], "", $row['Type']);
        $service_types = explode(",", $enum_values); // แปลง ENUM เป็น array
    }
} catch (Exception $e) {
    echo "Error fetching service_type ENUM: " . $e->getMessage();
}

// ดึงข้อมูล ENUM ของ equipment_service
$equipment_service = [];
try {
    $stmt = $pdo->query("SHOW COLUMNS FROM services LIKE 'equipment_service'");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $enum_values = str_replace(["enum(", ")", "'"], "", $row['Type']);
        $equipment_service = explode(",", $enum_values); // แปลง ENUM เป็น array
    }
} catch (Exception $e) {
    echo "Error fetching equipment_service ENUM: " . $e->getMessage();
}

?>
<style>
/* Datepicker */
.bootstrap-datetimepicker-widget {
  border: none; 
  box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}
.bootstrap-datetimepicker-widget .datepicker-days td.today {
  background-color: #ffc107; /* สีเหลือง */
  border-color: #ffc107;
}
.bootstrap-datetimepicker-widget .datepicker-days td.active,
.bootstrap-datetimepicker-widget .datepicker-days td.active:hover {
  background-color: #17a2b8; /* สีฟ้า */
  border-color: #17a2b8;
}
</style>

<div class="modal fade" id="addBillModal" tabindex="-1" aria-labelledby="addBillModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="billForm" method="POST" action="">
        <input type="hidden" name="action" value="add_bill">
        <?php if ($modal_customer_id): ?>
          <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($modal_customer_id); ?>">
        <?php endif; ?>

        <div class="modal-header bg-warning text-white" style="border-bottom: none;">
          <h5 class="modal-title" id="addBillModalLabel">สร้างบิลใหม่</h5>
          <button type="button" class="btn-close ms-auto" data-bs-dismiss="modal" aria-label="ปิด"></button>
        </div>

        <div class="modal-body p-4">

          <div class="mb-3">
            <label for="bill_number" class="form-label">หมายเลขบิล</label>
            <input type="text" class="form-control border-0 shadow-sm" id="bill_number" name="bill_number" required>
          </div>

          <?php if ($modal_customer_id): ?>
            <div class="mb-3">
              <label class="form-label">ลูกค้า</label>
              <p class="form-control-plaintext"><?php echo htmlspecialchars($customer_name); ?></p>
            </div>
          <?php else: ?>
            <div class="mb-3">
              <label for="customer_id" class="form-label">เลือกลูกค้า</label>
              <select class="form-select border-0 shadow-sm" id="customer_id" name="customer_id" required>
                <option value="">เลือกลูกค้า</option>
                <?php foreach ($customers as $customer): ?>
                  <option value="<?php echo htmlspecialchars($customer['customer_id']); ?>">
                    <?php echo htmlspecialchars($customer['customer_name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
          <?php endif; ?>

          <div class="mb-3">
            <label class="form-label">ประเภทบิล</label>
            <div class="btn-group w-100" role="group" aria-label="ประเภทบิล">
              <input type="radio" class="btn-check" name="bill_type" id="type1" value="CIP+" autocomplete="off" required>
              <label class="btn btn-outline-warning rounded-pill" for="type1">CIP+</label>

              <input type="radio" class="btn-check" name="bill_type" id="type2" value="Special bill" autocomplete="off">
              <label class="btn btn-outline-warning rounded-pill" for="type2">Special bill</label>

              <input type="radio" class="btn-check" name="bill_type" id="type3" value="N1" autocomplete="off">
              <label class="btn btn-outline-warning rounded-pill" for="type3">N1</label>
            </div>
          </div>

          <div class="mb-3">
            <label for="service_type" class="form-label">ประเภทบริการ</label>
            <select class="form-select border-0 shadow-sm" id="service_type" name="service_type" required>
              <option value="">เลือกประเภทบริการ</option>
              <?php foreach ($service_types as $type): ?>
                <option value="<?php echo htmlspecialchars($type); ?>">
                  <?php echo htmlspecialchars($type); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
            <label for="equipment_service" class="form-label">ลักษณะที่บริการอุปกรณ์</label>
            <select class="form-select border-0 shadow-sm" id="equipment_service" name="equipment_service" required>
              <option value="">เลือกลักษณะบริการอุปกรณ์</option>
              <?php foreach ($equipment_service as $type): ?>
                <option value="<?php echo htmlspecialchars($type); ?>">
                  <?php echo htmlspecialchars($type); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="mb-3">
  <label for="startDate" class="form-label">วันเริ่มต้นสัญญา</label>
  <div class="input-group date" id="datepicker" data-target-input="nearest">
    <input type="text" class="form-control datetimepicker-input border-0 shadow-sm" data-target="#datepicker" id="startDate" name="start_date" required />
    <span class="input-group-text" data-target="#datepicker" data-toggle="datetimepicker">
      <i class="fa fa-calendar"></i>
    </span>
  </div>
</div>

          <div class="mb-3">
            <label for="contractDuration" class="form-label">ระยะเวลาสัญญา (เดือน)</label>
            <input type="number" id="contractDuration" name="contract_duration" class="form-control border-0 shadow-sm" min="1" required>
          </div>

          <div class="mb-3">
            <label for="endDate" class="form-label">วันที่คาดว่าจะสิ้นสุดสัญญา</label>
            <input type="text" id="endDate" name="end_date" class="form-control border-0 shadow-sm" readonly>
          </div>

          <div class="mb-3 d-grid">
            <button type="button" id="calculateEndDate" class="btn btn-secondary rounded-pill">คำนวณวันสิ้นสุด</button>
          </div>

          <div class="mb-3">
            <label for="group_count" class="form-label">จำนวนกลุ่ม</label>
            <select class="form-select border-0 shadow-sm" id="group_count" name="group_count" required>
              <?php for ($i = 1; $i <= 5; $i++): ?>
                <option value="<?php echo $i; ?>"><?php echo $i; ?> กลุ่ม</option>
              <?php endfor; ?>
            </select>
          </div>

          <div id="groupContainer"></div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">ปิด</button>
          <button type="submit" class="btn btn-warning rounded-pill">สร้างบิล</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.39.0/js/tempusdominus-bootstrap-4.min.js"></script>

<script type="text/javascript">
  $(function () {
    $('#datepicker').datetimepicker({
      format: 'L', // แสดงผลเฉพาะวันที่
      icons: {
        time: 'fa fa-clock',
        date: 'fa fa-calendar',
        up: 'fa fa-arrow-up',
        down: 'fa fa-arrow-down',
        previous: 'fa fa-chevron-left',
        next: 'fa fa-chevron-right',
        today: 'fa fa-calendar-check-o',
        clear: 'fa fa-trash',
        close: 'fa fa-times'
      }
    });
  });


    document.getElementById('calculateEndDate').addEventListener('click', function() {
      const startDate = document.getElementById('startDate').value;
      const duration = parseInt(document.getElementById('contractDuration').value, 10);

      if (!startDate || isNaN(duration) || duration <= 0) {
        alert('กรุณาระบุวันเริ่มต้นและระยะเวลาสัญญาให้ถูกต้อง');
        return;
      }

      const startDateObj = new Date(startDate);
      startDateObj.setMonth(startDateObj.getMonth() + duration);

      const endDate = startDateObj.toISOString().split('T')[0];
      document.getElementById('endDate').value = endDate;
    });

</script>
