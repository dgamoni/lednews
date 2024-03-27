 <?php

 phpinfo();
 exit;
 
 //YT SEARCH

 //echo strtotime('2014-08-14T14:01:18.000Z');
 //exit;
 
 $realDate = date('Y-m-d H:i:s' ,'1408024878' );
echo $realDate;

exit;
 
 $exec = file_get_contents('test.txt');
 
 echo '<pre>';
 $json_exec = ( json_decode($exec));
 
 $items = $json_exec->items;
 
 foreach ($items as $item){
 	
 	$id = $item->id;
 	$title = $item->snippet->title;
  	?>
  	
  	<option  value="<?php echo $id ?>"   c?php @wp_automatic_opt_selected('<?php echo $id ?>',$camp_youtube_cat) ?> ><?php echo $title ?></option>
  	
  	<?php  
 	 
 	
 }
 
 
 
 
 
 
 exit;
 
 //echo urldecode('https://www.pinterest.com/resource/SearchResource/get/?source_url=%2Fsearch%2F%3Fq%happy&data=%7B%22options%22%3A%7B%22layout%22%3Anull%2C%22places%22%3Afalse%2C%22constraint_string%22%3Anull%2C%22show_scope_selector%22%3Anull%2C%22query%22%3A%22happy%22%2C%22scope%22%3A%22pins%22%2C%22bookmarks%22%3A%5B%22Y2JZakk0ZVU1WWVHMU5SMVp0V2tSQk5WbHFRVFZaYW1zMVRrUkplRnBxWjNwUFJGcHFUa1JTYTFwWFJYcE9WRkpvV20xSmVWcEVVbXBhYWtVeldsZFpORmx0VW0xTlYwVTBUbTFXYUZwSFVYZE9SR2N4VGxST2JVOVVRWGM9OlVIbzVUMkl5Tld4bVJFNXJUMVJOTUZwVVFUSlBSR3MxVFRKUmVFNHlWVFZhUkVVelQwZE9iRmxVU1RSWlYxcHRUbFJuTlU1NlNYaGFhbHBvVG5wc2JFNUVRWGhhVkVGNVdrZFpNazR5UlRSTmVtaHFXVlJhYTA5WFVYaGFSRms5fGY5ZGVkNjE5NTVjN2YwY2UxMGFlZjFlN2Q2ZDA2NmU2Y2RiMDYzNDZmMjJjM2E1YjgzN2FhZmIyZmI1ODkxMjI%3D%22%5D%7D%2C%22context%22%3A%7B%7D%7D&_=1430141949285');
 //echo urldecode('https://www.pinterest.com/resource/SearchResource/get/?source_url=%2Fsearch%2F%3Fq%3Dhappy&data=%7B%22options%22%3A%7B%22layout%22%3Anull%2C%22places%22%3Afalse%2C%22constraint_string%22%3Anull%2C%22show_scope_selector%22%3Anull%2C%22query%22%3A%22happy%22%2C%22scope%22%3A%22pins%22%2C%22bookmarks%22%3A%5B%22Y2JZakk0TVUxSWR6UlBSR2Q1VG5wS2FWbFhVWGhPTWtVd1RtMU5NazFVVG14T2FrNXJXV3BaTVZreVJUUmFSRmt5VDFSSk5FOUVXbXROZWtKcFRrZEZlVTVxVlRSYVIwMTVUMFJHYlUxNmFHaFBWRlY2V21wRk1rNVVVbTA9OlVIbzVUMkl5Tld4bVJFNXJUMVJOTUZwVVFUSlBSR3MxVFRKUmVFNHlWVFZhUkVVelQwZE9iRmxVU1RSWlYxcHRUbFJuTlU1NlNYaGFhbHBvVG5wc2JFNUVRWGhhVkVGNVdrZFpNazR5UlRSTmVtaHFXVlJhYTA5WFVYaGFSRms5fDIxM2I2MjFiNjg1ZWQwNjRjNTlhNzlkYTUwZDE3MjI5NmUzYjljM2QyYmMyZTZlOWE3OGU2ZTBkYjNkZDM0MmY%3D%22%5D%7D%2C%22context%22%3A%7B%7D%7D&module_path=App()%3EHeader()%3ERightHeader()%3ENotificationsConversationsButton(primary_on_hover%3Dfalse%2C+class_name%3Dnotifs+regular+merged%2C+text%3DNotifications%2C+check_limited_login%3Dtrue%2C+button_options%3D%5Bobject+Object%5D%2C+dropdown_options%3D%5Bobject+Object%5D%2C+log_element_type%3D139%2C+show_text%3Dfalse%2C+has_icon%3Dtrue%2C+supports_badge%3Dtrue%2C+show_dropdown_on_hover%3Dfalse%2C+resource%3DUpdatesResource())&_=1430153817150');
  //echo urldecode('https://www.pinterest.com/resource/UserPinsResource/get/?source_url=%2Fwelkerpatrick%2Fpins%2F&data=%7B%22options%22%3A%7B%22username%22%3A%22welkerpatrick%22%2C%22bookmarks%22%3A%5B%22LT4xMjY2NzA2MTIxODA5Mzg2OjI1OjI1fGM4MDhkOGU4MzkzYjc4YTJiNjFlZjA3NjEzZjk2ZGE5NzFiMTlmMmVjZTkzYmQ4NzU2ZDAwYzE0ZGZiYzhlNDQ%3D%22%5D%7D%2C%22context%22%3A%7B%7D%7D&_=1430235845016');
  echo urldecode('https://www.pinterest.com/resource/BoardFeedResource/get/?source_url=%2Fwelkerpatrick%2Frecipes%2F&data=%7B%22options%22%3A%7B%22board_id%22%3A%221266774834151486%22%2C%22board_url%22%3A%2222%2C%22board_layout%22%3A%22default%22%2C%22prepend%22%3Atrue%2C%22page_size%22%3Anull%2C%22access%22%3A%5B%5D%2C%22bookmarks%22%3A%5B%22LT4xMjY2NzA2MTIxNTkzNDMyOjI1OjI1fDg4OTVkMDI0NjJlMzE1ZjFjNWQ0YWYxYWY4MTljZjEyYTAyMzkyMTk0NDQ3NmFkZmQ5MTkyNmNkNTQyNGRhZDM%3D%22%5D%7D%2C%22context%22%3A%7B%7D%7D&module_path=UserProfilePage(resource%3DUserResource(username%3D))%3EUserProfileContent(resource%3DUserResource(username%3D))%3EUserBoards()%3EGrid(resource%3DProfileBoardsResource(username%3D))%3EGridItems(resource%3DProfileBoardsResource(username%3D))%3EBoard(show_board_context%3Dfalse%2C+show_user_icon%3Dfalse%2C+view_type%3DboardCoverImage%2C+component_type%3D1%2C+resource%3DBoardResource(board_id%3D1266774834151486))&_=1430694254182');
 

 
 //curl ini
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_HEADER,0);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
 curl_setopt($ch, CURLOPT_TIMEOUT,20);
 curl_setopt($ch, CURLOPT_REFERER, 'http://www.bing.com/');
 curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8');
 curl_setopt($ch, CURLOPT_MAXREDIRS, 5); // Good leeway for redirections.
 curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Many login forms redirect at least once.
 curl_setopt($ch, CURLOPT_COOKIEJAR , "cookie.txt");
 
 
 
 //curl post
