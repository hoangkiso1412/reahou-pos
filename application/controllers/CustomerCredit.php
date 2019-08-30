<?php
/**
 * Geo POS -  Accounting,  Invoicing  and CRM Application
 * Copyright (c) Rajesh Dukiya. All Rights Reserved
 * ***********************************************************************
 *
 *  Email: support@ultimatekode.com
 *  Website: https://www.ultimatekode.com
 *
 *  ************************************************************************
 *  * This software is furnished under a license and may be used and copied
 *  * only  in  accordance  with  the  terms  of such  license and with the
 *  * inclusion of the above copyright notice.
 *  * If you Purchased from Codecanyon, Please read the full License from
 *  * here- http://codecanyon.net/licenses/standard/
 * ***********************************************************************
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class CustomerCredit extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('invoicecredit_model', 'invocies');
        $this->load->library("Aauth");
        if (!$this->aauth->is_loggedin()) {
            redirect('/user/', 'refresh');
        }
        if (!$this->aauth->premission(3)) {

            exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        }
        $this->load->library("Custom");
        $this->li_a = 'crm';
    }

    public function index()
    {
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Customers Credit';
        $this->load->view('fixed/header', $head);
        $this->load->view('customers/customercredit');
        $this->load->view('fixed/footer');
    }
    public function customer_credit_group()
    {
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Customers Credit';
        $this->load->view('fixed/header', $head);
        $this->load->view('customers/customercreditbyname',$data);
        $this->load->view('fixed/footer');
    }
    public function viewpayment(){
        $head['usernm'] = $this->aauth->get_user()->username;
        $head['title'] = 'Customers Payment';
        $this->load->model('accounts_model');
        $id = $this->input->get('id');
        $data['totalcredit']=$this->invocies->getAmountCredit($id);
        $data['acclist'] = $this->accounts_model->accountslist((integer)$this->aauth->get_user()->loc);
        $this->load->view('fixed/header', $head);
        $this->load->view('customers/payment_amount',$data);
        $this->load->view('fixed/footer');
    }
    public function ajax_list()
    {
        $list = $this->invocies->get_datatables($this->limited);
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $invoices) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<a href="' . base_url("invoices/view?id=$invoices->id") . '">&nbsp; ' . $invoices->tid . '</a>';
            $row[] = $invoices->name;
            $row[] = '';
            $row[] = $invoices->refer;
            $row[] = dateformat($invoices->invoicedate);
            $row[] = $invoices->total;
            $row[] =  $invoices->age;
            $row[] =  $invoices->invoiceduedate;
            $row[] =  $invoices->emp_name;
            $data[] = $row;
        }
        $output = array(
            "draw" => $this->input->post('draw'),
            "recordsTotal" => $this->invocies->count_all($this->limited),
            "recordsFiltered" => $this->invocies->count_filtered($this->limited),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    public function ajax_list_group()
    {
        $list = $this->invocies->get_datatables_group($this->limited);
        $data = array();
        $no = $this->input->post('start');
        foreach ($list as $invoices) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<a href="' . base_url("customercredit/viewpayment?id=$invoices->id") . '">&nbsp; ' . $invoices->name . '</a>';
            $row[] = $invoices->payment;
            $row[] = $invoices->debit;
            $row[] = '<a href="' . base_url("customercredit/viewpayment?id=$invoices->id") . '">&nbsp; ' . 'Pay'. '</a>';
            $data[] = $row;
        }
        $output = array(
            "draw"            => $this->input->post('draw'),
            "recordsTotal"    => $this->invocies->count_all($this->limited),
            "recordsFiltered" => $this->invocies->count_filtered($this->limited),
            "data"            => $data,
        );
        //output to json format
        echo json_encode($output);
    }
    public function pay(){
        if (!$this->aauth->premission(1)) {

            exit('<h3>Sorry! You have insufficient permissions to access this section</h3>');

        }
        $this->load->model("base_model");
        $amount2 = 0;
        $tid    = $this->input->get('tid',true);
        $amount = $this->input->get('amount_pay', true);
        $paydate = $this->input->get('paydate', true);
        $note = $this->input->get('shortnote', true);
        $pmethod = $this->input->get('pmethod', true);
        $acid = $this->input->get('account', true);
        $cid = $this->input->get('cid', true);
        $cname = $this->input->get('cname', true);
        $paydate = datefordatabase($paydate);

        $data = array(
            'acid' => $acid,
            'account' => $account['holder'],
            'type' => 'Income',
            'cat' => 'Sales',
            'credit' => $amount,
            'payer' => $cname,
            'payerid' => $cid,
            'method' => $pmethod,
            'date' => $paydate,
            'eid' => $this->aauth->get_user()->id,
            'tid' => $tid,
            'note' => $note,
            'loc' => $this->aauth->get_user()->loc
        );

        $this->db->insert('geopos_transactions', $data);


        $query=$this->base_model->get_value_byQuery("select count(geopos_invoices.csd) as length from geopos_invoices where geopos_invoices.status='due' and geopos_invoices.csd=".$cid,"length");
        $invoice_data=$this->base_model->select("select id from geopos_invoices where csd=? and status=?",array("csd" => $cid,"status"=>"due"));
        $id=array();
        foreach($invoice_data as $row){
            $id[] = $row->id;
        }
        echo "query:".$query."<br/>";
        for($i=0;$i<$query;$i++){
            echo "update:".$i."</br>";
            $creditamount=$this->base_model->get_value_byQuery("select pamnt as credit from geopos_invoices where geopos_invoices.status='due' and geopos_invoices.csd=".$cid." and id=".$id[$i],"credit");
            echo "credit amount".$creditamount."<br/>";
            $dueamount=$this->base_model->get_value_byQuery("select (total-pamnt) as credit from geopos_invoices where geopos_invoices.status='due' and geopos_invoices.csd=".$cid." and id=".$id[$i],"credit");
            echo "due amount".$dueamount."<br/>";
            if($amount>=$dueamount) {
                echo "con1"."<br/>";
                $dataInvoice = array(
                    'status' => 'paid',
                    'pamnt'  => $creditamount+$dueamount
                    //'pamnt'  => $i
                );
                $this->base_model->update('geopos_invoices', $dataInvoice, array("csd" => $cid,"id"=>$id[$i],"status" => "due"));
                $amount=$amount-$dueamount;
            }else{
                echo "con2"."<br/>";
                $creditamount=$this->base_model->get_value_byQuery("select pamnt as credit from geopos_invoices where geopos_invoices.status='due' and geopos_invoices.csd=".$cid." and id=".$id[$i],"credit");
                $dataInvoice = array(
                    'pamnt'  => $amount + $creditamount
                );
                $this->base_model->update('geopos_invoices', $dataInvoice, array("csd" => $cid,"id"=>$id[$i],"status" => "due"));
                redirect(site_url("customercredit/customer_credit_group"));
                exit();
            }  
        }
        redirect(site_url("customercredit/customer_credit_group"));
            exit();
        // echo json_encode(array('status' => 'Success', 'message' =>
        // $this->lang->line('Transaction has been added'), 'pstatus' => $this->lang->line($status), 'activity' => $activitym, 'amt' => $totalrm, 'ttlpaid' => amountExchange_s($amount, 0, $this->aauth->get_user()->loc)));
    }
}