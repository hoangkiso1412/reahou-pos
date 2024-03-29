<div class="content-body">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><?php echo "Year To Date"//$this->lang->line('Manage Invoices') ?></h4>
            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
            <div class="heading-elements">
                <ul class="list-inline mb-0">
                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                    <li><a data-action="close"><i class="ft-x"></i></a></li>
                </ul>
            </div>
        </div>
        <div class="card-content">
            <div id="notify" class="alert alert-success" style="display:none;">
                <a href="#" class="close" data-dismiss="alert">&times;</a>

                <div class="message"></div>
            </div>
            <div class="card-body">
                <div class="row">

                    <div class="col-md-2"><?php echo "Filter" ?></div>
                    <div class="col-md-2">
                        <select name="start_date" id="start_date" class="form-control margin-bottom">
                            <?php 
                                $query = $this->db->query("select distinct date_format(gi.invoicedate,'%Y') year FROM  geopos_invoices as gi order by year desc;");

                                foreach ($query->result() as $row)
                                {
                                    echo '<option value="'.$row->year.'">'.$row->year.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="button" name="search" id="search" value="Search" class="btn btn-info btn-md"/>
                    </div>

                </div>
                <hr>
                <table id="invoices" class="table table-striped table-bordered zero-configuration ">
                    <thead>
                    <tr>
                        <th><?php echo $this->lang->line('No') ?></th>
                        <th><?php echo "Customer" ?></th>
                        <th><?php echo "Address"//echo $this->lang->line('Customer') ?></th>
                        <th><?php echo "Tell"//$this->lang->line('Customer') ?></th>
                        <th><?php echo "Jan"//$this->lang->line('Customer') ?></th>
                        <th><?php echo "Feb"//$this->lang->line('Customer') ?></th>
                        <th><?php echo "Mar"?></th>
                        <th><?php echo "Apr"//$this->lang->line('Amount') ?></th>  
                        <th><?php echo "May"//$this->lang->line('Amount') ?></th> 
                        <th><?php echo "June"//$this->lang->line('Amount') ?></th>  
                        <th><?php echo "Jul"//$this->lang->line('Amount') ?></th>  
                        <th><?php echo "Aug"//$this->lang->line('Amount') ?></th>  
                        <th><?php echo "Sep"//$this->lang->line('Amount') ?></th>  
                        <th><?php echo "Oct"//$this->lang->line('Amount') ?></th>  
                        <th><?php echo "Nov"//$this->lang->line('Amount') ?></th>  
                        <th><?php echo "Des"//$this->lang->line('Amount') ?></th>
                        <th><?php echo "Total"//$this->lang->line('Amount') ?></th>  
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>

                </table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        draw_data();

        function draw_data(start_date = '') {
            $('#invoices').DataTable({
                'processing': true,
                'serverSide': true,
                'stateSave': true,
                responsive: true,
                'order': [],
                'ajax': {
                    'url': "<?php echo site_url('yeartodate/ajax_list')?>",
                    'type': 'POST',
                    'data': {
                        '<?=$this->security->get_csrf_token_name()?>': crsf_hash,
                        start_date: start_date
                    }
                },
                'columnDefs': [
                    {
                        'targets': [0],
                        'orderable': false,
                    },
                ],
                dom: 'Blfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        footer: true,
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10,11,12,13,14,15,16]
                        }
                    }
                ]
            });
        };

        $('#search').click(function () {

            var start_date = $('#start_date').val();
            if (start_date != '') {
                $('#invoices').DataTable().destroy();
                draw_data(start_date);
            } else {
                alert("Date range is Required");
            }
        });
    });
</script>