//curl get
$x='error';

$url = 'https://www.pinterest.com/resource/UserPinsResource/get/?source_url=%2Fwelkerpatrick%2Fpins%2F&data=%7B%22options%22%3A%7B%22username%22%3A%22welkerpatrick%22%7D%2C%22context%22%3A%7B%7D%7D&module_path=App()%3EUserProfilePage(resource%3DUserResource(username%3Dwelkerpatrick))%3EUserInfoBar(tab%3Dpins%2C+spinner%3D%5Bobject+Object%5D%2C+resource%3DUserResource(username%3Dwelkerpatrick%2C+invite_code%3Dnull))&_=1430235602830';
$url ="https://www.pinterest.com/resource/UserPinsResource/get/?source_url=%2Fwelkerpatrick%2Fpins%2F&data=%7B%22options%22%3A%7B%22username%22%3A%22welkerpatrick%22%7D%2C%22context%22%3A%7B%7D%7D&module_path=App()%3EUserProfilePage(resource%3DUserResource(username%3Dwelkerpatrick))%3EUserInfoBar(tab%3Dpins%2C+spinner%3D%5Bobject+Object%5D%2C+resource%3DUserResource(username%3Dwelkerpatrick%2C+invite_code%3Dnull))&_=1430662212800";
$url = "https://www.pinterest.com/resource/UserPinsResource/get/?source_url=%2Fwelkerpatrick%2Fpins%2F&data=%7B%22options%22%3A%7B%22username%22%3A%22welkerpatrick%22%7D%2C%22context%22%3A%7B%7D%7D&module_path=App()%3EUserProfilePage(resource%3DUserResource(username%3Dwelkerpatrick))%3EUserInfoBar(tab%3Dpins%2C+spinner%3D%5Bobject+Object%5D%2C+resource%3DUserResource(username%3Dwelkerpatrick%2C+invite_code%3Dnull))&_=1430662212800";
//$url = "https://www.pinterest.com/resource/UserPinsResource/get/?source_url=%2Fwelkerpatrick%2Fpins%2F&data=%7B%22options%22%3A%7B%22username%22%3A%22welkerpatrick%22%2C%22bookmarks%22%3A%5B%22LT4xMjY2NzA2MTIxODMyMDk3OjI1OjI1fDVhN2NmMzE0ZjFhMDljYzM1NGVhYzc0M2JjODBiNzMzMjEwOWE2ZTJjMGM5NzQ1NmI5MDg4N2FmZjgwODQ3ZTU%3D%22%5D%7D%2C%22context%22%3A%7B%7D%7D&module_path=App()%3EUserProfilePage(resource%3DUserResource(username%3Dwelkerpatrick))%3EUserInfoBar(tab%3Dpins%2C+spinner%3D%5Bobject+Object%5D%2C+resource%3DUserResource(username%3Dwelkerpatrick%2C+invite_code%3Dnull))&_=1430662212806";

