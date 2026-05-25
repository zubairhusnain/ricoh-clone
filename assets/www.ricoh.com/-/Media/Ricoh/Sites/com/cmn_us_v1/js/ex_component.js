$(function(){
	var next_step=document.getElementsByClassName("current");
	var step=document.getElementsByClassName("jp_step_current");
	var next_step_before=$(next_step).prev();

	var property={
		"background-image":"url('/cmn_jp_v1/img/ex_component/arrow_nextstep_act_before.jpg')",
		"background-repeat":"no-repeat",
		"background-position":"right center"
	}

	$(next_step_before).css(property);
});
