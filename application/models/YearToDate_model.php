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

class YearToDate_model extends CI_Model
{
    var $table = 'geopos_invoices';
    var $column_order = array(null, 'geopos_invoices.id','geopos_invoices.tid','geopos_invoices.invoicedate','geopos_invoices.invoiceduedate','geopos_invoices.total','geopos_invoices.status','geopos_customers.name','geopos_employees.name as emp_name','geopos_invoices.refer','(DATE_FORMAT(geopos_invoices.invoiceduedate,"%d")-DATE_FORMAT(now(),"%d")) as age', null);
    var $column_search = array('geopos_invoices.id','geopos_invoices.tid','geopos_invoices.invoicedate','geopos_invoices.invoiceduedate','geopos_invoices.total','geopos_invoices.status','geopos_customers.name','geopos_employees.name as emp_name','geopos_invoices.refer','(DATE_FORMAT(geopos_invoices.invoiceduedate,"%d")-DATE_FORMAT(now(),"%d")) as age');
    var $order = array('geopos_invoices.tid' => 'desc');

    public function __construct()
    {
        parent::__construct();
    }

    public function lastinvoice()
    {
        $this->db->select('tid');
        $this->db->from($this->table);
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $this->db->where('i_class', 0);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->row()->tid;
        } else {
            return 1000;
        }
    }


    public function invoice_details($id, $eid = '')
    {
        $this->db->select('geopos_invoices.*,SUM(geopos_invoices.shipping + geopos_invoices.ship_tax) AS shipping,geopos_customers.*,geopos_invoices.loc as loc,geopos_invoices.id AS iid,geopos_customers.id AS cid,geopos_terms.id AS termid,geopos_terms.title AS termtit,geopos_terms.terms AS terms');
        $this->db->from($this->table);
        $this->db->where('geopos_invoices.id', $id);
        if ($eid) {
            $this->db->where('geopos_invoices.eid', $eid);
        }
              if ($this->aauth->get_user()->loc) {
            $this->db->where('geopos_invoices.loc', $this->aauth->get_user()->loc);
        } elseif (!BDATA) {
            $this->db->where('geopos_invoices.loc', 0);
        }
        $this->db->join('geopos_customers', 'geopos_invoices.csd = geopos_customers.id', 'left');
        $this->db->join('geopos_terms', 'geopos_terms.id = geopos_invoices.term', 'left');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function invoice_products($id)
    {

        $this->db->select('*');
        $this->db->from('geopos_invoice_items');
        $this->db->where('tid', $id);
        $query = $this->db->get();
        return $query->result_array();

    }

    public function currencies()
    {

        $this->db->select('*');
        $this->db->from('geopos_currencies');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function currency_d($id, $loc = 0)
    {
        if ($loc) {
            $query = $this->db->query("SELECT cur FROM geopos_locations WHERE id='$loc' LIMIT 1");
            $row = $query->row_array();
            $id = $row['cur'];
        }
        $this->db->select('*');
        $this->db->from('geopos_currencies');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function warehouses()
    {
        $this->db->select('*');
        $this->db->from('geopos_warehouse');
        if ($this->aauth->get_user()->loc) {
            $this->db->where('loc', $this->aauth->get_user()->loc);
          if(BDATA)  $this->db->or_where('loc', 0);
        }  elseif(!BDATA) { $this->db->where('loc', 0); }

        $query = $this->db->get();

        return $query->result_array();

    }

    public function invoice_transactions($id)
    {

         $this->db->select('*');
        $this->db->from('geopos_transactions');
        $this->db->where('tid', $id);
        $this->db->where('ext', 0);
        $query = $this->db->get();
        return $query->result_array();

    }

    public function invoice_delete($id, $eid = '')
    {
        $this->db->trans_start();
        $this->db->select('tid,total,status');
        $this->db->from('geopos_invoices');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $result = $query->row_array();
        if ($this->aauth->get_user()->loc) {
            if ($eid) {

                $res = $this->db->delete('geopos_invoices', array('id' => $id, 'eid' => $eid, 'loc' => $this->aauth->get_user()->loc));


            } else {
                $res = $this->db->delete('geopos_invoices', array('id' => $id, 'loc' => $this->aauth->get_user()->loc));
            }
        }

        else {
            if (BDATA) {
                if ($eid) {

                    $res = $this->db->delete('geopos_invoices', array('id' => $id, 'eid' => $eid));


                } else {
                    $res = $this->db->delete('geopos_invoices', array('id' => $id));
                }
            } else {


                if ($eid) {

                    $res = $this->db->delete('geopos_invoices', array('id' => $id, 'eid' => $eid, 'loc' => 0));


                } else {
                    $res = $this->db->delete('geopos_invoices', array('id' => $id, 'loc' => 0));
                }
            }
        }

        $affect = $this->db->affected_rows();

        if ($res) {
            if ($result['status'] != 'canceled') {
                $this->db->select('pid,qty');
                $this->db->from('geopos_invoice_items');
                $this->db->where('tid', $id);
                $query = $this->db->get();
                $prevresult = $query->result_array();

                foreach ($prevresult as $prd) {
                    $amt = $prd['qty'];
                    $this->db->set('qty', "qty+$amt", FALSE);
                    $this->db->where('pid', $prd['pid']);
                    $this->db->update('geopos_products');
                }
            }


            if ($affect) $this->db->delete('geopos_invoice_items', array('tid' => $id));

            $data = array('type' => 9, 'rid' => $id);
            $this->db->delete('geopos_metadata', $data);

                        $alert= $this->custom->api_config(66);
            if ($alert['method'] == 1) {
                 $this->load->model('communication_model');
                 $subject= $result['tid'].' '. $this->lang->line('DELETED');
                 $body=$subject.'<br> '. $this->lang->line('Amount').' '. $result['total'].'<br> '. $this->lang->line('Employee').''. $this->aauth->get_user()->username.'<br> ID# '. $result['tid'];
               $out= $this->communication_model->send_corn_email($alert['url'], $alert['url'], $subject, $body, false, '');
            }

            if ($this->db->trans_complete()) {
                return true;
            } else {
                return false;
            }
        }

    }


    private function _get_datatables_query($opt = '')
    {
        // $sub_query_from = "(SELECT SUM(total) FROM geopos_invoices gi WHERE gi.csd=gim.csd AND date_format(invoicedate,'%m')='01') as Jan";
        $this->db->select("geopos_invoices.id,geopos_customers.name,geopos_customers.address,geopos_customers.phone_s,geopos_invoices.csd,
        (SELECT ifnull(SUM(total),0) FROM geopos_invoices gi WHERE gi.csd=geopos_customers.id AND date_format(invoicedate,'%m')='01') as jan,
        (SELECT ifnull(SUM(total),0) FROM geopos_invoices gi WHERE gi.csd=geopos_customers.id AND date_format(invoicedate,'%m')='02') as feb,
        (SELECT ifnull(SUM(total),0) FROM geopos_invoices gi WHERE gi.csd=geopos_customers.id AND date_format(invoicedate,'%m')='03') as mar,
        (SELECT ifnull(SUM(total),0) FROM geopos_invoices gi WHERE gi.csd=geopos_customers.id AND date_format(invoicedate,'%m')='04') as apr,
        (SELECT ifnull(SUM(total),0) FROM geopos_invoices gi WHERE gi.csd=geopos_customers.id AND date_format(invoicedate,'%m')='05') as may,
        (SELECT ifnull(SUM(total),0) FROM geopos_invoices gi WHERE gi.csd=geopos_customers.id AND date_format(invoicedate,'%m')='06') as jun,
        (SELECT ifnull(SUM(total),0) FROM geopos_invoices gi WHERE gi.csd=geopos_customers.id AND date_format(invoicedate,'%m')='07') as jul,
        (SELECT ifnull(SUM(total),0) FROM geopos_invoices gi WHERE gi.csd=geopos_customers.id AND date_format(invoicedate,'%m')='08') as aug,
        (SELECT ifnull(SUM(total),0) FROM geopos_invoices gi WHERE gi.csd=geopos_customers.id AND date_format(invoicedate,'%m')='09') as sep,
        (SELECT ifnull(SUM(total),0) FROM geopos_invoices gi WHERE gi.csd=geopos_customers.id AND date_format(invoicedate,'%m')='10') as oct,
        (SELECT ifnull(SUM(total),0) FROM geopos_invoices gi WHERE gi.csd=geopos_customers.id AND date_format(invoicedate,'%m')='11') as nov,
        (SELECT ifnull(SUM(total),0) FROM geopos_invoices gi WHERE gi.csd=geopos_customers.id AND date_format(invoicedate,'%m')='12') as descb FROM geopos_invoices inner join geopos_customers on geopos_customers.id=geopos_invoices.csd WHERE geopos_invoices.status='due' and date_format(geopos_invoices.invoicedate,'%Y')='2019' group by geopos_customers.name");
        //$this->db->from($this->table);
        
        //$this->db->where('geopos_invoices.i_class', 0);
        //$this->db->where('geopos_invoices.csd',1);
        //$this->db->group_by('csd');
        //$this->db->join('geopos_customers', 'geopos_invoices.csd=geopos_customers.id', 'inner');
        //$this->db->group_by('geopos_invoices.csd');

//         $this->db->query("SELECT 
// 	cs.name as custName,
// 	(SELECT ifnull(SUM(total),0) FROM geopos_invoices gi WHERE gi.csd=gim.csd AND date_format(invoicedate,'%m')='01') as Jan,
//     (SELECT SUM(total) FROM geopos_invoices gi WHERE gi.csd=gim.csd AND date_format(invoicedate,'%m')='02') as Feb,
//     (SELECT SUM(total) FROM geopos_invoices gi WHERE gi.csd=gim.csd AND date_format(invoicedate,'%m')='03') as Mar,
//     (SELECT SUM(total) FROM geopos_invoices gi WHERE gi.csd=gim.csd AND date_format(invoicedate,'%m')='04') as Apr,
//     (SELECT SUM(total) FROM geopos_invoices gi WHERE gi.csd=gim.csd AND date_format(invoicedate,'%m')='05') as May,
//     (SELECT SUM(total) FROM geopos_invoices gi WHERE gi.csd=gim.csd AND date_format(invoicedate,'%m')='06') as Jun,
//     (SELECT SUM(total) FROM geopos_invoices gi WHERE gi.csd=gim.csd AND date_format(invoicedate,'%m')='07') as Jul,
//     (SELECT SUM(total) FROM geopos_invoices gi WHERE gi.csd=gim.csd AND date_format(invoicedate,'%m')='08') as Aug,
//     (SELECT SUM(total) FROM geopos_invoices gi WHERE gi.csd=gim.csd AND date_format(invoicedate,'%m')='09') as Sep,
//     (SELECT SUM(total) FROM geopos_invoices gi WHERE gi.csd=gim.csd AND date_format(invoicedate,'%m')='10') as Oct,
//     (SELECT SUM(total) FROM geopos_invoices gi WHERE gi.csd=gim.csd AND date_format(invoicedate,'%m')='11') as Nov,
//     (SELECT SUM(total) FROM geopos_invoices gi WHERE gi.csd=gim.csd AND date_format(invoicedate,'%m')='12') as Decs
// FROM geopos_invoices gim inner join geopos_customers cs on cs.id=gim.csd WHERE gim.status='due' and date_format(gim.invoicedate,'%Y')='2019' GROUP BY csd");
        //$this->db->group_by("geopos_customers.id"); 
    }

    function get_datatables($opt = '')
    {
        $this->_get_datatables_query($opt);
        if ($_POST['length'] != -1)
            $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        $this->db->where('geopos_invoices.i_class', 0);
        if ($this->aauth->get_user()->loc) {
            $this->db->where('geopos_invoices.loc', $this->aauth->get_user()->loc);
        }  elseif(!BDATA) { $this->db->where('geopos_invoices.loc', 0); }

        return $query->result();
    }

    function count_filtered($opt = '')
    {
        $this->_get_datatables_query($opt);
        if ($opt) {
            $this->db->where('eid', $opt);
        }
        if ($this->aauth->get_user()->loc) {
            $this->db->where('geopos_invoices.loc', $this->aauth->get_user()->loc);
        }  elseif(!BDATA) { $this->db->where('geopos_invoices.loc', 0); }
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($opt = '')
    {
        $this->db->select('geopos_invoices.id');
        $this->db->from($this->table);
        $this->db->where('geopos_invoices.i_class', 0);
        if ($opt) {
            $this->db->where('geopos_invoices.eid', $opt);

        }
        if ($this->aauth->get_user()->loc) {
            $this->db->where('geopos_invoices.loc', $this->aauth->get_user()->loc);
        }  elseif(!BDATA) { $this->db->where('geopos_invoices.loc', 0); }
        return $this->db->count_all_results();
    }


    public function billingterms()
    {
        $this->db->select('id,title');
        $this->db->from('geopos_terms');
        $this->db->where('type', 1);
        $this->db->or_where('type', 0);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function employee($id)
    {
        $this->db->select('geopos_employees.name,geopos_employees.sign,geopos_users.roleid');
        $this->db->from('geopos_employees');
        $this->db->where('geopos_employees.id', $id);
        $this->db->join('geopos_users', 'geopos_employees.id = geopos_users.id', 'left');
        $query = $this->db->get();
        return $query->row_array();
    }

    public function meta_insert($id, $type, $meta_data)
    {
        $data = array('type' => $type, 'rid' => $id, 'col1' => $meta_data);
        if ($id) {
            return $this->db->insert('geopos_metadata', $data);
        } else {
            return 0;
        }
    }

    public function attach($id)
    {
        $this->db->select('geopos_metadata.*');
        $this->db->from('geopos_metadata');
        $this->db->where('geopos_metadata.type', 1);
        $this->db->where('geopos_metadata.rid', $id);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function meta_delete($id, $type, $name)
    {
        if (@unlink(FCPATH . 'userfiles/attach/' . $name)) {
            return $this->db->delete('geopos_metadata', array('rid' => $id, 'type' => $type, 'col1' => $name));
        }
    }

    public function gateway_list($enable = '')
    {
        $this->db->from('geopos_gateways');
        if ($enable == 'Yes') {
            $this->db->where('enable', 'Yes');
        }
        $query = $this->db->get();
        return $query->result_array();
    }
}