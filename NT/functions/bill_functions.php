<?php
// functions/bill_functions.php

function getBills($pdo, $customer_id = null) {
    $sql = "
        SELECT 
            b.bill_id, 
            b.bill_number, 
            b.bill_type, 
            b.bill_create_at, 
            b.bill_update_at, 
            b.all_price, 
            c.customer_name,
            bg.group_name, 
            bg.group_price, 
            bgi.group_info_name
        FROM 
            bill b
        LEFT JOIN 
            customer c ON b.customer_id = c.customer_id
        LEFT JOIN 
            bill_group bg ON b.bill_id = bg.bill_id
        LEFT JOIN 
            bill_group_info bgi ON bg.bill_group_id = bgi.bill_group_id
    ";
    
    if ($customer_id) {
        $sql .= " WHERE b.customer_id = :customer_id";
    }

    $sql .= " ORDER BY b.bill_create_at DESC";

    $stmt = $pdo->prepare($sql);

    if ($customer_id) {
        $stmt->execute([':customer_id' => $customer_id]);
    } else {
        $stmt->execute();
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCustomers($pdo) {
    $customer_stmt = $pdo->query("SELECT customer_id, customer_name FROM customer ORDER BY customer_name");
    return $customer_stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getCustomerName($pdo, $customer_id) {
    $customer_stmt = $pdo->prepare("SELECT customer_name FROM customer WHERE customer_id = :customer_id");
    $customer_stmt->execute([':customer_id' => $customer_id]);
    $customer_result = $customer_stmt->fetch(PDO::FETCH_ASSOC);
    return $customer_result ? $customer_result['customer_name'] : 'ไม่พบข้อมูลลูกค้า';
}

function addBill($pdo, $bill_number, $bill_type, $customer_id, $groups) {
    // ตรวจสอบความไม่ซ้ำหมายเลขบิล
    $check_stmt = $pdo->prepare("SELECT COUNT(*) FROM bill WHERE bill_number = :bill_number");
    $check_stmt->execute([':bill_number' => $bill_number]);
    if ($check_stmt->fetchColumn() > 0) {
        throw new Exception("หมายเลขบิลนี้มีอยู่แล้ว กรุณาใช้หมายเลขอื่น");
    }

    $total_price = 0;
    $pdo->beginTransaction();

    // เพิ่มบิล
    $bill_stmt = $pdo->prepare("
        INSERT INTO bill (bill_number, bill_type, all_price, customer_id) 
        VALUES (:bill_number, :bill_type, :all_price, :customer_id)
    ");
    $bill_stmt->execute([
        ':bill_number' => $bill_number,
        ':bill_type' => $bill_type,
        ':all_price' => $total_price,
        ':customer_id' => $customer_id
    ]);

    $bill_id = $pdo->lastInsertId();

    // เพิ่มกลุ่มในบิล
    if (is_array($groups)) {
        $group_stmt = $pdo->prepare("
            INSERT INTO bill_group (group_name, group_type, group_price_a, group_price_b, group_price, bill_id)
            VALUES (:group_name, :group_type, :group_price_a, :group_price_b, :group_price, :bill_id)
        ");

        $group_info_stmt = $pdo->prepare("
            INSERT INTO bill_group_info (group_info_name, bill_group_id)
            VALUES (:group_info_name, :bill_group_id)
        ");

        foreach ($groups as $index => $group) {
            $group_name = trim($group['name']);
            $group_type = trim($group['type']);
            $group_price_a = floatval($group['price_a']);
            $group_price_b = floatval($group['price_b']);
            $group_price = $group_price_a + $group_price_b;

            if (empty($group_name)) {
                throw new Exception("กรุณากรอกชื่อกลุ่มสำหรับกลุ่มที่ " . ($index + 1));
            }

            $group_stmt->execute([
                ':group_name' => $group_name,
                ':group_type' => $group_type,
                ':group_price_a' => $group_price_a,
                ':group_price_b' => $group_price_b,
                ':group_price' => $group_price,
                ':bill_id' => $bill_id
            ]);

            $bill_group_id = $pdo->lastInsertId();

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

            $total_price += $group_price;
        }

        // อัปเดตราคาในตาราง bill
        $update_price_stmt = $pdo->prepare("UPDATE bill SET all_price = :total_price WHERE bill_id = :bill_id");
        $update_price_stmt->execute([
            ':total_price' => $total_price,
            ':bill_id' => $bill_id
        ]);
    }

    $pdo->commit();
    return $bill_id;
}
