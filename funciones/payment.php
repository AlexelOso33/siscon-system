<?php

    require_once 'bd_admin.php';

    if(isset($_GET['payment_id'])){
        $now = date('Y-m-d H:i:s');
        $now = strtotime('-3 hours', strtotime($now));
        $hoy = date('Y-m-d h:i:s', $now);

        $idb = $_GET['id'];
        $colid = $_GET['collection_id'];
        $colstatus = $_GET['collection_status'];
        $payid = $_GET['payment_id'];
        $status = $_GET['status'];
        $extref = $_GET['external_reference'];
        $ptype = $_GET['payment_type'];
        $merord = $_GET['merchant_order_id'];
        $prefid = $_GET['preference_id'];
        $siteid = $_GET['site_id'];
        $prmode = $_GET['processing_mode'];
        $meracc = $_GET['merchant_account_id'];
        $user = 60;

        try {
            $sql = "SELECT `type_plan_length` AS tpl FROM `business_data` WHERE `number_business` = $idb";
            $res = mysqli_query($conna, $sql);
            $bus = mysqli_fetch_assoc($res);
            $tpl = $bus['tpl'];
            if($tpl == 1){
                $expdate = strtotime('+1 month', strtotime($hoy));
                $expdate = date('Y-m-d h:i:s', $expdate);
            } else if($tpl == 2){
                $expdate = strtotime('+1 year', strtotime($hoy));
                $expdate = date('Y-m-d h:i:s', $expdate);
            }
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }

        

        try {
            $stmt = $conna->prepare("INSERT INTO `pagos_business` (`id_business`, `collection_id`, `collection_status`, `payment_id`, `status`, `external_reference`, `payment_type`, `merchant_order_id`, `preference_id`, `site_id`, `processing_mode`, `merchant_account_id`, `date_inc_pago`, `user_pago`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issssssssssssi", $idb, $colid, $colstatus, $payid, $status, $extref, $ptype, $merord, $prefid, $siteid, $prmode, $meracc, $hoy, $user);
            $stmt->execute();
            if($stmt->insert_id > 0){
                $stat = 2;
                try {
                    $stmt1 = $conna->prepare("UPDATE `business_data` SET `status` = ?, `expiration_date` = ? WHERE `number_business` = ?");
                    $stmt1->bind_param("isi", $stat, $expdate, $idb);
                    $stmt1->execute();
                    if($stmt1->affected_rows > 0){
                        header("Location: https://siscon-system.com/procesador-pago.php?response_paym=1");
                    }
                } catch (\Throwable $th) {
                    echo "Error: ".$th->getMessage();
                }
            }
        } catch (\Throwable $th) {
            echo "Error: ".$th->getMessage();
        }
    } else {
        die('Non Authorized');
    }

?>