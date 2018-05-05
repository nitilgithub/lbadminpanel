<div id="addfeedbackModel" style="" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">×</button>
        <h3><span id="user-name-feedback" ></span> Feedback</h3>
    </div>
    <div class="modal-body">
        <div style="text-align: left;overflow: hidden;margin-bottom: 10px;" >
            <!-- Success Alert Show Start Here  -->
            <div id="success-feedback" class="alert alert-success">
                <button class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong> <span id="success-message-feedback" ></span>
            </div>
            <!-- Success Alert Show End Here  -->

            <!-- Error Alert Show Start Here  -->
            <div id="error-feedback" class="alert alert-error">
                <button class="close" data-dismiss="alert">×</button>
                <strong>Error!</strong> <span id="error-message-feedback" ></span>
            </div>
            <!-- Error Alert Show End Here  -->
        <form method="post" id="addUserFeedback" name="addUserAddress"  >
            <input type="hidden" name="userid" id="useridfeedback"  >
            <div class="d-t" >
                <div class="d-tr" >
                    <div class="d-td" >
                        <label for="" >User Name</label>
                        <input type="text" readonly id="feedback-username" >
                    </div>
                    <div class="d-td" >
                        <label for="orderid" >OrderId</label>
                        <select id="orderid" name="orderid" >
                            <option>Select Order Id</option>
                        </select>
                    </div>
                </div>
                <div class="d-tr">
                    <div class="d-td" >
                        <label for="serviceexperience" >Service Experience</label>
                        <select name="serviceexperience" >
                            <option>Select</option>
                            <option value="Extremely Like" >Extremely Like</option>
                            <option value="Satisfied" >Satisfied</option>
                            <option value="Not Satisfied" >Not Satisfied</option>
                        </select>
                    </div>
                    <div class="d-td" >
                        <label for="customerservicerepresentative" >Customer Service Representative</label>
                        <select name="customerservicerepresentative" >
                            <option>Select</option>
                            <option value="Extremely Like" >Extremely Like</option>
                            <option value="Satisfied" >Satisfied</option>
                            <option value="Not Satisfied" >Not Satisfied</option>
                        </select>
                    </div>
                </div>
                <div class="d-tr">
                    <div class="d-td" >
                        <label for="pickupdeliveryrating" >Pickup & Delivery Rating</label>
                        <select name="pickupdeliveryrating" >
                            <option>Select</option>
                            <option value="Extremely Like" >Extremely Like</option>
                            <option value="Satisfied" >Satisfied</option>
                            <option value="Not Satisfied" >Not Satisfied</option>
                        </select>
                    </div>
                    <div class="d-td" >
                        <label for="recommendtofriend" >Recommend to Friend</label>
                        <select name="recommendtofriend" >
                            <option>Select</option>
                            <option value="Extremely Like" >Extremely Like</option>
                            <option value="Satisfied" >Satisfied</option>
                            <option value="Not Satisfied" >Not Satisfied</option>
                        </select>
                    </div>
                </div>
            </div>
            <label for="feedback" >Comment</label>
            <textarea style="width: calc(100% - 57px);" id="feedback" name="feedback" placeholder="Comment" ></textarea>
            <div class="d-t" >
                <div class="d-tr" >
                    <div class="d-td" >
                        <label for="feedbacktakendate" >Feedback Taken</label>
                        <input type="text"  name="feedbacktakendate"  id="feedbacktekandate"  class="datepicker" placeholder="Feedback Taken Date"  />
                    </div>
                    <div class="d-td" >
                        <label for="feedbacktakenby" >Feedback Taken By</label>
                        <select name="feedbacktakenby" >
                            <option>Select</option>
                            <?php
                            foreach($emplist as $option)
                            {
                                ?>
                                <option <?= user_id() == $option->id ? 'selected' : '';   ?> <?= !empty($values) && $values->$cname == $option->id ? 'selected' : ''; ?> value='<?= $option->id; ?>' ><?= $option->name; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <input type="submit" class="btn btn-success pull-left" value="Save" >
        </form>
        </div>
    </div>
</div>

<div id="feedbackModel" style="width: 800px;margin: 0 auto;left: 0;right: 0;" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">×</button>
        <h3><span id="user-name-feedback-model" ></span> Feedback  <span id="down-excel-btn" ></span></h3>
    </div>
    <div class="modal-body">
        <div id="user-feedback-table" ></div>
    </div>
</div>