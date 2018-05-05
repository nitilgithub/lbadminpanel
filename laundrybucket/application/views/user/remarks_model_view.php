<div id="addremarksModel" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">×</button>
        <h3><span id="user-name-remarks" ></span> Remarks</h3>
    </div>
    <div class="modal-body">
        <div style="text-align: left;overflow: hidden;margin-bottom: 10px;" >
            <!-- Success Alert Show Start Here  -->
            <div id="success-remarks" class="alert alert-success">
                <button class="close" data-dismiss="alert">×</button>
                <strong>Success!</strong> <span id="success-message-remarks" ></span>
            </div>
            <!-- Success Alert Show End Here  -->

            <!-- Error Alert Show Start Here  -->
            <div id="error-remarks" class="alert alert-error">
                <button class="close" data-dismiss="alert">×</button>
                <strong>Error!</strong> <span id="error-message-remarks" ></span>
            </div>
            <!-- Error Alert Show End Here  -->
            <form method="post" id="addUserRemarks" name="addUserRemarks"  >
                <input type="hidden" name="userid" id="useridremarks" >
                <label class="d-n" for="orderid" >OrderId</label>
                <select class="d-n" id="orderidremark" name="orderid" >
                    <option>Select Order Id</option>
                </select>
                <label for="remarks" >Remarks</label>
                <textarea style="width: calc(100% - 15px);" id="remarks" name="remarks" placeholder="Remarks" ></textarea>
                <input type="submit" class="btn btn-success pull-left" value="Save" >
            </form>

        </div>
    </div>
</div>

<div id="remarksModel" class="modal hide">
    <div class="modal-header">
        <button data-dismiss="modal" class="close" type="button">×</button>
        <h3><span id="user-name-remarks-model" ></span> Remarks</h3>
    </div>
    <div class="modal-body">
        <div id="user-remarks-table" ></div>
    </div>
</div>