

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monthly Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
    <?php include '../components/header.php'; ?>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Monthly Report</h1>
        <div class="row g-3">
    <div class="col-md-4">
        <label for="reportYear" class="form-label">เลือกปี</label>
        <select class="form-select" id="reportYear" name="reportYear">
            <option value="">All Years</option>
            <?php for ($year = date('Y'); $year >= date('Y') - 10; $year--): ?>
                <option value="<?php echo $year; ?>"><?php echo $year; ?></option>
            <?php endfor; ?>
        </select>
    </div>
    <div class="col-md-4">
        <label for="reportMonth" class="form-label">เลือกเดือน</label>
        <select class="form-select" id="reportMonth" name="reportMonth">
            <option value="">ทั้งหมด</option>
            <option value="01">มกราคม</option>
            <option value="02">กุมภาพันธ์</option>
            <option value="03">มีนาคม</option>
            <option value="04">เมษายน</option>
            <option value="05">พฤษภาคม</option>
            <option value="06">มิถุนายน</option>
            <option value="07">กรกฏาคม</option>
            <option value="08">สิงหาคม</option>
            <option value="09">กันยายน</option>
            <option value="10">ตุลาคม</option>
            <option value="11">พฤศจิกายน</option>
            <option value="12">ธันวาคม</option>
        </select>
    </div>
    <div class="col-md-4">
        <label for="reportType" class="form-label">เลือกหัวข้อรายงาน</label>
        <select class="form-select" id="reportType" name="reportType">
        <option value="">เลือกรายงาน</option>
            <option value="">จำนวนลูกค้าทั้งหมดและรายได้ทั้งหมด</option>
            <option value="summary">จำนวนลูกค้าตามประเภทกลุ่มลูกค้า และรายได้ทั้งหมด</option>
            <option value="customers_by_type">จำนวนตามประเภทบริการ จำนวนลูกค้าและรายได้ทั้งหมด</option>
        </select>
    </div>
</div>
<div class="col-md-12 d-flex justify-content-end mt-3">
    <button type="button" class="btn btn-primary" id="filterReportByType">Filter Report</button>
</div>
<script>
   document.addEventListener('DOMContentLoaded', function () {
    const reportYearInput = document.getElementById('reportYear');
    const reportMonthSelect = document.getElementById('reportMonth');
    const reportTypeSelect = document.getElementById('reportType');
    const filterReportByTypeButton = document.getElementById('filterReportByType');

    // ฟังก์ชันกรองรายงาน
    filterReportByTypeButton.addEventListener('click', function () {
        const selectedYear = reportYearInput.value.trim();
        const selectedMonth = reportMonthSelect.value;
        const selectedType = reportTypeSelect.value;

        // ตรวจสอบความถูกต้องของปี
        if (selectedYear && (isNaN(selectedYear) || selectedYear < 1900 || selectedYear > 3000)) {
            alert('Please enter a valid year between 1900 and 3000.');
            return;
        }

        if (!selectedYear && !selectedMonth && !selectedType) {
            alert('Please select at least a year, month, or report type.');
            return;
        }

        // ตัวอย่างการส่งคำขอไปยังเซิร์ฟเวอร์
        const queryParams = new URLSearchParams();
        if (selectedYear) queryParams.append('year', selectedYear);
        if (selectedMonth) queryParams.append('month', selectedMonth);
        if (selectedType) queryParams.append('type', selectedType);

        fetch(`/api/get_report?${queryParams.toString()}`)
            .then(response => response.json())
            .then(data => {
                // Logic for updating the report display based on data
                console.log('Report Data:', data);
                alert('Report updated. Check console for details.');
            })
            .catch(error => {
                console.error('Error fetching report:', error);
                alert('An error occurred while fetching the report.');
            });
    });
});

</script>
</body>
</html>

