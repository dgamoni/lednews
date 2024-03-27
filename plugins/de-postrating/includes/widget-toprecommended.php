<?php
add_action( 'widgets_init', 'de_toprec_sidebar_widget' );

function de_toprec_sidebar_widget() {
	register_widget( 'toprec_sidebar_widget' );
}

class toprec_sidebar_widget extends WP_Widget {

	
	function toprec_sidebar_widget() {

		$widget_ops = array( 'classname' => 'toprec_sidebar_widget', 'description' => 'DePostrating: Рекомендуемое' );

		$this->WP_Widget( 'toprec_sidebar_widget', 'DePostrating: Рекомендуемое', $widget_ops );
	}
    //те, що наш віджет виводитиме у фронт-енд
	function widget( $args, $state ) {
		extract( $args );

		$title = apply_filters('widget_title', $state['title'] );
        $count = $state['count'];
        $sDay = $state['time'];
        $aChoosenTypes = get_option("de_ptypes");

        echo $before_widget;

        $aFilter = array();
        $sTime  = time() - ($sDay*24*60*60);
        $aFilter['DateFrom']  = date('Y-m-d H:i:s', $sTime);
        $aFilter['OrderBy']  = 'SocialActions';
        $aFilter['Limit']  = $count;
        $aData = RatingGetRatedPostsByFilter($aFilter);

		if ( $title )
			echo $before_title . $title . $after_title;

        echo '<ul class="depostrating-toprec-posts">';
        foreach ( $aData as $aPost ) {
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
        echo '</ul>';

        echo $after_widget;
	}

	//оновлюємо дані віджета
	function update( $state_new, $state_old ) {
		$state = $state_old;

        $state['title'] = strip_tags( $state_new['title'] );
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
            'title' => 'Рекомендуемые посты',
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