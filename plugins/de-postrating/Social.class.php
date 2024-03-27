<?php
//error_reporting(E_ALL);

class Social{

	//*****************************************************************************************************
	protected $oDb;

	// Параметри БД
	public $dbLogin 				= "root";
	public $dbPassword              = "";
	public $dbName					= "shopdemo";
	public $dbHost					= "localhost";
	
	protected $oVkontakte				= null;
	public $vkApiId						= '';
	public $vkSecretKey					= "";
	
	protected $oFacebook				= null;
	public $fbApiId						= '';
	public $fbSecretKey					= '';
	
	protected	$bShowDebugMessages		= false;
	protected	$ClassPath				= null;

	// dirty - методы используют получение данных через служебные линки
	// clean - методы используют oфициальные АПИ. Вконтакте это не касается
	protected	$Config		= array(
											'Facebook'		=> array('enabled' => true, 	'method' => 'clean'),
											'Vkontakte'		=> array('enabled' => true,		'method' => 'dirty', 'type' => 'share'),
											'Twitter'		=> array('enabled' => true,		'method' => 'dirty'),
											'LinkedIn'		=> array('disabled' => false,	'method' => 'dirty'),
											'GooglePlus'	=> array('enabled' => true,		'method' => 'dirty'),
											'Odnoklassniki'	=> array('disabled' => false,	'method' => 'dirty'),
								);
	

	//*****************************************************************************************************
	function __construct($aDbParams	= null){
	
		if(!empty($aDbParams)){
			$this->dbLogin 		= $aDbParams['DbLogin'];
			$this->dbPassword   = $aDbParams['DbPassword'];
			$this->dbName		= $aDbParams['DbName'];
			$this->dbHost		= $aDbParams['DbHost'];
		}

        $this->fbApiId	= get_option('de_fbAppId');
        $this->fbSecretKey	= get_option('de_fbSecretKey');

        $this->vkApiId	= get_option('de_vkAppId');
        $this->vkSecretKey	= get_option('de_vkSecretKey');

        $this->Config['Facebook']['enabled'] = ( get_option('de_fb_enable') == 'enable' ? true : false);
        $this->Config['Facebook']['method'] = ( get_option('de_fb_method') == 'clean' ? 'clean' : 'dirty');
        $this->Config['Vkontakte']['enabled'] = ( get_option('de_vk_enable') == 'enable' ? true : false);
        $this->Config['Vkontakte']['method'] = ( get_option('de_vk_method') == 'clean' ? 'clean' : 'dirty');
        $this->Config['Vkontakte']['type'] = ( get_option('de_vk_type') == 'share' ? 'share' : 'like');
        $this->Config['Twitter']['enabled'] = ( get_option('de_twi_enable') == 'enable' ? true : false);
        $this->Config['LinkedIn']['enabled'] = ( get_option('de_li_enable') == 'enable' ? true : false);
        $this->Config['GooglePlus']['enabled'] = ( get_option('de_gplus_enable') == 'enable' ? true : false);
        $this->Config['Odnoklassniki']['enabled'] = ( get_option('de_ok_enable') == 'enable' ? true : false);

		$this->ClassPath	= dirname(__FILE__);
		if ($this->ConnectDb()){
			$this->CheckCreateTables();
			return true;
		}else return false;
		
	}
	
	//*****************************************************************************************************
	protected function CheckCreateTables(){

		$sQuery 	= "SELECT COUNT(*) AS TableCount FROM information_schema.tables 
						WHERE table_name = 'social_rank';";
		$aResult	= $this->oDb->SelectRow($sQuery);

		if($aResult['TableCount'] == 0) $this->Install();
		return true;
	}
	
	//*****************************************************************************************************
	protected function ConnectDb(){
		if(!$this->oDb){
			if( !class_exists('DbSimple_Generic') ) require_once($this->ClassPath.'/includes/DbSimple/Generic.php');
			$this->oDb	= DbSimple_Generic::connect("mysql://".$this->dbLogin.":".$this->dbPassword."@".$this->dbHost."/".$this->dbName."");
			if($this->oDb){
				$this->oDb->query('SET NAMES utf8');
				return true;
			}else return false;
		}else return true;
	}
	
	//*****************************************************************************************************
	protected function ConnectFacebook(){
		if(!$this->oFacebook){
			require_once($this->ClassPath.'/includes/facebook.php');
			$this->oFacebook 	= new Facebook(array(
				'appId'  => $this->fbApiId ,
				'secret' => $this->fbSecretKey ,
				'cookie' => true,
			));

			if($this->oFacebook) return true;
			else return false;
		}else return true;
	}
	
