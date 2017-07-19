<?php

require_once("../config.php");

$host_dir = "http://teckfreeks.com/textimz/";

//$sql = "select i_news_id, s_headline, s_content, s_source from v_news where b_active=1";
$sql = "SELECT a.pk_i_id as i_news_id , a.s_headline, a.s_content, b.s_source from t_news_item as a, t_media as b where a.pk_i_id = b.fk_i_item_id and b_active = 1 group by i_news_id order by a.pk_i_id desc";
	$response = array();
	$posts = array();
	
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	//$res = $stmt->fetch(PDO::FETCH_ASSOC);
	
	while($row = $stmt->fetch(PDO::FETCH_ASSOC))
{


	$i_news_id=$row['i_news_id'];
	$s_headline=$row['s_headline'];
	$s_content=$row['s_content'];
	$s_source =$row['s_source'];

	//echo($s_source);
		//array_push($category_id, $parent_category_id);

	//$image_loc=$inc;

	$posts[] = array('i_news_id'=> $i_news_id, 's_headline'=> $s_headline,'s_content'=>$s_content,'s_source'=>$s_source);
	
}

$response['posts'] = $posts;
makeDir('api_folder');
$fp = fopen('api_folder'.DIRECTORY_SEPARATOR.'news_detail.json', 'w');
fwrite($fp,json_encode($response));
fclose($fp);










$sql = "SELECT a.pk_i_id as i_event_id , a.s_event_name, a.s_date, a.s_time, a.s_venue, a.s_details, a.s_link, a.s_tags, b.s_source from t_events as a, t_media_events as b where a.pk_i_id = b.fk_i_item_id group by i_event_id order by a.pk_i_id desc";
	$response = array();
	$posts = array();
	
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	//$res = $stmt->fetch(PDO::FETCH_ASSOC);
	
	while($row = $stmt->fetch(PDO::FETCH_ASSOC))
{


	$i_event_id=$row['i_event_id'];
	$s_event_name=$row['s_event_name'];
	$s_date=$row['s_date'];
	$s_time =$row['s_time'];
	$s_venue =$row['s_venue'];
	$s_details =$row['s_details'];
	$s_link =$row['s_link'];
	$s_tags =$row['s_tags'];
	$s_source =$row['s_source'];

	//echo($s_source);
		//array_push($category_id, $parent_category_id);

	//$image_loc=$inc;

	$posts[] = array('i_event_id'=> $i_event_id, 's_event_name'=> $s_event_name, 's_date'=>$s_date, 's_time'=>$s_time, 's_venue'=>$s_venue, 's_details'=>$s_details, 's_link'=>$s_link, 's_tags'=>$s_tags, 's_source'=>$s_source );
	
}

$response['posts'] = $posts;
makeDir('api_folder');
$fp = fopen('api_folder'.DIRECTORY_SEPARATOR.'event_detail.json', 'w');
fwrite($fp,json_encode($response));
fclose($fp);













function makeDir($path)
{
     return is_dir($path) || mkdir($path);
}

echo "content updated on ".date('Y-m-d H:i:s');

?>