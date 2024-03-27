<?php

class DESTAT_Cron {

	public function addNewCronSchedule( $schedules ) {

		$schedules['every_2_sec'] = array(
			'interval' => 2,
			'display'  => __( 'Every 2 sec' )
		);

		$schedules['every_13_sec'] = array(
			'interval' => 13,
			'display'  => __( 'Every 13 sec' )
		);

		$schedules['every_20_sec'] = array(
			'interval' => 20,
			'display'  => __( 'Every 20 sec' )
		);

		$schedules['every_30_sec'] = array(
			'interval' => 30,
			'display'  => __( 'Every 30 sec' )
		);

		$schedules['every_1_min'] = array(
			'interval' => 60,
			'display'  => __( 'Every 1 minute' )
		);

		$schedules['every_2_min'] = array(
			'interval' => 120,
			'display'  => __( 'Every 2 minutes' )
		);

		$schedules['every_3_min'] = array(
			'interval' => 180,
			'display'  => __( 'Every 3 minutes' )
		);


		$schedules['every_five_min'] = array(
			'interval' => 300,
			'display'  => __( 'Every 5 minutes' )
		);

		$schedules['every_ten_min'] = array(
			'interval' => 600,
			'display'  => __( 'Every 10 minutes' )
		);

		$schedules['every_twelve_min'] = array(
			'interval' => 1200,
			'display'  => __( 'Every 20 minutes' )
		);

		$schedules['every_thirty_min'] = array(
			'interval' => 1800,
			'display'  => __( 'Every 30 minutes' )
		);

		$schedules['every_1_hour'] = array(
			'interval' => 3600,
			'display'  => __( 'Every 1 hour' )
		);

		$schedules['every_2_hour'] = array(
			'interval' => 7200,
			'display'  => __( 'Every 2 hours' )
		);

		$schedules['every_3_hour'] = array(
			'interval' => 10800,
			'display'  => __( 'Every 3 hours' )
		);

		$schedules['every_4_hour'] = array(
			'interval' => 14400,
			'display'  => __( 'Every 4 hours' )
		);

		return $schedules;
	}

	public function scheduleCron() {

		$this->unscheduleCron();

		$DESTAT_Cron_Current_Day_Posts = get_option( 'DESTAT_Cron_One_Day_Posts' );
		$DESTAT_Cron_New_Posts         = get_option( 'DESTAT_Cron_New_Posts' );
		$DESTAT_Cron_Old_Posts         = get_option( 'DESTAT_Cron_Old_Posts' );
		$DESTAT_Cron_All_Old_Posts     = get_option( 'DESTAT_Cron_All_Old_Posts' );

		$this->wp_schedule_event( 'DESTAT_Cron_Current_Day_Posts', $DESTAT_Cron_Current_Day_Posts );

		$this->wp_schedule_event( 'DESTAT_Cron_New_Posts', $DESTAT_Cron_New_Posts );

		$this->wp_schedule_event( 'DESTAT_Cron_Old_Posts', $DESTAT_Cron_Old_Posts );

		$this->wp_schedule_event( 'DESTAT_Cron_All_Old_Posts', $DESTAT_Cron_All_Old_Posts );

	}

	public function unscheduleCron() {

		$this->wp_clear_scheduled_hook( 'DESTAT_Cron_Current_Day_Posts' );
		$this->wp_clear_scheduled_hook( 'DESTAT_Cron_New_Posts' );
		$this->wp_clear_scheduled_hook( 'DESTAT_Cron_Old_Posts' );
		$this->wp_clear_scheduled_hook( 'DESTAT_Cron_All_Old_Posts' );

		$this->wp_clear_scheduled_hook( 'DESTAT_One_Day_Update_Posts' );
		$this->wp_clear_scheduled_hook( 'DESTAT_New_Update_Posts' );
		$this->wp_clear_scheduled_hook( 'DESTAT_Old_Update_Posts' );
		$this->wp_clear_scheduled_hook( 'DESTAT_All_Old_Update_Posts' );

	}


	public function deactivateScheduled() {

		$this->unscheduleCron();
	}

	public function wp_clear_scheduled_hook( $hook ) {

		$functions = new DESTAT_Functions();

		$blogs = $functions->getBlogs();
		foreach ( $blogs as $blog_id ) {
			if ( function_exists( 'switch_to_blog' ) ) {
				switch_to_blog( $blog_id );
			}
			wp_clear_scheduled_hook( $hook );

			if ( function_exists( 'restore_current_blog' ) ) {
				restore_current_blog();
			}

		}


		return true;


	}

	public function wp_schedule_event( $hook, $value ) {

		if ( empty( $value ) ) {
			return false;
		}

		$functions = new DESTAT_Functions();

		$blogs = $functions->getBlogs();
		foreach ( $blogs as $blog_id ) {

			if ( function_exists( 'switch_to_blog' ) ) {
				switch_to_blog( $blog_id );
			}

			if ( ! wp_next_scheduled( $hook ) ) {
				wp_schedule_event( time(), $value, $hook );
			}

			if ( function_exists( 'restore_current_blog' ) ) {
				restore_current_blog();
			}

		}

		return true;
	}

}