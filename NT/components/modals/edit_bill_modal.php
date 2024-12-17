<!-- components/modals/edit_bill_modal.php -->
<div class="modal fade" id="editBillModal" tabindex="-1" aria-labelledby="editBillModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="editBillForm" method="POST" action="../functions/bill_functions.php">
                <input type="hidden" name="action" value="edit_bill">
                <input type="hidden" name="bill_id" id="edit_bill_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBillModalLabel">แก้ไขบิล</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                </div>
                <div class="modal-body">
                    <!-- หมายเลขบิล -->
                    <div class="mb-3">
                        <label for="edit_bill_number" class="form-label">หมายเลขบิล</label>
                        <input type="text" class="form-control" id="edit_bill_number" name="bill_number" required>
                    </div>

                    <!-- ลูกค้า -->
                    <div class="mb-3">
                        <label for="edit_customer_id" class="form-label">เลือกลูกค้า</label>
                        <select class="form-select" id="edit_customer_id" name="customer_id" required>
                            <option value="">เลือกลูกค้า</option>
                            <?php foreach ($customers as $customer): ?>
                                <option value="<?php echo $customer['customer_id']; ?>">
                                    <?php echo htmlspecialchars($customer['customer_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- ประเภทบิล -->
                    <div class="mb-3">
                        <label class="form-label">ประเภทบิล</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="bill_type" id="edit_type1" value="ประเภท1" required>
                                <label class="form-check-label" for="edit_type1">ประเภท 1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="bill_type" id="edit_type2" value="ประเภท2">
                                <label class="form-check-label" for="edit_type2">ประเภท 2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="bill_type" id="edit_type3" value="ประเภท3">
                                <label class="form-check-label" for="edit_type3">ประเภท 3</label>
                            </div>
                        </div>
                    </div>

                    <!-- จำนวนกลุ่ม -->
                    <div class="mb-3">
                        <label for="edit_group_count" class="form-label">จำนวนกลุ่ม</label>
                        <select class="form-select" id="edit_group_count" required>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?> กลุ่ม</option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- คอนเทนเนอร์สำหรับกลุ่ม -->
                    <div id="edit_groupContainer"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
                </div>
            </form>
        </div>
    </div>
</div>
