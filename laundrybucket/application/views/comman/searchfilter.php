<?php
    if($pageheading == "Users")
    {
?>
 <div class="post-search-panel">
                <select class="select" id="filter" >
                <option value="">Search By</option>
                <?php
foreach ($filter['search_option'] as $opt)
{

    ?>
                    <option value="<?php echo $opt['value']; ?>"><?php echo $opt['label']; ?></option>
                    <?php
}
?>
            </select>
            <input type="text" id="keywords" placeholder="Search Here <?= !empty($pageheading) ? $pageheading : '' ?> ..." />
            <input type="button" onclick="searchFilter();" value="Search" class="btn btn-success m-b-10" id="btnsearch" name="btnsearch" >
        </div>
<?php
    }
?>