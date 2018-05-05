<div class="row-fluid">
    <div class="span12">
        <div class="widget-box">
            <div class="widget-title"> <span class="icon"> <i class="icon-barcode"></i> </span>
                <h5 ><?= $userInfo->UserFirstName." ".$userInfo->UserLastName ?>'s Order Summary/Order Dashboard</h5>
            </div>
            <div class="widget-content" id="order-dashboard" >
                <div class="row-fluid">
<!--                    <div class="span6">-->
<!--                        <table class="">-->
<!--                            <tbody>-->
<!--                            <tr>-->
<!--                                <td><h4>Your Company Name</h4></td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td>Your Town</td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td>Your Region/State</td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td>Mobile Phone: +4530422244</td>-->
<!--                            </tr>-->
<!--                            <tr>-->
<!--                                <td >me@company.com</td>-->
<!--                            </tr>-->
<!--                            </tbody>-->
<!--                        </table>-->
<!--                    </div>-->
                    <?php
                        if(isset($orderInfo) && !empty($orderInfo))
                        {
                            foreach($orderInfo as $order)
                            {
                    ?>
                    <div class="span4">
                        <div class="box" >
                            <?php
                                $rbclass = "";
                                $rbtext = "";

                                if($order->orderstatusid == 0)
                                {
                                    $rbtext = "Ready for Pickup";
                                    $rbclass = "pickup-ribbon";
                                }elseif($order->orderstatusid == 1)
                                {
                                    $rbtext = "Received";
                                    $rbclass = "received-ribbon";
                                }elseif($order->orderstatusid == 2)
                                {
                                    $rbtext = " In Process";
                                    $rbclass = "process-ribbon";
                                }elseif($order->orderstatusid == 3)
                                {
                                    $rbtext = "Out for Delivery";
                                    $rbclass = "out-for-delivery-ribbon";
                                }elseif($order->orderstatusid == 4)
                                {
                                    $rbtext = "Delivered";
                                    $rbclass = "deliver-ribbon";
                                }elseif($order->orderstatusid == 5)
                                {
                                    $rbtext = "Cancelled";
                                    $rbclass = "cancel-ribbon";
                                }elseif($order->orderstatusid == 6)
                                {
                                    $rbtext = "Partial Delivered";
                                    $rbclass = "partial-delivered-ribbon";
                                }
                            ?>
                            <div class="ribbon <?= $rbclass ?>"><span><?= $rbtext ?></span></div>

                        <table class="table table-bordered table-invoice m-b-0">
                            <tbody>
                            <tr>
                            <tr>
                                <td class="width30">Order ID:</td>
                                <td class="width70"><strong><?= $order->orderid ?></strong></td>
                            </tr>
                            <tr>
                                <td>Order Date:</td>
                                <td><strong><?= !empty($order->orderdate) ? date('d-M-Y',strtotime($order->orderdate)) : 'NA' ?></strong></td>
                            </tr>
                            <tr>
                                <td>Total Amount:</td>
                                <td><strong><?= !empty($order->ordertotalamount) ? $order->ordertotalamount : '0.00' ?></strong></td>
                            </tr>
                            <tr>
                                <td>Paid Amount:</td>
                                <td><strong><?= !empty($order->orderpaidamount) ? $order->orderpaidamount : '0.00' ?></strong></td>
                            </tr>
                            <tr>
                                <td>Pending Amount:</td>
                                <?php
                                $balance = $order->ordertotalamount - $order->orderpaidamount;
                                ?>
                                <td><strong><?= !empty($balance) ? $balance : '0.00' ?></strong></td>
                            </tr>
                            <tr>
                                <td>
                                </td>
                                <?php
                                $balance = $order->ordertotalamount - $order->orderpaidamount;
                                ?>
                                <td>
                                    <a title="Edit" href="<?= base_url().midurl().$edit.enc($order->id)."/".enc($userInfo->UserId) ?>" class="btn btn-success" >Edit</a>
                                    <?php
                                        if($balance > 0)
                                        {
                                            ?>
                                    <a title="Pay Now" href="<?= base_url().midurl().$paynow.enc($order->id) ?>" class="btn btn-primary" >Pay Now</a>
                                            <?php
                                        }
                                    ?>
                                </td>
                            </tr>
<!--                            <td class="width30">Client Address:</td>-->
<!--                            <td class="width70"><strong>Cliente Company name.</strong> <br>-->
<!--                                501 Mafia Street., washington, <br>-->
<!--                                NYNC 3654 <br>-->
<!--                                Contact No: 123 456-7890 <br>-->
<!--                                Email: youremail@companyname.com </td>-->
                            </tr>
                            </tbody>

                        </table>
                        </div>
                    </div>
                    <?php
                            }
                        }else{
                    ?>
                            <div class="span6">
                                <p>No Data Found!</p>
                            </div>
                    <?php
                        }
                    ?>
                </div>
                <div class="row-fluid">
                    <div class="span12">
<!--                        <div class="pull-right">-->
<!--                            <h4><span>Amount Due:</span> $7,650.00</h4>-->
<!--                            <br>-->
<!--                            <a class="btn btn-primary btn-large pull-right" href="">Pay Invoice</a>-->
<!--                        </div>-->
                        <div class="pull-left" >
                            <ul id="order-status-list" >
                                <li><div class="order-status-icon pickup" ></div> Ready for Pickup</li>
                                <li><div class="order-status-icon received" ></div> Order received</li>
                                <li><div class="order-status-icon process" ></div> Order is in Process</li>
                                <li><div class="order-status-icon readytodeliver" ></div> Order is ready to deliver</li>
                                <li><div class="order-status-icon delivered" ></div> Order delivered</li>
                                <li><div class="order-status-icon cancelled" ></div> Order Cancelled</li>
                                <li><div class="order-status-icon partial-delivered" ></div> Order Partial Delivered</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>