	//*****************************************************************************************************
	protected function ConnectVk(){
		if(!$this->oVkontakte){
			require_once($this->ClassPath.'/includes/vkapi.class.php');
			$this->oVkontakte	= new vkapi($this->vkApiId, $this->vkSecretKey);
			
			if($this->oVkontakte) return true;
			else return false;
		}else return true;
	}
	
	//*****************************************************************************************************
	protected function ShowDebugMessage($uParam){
		if($this->bShowDebugMessages){
			print "<br><hr><br> ".date("H:i:s").":<br>";;
			print_r($uParam);
		}
	}
	
	//*****************************************************************************************************
	protected function SaveRecord($aRecord){
		$sQuery			= "INSERT INTO `social_rank` (?#) VALUES (?a)
							ON DUPLICATE KEY UPDATE ?a";
							
		$aKeys 		= array_keys($aRecord);
		$aValues	= array_values($aRecord);
		
		$aRecordOnUpdate = $aRecord;
		unset($aRecordOnUpdate['page_id']);
			
		return $this->oDb->Query($sQuery, $aKeys, $aValues, $aRecordOnUpdate);	

	}
	
	//*****************************************************************************************************
	public function GetStatsByPageId($iPageId){
		$sStatsQuery	= "SELECT * FROM social_rank WHERE page_id = ?d LIMIT 1";
		return $this->oDb->SelectRow($sStatsQuery, $iPageId);
	}
	
	//*****************************************************************************************************
	protected function GetFacebookVotesCountByUrl($sPageUrl){
		$aConfig	= $this->Config['Facebook'];
		if($aConfig['enabled'] == false) return 0;

		if($aConfig['method'] == 'clean'){		
			$this->ConnectFacebook();
			$aData		= array();
			$aData		= $this->oFacebook->api(array(
				'method'	=> 'fql.query',
				'query'		=> 'select total_count from link_stat where url = "'.$sPageUrl.'";'
				));
			return $aData[0]['total_count'];
		}else{
			$iSharesCount	= @json_decode(file_get_contents('http://graph.facebook.com/'.urlencode($sPageUrl)))->shares;
			if($iSharesCount) return $iSharesCount;
			else return 0;
		}
	}
	
	//*****************************************************************************************************
	protected function GetTwitterVotesCountByUrl($sPageUrl){
		$aConfig	= $this->Config['Twitter'];
		if($aConfig['enabled'] == false) return 0;
	

		$sApiAnswer 		= file_get_contents('http://urls.api.twitter.com/1/urls/count.json?url='.urlencode($sPageUrl));
        $aDecodedAnswer 	= json_decode($sApiAnswer,true);
        if($aDecodedAnswer['count']){
			$iTwVotesCount = $aDecodedAnswer['count'];
		}else $iTwVotesCount = 0; //TODO: У випадку відмови сервера АПІ підставляти попереднє значення лічильника

		return $iTwVotesCount;
	}
	
	//*****************************************************************************************************
	// http://ar3b.com/wp-content/uploads/socialsharescount.txt
	protected function GetOdnoklassnikiVotesCountByUrl($sPageUrl){
		$aConfig	= $this->Config['Odnoklassniki'];
		if($aConfig['enabled'] == false) return 0;
	
		if (!($request = file_get_contents('http://www.odnoklassniki.ru/dk?st.cmd=extOneClickLike&uid=odklocs0&ref='.$sPageUrl)))
			return 0;
		$tmp = array();
        if (!(preg_match("/^ODKL.updateCountOC\('[\d\w]+','(\d+)','(\d+)','(\d+)'\);$/i",$request,$tmp)))
			return false;
		return $tmp[1];
	}
	
	//*****************************************************************************************************
	protected function GetLinkedinVotesCountByUrl($sPageUrl){
		$aConfig	= $this->Config['LinkedIn'];
		if($aConfig['enabled'] == false) return 0;
		
		if($aConfig['method'] == 'dirty'){		
			return json_decode(str_replace(array('IN.Tags.Share.handleCount(',');'),'',
				file_get_contents('http://www.linkedin.com/countserv/count/share?url='.urlencode($sPageUrl))))->count;
		}else return -1;
	}
	
	//*****************************************************************************************************
	protected function GetGoogleplusVotesCountByUrl($sPageUrl){
		$aConfig	= $this->Config['GooglePlus'];
		if($aConfig['enabled'] == false) return 0;
	
	
		if($aConfig['method'] == 'dirty'){	
			$ch = curl_init();
			curl_setopt_array($ch, array( 
			CURLOPT_HTTPHEADER => array('Content-type: application/json'), 
			CURLOPT_POST => true, 
			CURLOPT_POSTFIELDS => '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"'.$sPageUrl.'","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]',
			CURLOPT_RETURNTRANSFER => true, 
			CURLOPT_SSL_VERIFYPEER => false, 
			CURLOPT_URL => 'https://clients6.google.com/rpc?key=AIzaSyCKSbrvQasunBoV16zDH9R33D88CeLr9gQ' 
			)); 
			$res = curl_exec($ch); 
			curl_close($ch); 
			$json = json_decode($res,true); 
			return @$json[0]['result']['metadata']['globalCounts']['count']; 
		}
	}
	
