<?php
if(empty($isbool))
   {
    	$isbool = array();
   }

if(empty($isactive))
{
    $isactive = array();
}


if(empty($isdate))
{
    $isdate = array();
}
?>
<?php
if(!empty($tbldata) && !isset($tbldata->status))
{
    ?>
<div class="widget-box">
  <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
    <h5><?= !empty($pageheading) ? $pageheading : '' ?>  <span style="color: #5bb75b;" >Total Pending Amount: <?= $penAmount ?> ₹</span></h5>
      <?php
        if(isset($btnExpExcel))
        {
      ?>
    <a href="<?= base_url().midurl().$btnExpExcel->url ?>" class="btn btn-mini btn-danger pull-right m-t-7 m-r-7" ><i class="icon-table" ></i>  <?= $btnExpExcel->lable ?></a>
      <?php
        }
      ?>
    <!-- <span class="icon f-r b-l"><a title="" href="#"><i class="icon-trash"></i> Delete</a></span> -->
  </div>

  <div class="widget-content nopadding"  >

    <table class="table table-bordered data-table tbl-txt-cen">
      <thead>
        <tr>
        	<!-- <th>&nbsp;</th> -->
        	<th>#</th>
        <?php
         foreach($thead as $th)
		 {
		 	echo "<th>".$th."</th>";	
		 }	

         if(!empty($edit)) {
            echo "<th>&nbsp;</th>";
         }

         if(!empty($del)) {
            echo "<th>&nbsp;</th>";
         }

//        if(!empty($view)) {
//            echo "<th>&nbsp;</th>";
//        }

        if(isset($extrabtn))
        {
            foreach ($extrabtn as $ebtn)
            {
                if(isset($ebtn['view-head']) && $ebtn['view-head'] == true )
                {
                    echo "<th>".$ebtn['title']."</th>";
                }else{
                    echo "<th>&nbsp;</th>";
                }

            }
        }

        ?>
        </tr>
      </thead>
      <tbody>
      	<?php
			$count = !empty($cstart) ? $cstart : 1;              	
      		for($i = 0; $i < sizeof($tbldata); $i++)
			{
      	?>
        <tr class="gradeX">
        	<!-- <td>
        		<div class="controls">
        		<label>
        			<input type="checkbox" />
        		</label>
        		</div>
    		</td> -->
    	<?php
    		echo "<td>".$count."</td>";
			
        	for($j = 0; $j < sizeof($thead); $j++)
			// var_dump($td);
		 {
		 	$col = strtolower($thead[$j]);
			$col = trim($col);
			$col = str_replace(' ', '', $col);
			$col = str_replace('/', '_', $col);
			echo "<td class='center' >";
                if( in_array($col, $isbool))
                {
                    echo $tbldata[$i]->$col == 1 ? 'Yes' : 'No';
                }

             elseif( in_array($col, $isactive))
             {
                 echo $tbldata[$i]->$col == 1 ? 'Activated' : 'Deactivated';
             }

             elseif( in_array($col, $isdate) && !empty($col) )
             {
                 echo !empty($tbldata[$i]->$col) ? date('d-M-Y',strtotime($tbldata[$i]->$col)) : 'NA';
             }

			elseif (!empty($imgArry) && in_array($col, $imgArry))
            {
                if(!empty($tbldata[$i]->$col))
                {
                    echo "<img src=".$tbldata[$i]->$col." width='55' height='55' />";
                }else{
                    echo "<img src=".base_url().'assets/img/noimage.png'." width='55' height='55' />";
                }

            }
            elseif($col == 'gender')
            {
                if(!empty($tbldata[$i]->$col))
                {
                    echo $tbldata[$i]->$col == 'm' ? 'Male' : 'Female' ;
                }
            }
			else{
		 	echo $tbldata[$i]->$col;
			}

			echo "</td>";
		 }
		 
		 $id = $tbldata[$i]->id;
		 $id = $encode->enc($id);
		 
    	?>
            <?php
//            if(!empty($view))
//            {
//                ?>
<!--                <td><a class="tip" href="--><?//= base_url().midurl().$view.$id; ?><!--" data-original-title="View More..."><i class="icon-eye-open"></i></a></td>-->
<!--                --><?php
//            }
//            ?>
            <?php
            if(!empty($edit)) {
                ?>
                <td><a class="tip" href="<?= base_url().midurl(). $edit . $id; ?>" data-original-title="Edit"><i class="icon-pencil"></i></a></td>
            <?php
                }
            ?>
            <?php
                if(!empty($del))
                {
            ?>
		  <td><a class="tip" href="<?= base_url().midurl().$del.$id; ?>" Onclick="return ConfirmDelete()" data-original-title="Delete"><i class="icon-trash"></i></a></td>
            <?php
                }

            if(isset($extrabtn))
            {
                foreach ($extrabtn as $ebtn)
                {
                    echo "<td>";
                    if($ebtn['open'] == 'anchor')
                    {
                        $aURL = !empty($ebtn['anchor-url']) ? $ebtn['anchor-url'].$id : '#' ;
                 ?>
                        <a href="<?= $aURL ?>" data-original-title="<?= $ebtn['title'] ?>" class="btn <?= $ebtn['class'] ?> tip" ><i class="<?= $ebtn['icon-class'] ?>" ></i> <?= $ebtn['name'] ?></a>
                 <?php
                    }
                    elseif($ebtn['open'] == 'model')
                    {
                  ?>
                        <button class="btn <?= $ebtn['class'] ?> tip" data-original-title="<?= $ebtn['title'] ?>" ><i class="<?= $ebtn['icon-class'] ?>"></i> <?= $ebtn['name'] ?></button>
                   <?php
                    }
                    ?>
                    <?php
                    echo "</td>";
                }
            }

            ?>

        </tr>
        <?php
        	$count += 1; 
			}
        ?>
      </tbody>
    </table>

  </div>

</div>
    <?php
}
else{
    ?>
    <h3 class="text-center">No Record Found!</h3>
    <?php
}
?>
<?php
if(!empty($pagination) && $pagination == 1)
	{
?>
<div class="dataTables_paginate fg-buttonset ui-buttonset fg-buttonset-multi ui-buttonset-multi paging_full_numbers" id="DataTables_Table_0_paginate">
    	<?php echo $this->ajax_pagination->create_links(); ?>
</div>
<?php
	}
 ?>