//first page search
$url='https://www.pinterest.com/resource/SearchResource/get/?source_url=%2Fsearch%2Fpins%2F%3Fq%3Dquotes&data=%7B%22options%22%3A%7B%22layout%22%3Anull%2C%22places%22%3Afalse%2C%22constraint_string%22%3Anull%2C%22show_scope_selector%22%3Atrue%2C%22query%22%3A%22quotes%22%2C%22scope%22%3A%22pins%22%2C%22bookmarks%22%3A%5B%22Y2JZakk0TlU1NlZqaFpiVVUwVFdwV2FFMVhSVE5hYWtGNFQwZE5lRTlIU21oTk1rcG9UMFJCZUUxcVVUTmFSRmswVFhwWmVVMUVhM3BaVkVacFRWUkplRnBYVVhkWlZGbDVXVlJCTWsxNlJYbFplbGw2VDBSUmVWcEhWVEJQUVQwOTpVSG81VDJJeU5XeG1SRTVyVDFSTk1GcFVRVEpQUkdzMVRUSlJlRTR5VlRWYVJFVXpUMGRPYkZsVVNUUlpWMXB0VGxSbk5VNTZTWGhhYWxwb1RucHNiRTVFUVhoYVZFRjVXa2RaTWs0eVJUUk5lbWhxV1ZSYWEwOVhVWGhhUkZrOXxiYjVjMDhmMzYwYzBlNTlkOThhNGUwYzdiMDBlMDFlMTdjNmE1Y2JhYzZlZTZlODU1NzQxZjc0NGQ3MjU2NWVm%22%5D%7D%2C%22context%22%3A%7B%7D%7D&module_path=App()%3EHeader()%3ESearchForm()%3ETypeaheadField(enable_recent_queries%3Dtrue%2C+support_guided_search%3Dtrue%2C+resource_name%3DAdvancedTypeaheadResource%2C+name%3Dq%2C+tags%3Dautocomplete%2C+class_name%3DbuttonOnRight%2C+type%3Dtokenized%2C+prefetch_on_focus%3Dtrue%2C+value%3D%22%22%2C+input_log_element_type%3D227%2C+hide_tokens_on_focus%3Dundefined%2C+support_advanced_typeahead%3Dfalse%2C+view_type%3Dguided%2C+populate_on_result_highlight%3Dtrue%2C+search_delay%3D0%2C+search_on_focus%3Dtrue%2C+placeholder%3DFind+creative+ideas%2C+show_remove_all%3Dtrue)&_=1430226604794';

//welpatric replaced with annalouse
$url ="https://www.pinterest.com/resource/UserPinsResource/get/?source_url=%2Fannelouise%2Fpins%2F&data=%7B%22options%22%3A%7B%22username%22%3A%22annelouise%22%2C%22bookmarks%22%3A%5B%22LT4xMjY2NzA2MTIxODMyMjgwOjI1OjI1fDliOTgzOGY3YTI0M2UxMzdjMTY2ODM2YWM1Mjc4MWEzODg5MDQ0YTg4OTMzYWNjNDFmN2MyZmI3NjBhNjg3NmQ%3D%22%5D%7D%2C%22context%22%3A%7B%7D%7D&module_path=App()%3EUserProfilePage(resource%3DUserResource(username%3Dannelouise))%3EUserInfoBar(tab%3Dpins%2C+spinner%3D%5Bobject+Object%5D%2C+resource%3DUserResource(username%3Dannelouise%2C+invite_code%3Dnull))&_=1430667298566";