	//*****************************************************************************************************
	protected function GetVkontakteVotesByUrl($sPageUrl){
		$aConfig	= $this->Config['Vkontakte'];
        $svkApiId   = $this->vkApiId;
		if($aConfig['enabled'] == false) return 0;

        /**/ $oUniLog	= GetLog();
        if( $aConfig['type'] == 'share' ) {
            /**/ $oUniLog->logDebug('Vk gathering shares!');
			if (!($request = file_get_contents('http://vkontakte.ru/share.php?act=count&index=1&url='.$sPageUrl)))
				return false;
			$tmp = array();
			if (!(preg_match('/^VK.Share.count\((\d+), (\d+)\);$/i',$request,$tmp)))
				return 0;
			return $tmp[2];
		}elseif( ($aConfig['type'] == 'like') && ($aConfig['method'] == 'dirty') ){
		    /**/ $oUniLog->logDebug('Vk gathering likes (dirty method)!');
            $Response = file_get_contents("http://vk.com/widget_like.php?app=".$svkApiId."&url=".$sPageUrl);
            $aResponse = explode('<b>',$Response);
            $sResponse = $aResponse[1];
            $aVkLikes = explode('</b>',$sResponse);
            if ( !$aVkLikes[0] ) return 0;
            return $aVkLikes[0];
		}elseif( ($aConfig['type'] == 'like') && ($aConfig['method'] == 'clean') ){
		    /**/ $oUniLog->logDebug('Vk gathering likes (clean method)!');
			$this->ConnectVk();
			$aApiResponse 	= $this->oVkontakte->api('likes.getList', array(
																	'type' 		=> 'sitepage', 
																	'owner_id'	=> $this->vkApiId,
																	'page_url'	=> $sPageUrl
																)
											);
	
			if(!empty($aApiResponse['response']['count'])){
				return $aApiResponse['response']['count'];
			}else return 0;
		}
	

	}
	
	//*****************************************************************************************************
	public function GetTotalVotesCountByPageId($iPageId, $sPageUrl, $sPageAddDatetime = null){
		if(empty($sPageAddDatetime)) $iPageAddDatetime = time();
		else $iPageAddDatetime	= strtotime($sPageAddDatetime);

		$aData	= $this->GetStatsByPageId($iPageId);
		
		if($aData){
			if($aData['page_url'] !== $sPageUrl){
				$aData['page_url'] = $sPageUrl;
				$aData = $this->UpdateRecord($aData);
			}
			return $aData['page_votes_sum'];
		}else{
			$aData	= array();
			$aData['page_id']				= $iPageId;
			$aData['page_url']				= $sPageUrl;
			$aData['page_fb_votes']			= $this->GetFacebookVotesCountByUrl($sPageUrl);
			$aData['page_tw_votes']			= $this->GetTwitterVotesCountByUrl($sPageUrl);
			$aData['page_vk_votes']			= $this->GetVkontakteVotesByUrl($sPageUrl);
			$aData['page_ln_votes']			= $this->GetLinkedinVotesCountByUrl($sPageUrl);
			$aData['page_gp_votes']			= $this->GetGoogleplusVotesCountByUrl($sPageUrl);
			$aData['page_ok_votes']			= $this->GetOdnoklassnikiVotesCountByUrl($sPageUrl);
			
			$aData['page_votes_sum']		= $aData['page_fb_votes'] + $aData['page_tw_votes'] + $aData['page_vk_votes']
												+ $aData['page_gp_votes'] + $aData['page_ln_votes'] + $aData['page_ok_votes'];
			$aData['page_update_datetime']	= time();
			$aData['page_add_datetime']		= $iPageAddDatetime;
			
			$this->SaveRecord($aData);
			return 	$aData['page_votes_sum'];
		}
	}
	
	//*****************************************************************************************************
	protected function BuildWhereStatementFromFilter($aFilter){
		$sWhere = ' WHERE 1=1 ';
		
		if(isset($aFilter['add_date_from'])){
			$sWhere.=" AND `page_add_datetime` >= ".(int)$aFilter['add_date_from'];
		}
		
		if(isset($aFilter['add_date_for'])){
			$sWhere.=" AND `page_add_datetime` <= ".(int)$aFilter['add_date_for'];
		}

		if(isset($aFilter['page_id'])){
			$sWhere.=" AND `page_id` = ".(int)$aFilter['page_id'];
		}
		
		return $sWhere;
	}
	
