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

class YearToDate extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('yeartodate_model', 'invocies');
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
        $this->load->view('customers/yeartodate');
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
            $row[] = $invoices->geopos_customers;
            $row[] = '';
            $row[] = '';
            $row[] = $invoices->jan;
            $row[] = $invoices->feb;
            $row[] = $invoices->mar;
            $row[] = $invoices->apr;
            $row[] = $invoices->may;
            $row[] = $invoices->jun;
            $row[] = $invoices->jul;
            $row[] = $invoices->aug;
            $row[] = $invoices->sep;
            $row[] = $invoices->oct;
            $row[] = $invoices->nov;
            $row[] = $invoices->descb;
            $row[] = '';
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
}