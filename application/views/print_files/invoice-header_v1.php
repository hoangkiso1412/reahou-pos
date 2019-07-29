<?php $loc = location($invoice['loc']); ?>
<table style="margin-left: 25px">
    <tr>
        <td class="myco">
            <img src="<?php $loc = location($invoice['loc']);
            echo FCPATH . 'userfiles/company/' . $loc['logo'] ?>"
                 class="top_logo">
        </td>
        <td class="myc">
            <?php echo $loc['cname'] ?>
        </td>
        <td class="myw">
            <?php echo $loc['address'] . ',' . $loc['city'] . ', ' . $loc['country'] 
                    . '<br>' . $this->lang->line('Phone') . ': ' . $loc['phone'] 
                    . '<br>' . $this->lang->line('Email') . ' : ' . $loc['email'];
                    if ($loc['taxid']) echo '<br>' . $this->lang->line('Tax') . ' ID: ' . $loc['taxid'];
            ?>
        </td>
    </tr>
</table>
<br>