	//*****************************************************************************************************
	public function GetRecordsByFilter($aFilter){
		$sWhere		= $this->BuildWhereStatementFromFilter($aFilter);
		$sQuery		= "SELECT * FROM social_rank".$sWhere;

		if(isset($aFilter['Order']['Column'])){
			$sQuery.= " ORDER BY ".$aFilter['Order']['Column']." ".$aFilter['Order']['Direction'];
		}else $sQuery.= " ORDER BY page_update_datetime ASC";
		
		if(isset($aFilter['Limit'])){
			$sQuery.= " LIMIT ".(int)$aFilter['Limit'];
		}

		$aRecords	= $this->oDb->Select($sQuery);
		
		if($aRecords) return $aRecords;
		else return array();
	}
	
	//*****************************************************************************************************
	protected function UpdateRecord($aRecord){
	    /**/ $oUniLog	= GetLog();
		$aRecord['page_fb_votes']			= $this->GetFacebookVotesCountByUrl($aRecord['page_url']);
		$aRecord['page_tw_votes']			= $this->GetTwitterVotesCountByUrl($aRecord['page_url']);
		$aRecord['page_vk_votes']			= $this->GetVkontakteVotesByUrl($aRecord['page_url']);
		$aRecord['page_ln_votes']			= $this->GetLinkedinVotesCountByUrl($aRecord['page_url']);
		$aRecord['page_gp_votes']			= $this->GetGoogleplusVotesCountByUrl($aRecord['page_url']);
		$aRecord['page_ok_votes']			= $this->GetOdnoklassnikiVotesCountByUrl($aRecord['page_url']);
		$aRecord['page_votes_sum']			= $aRecord['page_fb_votes'] + $aRecord['page_tw_votes'] + $aRecord['page_vk_votes'] +
												$aRecord['page_ln_votes'] + $aRecord['page_gp_votes'] + $aRecord['page_ok_votes'];

		$aRecord['page_update_datetime']	= time();
        /**/ $oUniLog->logInfo('Social Actions for URL: ',$aRecord['page_url']);
        /**/ $oUniLog->logInfo('page_fb_votes: ',$aRecord['page_fb_votes']);
        /**/ $oUniLog->logInfo('page_tw_votes: ',$aRecord['page_tw_votes']);
        /**/ $oUniLog->logInfo('page_vk_votes: ',$aRecord['page_vk_votes']);
        /**/ $oUniLog->logInfo('page_ln_votes: ',$aRecord['page_ln_votes']);
        /**/ $oUniLog->logInfo('page_gp_votes: ',$aRecord['page_gp_votes']);
        /**/ $oUniLog->logInfo('page_ok_votes: ',$aRecord['page_ok_votes']);
        /**/ $oUniLog->logInfo('-------------------');

		$this->SaveRecord($aRecord);
		
		return $aRecord;
	}
	
	//*****************************************************************************************************
	public function UpdateByFilter($aFilter){
		
		$aRecords	= $this->GetRecordsByFilter($aFilter);
		if($aRecords){
			foreach($aRecords as $aRecord){
			    $this->UpdateRecord($aRecord);
			}
		}
		return true;	
	}
	
	//*****************************************************************************************************
	protected function RunSqlQueryFromFile($sFilePath){
		$sQuery			= file_get_contents($sFilePath);
		return	$this->oDb->Query($sQuery);
	}
	
	//*****************************************************************************************************
	public function Install(){
		
		$this->oDb->Query("
							CREATE TABLE IF NOT EXISTS `social_rank` (
							`page_id`				INT NOT NULL,
							`page_url`				VARCHAR(8000),							
							`page_fb_votes`			INT,
							`page_tw_votes`			INT,
							`page_vk_votes`			INT,
							`page_ln_votes`			INT,
							`page_gp_votes`			INT,
							`page_ok_votes`			INT,
							`page_votes_sum`		INT,
							`page_update_datetime`	INT,
							`page_add_datetime`		INT,
						  

						  PRIMARY KEY  (`page_id`),
							KEY				`page_url`				(`page_url`),
							KEY 			`page_votes_sum` 		(`page_votes_sum`),
							KEY 			`page_update_datetime` 	(`page_update_datetime`),
							KEY 			`page_add_datetime` 	(`page_add_datetime`)
						) 
								ENGINE = InnoDB	DEFAULT CHARACTER SET = utf8
								COLLATE = utf8_general_ci;
		");
		
		return true;
	}
	
}
?>