//welpatric vanillia request
$url ="https://www.pinterest.com/resource/UserPinsResource/get/?source_url=%2Fwelkerpatrick%2Fpins%2F&data=%7B%22options%22%3A%7B%22username%22%3A%22welkerpatrick%22%2C%22bookmarks%22%3A%5B%22LT4xMjY2NzA2MTIxODEyNjgwOjMzNzozNTB8ZjIyNWU4NGQ4ZGE5MDcyNmZmY2U5YmNkMGMwMWRhMWYzYTQyYTZjM2Q2Y2M5ZmYxMWZhMGIwZmQ0NmY2NDVhNg%3D%3D%22%5D%7D%2C%22context%22%3A%7B%7D%7D&module_path=App()%3EUserProfilePage(resource%3DUserResource(username%3Dwelkerpatrick))%3EUserInfoBar(tab%3Dpins%2C+spinner%3D%5Bobject+Object%5D%2C+resource%3DUserResource(username%3Dwelkerpatrick%2C+invite_code%3Dnull))&_=1430667298583";

//copied from automatic
$url ="https://www.pinterest.com/resource/BoardFeedResource/get/?source_url=%2Fwelkerpatrick%2Frecipes%2F&data=%7B%22options%22%3A%7B%22board_id%22%3A%2225543991575445642%22%2C%22board_url%22%3A%22%2Fwelkerpatrick%2Frecipes%2F%22%2C%22board_layout%22%3A%22default%22%2C%22prepend%22%3Atrue%2C%22page_size%22%3Anull%2C%22access%22%3A%5B%5D%7D%2C%22context%22%3A%7B%7D%7D&module_path=App()%3EUserProfilePage(resource%3DUserResource(username%3Dwelkerpatrick))%3EUserProfileContent(resource%3DUserResource(username%3Dwelkerpatrick))%3EUserBoards()%3EGrid(resource%3DProfileBoardsResource(username%3Dwelkerpatrick))%3EGridItems(resource%3DProfileBoardsResource(username%3Dwelkerpatrick))%3EBoard(show_board_context%3Dfalse%2C+show_user_icon%3Dfalse%2C+view_type%3DboardCoverImage%2C+component_type%3D1%2C+resource%3DBoardResource(board_id%3D25543991575445642))&_=1430694254166";



curl_setopt($ch, CURLOPT_HTTPGET, 1);
curl_setopt($ch, CURLOPT_URL, trim($url));

curl_setopt($ch,CURLOPT_HTTPHEADER,array('Referer: https://www.pinterest.com/','X-NEW-APP: 1','X-Requested-With: XMLHttpRequest'));


	$exec=curl_exec($ch);
	 $x=curl_error($ch);
 
	 echo '<pre>';
  print_r(json_decode($exec));

 
 
 exit;
 
 
 $exec = ' i love  href="/banana">bana href="//ban ';

 $exec = preg_replace('{href="/(\w)}', 'href="host/$1', $exec);
 
 echo $exec;

  
 exit;
  
//curl ini
$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER,0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_TIMEOUT,20);
curl_setopt($ch, CURLOPT_REFERER, 'http://www.bing.com/');
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.8) Gecko/2009032609 Firefox/3.0.8');
curl_setopt($ch, CURLOPT_MAXREDIRS, 5); // Good leeway for redirections.
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // Many login forms redirect at least once.
curl_setopt($ch, CURLOPT_COOKIEJAR , "cookie.txt");
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

curl_setopt($ch,CURLOPT_HTTPHEADER,array('Authorization: bearer 25af5917e444b4be6880e252447ca83f'));


//curl get
$x='error';


$url='https://api.vimeo.com/videos?query=ronaldo';
$url='https://api.vimeo.com/users/staffpicks/videos';

//$url = 'https://api.vimeo.com/users/cguinness/videos';

curl_setopt($ch, CURLOPT_HTTPGET, 1);
curl_setopt($ch, CURLOPT_URL, trim($url));
 
	$exec=curl_exec($ch);
	 $x=curl_error($ch);
 
	 echo $exec.$x;
 
?>

