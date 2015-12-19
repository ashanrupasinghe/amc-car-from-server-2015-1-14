jQuery(document).ready(function(){
	var austDay = new Date();
	austDay = new Date(
		austDay.getFullYear(),
		austDay.getMonth()+parseInt(counter_options.months),
		austDay.getDate()+parseInt(counter_options.days),
		austDay.getHours()+parseInt(counter_options.hours),
		austDay.getMinutes()+parseInt(counter_options.minutes),
		austDay.getSeconds()+parseInt(counter_options.seconds)
	);
	jQuery('#counter').countdown({
		until: austDay, 
		format: 'dHMS'
	});
});