<!doctype html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Print Invoice #<?php echo $invoice['tid'] ?></title>
        <style>
            body {
                color: #2B2000;
                font-family: 'Helvetica';
            }
            .hidden{
                display:none !important;
            }
            
            .text-center{
                text-align:center;
            }
            
            .invoice-box {
                width: 210mm;
                height: 297mm;
                margin: auto;
                padding: 4mm;
                border: 0;
                font-size: 9pt !important;
                /*line-height: 14pt;*/
                color: #000;
            }
            
            .inv_footer_note{
                font-size: 9pt !important;
                height: 100% !important;
                width: 100% !important;
            }
            .div-note{
                height: 100% !important;
                width: 100% !important;
                border:1px solid black !important;
            }
            
            table {
                width: 100%;
                /*line-height: 16pt;*/
                text-align: left;
                border-collapse: collapse;
            }
            
            .plist{
                
            }
            
            .plist tr td {
                line-height: 12pt;
            }
            
            .subtotal {
                page-break-inside: avoid;
            }
            
            .subtotal tr td {
                line-height: 10pt;
                padding: 6pt;
            }
            
            .subtotal tr td {
                border: 1px solid #ddd;
            }
            
            .sign {
                text-align: right;
                font-size: 10pt;
                margin-right: 110pt;
            }
            
            .sign1 {
                text-align: right;
                font-size: 10pt;
                margin-right: 90pt;
            }
            
            .sign2 {
                text-align: right;
                font-size: 10pt;
                margin-right: 115pt;
            }
            
            .sign3 {
                text-align: right;
                font-size: 10pt;
                margin-right: 115pt;
            }
            
            .terms {
                font-size: 9pt;
                line-height: 16pt;
                margin-right: 20pt;
            }
            
            .invoice-box table td {
                padding: 2pt 2pt 3pt 2pt;
                vertical-align: top;
            }
            
            .invoice-box table.top_sum td {
                padding: 0;
                font-size: 12pt;
            }
            
            .party tr td:nth-child(3) {
                /*text-align: center;*/
            }
            
            .invoice-box table tr.top table td {
                padding-bottom: 20pt;
            }
            
            table tr.top table td.title {
                font-size: 45pt;
                line-height: 45pt;
                color: #555;
            }
            
            table tr.information table td {
                padding-bottom: 20pt;
            }
            
            table tr.heading td {
                background: #515151;
                color: #FFF;
                padding: 3pt 1pt;
                font-size : 9pt !important;
                text-align:center;
            }
            
            table tr.details td {
                padding-bottom: 20pt;
            }
            
            .invoice-box table tr.item td, .inv-total {
                font-size : 9pt !important;
                border: 1px solid #ddd;
            }
            
            table tr.b_class td {
                border-bottom: 1px solid #ddd;
            }
            
            table tr.b_class.last td {
                border-bottom: none;
            }
            
            table tr.total td:nth-child(4) {
                border-top: 2px solid #fff;
                font-weight: bold;
            }
            
            .myco {
                width: 30pt;
            }
            
            .myco2 {
                width: 200pt;
            }
            
            .myw {
                width: 300pt;
                font-size: 14pt;
                /*line-height: 14pt;*/
            }
            .myc {
                width: 370pt;
                font-size: 20pt;
                line-height:30pt;
                font-weight: bold;
                font-family:'KhMoul' !important;
            }
            .invh {
                font-size: 13pt;
                font-weight: bold;
                font-family:'KhMoul' !important;
                text-align:center;
                margin:0;
                padding:0;
                line-height: 17pt;
            }
            
            .no-space{
                padding:0 !important;
                margin:0 !important;
            }
            
            .mfill {
                /*background-color: #eee;*/
            }
            
            .descr {
                font-size: 10pt;
                color: #515151;
            }
            
            .tax {
                font-size: 10px;
                color: #515151;
            }
            
            .t_center {
                text-align: right;
            }
            
            .party {
                /*border: #ccc 1px solid;*/
                
            }
            .border1 {
                border: #ccc 1px solid;
            }
            
            .border-top{
                border-top:1px solid #000;
            }
            
            .top_logo {
                max-height: 180px;
                max-width: 250px;
        <?php if(LTR=='rtl') echo 'margin-left: 200px;' ?>
            }
            
            .col-right{
                text-align: right;
            }
            
            .col-left{
                text-align: left;
            }
            
            .def-font{
                font-size:9pt;
            }
        </style>
    </head>
    <body dir="<?= LTR ?>">
        <div>
            <?php echo $c_header; ?>
        </div>
        <div class="invh">វិក្ក័យប័ត្រលក់<br>COMMERCIAL INVOICE</div>
        <div class="invoice-box">
            <table class="party" style='font-size:10pt'>
                <tbody>
                    <tr>
                        <td width='9%'>
                            Bill To<br>
                            Address<br>
                            Tel<br>
                            Ship To<br>
                            Address
                        </td>
                        <td width='1%'>
                            :<br>
                            :<br>
                            :<br>
                            :<br>
                            :
                        </td>
                        <td>
                        <?php 
                        echo $invoice['name'].($invoice['company']?' (' . $invoice['company'].')':'');
                        echo '<br>' . $invoice['address'] . $invoice['city'] . ($invoice['country']?', ' . $invoice['country']:'');
                        echo '<br>' . $invoice['phone'];
                        if(@$invoice['name_s']){
                        echo '<br>' . $invoice['name_s'];
                        echo '<br>' . $invoice['address_s'] . $invoice['city_s'] . ($invoice['country_s']?', ' . $invoice['country_s']:'');
                        }
                        else{
                        echo '<br>' . $invoice['name'].($invoice['company']?' (' . $invoice['company'].')':'');
                        echo '<br>' . $invoice['address'] . $invoice['city'] . ($invoice['country']?', ' . $invoice['country']:'');
                        }
                        ?>
                        </td>
                        <td width='18%'>
                            Invoice No<br>
                            Date<br>
                            Sale Rep<br>
                            Term of payment<br>
                            Due Date
                        </td>
                        <td width='1%'>
                            :<br>
                            :<br>
                            :<br>
                            :<br>
                            :
                        </td>
                        <td style="text-align: right" width='23%'>
                         <?php 
                        echo $general['prefix'] . ' ' . $invoice['tid'];
                        echo '<br>' . dateformat($invoice['invoicedate']);
                        echo '<br>' . $employee['name'];
                        echo '<br>' . $invoice['termtit'];
                        echo '<br>' . dateformat($invoice['invoiceduedate']);
                        ?>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="plist" cellpadding="0" cellspacing="0">
                <tr class="heading">
                    <td style="width: 1rem;">
                        #
                    </td>
                    <td>
                        BARCODE
                    </td>
                    <td>
                        CODE
                    </td>
                    <td>
                <?php echo strtoupper($this->lang->line('Description')) ?>
                    </td>
                    <td>
                        PACKING
                    </td>
                    <td>
                <?php echo strtoupper($this->lang->line('Qty')) ?>
                    </td>
                    <td>
                <?php echo strtoupper($this->lang->line('Price')) ?>
                    </td>
            <?php if ($invoice['tax'] > 0) echo '<td>' . strtoupper($this->lang->line('Tax')) . '</td>';
            echo '<td>' . strtoupper($this->lang->line('Discount')) . '</td>'; ?>
                    <td class="t_center">
                <?php echo strtoupper($this->lang->line('SubTotal')) ?>
                    </td>
                </tr>
        <?php
        $fill = true;
        $sub_t = 0;
        $sub_t_col = 5;
        $n = 1;
        $f_cspan = 5;
        foreach ($products as $row) {
            $cols = 8;
            $f_cspan = 5;
            if ($fill == true) {
                $flag = ' mfill';
            } else {
                $flag = '';
            }
            $sub_t += $row['price'] * $row['qty'];
                
                
            echo '<tr class="item' . $flag . '">  <td>' . $n . '</td>
                <td style="width:14%;">'.$row['barcode'].'</td><td style="width:8%;">'.$row['product_code'].'</td>
                <td>' . $row['product'] . '</td>
                <td style="width:12%;">'.$row['unit'].'</td>
                <td style="width:4%;" class="col-right">' . +amountFormat_general($row['qty'],true) . '</td>
		<td class="col-right">' . amountExchange($row['price'], $invoice['multi'], $invoice['loc']) . '</td>';
            if ($invoice['tax'] > 0) {
                $cols++;
                $f_cspan++;
                echo '<td style="width:10%;" class="col-right">' . amountExchange($row['totaltax'], $invoice['multi'], $invoice['loc']) . ' <span class="tax">(' . amountFormat_s($row['tax']) . '%)</span></td>';
            }
                
            echo ' <td style="width:9%;" class="col-right">' . amountExchange($row['totaldiscount'], $invoice['multi'], $invoice['loc']) . '</td>';
                
            echo '<td style="width:10%;" class="t_center">' . amountExchange($row['subtotal'], $invoice['multi'], $invoice['loc']) . '</td></tr>';
                
            if ($row['product_des']) {
                $cc = $cols++;
                    
                echo '<tr class="item' . $flag . ' descr">  <td> </td>
                            <td colspan="' . $cc . '">' . nl2br($row['product_des']) . '&nbsp;</td>
                                
                        </tr>';
            }
            if (CUSTOM) {
                $p_custom_fields = $this->custom->view_fields_data($row['pid'], 4, 1);
                    
                if (is_array($p_custom_fields[0])) {
                    $z_custom_fields = '';
                        
                    foreach ($p_custom_fields as $row) {
                        $z_custom_fields .= $row['name'] . ': ' . $row['data'] . '<br>';
                    }
                        
                    echo '<tr class="item' . $flag . ' descr">  <td> </td>
                            <td colspan="' . $cc . '">' . $z_custom_fields . '&nbsp;</td>
                                
                        </tr>';
                }
            }
            $fill = !$fill;
            $n++;
        }
            
        if ($invoice['shipping'] > 0) {
            
            $sub_t_col++;
        }
        if ($invoice['tax'] > 0) {
            $sub_t_col++;
        }
        $rming = $invoice['total'] - $invoice['pamnt'];
        if ($rming > 0) {
            $sub_t_col++;
        }
        ?>
                
                <tr>
                    <td class="border1" colspan="<?php echo $f_cspan?>" rowspan="<?php echo $sub_t_col ?>">
                        <div class='inv_footer_note'>
                            <div>Note: * Please note that deposite are non-refundable, non-transferable</div>
                            <div>កំណត់សំគាល់/Remarks:</div>
                            <div class='div-note'><?php echo $invoice['notes'] ?></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="col-right border1 inv-total" colspan="3">សរុប/SUBTOTAL(USD)</td>
                    <td class="col-right border1 inv-total"><?php echo amountExchange($sub_t, $invoice['multi'], $invoice['loc']); ?></td>
                </tr>
                <tr>
                    <td class="col-right border1 inv-total" colspan="3">ចុះថ្លៃ/DISCOUNT(USD)</td>
                    <td class="col-right border1 inv-total"><?php echo amountExchange($invoice['discount'], $invoice['multi'], $invoice['loc']) ?></td>
                </tr>
                <?php if ($invoice['tax'] > 0) { ?>
                <tr>
                    <td class="col-right border1 inv-total" colspan="3">ពន្ធ/TAX(USD)</td>
                    <td class="col-right border1 inv-total"><?php echo amountExchange($invoice['tax'], $invoice['multi'], $invoice['loc']) ?></td>
                </tr>
                <?php }
                if ($invoice['shipping'] > 0) { ?>
                <tr>
                    <td class="col-right border1 inv-total" colspan="3">ដឹកជញ្ជូន/SHIPPING(USD)</td>
                    <td class="col-right border1 inv-total"><?php echo amountExchange($invoice['shipping'], $invoice['multi'], $invoice['loc']) ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td class="col-right border1 inv-total" colspan="3">សរុបរួម/TOTAL(USD)</td>
                    <td class="col-right border1 inv-total"><?php echo amountExchange($invoice['total'], $invoice['multi'], $invoice['loc']) ?></td>
                </tr>
                <tr>
                    <td class="col-right border1 inv-total" colspan="3">បានបង់/PAID(USD)</td>
                    <td class="col-right border1 inv-total"><?php echo amountExchange($invoice['pamnt'], $invoice['multi'], $invoice['loc']) ?></td>
                </tr>
                <?php if ($rming > 0) { ?>
                <tr>
                    <td class="col-right border1 inv-total" colspan="3">ត្រូវបង់/BALANCE(USD)</td>
                    <td class="col-right border1 inv-total"><?php echo amountExchange($rming, $invoice['multi'], $invoice['loc']) ?></td>
                </tr>
                <?php } ?>
                <tr>
                    <td colspan="<?php echo $f_cspan+4?>">
                        <div height='110px'>
                            <table class='text-center def-font'>
                                <tr>
                                    <td height='100px'></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td class='border-top' width='20%'>Authorize by</td>
                                    <td width='15%'></td>
                                    <td class='border-top' width='20%'>Delivery by</td>
                                    <td width='15%'></td>
                                    <td class='border-top' width='20%'>Received by</td>
                                </tr>
                                <tr>
                                    <td colspan='5' class='col-left'>
                                        <br>
                                        <?php 
                                            date_default_timezone_set('Asia/Phnom_Penh');
                                            $cdate = date('Y/m/d h:i:s a', time());
                                            $loc_com = location($invoice['loc']);
                                            $com_name = explode("<br>",$loc_com['cname']);
                                            $com_name = $com_name[0];
                                        ?>
                                        Printed Date & Time <?php echo $cdate ?>
                                        <br>
                                        <div style='font-size:7pt'>
                                            <u>***ការទូទាត់វិក័យប័ត្រ</u>
                                            <br>១- សូមទូទាត់ប្រាក់អោយបុគ្គលិកដែលមានប័ណ្ណសំគាល់ខ្លួន និង វិក័យប័ត្រលក់ ដែលចេញដោយក្រុមហ៊ុន។
                                            <br>២- សែកត្រូវសរសេរថា ទូទាត់ជូន "<?php echo $com_name ?>"។ សូមរក្សាទុកបង្កាន់ដៃបង់ប្រាក់ដើម្បីធ្វើការផ្ទៀងផ្ទាត់ ក្នុងករណីចាំបាច់ណាមួយ។
                                            <br>៣- ប្រសិនបើលោកអ្នកមិនអនុវត្តន៍តាមនិតិវិធីខាងលើនេះទេ ក្រុមហ៊ុននឹងមិនទទួលខុសត្រូវចំពោះការខូចខាតបាត់បង់ទាំងឡាយណាដែលអាចកើតមានឡើយ។
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                </tr>
            </table>
            
                <?php $rming = $invoice['total'] - $invoice['pamnt'];
    if ($rming < 0) {
        $rming = 0;
    }
    if (@$round_off['other']) {
        $rming = round($rming, $round_off['active'], constant($round_off['other']));
    }
    //echo amountExchange($rming, $invoice['multi'], $invoice['loc']);?>
        </div>
    </body>
</html>