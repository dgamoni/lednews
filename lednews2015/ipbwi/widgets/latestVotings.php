<!-- mfunc --><!--dynamic-cached-content--><form action="#" method="post">
<?php
	$widget = '<li class="ipbwi_widget_latestVoting_entry">
	<table>';
	foreach($poll['choices'] as $key => $choice){
		// print question
		$widget .= '<tr><td colspan="3"><strong>'.$choice['question'].'</strong></td></tr>';
		// print choices
		$i = 0;
		foreach($choice as $option){
			if(!$GLOBALS['ipbwi']->poll->voted($topic['tid'])){
				// print radio-button
				if($i > 1 && $choice['multi'] == 0){
					$widget .= '<tr><td><input name="choice['.$key.']" value="'.$option['option_id'].'" class="radiobutton" type="radio" />'.$option['option_title'].'</td></tr>'."\n";
				// print checkbox if multi = 1
				}elseif($i > 1 && $choice['multi'] == 1){
					$widget .= '<tr><td><input name="choice_'.$key.'_'.$option['option_id'].'" value="1" class="checkbox" type="checkbox" />'.$option['option_title'].'</td></tr>'."\n";
				}
			// print poll result
			}elseif($i > 1){
				$widget .= '
				<tr>
					<td class="ipbwi_widget_latestVoting_choice">'.$option['option_title'].'</td>
					<td class="ipbwi_widget_latestVoting_votes">[<strong>'.$option['votes'].'</strong>]</td>
					<td class="ipbwi_widget_latestVoting_gradient" style="width:25%;"><img src="'.$GLOBALS['ipbwi']->getBoardVar('img_url').'/gradient_bg.png" width="'.$option['percentage'].'" height="11" alt="" style="background-color:'.(($instance['gradientColor'] != '') ? $instance['gradientColor'] : '#243F5C').';" /></td>
					<td class="ipbwi_widget_latestVoting_percentage">&nbsp;['.$option['percentage'].'%]</td>
				</tr>';
			}
			$i++;
		}
	}
	$widget .= '</table>';
	if(!$GLOBALS['ipbwi']->poll->voted($topic['tid'])){
		if($GLOBALS['ipbwi']->member->isLoggedIn()){
			$widget .= '<p style="margin-top:20px;"><input type="submit" name="ipbwi_vote_poll" value="Vote now!" /></p>';
		}else{
			$widget .= '<p style="margin-top:20px;">Please login to cast a vote.</p>';
		}
	}else{
		$widget .= '<p><strong>You have already voted in this poll.</strong></p>';
	}
	$widget .= '<input type="hidden" name="tid" value="'.$topic['tid'].'" /></form>
	<div class="ipbwi_widget_latestVoting_votesCount">'.$GLOBALS['ipbwi']->poll->totalVotes($topic['tid']).' Votes</div>';
	
	if($GLOBALS['ipbwi']->getBoardVar('htaccess_mod_rewrite') == 1){
		$widget .= '<div class="ipbwi_widget_latestVoting_ViewTopic"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'topic/'.$topic['tid'].'-'.$topic['title_seo'].'/"><strong>'.(($instance['viewTopic'] != '') ? $instance['viewTopic'] : 'View Poll Discussion Topic').'</strong></a></div>';
	}elseif($GLOBALS['ipbwi']->getBoardVar('url_type') == 'query_string'){
		$widget .= '<div class="ipbwi_widget_latestVoting_ViewTopic"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php?/topic/'.$topic['tid'].'-'.$topic['title_seo'].'/"><strong>'.(($instance['viewTopic'] != '') ? $instance['viewTopic'] : 'View Poll Discussion Topic').'</strong></a></div>';
	}elseif($GLOBALS['ipbwi']->getBoardVar('url_type') == 'path_info'){
		$widget .= '<div class="ipbwi_widget_latestVoting_ViewTopic"><a href="'.$GLOBALS['ipbwi']->getBoardVar('url').'index.php/topic/'.$topic['tid'].'-'.$topic['title_seo'].'/"><strong>'.(($instance['viewTopic'] != '') ? $instance['viewTopic'] : 'View Poll Discussion Topic').'</strong></a></div>';
	}
	
	echo
	$before_widget.
	$before_title.
	(($title != '') ? $title : 'Latest Voting').
	$after_title.
	$widget.
	$after_widget;
?><!--/dynamic-cached-content--><!-- /mfunc -->