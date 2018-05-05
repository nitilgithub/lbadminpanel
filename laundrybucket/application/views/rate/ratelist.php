<div class="accordion" id="collapse-group">
    <?php
        $i = 1;
        foreach ($ratelist as $list)
        {
    ?>
    <div class="accordion-group widget-box">
        <div class="accordion-heading">
            <div class="widget-title"> <a data-parent="#collapse-group" href="#collapseG<?= $list->id ?>" data-toggle="collapse">
<!--                    <span class="icon"><i class="icon-magnet"></i></span>-->
                    <h5><?= $list->name ?></h5>
                </a> </div>
        </div>
        <div class="collapse <?= $i == 1 ? 'in' : '' ?> accordion-body" id="collapseG<?= $list->id ?>">

            <div class="widget-content">
                <?php
                $rateservicelist = $list->rateservicelist;
                if(!empty($rateservicelist))
                {
                ?>
                <div class="widget-box">
                    <div class="widget-title">

                        <ul class="nav nav-tabs">
                            <?php
                                $count = 1;
                                foreach ($rateservicelist as $slist)
                                {

                            ?>
                            <li class="<?= $count == 1 ? 'active' : '' ?>"><a data-toggle="tab" href="#tab<?= $slist->ServiceCatId ?>"><?= $slist->ServiceCatName ?></a></li>
                            <?php
                                    $count = $count + 1;
                                }
                            ?>
                        </ul>
                    </div>
                    <div class="widget-content tab-content">
                        <?php
                        $count = 1;
                        foreach ($rateservicelist as $slist)
                        {

                        ?>
                        <div id="tab<?= $slist->ServiceCatId ?>" class="tab-pane <?= $count == 1 ? 'active' : '' ?>">
                            <?php
                                $itemlist = $slist->itemlist;
                                if(!empty($itemlist))
                                {
                            ?>
                            <table class="table table-bordered table-invoice-full">
                                <thead>
                                <tr>
                                    <th class="head0">Item Name</th>
                                    <th class="head1">Standard Rate (₹)</th>
                                    <th class="head0 right">Premium Rate (₹)</th>
                                    <th class="head1 right">Price</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                    foreach ($itemlist as $item)
                                    {
                                ?>
                                <tr>
                                    <td><?= $item->ItemName  ?></td>
                                    <td class="center"><strong><?= $item->StandardRate  ?></strong></td>
                                    <td class="center"><strong><?= $item->PremiumRate  ?></strong></td>
                                    <td class="center"><?= $item->UnitName  ?></td>
                                </tr>
                                <?php
                                    }
                                ?>
                                </tbody>
                            </table>
                            <?php
                                }
                            ?>
                        </div>
                            <?php
                            $count = $count + 1;
                        }
                        ?>

                    </div>
                </div>
                    <?php
                }else{
                ?>
                    <h5 class="text-center">No Record Found!</h5>
                <?php
                }
                ?>
            </div>

        </div>
    </div>
    <?php
            $i = $i + 1;
        }
    ?>
</div>