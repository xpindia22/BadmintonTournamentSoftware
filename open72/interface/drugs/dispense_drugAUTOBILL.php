<?php

// Copyright (C) 2006 Rod Roark <rod@sunsetsystems.com>
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.

require_once("../globals.php");
require_once("drugs.inc.php");
require_once("$srcdir/options.inc.php");

use OpenEMR\Common\Acl\AclMain;
use OpenEMR\Common\Twig\TwigContainer;
use OpenEMR\Services\FacilityService;
use PHPMailer\PHPMailer\PHPMailer;

$facilityService = new FacilityService();

function send_email($subject, $body)
{
    $recipient = $GLOBALS['practice_return_email_path'];
    if (empty($recipient)) {
        return;
    }

    $mail = new PHPMailer();
    $mail->From = $recipient;
    $mail->FromName = 'In-House Pharmacy';
    $mail->isMail();
    $mail->Host = "localhost";
    $mail->Mailer = "mail";
    $mail->Body = $body;
    $mail->Subject = $subject;
    $mail->AddAddress($recipient);
    if (!$mail->Send()) {
        error_log('There has been a mail error sending to' . " " . errorLogEscape($recipient .
        " " . $mail->ErrorInfo));
    }
}

$sale_id         = $_REQUEST['sale_id'];
$drug_id         = $_REQUEST['drug_id'];
$prescription_id = $_REQUEST['prescription'];
$quantity        = $_REQUEST['quantity'];
$fee             = $_REQUEST['fee'];
$user            = $_SESSION['authUser'];
$encounter       = $_SESSION['encounter'];
// $ndc_number     = $_REQUEST['ndc_number'];
if (!AclMain::aclCheckCore('admin', 'drugs')) {
    echo (new TwigContainer(null, $GLOBALS['kernel']))->getTwig()->render('core/unauthorized.html.twig', ['pageTitle' => xl("Dispense Drug")]);
    exit;
}

if (!$drug_id) {
    $drug_id = 0;
}

if (!$prescription_id) {
    $prescription_id = 0;
}

if (!$quantity) {
    $quantity = 0;
}

 //auto calculate the fee for no of tablets prescribed and add that amount to the bill.
try {
    $db = new PDO('mysql:host=localhost;dbname=open72;charset=utf8', 'root', '');

    // Prepare the SQL statement
    $stmt = $db->prepare("SELECT ndc_number FROM drugs WHERE drug_id = :drug_id");

    // Bind the parameters
    $stmt->bindParam(':drug_id', $drug_id);

    // Execute the statement
    $stmt->execute();

    // Fetch the row
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Get the ndc_number
    $ndc_number = $row['ndc_number'];
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

if (!$fee) {
    if (isset($ndc_number) && is_numeric($ndc_number)) {
        $fee = ($quantity * $ndc_number);
    } else {
        echo "The variable \$ndc_number is not set or not a number.";
    }
}


$inventory_id = 0;
$bad_lot_list = '';
$today = date('Y-m-d');

// If there is no sale_id then this is a new dispensation.
//
 
if (! $sale_id) {
  // Post the order and update inventory, deal with errors.
  //
    if ($drug_id) {
        $sale_id = sellDrug($drug_id, $quantity, $fee, $pid, $encounter, $prescription_id, $today, $user);
        if (!$sale_id) {
            die(xlt('Inventory is not available for this order.'));
        }

     
    $res = sqlStatement("SELECT * FROM drug_inventory WHERE " .
    "drug_id = '$drug_id' AND on_hand > 0 AND destroy_date IS NULL " .
    "ORDER BY expiration, inventory_id");
    while ($row = sqlFetchArray($res)) {
    if ($row['expiration'] > $today && $row['on_hand'] >= $quantity) {
    break;
    }
    $tmp = $row['lot_number'];
    if (! $tmp) $tmp = '[missing lot number]';
    if ($bad_lot_list) $bad_lot_list .= ', ';
    $bad_lot_list .= $tmp;
    }

    if ($bad_lot_list) {
    send_email("Lot destruction needed",
    "The following lot(s) are expired or too small to fill prescription " .
    "$prescription_id and should be destroyed: $bad_lot_list\n");
    }

    if (! $row) {
    die("Inventory is not available for this order.");
    }

    $inventory_id = $row['inventory_id'];

    sqlStatement("UPDATE drug_inventory SET " .
    "on_hand = on_hand - $quantity " .
    "WHERE inventory_id = $inventory_id");

    $rowsum = sqlQuery("SELECT sum(on_hand) AS sum FROM drug_inventory WHERE " .
    "drug_id = '$drug_id' AND on_hand > '$quantity' AND expiration > CURRENT_DATE");
    $rowdrug = sqlQuery("SELECT * FROM drugs WHERE " .
    "drug_id = '$drug_id'");
    if ($rowsum['sum'] <= $rowdrug['reorder_point']) {
    send_email("Drug re-order required",
    "Drug '" . $rowdrug['name'] . "' has reached its reorder point.\n");
    }
    
    } 
 
} 


?>
