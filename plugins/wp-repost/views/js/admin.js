// JavaScript Document

jQuery(document).ready(function($) {
    
	$(".inline").colorbox({inline:true, width:"50%"});
	
	$('#facebook-login').on('click', function(e){
		$.colorbox({
			inline	:	true,
			href	:	'#facebook-login-frame'
		});
		e.preventDefault();
	});
	
	if( location.hash == "#_=_" || location.hash == "#fbadd" ){
		$.colorbox({
			href	:	ajaxurl + '?action=display_pages',
			height	:	500,
			width	:	400
		});
	}

	if( $('.edit-repost-timestamp').length ){
		$('.edit-repost-timestamp').each(function(i, e){
			$(this).on('click', function(e){
				$( $(this).attr('href') ).toggle();
				e.preventDefault();
			})
		})
	}
	
	function toggleBitly(){
		if( $('#top_url_shortner').length > 0 ){
			$('#top_url_shortner').on('change', function(e){
				if( $(this).val() == 'bit.ly' ){
					$('.bitly').show();
				} else {
					$('.bitly').hide();
				}
			});
			
			if( $('#top_url_shortner').val() == 'bit.ly' ){
				$('.bitly').show();
			} else {
				$('.bitly').hide();
			}
		}
	}
	
	if( $('#top_use_url_shortner').length > 0 ){
		$('#top_use_url_shortner').on('change', function(e){
			if( $(this).is(':checked') ){
				$('.url_shortner').show();
				toggleBitly();
			} else {
				$('.url_shortner').hide();
				$('.bitly').hide();
			}
		});
		if( $('#top_use_url_shortner').is(':checked') ){
			$('.url_shortner').show();
			toggleBitly();
		} else {
			$('.url_shortner').hide();
			$('.bitly').hide();
		}
	}
	
	if( $('#opp_global_settings').length ){
		$('#opp_global_settings').on('change', function(e){
			if( $(this).is(':checked') ){
				$('#opp_global_fields').show();
			} else {
				$('#opp_global_fields').hide();
			}
		});
	}
	
	if( $('#opp_repost_setting input[name=opp_repost_option]').length ){
		var m_names = new Array(
			"January",
			"February",
			"March",
			"April",
			"May",
			"June",
			"July",
			"August",
			"September",
			"October",
			"November",
			"December"
		);
		var d_names = new Array(
			'sunday', 'monday', 'tuesday',
			'wednesday', 'thursday', 'friday', 'saturday'
		);

		var repostDate;
		var today = new Date();
		
		$('#opp_repost_setting input[name=opp_repost_option]').on('change', function(e){
			if( $(this).val() == 'repeat' ){
				$('#repeat_repost_option').show();
			} else {
				$('#repeat_repost_option').hide();
			}
			
			if( $(this).val() == 'days' ){
				$('#opp_repost_date').text('not set.');
			}
			
			if( $(this).val() == 'date' ){
				$('#opp_repost_schedule').val( $('#opp_repost_on_date').val() );
				$('#opp_repost_date').text('Set on ' + $('#opp_repost_on_date').val() );
			}
		});
		
		// Set the next schedule on "from now" button click
		$('#opp_schedule_now').on('click', function(e){
			if( $('#opp_repost_after_time').val() == 'weeks' ){
				repostDate = new Date(new Date().getTime()+(parseInt($('#opp_repost_after_number').val())*24*60*60*1000*7));
			} else if( $('#opp_repost_after_time').val() == 'months' ) {
				repostDate = new Date(new Date().setMonth(today.getMonth()+parseInt($('#opp_repost_after_number').val())));
			} else {
				repostDate = new Date(new Date().getTime()+(parseInt($('#opp_repost_after_number').val())*24*60*60*1000));
			}
			
			if( $('input[name=opp_repost_option]:checked').val() == 'days' ){
				$('#opp_repost_schedule').val( beautifyDate( repostDate ) );
				$('#opp_repost_date').text('Set on ' + beautifyDate( repostDate ) );
			}
			
			e.preventDefault();
		});
		
		// Set next schedule on "Repost on" date change
		$('#opp_repost_on_date').on('change', function(e){
			if( $('input[name=opp_repost_option]:checked').val() == 'date' ){
				$('#opp_repost_schedule').val( $(this).val() );
				$('#opp_repost_date').text('Set on ' + $(this).val() );
			}
		});
		
		// Set next schedule on "Repeat" options
		$('input[name=opp_repost_option], #opp_repost_repeats, #opp_repost_every, input.opp_repost_on, input.opp_repost_monthly_on, input[name=opp_repost_by], #opp_repost_start, input[name=opp_repost_end], #opp_repost_end_after, #opp_repost_end_on').on('change', function(e){
			
			var repeats;
			var repeat;
			var end;
			var text = '';
			var option = $('#opp_repost_repeats').val();
			var every = parseInt($('#opp_repost_every').val());
			var start = new Date($('#opp_repost_start').val());
			var weekdays = $('input.opp_repost_on:checked');
			var months = $('input.opp_repost_monthly_on:checked');
			var monthday = $('input[name=opp_repost_by]:checked').val();
			var tempDate = null;
			repostDate = null;
			
			// Let's loop while we have the next date of the repost schedule
			while( repostDate == null ){
				
				if( tempDate != null ){
					repostDate = tempDate;
				} else {
					repostDate = new Date();
				}
				
				text = ''; // Reset the text
				
				// Get repeat option (Daily, Weekly on, Monthly every, yearly) and
				// set the next schedule (depends on start date and end date)
				if( option == 'weekly' ){
					$('#opp_repeat_on_wrap').show();
					$('#opp_repeat_monthly_on_wrap').hide();
					$('#opp_repeat_by_wrap').hide();
					
					repeats = "weeks";
					repeat = "Weekly";
					
					if( weekdays.length ){
						if( weekdays.length >= 7 ){
							text = text + ' on all days';
						} else {
							text = text + ' on';
							weekdays.each(function(index, element) {
								text = ( index > 0 ) ? text + ', ' + ucfirst( $(this).val() ) : text + ' ' + ucfirst( $(this).val() );
							});
						}
					}
					
					nextDay = null;
					day = repostDate.getDay();
					while( nextDay == null ){
						now = day;
						day++;

						if( day > 6 ){
							day = 0;
						}

						if( $('.opp_repost_on[data-value='+ day +']').is(':checked') ){
							nextDay = day;
						} else if( !weekdays.length ) {
							nextDay = now;
						}
					}
					
					// Change "Repeat every" options
					$('#opp_repost_every').empty();
					for( i=1; i<=52; i++ ){
						$('#opp_repost_every').append(
							jQuery('<option />', {
								value: i
							}).text(i)
						)
					}
					$('#opp_repost_every').val( every );
					
					repostDate = getNextDay( repostDate, nextDay, every );
				} else if( option == 'monthly' ) {
					$('#opp_repeat_by_wrap').show();
					$('#opp_repeat_monthly_on_wrap').show();
					$('#opp_repeat_on_wrap').hide();
					
					repeats = "months";
					repeat = "Monthly";
					
					if( monthday == 'month' ){
						text = text + ' on day ' + today.getDate();
						repostDate.setMonth(repostDate.getMonth()+every);
					} else {
						text = text + ' on the ' + weekAndDay( today );
						repostDate.setMonth(repostDate.getMonth()+every);
						index =  (0 | repostDate.getDate() / 7);
						//(0 | today.getDate() / 7);
						repostDate = getDateByWeekday(repostDate.getMonth(),repostDate.getFullYear(),today.getDay(),index);
					}
					
					// Let's check if the month of next sched is turned on, otherwise jump to next month
					if( months.length ){
						if( months.length >= 12 ){
							text = text + ' of all months';
						} else {
							text = text + ' of ';
							selectedMonths = new Array();
							months.each(function(index, element) {
								text = ( index > 0 ) ? text + ', ' + ucfirst( $(this).val() ) : text + ' ' + ucfirst( $(this).val() );
								selectedMonths.push( ucfirst( $(this).val() ) );
							});
							
							loopStopper = 0;
							while( loopStopper <= 36 && selectedMonths.indexOf( m_names[repostDate.getMonth()] ) < 0 ){
								if( monthday == 'month' ){
									repostDate.setMonth(repostDate.getMonth()+every);
								} else {
									if( (repostDate.getMonth()+every) >= 12 ){
										repostDate.setMonth(repostDate.getMonth()+every);
										repostDate.setFullYear(repostDate.getFullYear() + ((repostDate.getMonth()+every)/12));
									} else {
										repostDate.setMonth(repostDate.getMonth()+every);
									}
									
									index =  (0 | today.getDate() / 7);
									repostDate = getDateByWeekday(repostDate.getMonth(),repostDate.getFullYear(),today.getDay(),index);
								}
								
								if( loopStopper >= 36 ) repostDate = '';
								loopStopper++;
							} // Endwhile
							
							/*if( selectedMonths.indexOf( m_names[repostDate.getMonth()] ) < 0 ){
								tempDate = repostDate;
								repostDate = null;
							}*/
						}
					} else {
						text = text + ' of all months';
					}
					
					// Change "Repeat every" options
					$('#opp_repost_every').empty();
					for( i=1; i<=12; i++ ){
						$('#opp_repost_every').append(
							jQuery('<option />', {
								value: i
							}).text(i)
						)
					}
					$('#opp_repost_every').val( every );
				} else if( option == 'yearly' ){
					$('#opp_repeat_by_wrap').hide();
					$('#opp_repeat_monthly_on_wrap').hide();
					$('#opp_repeat_on_wrap').hide();
					
					repeats = "years";
					repeat = "Yearly";
					
					repostDate.setFullYear((repostDate.getFullYear()+every), repostDate.getMonth(), repostDate.getDate());
					
					// Change "Repeat every" options
					$('#opp_repost_every').empty();
					for( i=1; i<=30; i++ ){
						$('#opp_repost_every').append(
							jQuery('<option />', {
								value: i
							}).text(i)
						)
					}
					$('#opp_repost_every').val( every );
				} else {
					$('#opp_repeat_by_wrap').hide();
					$('#opp_repeat_monthly_on_wrap').hide();
					$('#opp_repeat_on_wrap').hide();
					
					repeats = "days";
					repeat = "Daily";
					repostDate = new Date(repostDate.getTime()+(every*24*60*60*1000));
					
					// Change "Repeat every" options
					$('#opp_repost_every').empty();
					for( i=1; i<=30; i++ ){
						$('#opp_repost_every').append(
							jQuery('<option />', {
								value: i
							}).text(i)
						)
					}
					$('#opp_repost_every').val( every );
				}
				
				if( repostDate ){
					// Let's check if the next sched is before the end date
					if( $('input[name=opp_repost_end]:checked').val() == 'after' && $('#opp_repost_end_after').val() > 1 ){
						text = text + ', ' + $('#opp_repost_end_after').val() + ' times';
						text = text + '. Next repost schedule is on ' +  beautifyDate( repostDate );
					} else if( $('input[name=opp_repost_end]:checked').val() == 'on' && $('#opp_repost_end_on').val() != '' ){
						end = new Date($('#opp_repost_end_on').val());
						text = text + ', until ' + m_names[end.getMonth()] + ' ' + end.getDate() + ' ' + end.getFullYear();
						if( repostDate < end ){
							text = text + '. Next repost schedule is on ' +  beautifyDate( repostDate );
						} else {
							tempDate = null;
							repostDate = '';
						}
					} else {
						text = text + '. Next repost schedule is on ' +  beautifyDate( repostDate );
					}
					
					// Let's check if the sched is on or after the start date
					if( (repostDate >= start || repostDate == '') && repostDate != null ){
						repostDate = repostDate;
					} else {
						tempDate = repostDate;
						repostDate = null;
					}
				}
			}// Endwhile
			
			// Set meta fields
			if( $('input[name=opp_repost_option]:checked').val() == 'repeat' ){
				if( $('#opp_repost_every').val() > 1 ){
					$('#opp_repost_date').text('Set every ' + $('#opp_repost_every').val() + ' ' + repeats + text );
				} else {
					$('#opp_repost_date').text('Set ' + repeat + text );
				}
			}
			
			if( repostDate != '' ) $('#opp_repost_schedule').val( beautifyDate( repostDate ) );
		}).stop();
	}
	
	if( $('.datepicker').length ){
		$('.datepicker').each(function(index, element) {
            $(this).datepicker();
        });
	}
	
	
	/***************************************************************************
	 * index what occurence of the weekday we look for (1 and upwards)
	 * weekday 0-6  (= Sunday - Monday)
	 * month 1-12   (= January - December) optional defaults to current month
	 * year (a four digit number) optional defaults to current year
	 **************************************************************************/
	function getDateByWeekday(month, year, weekDay, index){
		var nextDate = new Date(), now = new Date(), result;
		
		// Set the date to the first day of the month
		nextDate.setYear(year);
		nextDate.setMonth(month);
		nextDate.setDate(1);
		
		// Calculate the distance to the nearest required weekday
		var d = weekDay - nextDate.getDay() + 1;
		if ( d < 0 ) { d += 7; }
		
		// Calculate the date corresponding to the required weekday
		d += (index || 0) * 7;
		
		result = new Date(nextDate.setDate(d));
		
		// Set the date and return the result as all methods do
		return result;
		
		/*var now = new Date(), co = 1, result;
		// month = month || now.getMonth();
		// year = year || now.getFullYear();
		
		if( weekDay < new Date(year, month, co).getDay() ){
			index++;
		}
		
		do {
			result = new Date(year, month, co);
			result.getDay() == weekDay && index--;
			
			if(result.getMonth() +1 != month || index + co < 2) return now;
			co++;
		}
		while (index > 0);
		
		return result*/
	}
	
	function getNextDay( date, x, weeks ){
		today = new Date(date);

		if(weeks > 1){
			new_weeks = weeks * 7;
			date.setDate(date.getDate() + (x+(new_weeks-date.getDay())));
		} else {
			date.setDate(date.getDate() + (x+(7-date.getDay())) % 7);
		}
		
		if( date <= today ){
			weeks = weeks * 7;
			return new Date(today.getTime()+weeks*24*60*60*1000);
		} else {
			return date;
		}
	}
	
	function weekAndDay( date ) {
		days = ['Sunday','Monday','Tuesday','Wednesday',
				'Thursday','Friday','Saturday'],
		prefixes = ['first', 'second', 'third', 'fourth', 'fifth'];
	
		return prefixes[0 | date.getDate() / 7] + ' ' + days[date.getDay()];
	}
	
	function ucfirst( string ){
		// $(this).val().substring(0,1).toUpperCase() + $(this).val().substring(1);
		return string.substring(0,1).toUpperCase() + string.substring(1);
	}
	
	function beautifyDate( repostDate ){
		return (repostDate.getMonth()+1) + '/' + repostDate.getDate() + '/' + repostDate.getFullYear();
	}
	
});