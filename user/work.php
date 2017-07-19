<?php
include "../config.php";
if(isset($_GET['w']))
{
	$i = $_GET['w'];
	if ($i=="country") 
	{?>
	<h1>Country</h1>

	<a href="extras.php?mode=add_coun"><button id="addCat">Add New Country</button></a><br><br>
	<a href="extras.php?mode=view_coun"><button id="view">View all Country</button></a><br><br>
	<a href="../user">Home</a>
	<?php 
	}
}

if (isset($_GET['mode'])) 
{
	if ($_GET['mode']=="add_coun") 
	{?>
	<form method="post">
	Add Country :
	<input type='text' name='country'>	
	<br> <button>Submit </button>
	</form>
	<?php }
	if (isset($_POST['country'])) 
	{
		$Country_name = $_POST['country'];
		$check = $conn->prepare("SELECT * FROM s_country WHERE country_name = Country_name");
		$check ->execute();
		while ($result = $check->fetch(PDO::FETCH_ASSOC))
            {
                    $ids[]  = $result['pk_i_id'];
            }
    	$totalIds = count($ids);
    	if ($totalIds >=1 ) 
    	{
    	echo "exist";
    	}
    	else
    	{
		$stmt = $conn->prepare("INSERT INTO s_country(country_name) VALUES(:Country_name)");
		$stmt->bindParam('Country_name', $Country_name);
		$stmt->execute(); 
		}		
	}
}
?>


<!--category in event.php -->
  <tr>  
  <th class="tg-6k2t" >Category</th>
    <th class="tg-6k2t" >
    <?php
    if ($edit == true) {?>
      <script type="text/javascript">  $(".textarea").hide(); </script>
      <select name="category" id="category" style="width: 400;">
    <?php  $query = $conn->prepare("SELECT * FROM t_categories");
    $query -> execute();
    while ($allcategories = $query->fetch(PDO::FETCH_ASSOC)) { ?>
      <option value="<?php echo $allcategories['pk_i_id']; ?>"<?php if(!$edit) echo "disabled" ;?>> <?php echo $allcategories['product_name']; ?></option>
      <?php }
    }
    else if (!$new_item)
      { ?>
      <textarea class ="textarea" style="width: 400;" value="<?php echo $allcategories['pk_i_id']; ?>  "<?php if(!$edit) echo "disabled" ;?>><?php echo $allcategories['product_name']; ?></textarea>
    <?php  } 
    else { ?>
      <script type="text/javascript">  $(".textarea").hide(); </script>
      <select name="category" id="category" style="width: 400;">
    <?php  $query = $conn->prepare("SELECT * FROM t_categories");
    $query -> execute();
    while ($allcategories = $query->fetch(PDO::FETCH_ASSOC)) { ?>
      <option value="<?php echo $allcategories['pk_i_id']; ?>  "<?php if(!$edit) echo "disabled" ;?>> <?php echo $allcategories['product_name']; ?></option>
      <?php } }?>
    </select></th>
  </tr> 

