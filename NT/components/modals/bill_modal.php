<?php
// components/modals/bill_modal.php
?>

<!-- โมดัลสำหรับสร้างบิลใหม่ -->
<div class="modal fade" id="addBillModal" tabindex="-1" aria-labelledby="addBillModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="billForm" method="POST" action="">
                <input type="hidden" name="action" value="add_bill">
                <?php if (isset($customer_id) && $customer_id): ?>
                    <!-- ถ้ามี customer_id จาก GET, ใช้แบบซ่อน -->
                    <input type="hidden" name="customer_id" value="<?php echo htmlspecialchars($customer_id); ?>">
                <?php endif; ?>
                <div class="modal-header">
                    <h5 class="modal-title" id="addBillModalLabel">สร้างบิลใหม่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="ปิด"></button>
                </div>
                <div class="modal-body">
                    <!-- หมายเลขบิล -->
                    <div class="mb-3">
                        <label for="bill_number" class="form-label">หมายเลขบิล</label>
                        <input type="text" class="form-control" id="bill_number" name="bill_number" required>
                    </div>

                    <?php if (!isset($customer_id) || !$customer_id): ?>
                        <!-- ลูกค้า -->
                        <div class="mb-3">
                            <label for="customer_id" class="form-label">เลือกลูกค้า</label>
                            <select class="form-select" id="customer_id" name="customer_id" required>
                                <option value="">เลือกลูกค้า</option>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?php echo $customer['customer_id']; ?>">
                                        <?php echo htmlspecialchars($customer['customer_name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php else: ?>
                        <div class="mb-3">
                            <label class="form-label">ลูกค้า</label>
                            <p class="form-control-plaintext"><?php echo htmlspecialchars($customer_name); ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- ประเภทบิล -->
                    <div class="mb-3">
                        <label class="form-label">ประเภทบิล</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="bill_type" id="type1" value="ประเภท1" required>
                                <label class="form-check-label" for="type1">ประเภท 1</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="bill_type" id="type2" value="ประเภท2">
                                <label class="form-check-label" for="type2">ประเภท 2</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="bill_type" id="type3" value="ประเภท3">
                                <label class="form-check-label" for="type3">ประเภท 3</label>
                            </div>
                        </div>
                    </div>

                    <!-- จำนวนกลุ่ม -->
                    <div class="mb-3">
                        <label for="group_count" class="form-label">จำนวนกลุ่ม</label>
                        <select class="form-select" id="group_count" required>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?> กลุ่ม</option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- คอนเทนเนอร์สำหรับกลุ่ม -->
                    <div id="groupContainer"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                    <button type="submit" class="btn btn-primary">สร้างบิล</button>
                </div>
            </form>
        </div>
    </div>
</div>
