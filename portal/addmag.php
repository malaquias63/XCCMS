<?php

include("config.php");
include('session.php');
include('header.php');

$mac =  $_POST["name"];
$resellerid =$login_session;
$bouquest = $_POST['boq'];
$bouquet_ids = $_POST['boq'];
$expire_datemonth = $_POST['duration'];
$is_trial=0;
if ($expire_datemonth=="0") {
    $expire_date = strtotime(' + 3 days');
    $is_trial=1;
}elseif ($expire_datemonth=="1") {
    $expire_date = strtotime(' + 30 days');
} elseif ($expire_datemonth=="3") {
    $expire_date = strtotime(' + 90 days');
} elseif ($expire_datemonth=="6") {
    $expire_date = strtotime(' + 183 days');
} elseif ($expire_datemonth=="12") {
    $expire_date = strtotime(' + 365 days');
}
$max_connections = 1;
$reseller = 0;

###############################################################################
$post_data = array( 'user_data' => array(
  'mac' => $mac,
  'exp_date' => $expire_date,
  'bouquet' => json_encode( $bouquet_ids ),
  'member_id' => $owner,
  'id'=>$owner,
  'is_trial'=> $is_trial));

$opts = array( 'http' => array(
    'method' => 'POST',
    'header' => 'Content-type: application/x-www-form-urlencoded',
    'content' => http_build_query( $post_data ) ) );

$context = stream_context_create( $opts );
$api_result = json_decode( file_get_contents( $panel_url . "api.php?action=stb&sub=create", false, $context ) );
?>
<div >
     <?php
     if ($mac !='') {?>
     <div class="alert alert-success" role="alert">
  <strong>MAC ADDED</strong>
</div>
<div class="alert alert-info" role="alert">
MAc ADDRESS: <strong><?php echo $mac?></strong>
</div>
<?php }?>
 </div>
</div>
<form action="addmag.php" method="post">
            <div class="form-group m-4">
                <form>
                        <div class="form-group">
                    <div class="row">
                        <input type="text" class="form-control mb-2" placeholder="MAC ADDRESS" name="name">
                    </div>
                </div>

                    <div class="form-group">
                        <label for="duration">DURATION</label>
                        <select class="form-control" id="duration" name="duration">
                            <option value="1">1 Months</option>
                            <option value="3">3 Months</option>
                            <option value="6">6 Months</option>
                            <option value="9">9 Months</option>
                            <option value="12">12 Months</option>
                        </select>
                    </div>
                    <input type="button" id="select_all" class="btn btn-warning mb-2" name="select_all" value="Select All">
                    <input type="button" id="unselect_all" class="btn btn-danger mb-2" name="unselect_all" value="UNSelect All">
                    <div class="row" style="height: 600px">

                        <select class="custom-select mb-2" id="countries" name="boq[]" multiple>
                        <?php
        $bouquetsq= "SELECT * FROM `bouquets`";
        $result = mysqli_query($db,$bouquetsq);
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                echo "<option value='". $row[id]."' selected>". $row[bouquet_name]."</option>";
            }
        }
        ?>
                        </select>
                    </div>
                    <div class="row">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </form>

   
        <script>
        $('#select_all').click(function() {
            $('#countries option').prop('selected', true);
        });
        $('#unselect_all').click(function() {
            $('#countries option').prop('selected', false);
        });
    </script>
 <?php include('footer.php');?>