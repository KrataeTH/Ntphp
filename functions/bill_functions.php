<!-- function_bill.php -->
<?php

/**
 * สร้างบิลใหม่
 *
 * @param PDO $pdo
 * @param string $bill_number
 * @param string $bill_type
 * @param int $customer_id
 * @param array $groups
 * @return void
 * @throws Exception
 */
function create_bill(PDO $pdo, $bill_number, $bill_type, $customer_id, $groups, $services = []) {
    // ตรวจสอบความไม่ซ้ำของหมายเลขบิล
    $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM bill WHERE bill_number = :bill_number");
    $check_stmt->execute([':bill_number' => $bill_number]);
    if ($check_stmt->fetchColumn() > 0) {
        throw new Exception("หมายเลขบิลนี้มีอยู่แล้ว กรุณาใช้หมายเลขอื่น");
    }

    $total_price = 0;

    // เริ่มการทำธุรกรรม
    $pdo->beginTransaction();

    try {
        // แทรกข้อมูลบิล
        $bill_stmt = $pdo->prepare("
            INSERT INTO bill 
            (bill_number, bill_type, all_price, customer_id) 
            VALUES (:bill_number, :bill_type, :all_price, :customer_id)
        ");

        $bill_stmt->execute([
            ':bill_number' => $bill_number,
            ':bill_type' => $bill_type,
            ':customer_id' => $customer_id,
            ':all_price' => $total_price // จะอัปเดตภายหลัง
        ]);

        $bill_id = $pdo->lastInsertId();

         // เพิ่มข้อมูลในตาราง services
         if (!empty($services) && is_array($services)) { // ตรวจสอบ $services ก่อน
            $service_stmt = $pdo->prepare("
                INSERT INTO services 
                (service_type, equipment_service, bill_id) 
                VALUES (:service_type, :equipment_service, :bill_id)
            ");

            foreach ($services as $service) {
                // ตรวจสอบและทำความสะอาดข้อมูล (ถ้าจำเป็น)
                $service_type = trim($service['service_type']); 
                $equipment_service = trim($service['equipment_service']); 

                $service_stmt->execute([
                    ':service_type' => $service_type,
                    ':equipment_service' => $equipment_service,
                    ':bill_id' => $bill_id
                ]);
            }
        }

        // ประมวลผลกลุ่มบิล
        if (!empty($groups) && is_array($groups)) {
            $group_stmt = $pdo->prepare("
                INSERT INTO bill_group 
                (group_name, group_type, group_price_a, group_price_b, group_price, bill_id) 
                VALUES (:group_name, :group_type, :group_price_a, :group_price_b, :group_price, :bill_id)
            ");

            $group_info_stmt = $pdo->prepare("
                INSERT INTO bill_group_info 
                (group_info_name, bill_group_id) 
                VALUES (:group_info_name, :bill_group_id)
            ");

            foreach ($groups as $index => $group) {
                // ตรวจสอบและทำความสะอาดข้อมูล
                $group_name = trim($group['name']);
                $group_type = trim($group['type']);
                $group_price_a = floatval($group['price_a']);
                $group_price_b = floatval($group['price_b']);
                $group_price = $group_price_a + $group_price_b;

                if (empty($group_name)) {
                    throw new Exception("กรุณากรอกชื่อกลุ่มสำหรับกลุ่มที่ " . ($index + 1));
                }

                // แทรกข้อมูลกลุ่มบิล
                $group_stmt->execute([
                    ':group_name' => $group_name,
                    ':group_type' => $group_type,
                    ':group_price_a' => $group_price_a,
                    ':group_price_b' => $group_price_b,
                    ':group_price' => $group_price,
                    ':bill_id' => $bill_id
                ]);

                $bill_group_id = $pdo->lastInsertId();

                // แทรกข้อมูลเพิ่มเติมถ้ามี
                if (isset($group['info']) && is_array($group['info'])) {
                    foreach ($group['info'] as $info) {
                        $info = trim($info);
                        if (!empty($info)) {
                            $group_info_stmt->execute([
                                ':group_info_name' => $info,
                                ':bill_group_id' => $bill_group_id
                            ]);
                        }
                    }
                }

                // อัปเดตยอดรวม
                $total_price += $group_price;
            }

            // อัปเดตบิลด้วยยอดรวมที่คำนวณได้
            $update_price_stmt = $pdo->prepare("UPDATE bill SET all_price = :total_price WHERE bill_id = :bill_id");
            $update_price_stmt->execute([
                ':total_price' => $total_price,
                ':bill_id' => $bill_id
            ]);
        }

        // ยืนยันการทำธุรกรรม
        $pdo->commit();
    } catch (Exception $e) {
        // ยกเลิกการทำธุรกรรมในกรณีมีข้อผิดพลาด
        $pdo->rollBack();
        throw $e; // ส่งต่อข้อผิดพลาดให้กับ caller
    }
}

/**
 * ดึงข้อมูลลูกค้า
 *
 * @param PDO $pdo
 * @return array
 * @throws Exception
 */
function get_customers(PDO $pdo) {
    $customer_stmt = $pdo->query("SELECT customer_id, customer_name FROM customer ORDER BY customer_name");
    return $customer_stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * ดึงข้อมูลบิล
 *
 * @param PDO $pdo
 * @param int|null $customer_id
 * @return array
 * @throws Exception
 */
function get_bills(PDO $pdo, $customer_id = null) {
    // สร้าง SQL พื้นฐานสำหรับดึงข้อมูลบิล
    $sql = "
    SELECT 
        b.bill_id,
        b.bill_number,
        b.bill_status,
        b.bill_type,
        b.all_price,
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

    // ตรวจสอบว่ามี customer_id หรือไม่
    if ($customer_id) {
        $sql .= " WHERE b.customer_id = :customer_id";
    }

    $sql .= " ORDER BY b.bill_create_at DESC";

    // เตรียมและประมวลผล SQL
    $stmt = $pdo->prepare($sql);

    if ($customer_id) {
        $stmt->execute([':customer_id' => $customer_id]);
    } else {
        $stmt->execute();
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/**
 * ดึงชื่อของลูกค้าจาก customer_id
 *
 * @param PDO $pdo
 * @param int $customer_id
 * @return string
 * @throws Exception
 */
function get_customer_name(PDO $pdo, $customer_id) {
    $customer_stmt = $pdo->prepare("SELECT customer_name FROM customer WHERE customer_id = :customer_id");
    $customer_stmt->execute([':customer_id' => $customer_id]);
    $customer_result = $customer_stmt->fetch(PDO::FETCH_ASSOC);
    
    return $customer_result ? $customer_result['customer_name'] : 'ไม่พบข้อมูลลูกค้า';
}


/**
 * นับจำนวนบิลที่ไม่ซ้ำกัน
 *
 * @param array $bills
 * @return int
 */
function count_unique_bills($bills) {
    $unique_bills = [];
    foreach ($bills as $bill) {
        $unique_bills[$bill['bill_id']] = true;
    }
    return count($unique_bills);
}
?>