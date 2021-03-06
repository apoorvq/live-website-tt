<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>

<?php
//to create, edit, delete(inactivate) a single event event

/**
$_POST[event_id] - 0/false or not set ->new event
$_POST[edit] - 1/true ->view/edit an event
        $_POST[edit] - 0/false or not set ->view
        $_POST[edit] - 1/true ->edit
*/

session_start();
require_once ('./functions.php');
check_login();
if(isset($_GET['id'])) {
    $event_id = intval($_GET['id']);
    $new_event = false;
        
    $res = get_event_details($event_id);
    if($res == null) {
        echo "Invalid ID No";
        exit();
    }
    if(isset($_GET['edit'])) {
        if($_GET['edit']=='true') {
            $edit = true;
            $title = "Edit event #$event_id";
        }
        else {
            $edit = false;
            $title = "View event #$event_id";
        }        
    }
    else {
        $edit = false;
        $title = "View event #$event_id";
    }
    
    $sql = "SELECT s_source FROM t_media_events where fk_i_item_id = $event_id ORDER BY pk_i_id DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if($img= ($stmt->fetch(PDO::FETCH_ASSOC)));
        $img_loc = $img['s_source'];
}
else {
    $new_event = true;
    $edit = true;
    $title = "Add a new event";
}
?>
<style type="text/css">
.tg  {border-collapse:collapse;border-spacing:0;border-color:#999;}
.tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#999;color:#444;background-color:#F7FDFA;border-top-width:1px;border-bottom-width:1px;}
.tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#999;color:#fff;background-color:#26ADE4;border-top-width:1px;border-bottom-width:1px;}
.tg .tg-yw4l{vertical-align:top}
.tg .tg-6k2t{background-color:#D2E4FC;vertical-align:top}
@media screen and (max-width: 767px) {.tg {width: auto !important;}.tg col {width: auto !important;}.tg-wrap {overflow-x: auto;-webkit-overflow-scrolling: touch;}}</style>

<h1><?php echo $title;?></h1>
<form method="POST" action="./event_action.php" enctype="multipart/form-data">
<?php if($new_event) { ?><input type="hidden" name="is_new" id="is_new" value="true"/><?php }?>
<?php if(isset($event_id)) { ?><input type="hidden" name="event_id" id="event_id" value="<?php echo $event_id;?>"/><?php } ?>
<div class="tg-wrap"><table class="tg">
<tr> 
   <th class="tg-6k2t" >Country</th>
    <th class="tg-6k2t" >
    <?php
    if ($edit == true) {?>
      <script type="text/javascript">  $(".textarea").hide(); </script>
      <select name="country" id="country" style="width: 400;">
    <?php  $query = $conn->prepare("SELECT * FROM s_country");
    $query -> execute();
    while ($allCountries = $query->fetch(PDO::FETCH_ASSOC)) { ?>
      <option value="<?php echo $allCountries['pk_i_id']; ?>"<?php if(!$edit) echo "disabled" ;?>> <?php echo $allCountries['country_name']; ?></option>

      <?php $_SESSION['country'] = $_GET['i'];  }
    }
    else if (!$new_item)
      { unset($stmt);
        $con_id = $res['fk_country_id'];
        $stmt = $conn->prepare("SELECT country_name FROM s_country where pk_i_id = :con_id ORDER BY pk_i_id DESC LIMIT 1");
        $stmt->bindParam(':con_id', $con_id);
        $stmt->execute();
        $result= ($stmt->fetch(PDO::FETCH_ASSOC));
        $con = $result['country_name'];
        ?>
      <textarea class ="textarea" style="width: 400;" value="<?php echo $result['pk_i_id']; ?>  "<?php if(!$edit) echo "disabled" ;?>><?php echo $con; ?></textarea>
    <?php  } 
    else if ($new_item == true){ ?>
      <script type="text/javascript">  $(".textarea").hide(); alert("yes") </script>
      <select name="country" id="country_edits" style="width: 400;">
    <?php  $query = $conn->prepare("SELECT * FROM s_country");
    $query -> execute();
    while ($allCountries = $query->fetch(PDO::FETCH_ASSOC)) { ?>
      <option value="<?php echo $allCountries['pk_i_id']; ?>  "<?php if(!$edit) echo "disabled" ;?>> <?php echo $allCountries['country_name']; ?></option>
      <?php } }?>
    </select></th>
  </tr>
  <script type="text/javascript">
      $(function(){
      // bind change event to select
      $('#country').on('change', function () {
        var url      = window.location.href; 
        alert(url);
          var count = $(this).val(); // get selected value
          if (count) { // require a URL
            $('#country').prop('disabled', true);
              window.location = url+"&i="+count; // redirect
          }
          return false;
      });
    });
  </script>

<tr>  
  <th class="tg-6k2t" >City</th>
    <th class="tg-6k2t" >
    <?php
    if ($edit == true) {
      if (isset($_GET['i'])) {
        $i=$_GET['i'];

      ?>
      
      <script type="text/javascript">  $(".textarea").hide(); $(function(){$('#country').prop('disabled', true);</script>
      <select name="city" id="city" style="width: 400;">
    <?php  $query = $conn->prepare("SELECT * FROM s_city WHERE fk_country_id = :i");
      $query->bindParam(':i', $i);
    $query -> execute();
    while ($allCities = $query->fetch(PDO::FETCH_ASSOC)) { ?>
      <option value="<?php echo $allCities['pk_i_id']; ?>"<?php if(!$edit) echo "disabled" ;?>> <?php echo $allCities['city_name']; ?></option>
      <?php }}
    }
    else if (!$new_item)
      {
        unset($stmt);
        unset($result);
        $city_id = $res['fk_city_id'];
        $stmt = $conn->prepare("SELECT city_name FROM s_city where pk_i_id = $city_id ORDER BY pk_i_id DESC LIMIT 1");
        $stmt->execute();
        $result= ($stmt->fetch(PDO::FETCH_ASSOC));
        $cityname = $result['city_name'];
       ?>
      <textarea class ="textarea" style="width: 400;" value="<?php echo $allCities['pk_i_id']; ?>  "<?php if(!$edit) echo "disabled" ;?>><?php echo $cityname; ?></textarea>
    <?php  } 
    else {
    if (isset($_GET['i'])) {
        $i=$_GET['i']; ?>
      <script type="text/javascript">  $(".textarea").hide(); </script>
      <select name="city" id="city" style="width: 400;">
    <?php  $query = $conn->prepare("SELECT * FROM s_city WHERE fk_country_id = :i");
      $query->bindParam(':i', $i);
    $query -> execute();
    while ($allCities = $query->fetch(PDO::FETCH_ASSOC)) { ?>
      <option value="<?php echo $allCities['pk_i_id']; ?>  "<?php if(!$edit) echo "disabled" ;?>> <?php echo $allCities['city_name']; ?></option>
      <?php }} }?>
    </select></th>
  </tr> 
  <tr>
    <th class="tg-yw4l" >Event name</th>
    <th class="tg-yw4l" ><input type="text" name="event" id="event" style="width: 400;" <?php if(!$new_event) echo "value='".$res['event_name']."'";?> <?php if(!$edit) echo "disabled" ;?>/></th>
  </tr>
  <tr>
    <th class="tg-yw4l" >Date Start</th>
    <th class="tg-yw4l" ><input type="date" name="date_start" id="date" style="width: 400;" <?php if(!$new_event) echo "value='".$res['date_start']."'";?> <?php if(!$edit) echo "disabled" ;?>/></th>
  </tr>
  <tr>
    <th class="tg-6k2t" >Date End</th>
    <th class="tg-6k2t" ><input type="date" name="date_end" id="date" style="width: 400;" <?php if(!$new_event) echo "value='".$res['date_end']."'";?> <?php if(!$edit) echo "disabled" ;?>/></th>
  </tr>
  <tr>
    <th class="tg-yw4l" >Time Start</th>
    <th class="tg-yw4l" ><input type="time" name="time_start" id="time" style="width: 400;" <?php if(!$new_event) echo "value='".$res['time_start']."'";?> <?php if(!$edit) echo "disabled" ;?>/></th>
  </tr>
  <tr>
    <th class="tg-6k2t" >Time End</th>
    <th class="tg-6k2t" ><input type="time" name="time_end" id="time" style="width: 400;" <?php if(!$new_event) echo "value='".$res['time_end']."'";?> <?php if(!$edit) echo "disabled" ;?>/></th>
  </tr>
    
  
<tr>  
  <th class="tg-6k2t" >Product</th>
    <th class="tg-6k2t" >
    <?php
    if ($edit == true) {?>
      <script type="text/javascript">  $(".textarea").hide(); </script>
      <select name="Product" id="Product" style="width: 400;">
    <?php  $query = $conn->prepare("SELECT * FROM s_product");
    $query -> execute();
    while ($allproducts = $query->fetch(PDO::FETCH_ASSOC)) { ?>
      <option value="<?php echo $allproducts['pk_i_id']; ?>"<?php if(!$edit) echo "disabled" ;?>> <?php echo $allproducts['product_name']; ?></option>
      <?php }
    }
    else if (!$new_item)
      {
        unset($stmt);
        unset($result);
        $product_id = $res['fk_product_id'];
        $stmt = $conn->prepare("SELECT product_name FROM s_product where pk_i_id = $product_id ORDER BY pk_i_id DESC LIMIT 1");
        $stmt->execute();
        $result= ($stmt->fetch(PDO::FETCH_ASSOC));
        $productname = $result['product_name']; ?>
      <textarea class ="textarea" style="width: 400;" value="<?php echo $allproducts['pk_i_id']; ?>  "<?php if(!$edit) echo "disabled" ;?>><?php echo $productname; ?></textarea>
    <?php  } 
    else { ?>
      <script type="text/javascript">  $(".textarea").hide(); </script>
      <select name="Product" id="Product" style="width: 400;">
    <?php  $query = $conn->prepare("SELECT * FROM s_product");
    $query -> execute();
    while ($allproducts = $query->fetch(PDO::FETCH_ASSOC)) { ?>
      <option value="<?php echo $allproducts['pk_i_id']; ?>  "<?php if(!$edit) echo "disabled" ;?>> <?php echo $allproducts['product_name']; ?></option>
      <?php } }?>
    </select></th>
  </tr> 


  <tr>  
  <th class="tg-6k2t" >Category</th>
    <th class="tg-6k2t" >
    <?php
    if ($edit == true) {?>
      <script type="text/javascript">  $(".textarea").hide(); </script>
      <select name="Category" id="Category" style="width: 400;">
    <?php  $query = $conn->prepare("SELECT * FROM s_categories");
    $query -> execute();
    while ($allcategories = $query->fetch(PDO::FETCH_ASSOC)) { ?>
      <option value="<?php echo $allcategories['pk_i_id']; ?>"<?php if(!$edit) echo "disabled" ;?>> <?php echo $allcategories['categories_name']; ?></option>
      <?php }
    }
    else if (!$new_item)
      { 
        unset($stmt);
        unset($result);
        $category_id = $res['fk_category_id'];
        $stmt = $conn->prepare("SELECT categories_name FROM s_categories where pk_i_id = $category_id ORDER BY pk_i_id DESC LIMIT 1");
        $stmt->execute();
        $result= ($stmt->fetch(PDO::FETCH_ASSOC));
        $categoryname = $result['categories_name']; ?>
      <textarea class ="textarea" style="width: 400;" value="<?php echo $allcategories['pk_i_id']; ?>  "<?php if(!$edit) echo "disabled" ;?>><?php echo $categoryname; ?></textarea>
    <?php  } 
    else { ?>
      <script type="text/javascript">  $(".textarea").hide(); </script>
      <select name="Category" id="Category" style="width: 400;">
    <?php  $query = $conn->prepare("SELECT * FROM s_categories");
    $query -> execute();
    while ($allcategories = $query->fetch(PDO::FETCH_ASSOC)) { ?>
      <option value="<?php echo $allcategories['pk_i_id']; ?>  "<?php if(!$edit) echo "disabled" ;?>> <?php echo $allcategories['categories_name']; ?></option>
      <?php } }?>
    </select></th>
  </tr> 
    <tr>  

  <th class="tg-6k2t" >Who Should attend:</th>
    <th class="tg-6k2t" >
    <?php
    if ($edit == true) {?>
      <script type="text/javascript">  $(".textarea").hide(); </script>
      <select name="attendee" id="attendee" style="width: 400;">
    <?php  $query = $conn->prepare("SELECT * FROM s_attendee");
    $query -> execute();
    while ($allattendies = $query->fetch(PDO::FETCH_ASSOC)) { ?>
      <option value="<?php echo $allattendies['pk_i_id']; ?>"<?php if(!$edit) echo "disabled" ;?>> <?php echo $allattendies['attendee']; ?></option>
      <?php }
    }
    else if (!$new_item)
      { 
        unset($stmt);
        unset($result);
        $attendee_id = $res['fk_attendee_id'];
        $stmt = $conn->prepare("SELECT attendee FROM s_attendee where pk_i_id = $attendee_id ORDER BY pk_i_id DESC LIMIT 1");
        $stmt->execute();
        $result= ($stmt->fetch(PDO::FETCH_ASSOC));
        $attendeename = $result['attendee']; ?>
      <textarea class ="textarea" style="width: 400;" value="<?php echo $allattendies['pk_i_id']; ?>  "<?php if(!$edit) echo "disabled" ;?>><?php echo $attendeename; ?></textarea>
    <?php  } 
    else { ?>
      <script type="text/javascript">  $(".textarea").hide(); </script>
      <select name="city" id="city" style="width: 400;">
    <?php  $query = $conn->prepare("SELECT * FROM s_attendee");
    $query -> execute();
    while ($allattendies = $query->fetch(PDO::FETCH_ASSOC)) { ?>
      <option value="<?php echo $allattendies['pk_i_id']; ?>  "<?php if(!$edit) echo "disabled" ;?>> <?php echo $allattendies['attendee']; ?></option>
      <?php } }?>
    </select></th>
  </tr>
  <tr>
    <th class="tg-yw4l" >Venue</th>
    <th class="tg-yw4l" ><input type="text" name="venue" id="venue" style="width: 400;" <?php if(!$new_event) echo "value='".$res['venue']."'";?> <?php if(!$edit) echo "disabled" ;?>/></th>
  </tr>
  <tr>
    <th class="tg-6k2t" >Description</th>
    <th class="tg-6k2t" ><input type="text" name="details" id="details" style="width: 400; height: 300;" <?php if(!$new_event) echo "value='".$res['details']."'";?> <?php if(!$edit) echo "disabled" ;?>/></th>
  </tr>
  <tr>
    <th class="tg-yw4l" >Links</th>
    <th class="tg-yw4l" ><input type="text" name="link" id="link" style="width: 400;" <?php if(!$new_event) echo "value='".$res['link']."'";?> <?php if(!$edit) echo "disabled" ;?>/></th>
  </tr>  
  <tr>
    <th class="tg-6k2t" >Tags</th>
    <th class="tg-6k2t" ><input type="text" name="tags" id="tags" style="width: 400;" <?php if(!$new_event) echo "value='".$res['tags']."'";?> <?php if(!$edit) echo "disabled" ;?>/></th>
  </tr>  

  
  
 <!-- 
  <tr>
    <td class="tg-6k2t"></td>
    <td class="tg-6k2t"><label id="chars" >Characters left: </label></td>
  </tr>
 --> 
<?php if(!$new_event) { ?>
  <tr>
    <td class="tg-6k2t">Image</td>
    <td class="tg-6k2t"><img src="<?php echo $img_loc; ?>"/></td>
  </tr>
<?php } ?>
<?php if($edit) {?>
  <tr>
    <td class="tg-6k2t"><?php if($new_event) echo "Add an Image"; else echo "Change Image"; ?></td>
    <td class="tg-6k2t"><input type="file" name="fileToUpload" id="fileToUpload"></td>
  </tr>
<?php } ?>

</table></div>


<?php if($new_event) { ?>
<br /><br />
<input type="submit" value="Add this event"/>
<?php } else if ($edit) {?>
<br /><br />
<input type="submit" value="Update this event"/>
<?php } ?>
</form>


<?php if(!$new_event && !$edit) { ?>
<br />
<a href="./event.php?id=<?php echo $event_id ?>&edit=true"><button>Edit this event</button</a>
<?php } ?>


<?php /*
<script type="text/javascript">

document.getElementById('chars').innerHTML = "Characters left: " + (650 - content.value.length);
document.getElementById('content').oninput = function () {
document.getElementById('chars').innerHTML = "Characters left: " + (650 - this.value.length);
};
document.getElementById('content').onkeypress = function () {
document.getElementById('chars').innerHTML = "Characters left: " + (650 - this.value.length);
};
</script>
*/?>