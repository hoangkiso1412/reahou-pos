<?php foreach($totalcredit as $d){ ?>
<!-- Modal HTML -->
<div id="part_payment" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">      
                <h4 class="modal-title"><?php echo $this->lang->line('Payment Confirmation') ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <form class="payment" id="form-payment" method="get" action="<?php echo site_url('customercredit/pay') ?>">
                <div class="row">
                        <div class="col mb-1"><label
                                for="pmethod"><?php echo "Total Amount Credit"//$this->lang->line('Payment Method') ?></label>
                                <input type="text" class="form-control" placeholder="Total Amount" name="amount"
                                        id="rmamount"
                                        readonly
                                        value="<?= amountExchange_s(($d['debit']), 0, $this->aauth->get_user()->loc) ?>">
                            </div>
                        </div>
                    <div class="row">
                        <div class="col">
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control" placeholder="Total Amount" name="amount_pay"
                                        id="rmpay">
                                <div class="form-control-position">
                                    <?php echo $this->config->item('currency') ?>
                                </div>
                                
                            </fieldset>
                            
                            
                        </div>
                        <div class="col">
                            <fieldset class="form-group position-relative has-icon-left">
                                <input type="text" class="form-control required"
                                        placeholder="Billing Date" name="paydate"
                                        data-toggle="datepicker">
                                <div class="form-control-position">
                                    <span class="fa fa-calendar"
                                          aria-hidden="true"></span>
                                </div>
                                
                            </fieldset>
                            
                            
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col mb-1"><label
                                for="pmethod"><?php echo $this->lang->line('Payment Method') ?></label>
                            <select name="pmethod" class="form-control mb-1">
                                <option value="Cash"><?php echo $this->lang->line('Cash') ?></option>
                                <option value="Card"><?php echo $this->lang->line('Card') ?></option>
                                <option value="Balance"><?php echo $this->lang->line('Client Balance') ?></option>
                                <option value="Bank"><?php echo $this->lang->line('Bank') ?></option>
                            </select><label for="account"><?php echo $this->lang->line('Account') ?></label>
                            
                            <select name="account" class="form-control">
<?php foreach ($acclist as $row) {
echo '<option value="' . $row['id'] . '">' . $row['holder'] . ' / ' . $row['acn'] . '</option>';
}
?>
                            </select></div>
                    </div>
                    <div class="row">
                        <div class="col mb-1"><label
                                for="shortnote"><?php echo $this->lang->line('Note') ?></label>
                            <input type="text" class="form-control"
                                    name="shortnote" placeholder="Short note"
                                    value="Payment for customer #<?php echo $d['name'] ?>"></div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" class="form-control required"
                                name="tid" id="invoiceid" value="1">
                        <button type="button" class="btn btn-default"
                                data-dismiss="modal"><?php echo $this->lang->line('Close') ?></button>
                        <input type="hidden" name="cid" value="<?php echo $d['id'] ?>"><input
                                                                                                      type="hidden"
                                                                                                      name="cname"
                                                                                                      value="<?php echo $d['name'] ?>">
                        <button type="submit" class="btn btn-primary"><?php echo $this->lang->line('Make Payment'); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if($cancel_enable==1){ ?>
<!-- cancel -->
<div id="cancel_bill" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                
                <h4 class="modal-title"><?php echo $this->lang->line('Cancel Invoice'); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <div class="modal-body">
                <form class="cancelbill">
                    
                    
<?php echo $this->lang->line('You can not revert'); ?>
                    
                    
            </div>
            
            
            <div class="modal-footer">
                <input type="hidden" class="form-control"
                        name="tid" value="<?php echo $invoice['iid'] ?>">
                <button type="button" class="btn btn-default"
                        data-dismiss="modal"><?php echo $this->lang->line('Close'); ?></button>
                <button type="button" class="btn btn-danger"
                        id="send"><?php echo $this->lang->line('Cancel Invoice'); ?></button>
            </div>
            </form>
        </div>
    </div>
</div>

</div>
</div>
<?php }?>
<?php } ?>

<!-- Modal HTML -->
<script>
    $( document ).ready(function() {
      $('#part_payment').modal('toggle');
    });
    $("input[type='text']").click(function () {
      $(this).select();
    });
    function payment(){
      // alert("is ok");
      var rmamount = parseFloat($("#rmamount").val());
      var rmpay    = parseFloat($("#rmpay").val());
      // alert(rmamount);
      if(rmpay>rmamount) {
        alert("Please input with amount pay for less then or equal to amount credit!");
        $("#rmpay").select();
      }else{
        var actionurl = baseurl + 'customercredit/pay';
        alert(actionurl);
        actionProduct(actionurl);
      }
    }
    function actionProduct(actionurl) {
      alert("submit")
      var tid = $("#tid").val();
      var amount = $("#amount_pay").val();
      var paydate = $("#paydate").val();
      var note = $("#shortnote").val();
      var pmethod = $("#pmethod").val();
      var acid = $("#account").val();
      var cid = $("#account").val();
      var cname = $("#cname").val();
      $.ajax({
        url: actionurl,
        type: 'GET',
        data: {tid:$tid,amount:$amount,paydate,$paydate,note:$note,pmethod:$pmethod,acid:$acid,cid:$cid,cname:$cname},
        dataType: 'json',
        success: function (data) {
          console.log(data);
          alert(data);  
        },
        error: function (data) {

        }
      });
    }
    $('#part_payment').on('hidden.bs.modal', function (e) {
      // do something...
      window.open("<?php echo site_url("customercredit/customer_credit_group") ?>","_self");
    })
</script>