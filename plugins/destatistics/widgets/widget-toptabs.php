<?php
add_action( 'widgets_init', 'de_toptabs_sidebar_widget' );

function de_toptabs_sidebar_widget() {
	register_widget( 'toptabs_sidebar_widget' );
}

class toptabs_sidebar_widget extends WP_Widget {

	
	function toptabs_sidebar_widget() {

		$widget_ops = array( 'classname' => 'toptabs_sidebar_widget', 'description' => 'DePostrating: Табы' );

		$this->WP_Widget( 'toptabs_sidebar_widget', 'DePostrating: Табы', $widget_ops );
	}
    //те, що наш віджет виводитиме у фронт-енд
	function widget( $args, $state ) {
		extract( $args );

		$title = apply_filters('widget_title', $state['title'] );
        $count = $state['count'];
        $sDay = $state['time'];
        $sTab1Title = $state['tab_title1'];
        $sTab2Title = $state['tab_title2'];
        $aChoosenTypes = get_option("de_ptypes");

        echo $before_widget;

        //для таба 1
        $aFilter1 = array();
        $sTime  = time() - ($sDay*24*60*60);
        $aFilter1['DateFrom']  = date('Y-m-d H:i:s', $sTime);
        $aFilter1['OrderBy']  = 'Views';
        $aFilter1['Limit']  = $count;
        $aData1 = RatingGetRatedPostsByFilter($aFilter1);

		if ( $title )
			echo $before_title . $title . $after_title;

            echo '<div class="depr-tabsection">';
				   echo	'<ul class="depr-tabs">
							<li class="current">'.$sTab1Title.'</li>
							<li>'.$sTab2Title.'</li>
						</ul>
                        <div class="clear"></div>
						<div class="depr-box visible">
								<ul>';
        foreach ( $aData1 as $aPost ) {
            if ( in_array($aPost['Post']->post_type, $aChoosenTypes) ) {
        ?>
            <li>
                <span class="depr-views"><?php echo $aPost['Views']; ?></span>
                <a href="<?php echo get_permalink($aPost['Post']->ID) ?>" title="<?php echo htmlspecialchars($aPost['Post']->post_title, ENT_QUOTES); ?>">
                    <?php echo $aPost['Post']->post_title; ?>
                </a>
            </li>

        <?php
            }
        }
				         echo '</ul>
						 </div>
						 <div class="depr-box">
								<ul>';
        //для таба 2
        $aFilter2 = array();
        $aFilter2['DateFrom']  = date('Y-m-d H:i:s', $sTime);
        $aFilter2['OrderBy']  = 'SocialActions';
        $aFilter2['Limit']  = $count;
        $aData2 = RatingGetRatedPostsByFilter($aFilter2);

        foreach ( $aData2 as $aPost ) {
            if ( in_array($aPost['Post']->post_type, $aChoosenTypes) ) {
        ?>
            <li>
                <span class="depr-social-actions"><?php echo $aPost['SocialActions']; ?></span>
                <a href="<?php echo get_permalink($aPost['Post']->ID) ?>" title="<?php echo htmlspecialchars($aPost['Post']->post_title, ENT_QUOTES); ?>">
                    <?php echo $aPost['Post']->post_title; ?>
                </a>
            </li>

        <?php
            }
        }

				         echo '</ul>
						</div>';
            echo '</div>';

        echo $after_widget;
	}

	//оновлюємо дані віджета
	function update( $state_new, $state_old ) {
		$state = $state_old;

        $state['title'] = strip_tags( $state_new['title'] );
        $state['tab_title1'] = strip_tags( $state_new['tab_title1'] );
        $state['tab_title2'] = strip_tags( $state_new['tab_title2'] );
        $state['post_type'] = strip_tags( $state_new['post_type'] );
        $state['count'] = strip_tags( $state_new['count'] );
        $state['time'] = strip_tags( $state_new['time'] );

		return $state;
	}

	function widget_post_types_list( $state ) {

        $sWidgetPostType = $state['post_type'];
        $aChoosenTypes = get_option("de_ptypes");

        foreach ($aChoosenTypes as $sType ) {
            $sOutput .= '<option value="'.$sType.'"'.selected($sWidgetPostType, $sType).'>'.$sType.'</option>';
        }

        echo $sOutput;
    }

	//форма віджета у бек-енд
	function form( $state ) {

		$defaults = array(
            'title' => 'Топ посты табами',
            'tab_title1' => 'Популярное',
            'tab_title2' => 'Рекомендуемое',
            'post_type' => 'Тип поста',
            'count' => '3',
            'time' => '7'
        );

		$state = wp_parse_args( (array) $state, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __('Заголовок:') ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $state['title']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'tab_title1' ); ?>"><?php echo __('Заголовок для таба 1:') ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'tab_title1' ); ?>" name="<?php echo $this->get_field_name( 'tab_title1' ); ?>" value="<?php echo $state['tab_title1']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'tab_title2' ); ?>"><?php echo __('Заголовок для таба 2:') ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'tab_title2' ); ?>" name="<?php echo $this->get_field_name( 'tab_title2' ); ?>" value="<?php echo $state['tab_title2']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'post_type' ); ?>"><?php echo __('Тип поста:') ?></label>
			<select id="<?php echo $this->get_field_id( 'post_type' ); ?>" name="<?php echo $this->get_field_name( 'post_type' ); ?>">
                <?php $this->widget_post_types_list( $state ) ?>
            </select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'count' ); ?>"><?php echo __('К-во постов:') ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'count' ); ?>" name="<?php echo $this->get_field_name( 'count' ); ?>" value="<?php echo $state['count']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'time' ); ?>"><?php echo __('Период:') ?></label>
			<input class="widefat" type="text" id="<?php echo $this->get_field_id( 'time' ); ?>" name="<?php echo $this->get_field_name( 'time' ); ?>" value="<?php echo $state['time']; ?>" />
		</p>

	<?php
	}
}
?>