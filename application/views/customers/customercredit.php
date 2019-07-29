<div class="content-body">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title"><?php echo "Customer Credit"//$this->lang->line('Manage Invoices') ?></h4>
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

                    <div class="col-md-2"><?php echo $this->lang->line('Invoice Date') ?></div>
                    <div class="col-md-2">
                        <input type="text" name="start_date" id="start_date"
                               class="date30 form-control form-control-sm" autocomplete="off"/>
                    </div>
                    <div class="col-md-2">
                        <input type="text" name="end_date" id="end_date" class="form-control form-control-sm"
                               data-toggle="datepicker" autocomplete="off"/>
                    </div>

                    <div class="col-md-2">
                        <input type="button" name="search" id="search" value="Search" class="btn btn-info btn-sm"/>
                    </div>

                </div>
                <hr>
                <table id="invoices" class="table table-striped table-bordered zero-configuration ">
                    <thead>
                    <tr>
                        <th><?php echo $this->lang->line('No') ?></th>
                        <th>#</th>
                        <th><?php echo "Account Name"//echo $this->lang->line('Customer') ?></th>
                        <th><?php echo "Zoon"//$this->lang->line('Customer') ?></th>
                        <th><?php echo "Reference"//$this->lang->line('Customer') ?></th>
                        <th><?php echo "Date"//$this->lang->line('Customer') ?></th>
                        <th><?php echo $this->lang->line('Amount') ?></th>
                        <th><?php echo "Age"//$this->lang->line('Amount') ?></th>  
                        <th><?php echo "Due Date"//$this->lang->line('Amount') ?></th> 
                        <th><?php echo "Sale rep"//$this->lang->line('Amount') ?></th>  
                    </tr>
                    </thead>
                    <tbody>
                    </tbody>

                    <tfoot>
                    <tr>
                        <th><?php echo $this->lang->line('No') ?></th>
                        <th> #</th>
                        <th><?php echo "Acount Name"//echo $this->lang->line('Customer') ?></th>
                        <th><?php echo "Zoon"//$this->lang->line('Customer') ?></th>
                        <th><?php echo "Reference"//$this->lang->line('Customer') ?></th>
                        <th><?php echo "Date"//$this->lang->line('Customer') ?></th>
                        <th><?php echo $this->lang->line('Amount') ?></th>
                        <th><?php echo "Age"//$this->lang->line('Amount') ?></th>  
                         <th><?php echo "Due Date"//$this->lang->line('Amount') ?></th> 
                        <th><?php echo "Sale rep"//$this->lang->line('Amount') ?></th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>


<div id="delete_model" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

                <h4 class="modal-title"><?php echo $this->lang->line('Delete Invoice') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <p><?php echo $this->lang->line('delete this invoice') ?> ?</p>
            </div>
            <div class="modal-footer">
                <input type="hidden" id="object-id" value="">
                <input type="hidden" id="action-url" value="invoices/delete_i">
                <button type="button" data-dismiss="modal" class="btn btn-primary"
                        id="delete-confirm"><?php echo $this->lang->line('Delete') ?></button>
                <button type="button" data-dismiss="modal"
                        class="btn"><?php echo $this->lang->line('Cancel') ?></button>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        draw_data();

        function draw_data(start_date = '', end_date = '') {
            $('#invoices').DataTable({
                'processing': true,
                'serverSide': true,
                'stateSave': true,
                responsive: true,
                'order': [],
                'ajax': {
                    'url': "<?php echo site_url('customercredit/ajax_list')?>",
                    'type': 'POST',
                    'data': {
                        '<?=$this->security->get_csrf_token_name()?>': crsf_hash,
                        start_date: start_date,
                        end_date: end_date
                    }
                },
                'columnDefs': [
                    {
                        'targets': [0],
                        'orderable': false,
                    },
                ],
                "drawCallback": function ( settings ) {
                    var api = this.api();
                    var rows = api.rows( {page:'current'} ).nodes();
                    var last=null;
                    var subTotal = new Array();
                    var groupID = -1;
                    var aData = new Array();
                    var index = 0;
                    
                    api.column(1, {page:'current'} ).data().each( function ( group, i ) {
                        
                    // console.log(group+">>>"+i);
                    
                    var vals = api.row(api.row($(rows).eq(i)).index()).data();
                    var salary = vals[6] ? parseFloat(vals[6]) : 0;

                    // alert(salary);
                    
                    if (typeof aData[group] == 'undefined') {
                        aData[group] = new Array();
                        aData[group].rows = [];
                        aData[group].salary = [];
                    }
                
                        aData[group].rows.push(i); 
                        aData[group].salary.push(salary); 
                        
                    } );
                    var idx= 0;
                    for(var office in aData){
                        idx =  Math.max.apply(Math,aData[office].rows);
                        var sum = 0; 
                        $.each(aData[office].salary,function(k,v){
                            sum = sum + v;
                        });
                        console.log(aData[office].salary);
                        $(rows).eq( idx ).after(
                            '<tr class="group"><td colspan="5"></td><td>'+'TOTAL'+'</td>'+
                            '<td>'+(sum.toFixed(2))+'</td><td></td><td></td></tr>'
                        );  
                    };
                },
                dom: 'Blfrtip',
                buttons: [
                    {
                        extend: 'excelHtml5',
                        footer: true,
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
                        }
                    }
                ],
            });
        };

        $('#search').click(function () {
            alert('test');
            var start_date = $('#start_date').val();
            var end_date = $('#end_date').val();
            if (start_date != '' && end_date != '') {
                $('#invoices').DataTable().destroy();
                draw_data(start_date, end_date);
            } else {
                alert("Date range is Required");
            }
        });
    });
</script>