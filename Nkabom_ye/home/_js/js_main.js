// JavaScript Document


// var promise = require("bluebird");
// import * as Promise from "bluebird";
// import {Promise} from "bluebird";


$(function(){
	
	$(window).scroll(function(){
		if($(this).scrollTop()>300){
			$('.scrollToTop').fadeIn(1000);
		}else{
			$('.scrollToTop').fadeOut(1000);
		}
	});
	$('.scrollToTop').click(function(){
		$('html, body').animate({scrollTop: 0}, 1000);
		return false;
	});



	function view_pass(elem){
		var pass = elem.closest(".auth-form").find("input#user-pass");
		if(pass.val() !== ""){
			elem.toggleClass("selected");
			if(pass.attr("type") == "password"){
				pass.attr("type", "text");
			}else{
				pass.attr("type", "password");
			}
			pass.focus();
		}
	}

	function curr_page(str){
		//var get_url = window.location.href.split("?")[1], state = false;
		var get_url = window.location.href.split("?")[0], state = false;
		
		if(get_url != ""){
			let page_name_1 = get_url.split(".")[0].split("/")[get_url.split(".")[0].split("/").length-1];
			let page_name_2 = get_url.split("/")[get_url.split(".")[0].split("/").length-1];
			//console.log(page_name_2);
			
			//if(get_url.split("&")[1].indexOf(str) > -1){
			if(page_name_1 == str || page_name_2 == str){
				state = true;
			}
		}
		
		return state;
	}
		

	function treat_empty_arrs(in_arr, mode){
		if(mode){
			if(in_arr.length == 0){
				in_arr[in_arr.length] = "";
			}else{
				for(var i=0; i<in_arr.length; i++){
					if(typeof in_arr[i] === "object"){
						if(in_arr[i].length == 0){
							in_arr[i][in_arr[i].length] = "";
						}else{
							treat_empty_arrs(in_arr[i], mode);
						}
					}
				}
			
				//return in_arr;
			}
		}else{
			if(in_arr.length == 1 && in_arr[0] == ""){
				in_arr = [];
			}else{
				for(var i=0; i<in_arr.length; i++){
					if(typeof in_arr[i] === "object"){
						if(in_arr[i].length == 1 && in_arr[i][0] == ""){
							in_arr[i] = [];
						}else{
							treat_empty_arrs(in_arr[i], mode);
						}
					}
				}
			
				//return in_arr;
			}
		}
			
		return in_arr;
	}
	
	
	function display_birthday(data){
		let bd_mess = ["", ""];
		if(data[0][0] == 1){
			if(data[0][1] == 1){
				if(data[data[0][1]][0] == 1){
					bd_mess[0] += "Yesterday, "+data[4][0];
					bd_mess[0] += " was your Birthday.";
					
					bd_mess[1] = "birthday-1-1";
				}else{
					passed_suffix = (data[data[0][1]][1] == 1) ? "" : "s";
					bd_mess[0] += "Your Birthday was "+data[data[0][1]][1];
					bd_mess[0] += " day"+passed_suffix+" ago.";
					bd_mess[0] += " On "+data[4][0]+".";
					
					bd_mess[1] = "birthday-1-2";
				}
			}
			if(data[0][1] == 2){
				bd_mess[0] += "Hurray!!! Today, ";
				bd_mess[0] += data[4][0]+" is your Birthday...";
				bd_mess[0] += "Congratulations!! ";
				bd_mess[0] += "<em>You are "+data[4][1]+" years old.</em>";
				
				bd_mess[1] = "birthday-2";
			}
			if(data[0][1] == 3){
				if(data[data[0][1]][0] == 1){
					bd_mess[0] += "Tomorrow, "+data[4][0];
					bd_mess[0] += " is your Birthday..!!";
					
					bd_mess[1] = "birthday-2-1";
				}else{
					coming_suffix = (data[data[0][1]][1] == 1) ? "" : "s";
					bd_mess[0] += data[data[0][1]][1]+" day"+coming_suffix;
					bd_mess[0] += " remaining to your Birthday..";
					bd_mess[0] += " On "+data[4][0];
					
					bd_mess[1] = "birthday-2-2";
				}
			}
		}
		
		return bd_mess;
	}
		
		
	function make_cookie(cookie_set, mode){	
		var thisDate = new Date();
		var thisCookie = document.cookie.split("; ");
		
		if(mode == "set"){
			thisDate.setMonth(thisDate.getMonth()+1);
			for (var key in cookie_set){
				if(cookie_set.hasOwnProperty(key)){
					var saveCookie = key+"=" + cookie_set[key] + ";";
					saveCookie += "path=/;";
					saveCookie += "expires=" + thisDate.toGMTString() + ";";
					saveCookie += "expires=SameSite=None; Secure";
					document.cookie = saveCookie;
				}
			}
		}
		
		if(mode == "check"){
			//var outMsg = "";
			var matched = [];
			for(var i=0; i<thisCookie.length; i++){
				for (var key in cookie_set){
					if(cookie_set.hasOwnProperty(key)){
						if(thisCookie[i].split("=")[0] == key){
							//outMsg += "Cookie name is '";
							//outMsg += thisCookie[i].split("=")[0];
							//outMsg += "', and the value is '" + thisCookie[i].split("=")[1] + "'<br />";
							matched[i] = thisCookie[i].split("=")[0];
						}
					}
				}
			}
			return (matched.length != 0) ? true : false;
		}
		
		if(mode == "get"){
			for (var i=0; i<thisCookie.length; i++) {
				if (cookie_set == thisCookie[i].split("=")[0]) {
					return thisCookie[i].split("=")[1];
				}
			}
		}
		
		if(mode == "remove"){
			cookieCount = thisCookie.length;
			thisDate.setDate(thisDate.getDate()-1);
			for(var i=0; i<cookieCount; i++){
				for (var key in cookie_set){
					if(cookie_set.hasOwnProperty(key)){
						if(thisCookie[i].split("=")[0] == key){
							var cookieName = thisCookie[i].split("=")[0];
							document.cookie = cookieName + "=;path=/;expires=" + thisDate.toGMTString();
						}
					}
				}
			}
		}
	}

	
	function val_exist(var_1, var_2){
		for(var i=0; i<var_2.length; i++){
			if(var_1 == var_2[i]){
				return true;
			}
		}
		return false;
	}
	function val_exist_2(var_1, var_2){
		for(var i=0; i<var_2.length; i++){
			if(var_1 == var_2[i]){
				return [1, i];
			}
		}
		return [0, 0];
	}
	
	
	function pagination(){
		// Pagination
		$('.page-wrapper #page-main #page-body #mems-bottom div.pagination div:nth-child(1) a').css("display", "none");
		$('.page-wrapper #page-main #page-body #mems-bottom div.pagination div a').click(function(){
			// console.log("Okay, we're there.");
			if($(this).closest('div').index() == 0){
				pag[0] -= 1;
			}else{
				pag[0] += 1;
			}
			start_end = [(pag[0]*pag[1]), (pag[0]*pag[1])+pag[1]];
			
			displayAll_members();
			
			return false;
		});
	}
	
	
	





	
	// disp_opts: [0]: div elem to put list
	let mems_data = [[], []], disp_opts = [1, [0, 0]], grp_data = [], disp_state = [], pag = [0, 10], start_end = [0, 0+10];
	if(curr_page("page_2")){
		$('#page-2 #mem-form-data-lvl-nav input#nxt').click(function(){
			let names = $('#page-2 #mem-form-data-lvl-container .mem-form-data-lvl-sections:nth-child(1) span#data-set-1');
			let f_name = names.find('input:nth-child(1)').val(), o_name = names.find('input:nth-child(2)').val(), l_name = names.find('input:nth-child(3)').val();
			let gender = $('#page-2 #mem-form-data-lvl-container .mem-form-data-lvl-sections:nth-child(1) span#data-set-2 input').val();
			let dob = $('#page-2 #mem-form-data-lvl-container .mem-form-data-lvl-sections:nth-child(1) span#data-set-3 input').val();
			let nationality = $('#page-2 #mem-form-data-lvl-container .mem-form-data-lvl-sections:nth-child(1) span#data-set-4 select').val();
			// console.log("okay, better.."+f_name+", "+o_name+", "+l_name+", "+gender+", "+dob+", "+nationality);
			mems_processor([[f_name, o_name, l_name, gender, dob, nationality], 0], "mems-1");
		});
		// FORM VALIDATION:
		// ENSURE CONTACT ISN'T EMPTY (IN THE NEW GET BY VALUE; IN THE UPDATE GET BY ATTR EG. ALT="CONTACT")
		
		let disp_windows = "";
		$('.page-wrapper #page-main #page-body #mems-top a').each(function(){
			disp_windows += "<div class='content'><ul></ul></div>";
		});
		$('.page-wrapper #page-main #page-body #mems-middle').html(disp_windows);
		
		$('.page-wrapper #page-main #page-body #mems-middle div.content:nth-child(1)').css("display", "block");
		mems_processor([[[1, 0]], 0], "mems");
		
		// Pagination
		/* $('.page-wrapper #page-main #page-body #mems-bottom div.pagination div:nth-child(1) a').css("display", "none");
		$('.page-wrapper #page-main #page-body #mems-bottom div.pagination div a').click(function(){
			// console.log("Okay, we're there.");
			if($(this).closest('div').index() == 0){
				pag[0] -= 1;
			}else{
				pag[0] += 1;
			}
			start_end = [(pag[0]*pag[1]), (pag[0]*pag[1])+pag[1]];
			
			displayAll_members();
			
			return false;
		}); */
		pagination();
		
		// Mems Type
		$('.page-wrapper #page-main #page-body #mems-top select').change(function(){
			//console.log("Okay, what's wrong..");
			start_end = [0, 0+10];
			
			let opt_type = [0, 0]; // Mem type: [0]: Sel Opt. [1] Sel Type (0 'All', 1 'Actives', 2 'Inactives', 3 'Superiors')
			mems_processor([[[1, 0], [$(this).val(), opt_type]], 2], "mems"); // Group Options
		});
		$('.page-wrapper #page-main #page-body #mems-top div.mems-type div a').click(function(){
			// console.log($(this).closest('div').index());
			start_end = [0, 0+10];
			
			let val = 1;
			if($(this).hasClass('selected')){
				val = 0;
			}
			let opt_type = [0, 1, [$(this).closest('div').index(), $(this).index()]]; // Mem type: [0]: Sel Opt. [1] Sel Type (0 'All', 1 'Actives', 2 'Inactives', 3 'Superiors'), [2]: Category of Type
			mems_processor([[[1, 0], [val, opt_type]], 2], "mems");
			
			return false;
		});
		
	

		$('.page-wrapper #page-main #page-body #crop-img input[type=submit]').click(function() {
			let size_fields = $('.page-wrapper #page-main #page-body #crop-img');
			var x1 = size_fields.find("input[type=hidden]#x1-2").val();
			var y1 = size_fields.find("input[type=hidden]#y1-2").val();
			var x2 = size_fields.find("input[type=hidden]#x2-2").val();
			var y2 = size_fields.find("input[type=hidden]#y2-2").val();
			var w = size_fields.find("input[type=hidden]#w-2").val();
			var h = size_fields.find("input[type=hidden]#h-2").val();
			if(x1=="" || y1=="" || x2=="" || y2=="" || w=="" || h==""){
				alert("You must make a selection first");
				return false;
			}else{
				return true;
			}
		});
		// aspectRatio, maxWidth, maxHeight should all be dynamically set by php. Later, find a way to fix that.
		let new_size = 150, selection_size = 300;
		if($('.page-wrapper #page-main #page-body div#large_image').attr("imgsel") == 1){ // imgAreaSelect placed here for causing interference on jQuery.
			$('.page-wrapper #page-main #page-body div#large_image img').imgAreaSelect( { aspectRatio: '1:'+(new_size/new_size), maxWidth: selection_size, maxHeight: selection_size, onSelectChange: make_selection } );
		}
		function make_selection(image, selection){
			let sel_img = $('.page-wrapper #page-main #page-body div#large_image img');
			var scaleX = new_size / selection.width;
			// var scaleX = 100 / selection.width;
			var scaleY = new_size / selection.height;
			
			let sel_size = 100;
			let x = [selection.x1-sel_size, selection.x2-sel_size], y = [selection.y1-sel_size, selection.y2-sel_size], size = [selection.width+sel_size, selection.height+sel_size];
			var scaleX_1 = new_size / size[0];
			var scaleY_1 = new_size / size[1];
			
			let orig_size = [Number($('.page-wrapper #page-main #page-body div#large_image span').attr('wt')), Number($('.page-wrapper #page-main #page-body div#large_image span').attr('ht'))];
			
			// Project your new selection
			$('.page-wrapper #page-main #page-body div#img-selection div#img-selection-medium img').css(
				{ width: Math.round(scaleX_1 * sel_img.width())+'px', height: Math.round(scaleY_1 * sel_img.height())+'px', marginLeft: '-'+Math.round(scaleX * x[0])+'px', marginTop: '-'+Math.round(scaleY * y[0])+'px' }
			);
			
			let s_width = Math.round(scaleX * sel_img.width());
			let s_height = Math.round(scaleX * sel_img.height());
			$('.page-wrapper #page-main #page-body div#img-selection div#img-selection-small img').css(
				{ width: s_width+'px', height: s_height+'px', marginLeft: '-'+Math.round(scaleX * selection.x1)+'px', marginTop: '-'+Math.round(scaleY * selection.y1)+'px' }
			);
			
			let final_width = Math.round((orig_size[0]/sel_img.width())*selection_size);
			let final_height = Math.round((orig_size[1]/sel_img.height())*selection_size);
			let final_x1 = Math.round((final_width/selection_size)*selection.x1)
			let final_y1 = Math.round((final_height/selection_size)*selection.y1)
			
			// Get the Selection on all axes (X&Y) from imgAreaSelect() & fill their respective input (hidden) fields
			
			// Medium Size
			let size_fields = $('.page-wrapper #page-main #page-body #crop-img');
			size_fields.find("input[type=hidden]#x1-1").val(final_x1-sel_size);
			size_fields.find("input[type=hidden]#y1-1").val(final_y1-sel_size);
			size_fields.find("input[type=hidden]#x2-1").val(x[1]);
			size_fields.find("input[type=hidden]#y2-1").val(y[1]);
			size_fields.find("input[type=hidden]#w-1").val(final_width+250);
			size_fields.find("input[type=hidden]#h-1").val(final_height+200);
			
			// Small Size
			size_fields.find("input[type=hidden]#x1-2").val(final_x1); // The bigger the size/value the more the left moves to the right
			size_fields.find("input[type=hidden]#y1-2").val(final_y1); // The bigger the size/value the more the top moves to the bottom
			size_fields.find("input[type=hidden]#x2-2").val(selection.x2);
			size_fields.find("input[type=hidden]#y2-2").val(selection.y2);
			size_fields.find("input[type=hidden]#w-2").val(final_width); // The bigger the size/value the wider (width)
			size_fields.find("input[type=hidden]#h-2").val(final_height); // The bigger the size/value the longer (height)
			// console.log([final_width, orig_size[0]/3, final_height, orig_size[1]/15]);
		}
	}
	
	if(curr_page("page_3")){
		
		let disp_windows = "";
		$('.page-wrapper #page-main #page-body #mems-top a').each(function(){
			disp_windows += "<div class='content'><ul></ul></div>";
		});
		$('.page-wrapper #page-main #page-body #mems-middle').html(disp_windows);
		
		$('.page-wrapper #page-main #page-body #mems-middle div.content:nth-child('+disp_opts[0]+')').css("display", "block");
		
		// disp_opts[0] = 2;
		$('.page-wrapper #page-main #page-body #mems-top a.switch-view').each(function(){
			$(this).css( {border: '1px solid '+$(this).attr('clr')} );
			$(this).hover(
				function(){
					$(this).css( {backgroundColor: $(this).attr('clr'), color: $(this).attr('clr')} );
				},
				function(){
					if(!$(this).is('.selected')){
						$(this).css( {backgroundColor: "#fff", color: "#fff"} );
					}
				}
			);
		}).click(function(){
			/* $('.page-wrapper #page-main #page-body #mems-middle div.content').css("display", "none");
			let section = 1, child_elem = '';
			if(!$(this).is('.selected')){
				$(this).addClass('selected');
				section = 2;
				child_elem = ' ul#wrap-list'
			}else{
				$(this).removeClass('selected');
			}
			
			mems_processor([[[1, 0]], 0], "mems");
			pagination();
			
			let sel_window = '.page-wrapper #page-main #page-body #mems-middle div.content:nth-child('+section+')';
			let start_ht = '.page-wrapper #page-main #page-body #mems-middle div.content:nth-child('+(section == 1 ? 2 : 1)+')';
			start_ht = (section == 1) ? $(start_ht+child_elem).height()+20 : $(start_ht).height()+20;
			let end_ht = (section == 2) ? $(sel_window+child_elem).height()+20 : 0; // Change 0 to the child elem of parent (nth-1)
			// console.log(section+", "+start_ht+", "+sel_window+child_elem);
			$(sel_window).css( {display: "block", height: start_ht+'px'} ).stop().animate( {'height': end_ht+"px"}, 500); */
			
			
			let start_ht = '.page-wrapper #page-main #page-body #mems-middle div.content:nth-child('+disp_opts[0]+')', start_ht_elem = $(start_ht+' ul').height()+20;
			let end_ht = '', end_ht_elem = 0;
			if(!$(this).is('.selected')){
				$('.page-wrapper #page-main #page-body #mems-top a.switch-view').removeClass('selected').css( {backgroundColor: "#fff", color: "#fff"} );
				$(this).addClass('selected').css( {backgroundColor: $(this).attr('clr'), color: $(this).attr('clr')} );
				
				$('.page-wrapper #page-main #page-body #mems-middle div.content').css("display", "none");
				
				disp_opts[0] = Number($(this).attr('id'))+1;
				if(disp_opts[0] == 2){
					// let myWindow = window.open("", "", width=200, height=100);
					// disp_opts[1] = setTimeout(function(){myWindow.close()}, 10000);
					// disp_opts[1] = setInterval(function(){console.log("Okay, here..")}, 5000);
					
					mems_processor([[[1, 0]], 0], "mems");
					pagination();
				}
				
				end_ht = '.page-wrapper #page-main #page-body #mems-middle div.content:nth-child('+disp_opts[0]+')';
				end_ht_elem = $(end_ht+' ul').height()+20;
				
				$(end_ht).css( {display: "block", height: start_ht_elem+'px'} ).stop().animate( {height: end_ht_elem+"px"}, 500);
			}/* else{
				end_ht = '.page-wrapper #page-main #page-body #mems-middle div.content:nth-child('+disp_opts[0]+')';
				end_ht_elem = 20;
				$(this).removeClass('selected').css( {backgroundColor: "#fff", color: "#fff"} );
			} */
			
			return false;
		});
		
		$('.page-wrapper #page-main #page-body #mems-top a#switch-view-save').click(function(){
			// console.log("Okay, let's save");
			let lvl_data = [[1, 0], 0]; // [0]: Lvl & Grp, [1]: If an array(custom/sub grp indexes: 1 parent '1 based index', 0 child '0 based index') else 0 'mother grp'
			clearTimeout(disp_opts[1][0]);
			attendance_processor([[lvl_data, [mems_data[1], [0, 1],1]], 1], "att-1");
		});
	}
	
	function mems_processor(action, mode){
		action = treat_empty_arrs(action, true);
		var postData = {action: action, mode: mode};
		
		$.ajax({
			url: "../core/core/core_3.php",
			type: "POST",
			data: postData,
			dataType : "json",
			beforeSend: function() {
				//message_div(duration=false);
			},
			success: successFn,
			error: errorFn,
			
			// code to run regardless of success or failure
			complete: function( xhr, status ) {
				//console.log("The request is complete!");
			}
		});
		
		function successFn(data){
			
			if(data.mode == "mems-1"){
				// console.log(data.data);
				/* let new_data = [];
				for(let i=0; i<3; i++){
					for(let j=0; j<data.data.length; j++){
						new_data[new_data.length] = data.data[j];
					}
				} */
				
				let sel_mem_id = $('#page-2 #mem-form-data-lvl-container').attr("mid"), form = $('#page-2 #mem-form-data-lvl-nav input#nxt').attr("form");
				if(data.data.length > 0 && sel_mem_id == 0){
					let struct = "<div id='header'>";
						struct += "<h3>Does this person already exist?..</h3><p>The list below is showing because of the information you entered above. This is to avoid/eliminate repetition of a member's record. Click <strong>I'm Not in the list</strong> below to Proceed/Continue if you're sure this person does not exist already. Click <strong>I'm in the list</strong> below to Abort/Cancel this process below.</p>";
					struct += "</div>";
					struct += "<div id='body' class='mems-list'>";
						struct += compose_mem_profiles([0, data.data.length], data.data);
						// struct += compose_mem_profiles([0, new_data.length], new_data);
					struct += "</div>";
					struct += "<div id='footer'>";
						struct += "<a href='#' class='w3-button w3-light-green'>I'm Not in the list</a>";
						struct += "<a href='#' class='w3-button w3-red'>I'm in the list</a>";
					struct += "</div>";
					$('#page-2 #mem-form-data-verify').html(struct);
					
					$('#page-2 div#page-body #mem-form-data-verify div#footer a').click(function(){
						if($(this).index() == 0){
							$('#page-2 #mem-form-data-lvl-container form#'+form).submit();
						}else{
							// console.log($('#page-2 #mem-form-data-lvl-nav a').attr("href"));
							window.location.href = $('#page-2 #mem-form-data-lvl-nav a').attr("href");
						}
						
						return false;
					});
				}else{
					$('#page-2 #mem-form-data-lvl-container form#'+form).submit();
				}
			}
			
			if(data.mode == "mems"){
			// console.log(data.data);
				mems_data[0] = data.data[0][0];
				grp_data = data.data[0][1];
				disp_state = data.data[1];
				
				displayAll_members();
				
				var get_url = window.location.href.split("?"), url_reqs = get_url[1].split("&"), page_content = 0;
				for(let i=0; i<url_reqs.length; i++){
					let req = url_reqs[i].split("=");
					if(req[0] == "m-d"){
						if(req[1] == 3){
							page_content = 1;
						}
					}
				}
				if(page_content == 1){
					cont_email_processor();
					
					data_progress_processor($('.page-wrapper #page-main #page-body #view-mem-data:nth-child(1) div.data-progress-bar'));
				}
			}
			
			$('.mems-list div.content:nth-child('+disp_opts[0]+') ul li').each(function(){
				data_progress_processor($(this).find('div.profile-pic div.data-progress-bar'));
			});
		}
		
		function errorFn(xhr, status, strErr){
			console.log("This error happened: "+strErr);
		}
	}
	
	function data_progress_processor(prog_container){
		prog_container.find('span').animate( {"width": (prog_container.width()/5)*Number(prog_container.attr('id'))+"px"} );
	}
	
	// Only for Display Member Profile page
	function cont_email_processor(){
		let sel_mem_id = $('.page-wrapper #page-main #page-body #view-mem-data').attr('uid');
		
		// console.log(sel_mem_id);
		for(let i=0; i<mems_data[0].length; i++){
			if(sel_mem_id == mems_data[0][i][0]["id"]){
				let struct = "";
				for(let j=0; j<mems_data[0][i][0]["contact"].length; j++){
					struct += "<div id='"+mems_data[0][i][0]["contact"][j][0]+"' class='contacts' title='";
					struct += (mems_data[0][i][0]["contact"][j][2] == 1) ? "Primary" : "Secondary";
					struct += "'><div><span class='";
					struct += (mems_data[0][i][0]["contact"][j][2] == 1) ? "active" : "inactive";
					struct += "'></span></div><div>"+mems_data[0][i][0]["contact"][j][1]+"</div>";
					if(mems_data[0][i][0]["contact"][j][2] == 0){
						struct += "<a href='#' class='Clear' title='Clear'>&#x2715;</a>";
					}
					struct += "<br style='clear: both;' />";
					struct += "</div>";
				}
				struct += "<input type='tel' name='contact' placeholder='Add a Contact (xxxxxxxxxx)' /><a href='#' class='add'>Add</a>";
				$('.page-wrapper #page-main #page-body #view-mem-data .mem-data tr#contact td:nth-child(2)').html(struct);
				
				struct = "";
				for(let j=0; j<mems_data[0][i][0]["email"].length; j++){
					struct += "<div id='"+mems_data[0][i][0]["email"][j][0]+"' class='contacts' title='";
					struct += (mems_data[0][i][0]["email"][j][2] == 1) ? "Primary" : "Secondary";
					struct += "'><div><span class='";
					struct += (mems_data[0][i][0]["email"][j][2] == 1) ? "active" : "inactive";
					struct += "'></span></div><div>"+mems_data[0][i][0]["email"][j][1]+"</div>";
					if(mems_data[0][i][0]["email"][j][2] == 0){
						struct += "<a href='#' class='Clear' title='Clear'>&#x2715;</a>";
					}
					struct += "<br style='clear: both;' />";
					struct += "</div>";
				}
				struct += "<input type='tel' name='email' placeholder='Add an Email (name@example.com)' /><a href='#' class='add'>Add</a>";
				$('.page-wrapper #page-main #page-body #view-mem-data .mem-data tr#email td:nth-child(2)').html(struct);
			}
		}
		
		// Add
		$('.page-wrapper #page-main #page-body #view-mem-data div.mem-data tr.contacts td input').keyup(function(event){
			let data_type = ($(this).attr('name') == "contact") ? 1 : 0;
			if(event.key == "Enter" && $(this).val() != ""){
				event.preventDefault();
				event.stopImmediatePropagation();
				// console.log($(this).val());
				
				let act_type = 1; // 1: Add, 0: Clear/Remove, 2: Make Active
				// mems_processor([[[1, 0], [[[$(this).val(), data_type], sel_mem_id], act_type]], 1], "mems");
				mems_processor([[[1, 0], [[[$(this).val(), data_type], [sel_mem_id, $(this).closest('div.contacts').attr('id')]], act_type]], 1], "mems");
			}
		});
		$('.page-wrapper #page-main #page-body #view-mem-data div.mem-data tr.contacts td a.add').click(function(){
			let input = $(this).closest('td').find('input'), data_type = (input.attr('name') == "contact") ? 1 : 0;
			if(input.val() != ""){
				event.preventDefault();
				event.stopImmediatePropagation();
				
				let act_type = 1; // 1: Add, 0: Clear/Remove, 2: Make Active
				mems_processor([[[1, 0], [[[input.val(), data_type], [sel_mem_id, $(this).closest('div.contacts').attr('id')]], act_type]], 1], "mems");
				
				return false;
			}
		});
		
		// Clear
		$('.page-wrapper #page-main #page-body #view-mem-data div.mem-data tr.contacts td div.contacts a.Clear').click(function(){
			let act_type = 0; // 1: Add, 0: Clear/Remove, 2: Make Active
			let input = $(this).closest('td').find('input'), data_type = (input.attr('name') == "contact") ? 1 : 0;
			mems_processor([[[1, 0], [[["", data_type], [sel_mem_id, $(this).closest('div.contacts').attr('id')]], act_type]], 1], "mems");
			
			return false;
		});
		
		// Change Active state
		$('.page-wrapper #page-main #page-body #view-mem-data div.mem-data tr.contacts td div.contacts div span').click(function(){
			let act_type = 2; // 1: Add, 0: Clear/Remove, 2: Make Active
			let input = $(this).closest('td').find('input'), data_type = (input.attr('name') == "contact") ? 1 : 0;
			mems_processor([[[1, 0], [[["", data_type], [sel_mem_id, $(this).closest('div.contacts').attr('id')]], act_type]], 1], "mems");
				// console.log(sel_mem_id);
		});
	}
	
	function displayAll_members(){
		$('.page-wrapper #page-main #page-body #mems-bottom div.pagination div:nth-child(2) a').css("display", "inline-block");
		$('.page-wrapper #page-main #page-body #mems-bottom div.pagination div:nth-child(1) a').css("display", "inline-block");
		
		if(start_end[1] > mems_data[0].length){
			start_end[1] = mems_data[0].length;
			$('.page-wrapper #page-main #page-body #mems-bottom div.pagination div:nth-child(2) a').css("display", "none");
		}
		
		if(pag[0] == 0){
			$('.page-wrapper #page-main #page-body #mems-bottom div.pagination div:nth-child(1) a').css("display", "none");
		}
		
		$('.page-wrapper #page-main #page-body #mems-top div.mem-count:nth-child(1)').text(mems_data[0].length);
		
		if(curr_page("page_3")){ // Attendance: We need all Active members
			disp_state = [1, [1, 1, 1, 1]];
		}
		
		$('.page-wrapper #page-main #page-body #mems-top div.mems-type div').css("display", "none");
		$('.page-wrapper #page-main #page-body #mems-top div.mems-type div:nth-child('+(Number(disp_state[0])+1)+')').css("display", "block");
		$('.page-wrapper #page-main #page-body #mems-top div.mems-type div:nth-child('+(Number(disp_state[0])+1)+') a').removeClass('selected');
			// console.log(disp_opts);
		for(let i=0; i<disp_state[1].length; i++){
			if(disp_state[1][i] == 1){
				$('.page-wrapper #page-main #page-body #mems-top div.mems-type div:nth-child('+(Number(disp_state[0])+1)+') a:nth-child('+(i+1)+')').addClass('selected');
			}
		}
		
		let struct = compose_mem_profiles(start_end, mems_data[0]);
		$('.page-wrapper #page-main #page-body #mems-middle div.content:nth-child('+disp_opts[0]+')').html(struct);
		
		$(".mems-list ul#wrap-list li div.profile-nav a:nth-child(1)").click(function(event){
			event.preventDefault();
			event.stopImmediatePropagation();
			
			let mem_id = Number($(this).closest("li").attr("id"));
			if(!$(this).is('.selected')){
				$(this).addClass("selected");
				mems_data[1] = selected_list(mem_id, mems_data[1]); // Add
				
				if(curr_page("page_3")){ // 
					if(disp_opts[1][1] == 0){
						disp_opts[1][1] = 1;
						let lvl_data = [[1, 0], 0]; // [0]: Lvl & Grp, [1]: If an array(custom/sub grp indexes: 1 parent '1 based index', 0 child '0 based index') else 0 'mother grp'
						disp_opts[1][0] = setTimeout(function(){attendance_processor([[lvl_data, [mems_data[1], [0, 1],0]], 1], "att-1"); disp_opts[1][1] = 0;}, (1000*60)*5); // (1000*60)*5; 5 mins
					}
				}
			}else{
				$(this).removeClass("selected");
				mems_data[1] = selected_list(mem_id, mems_data[1], 1); // Remove
			}
			// console.log(disp_opts[1]);
			
			return false;
		});
		
		$('.page-wrapper #page-main #page-body #mems-middle div.content:nth-child('+disp_opts[0]+')').stop().animate( {'height': ($('.page-wrapper #page-main #page-body #mems-middle div.content:nth-child('+disp_opts[0]+') ul#wrap-list').height()+20)+"px"}, 500);
	}
	
	function selected_list(value, arr_data, type=0){
		if(type == 0){
			arr_data[arr_data.length] = value;
		}else{
			let val_state = val_exist_2(value, arr_data);
			if(val_state[0] == 1){
				arr_data.splice(val_state[1], 1);
			}
		}
		
		return arr_data;
	}
	
	function compare_arrs(val, arr){
		for(let i=0; i<arr.length; i++){
			for(let j=0; j<arr[i].length; j++){
				if(arr[i][j] == val[j] && j == arr[i].length-1){
					return true;
				}
			}
		}
		
		return false;
	}
	
	function compose_mem_profiles(start_end, mems_data){
		let struct = "<ul id='wrap-list'>";
		for(let i=start_end[0]; i<start_end[1]; i++){
			let name = mems_data[i][0].name;
			let id = mems_data[i][0].id;
			
			let birthday = display_birthday(mems_data[i][0].dob[5]);
			
			var get_url = window.location.href+"&m-d=3&on="+id;
			
			struct += "<li id='"+mems_data[i][0].id+"' style='border: 3px solid ";
			if(disp_state[0] == 1){ // Only in the Actives
				if(mems_data[i][0].user[1] == 1){ // Admin
					struct += "#000";
				}else if(mems_data[i][0].user[1] == 2){ // Super U
					struct += "red";
				}else if(mems_data[i][0].user[1] == 3){ // Ordinary U
					struct += "green";
				}else{
					struct += "#fff";
				}
			}else if(disp_state[0] == 2){ // Only in the Inactives
				for(let j=0; j<mems_data[i][0].register.length; j++){
					if(mems_data[i][0].register[j][4] == 1){ // Most recent/active record
						switch(Number(mems_data[i][0].register[j][3])){
							case 2: // Manually turned off by U (mem is alive)
								struct += "yellow";
								break;
							case 3: // Manually turned off by U (mem is dead)
								struct += "#000";
								break;
							default: // Automatically turned off by Sys (mem is alive)
								struct += "blue";
						}
					}
				}
			}else if(disp_state[0] == 3){ // Only in the Superiors
				struct += "orange";
			}else{ // Only in the All
				for(let j=0; j<mems_data[i][0].register.length; j++){
					if(mems_data[i][0].register[j][4] == 1){
						switch(Number(mems_data[i][0].register[j][3])){
							case 1: // Active
								struct += "green";
								break;
							default: // Automatically turned off by Sys (mem is alive)
								struct += "orange";
						}
					}
				}
			}
			
			// console.log(mems_data[i][0].img);
			struct += ";'>";
				struct += "<div class='profile-pic' title='"+(birthday[0] !== "" ? birthday[0] : "")+"'>";
					struct += "<div><img src='../"+mems_data[i][0].img[2]+"' style='width: 100%;' /></div>";
					struct += "<div id='"+mems_data[i][0].status[2]+"' class='data-progress-bar'><span></span><br style='clear: both;' /></div>";
				struct += "</div>";
				struct += "<div class='profile-nav'>";
					struct += "<a href='#'></a>";
					struct += "<a href='"+get_url+"'>"+name[0]+" ["+(i+1)+"] <div class='birthday-layout-1 "+birthday[1]+"'></div></a>";
				struct += "</div>";
			struct += "</li>";
		}
		struct += "<br style='clear: both;' />";
		struct += "</ul>";
		
		return struct;
	}
	
	
	
	function attendance_processor(action, mode){
		action = treat_empty_arrs(action, true);
		var postData = {action: action, mode: mode};
		
		$.ajax({
			url: "../core/core/core_4.php",
			type: "POST",
			data: postData,
			dataType : "json",
			beforeSend: function() {
				//message_div(duration=false);
			},
			success: successFn,
			error: errorFn,
			
			// code to run regardless of success or failure
			complete: function( xhr, status ) {
				//console.log("The request is complete!");
			}
		});
		
		function successFn(data){
			console.log(data.data);
		}
		
		function errorFn(xhr, status, strErr){
			console.log("This error happened: "+strErr);
		}
	}
	
	
	
	
	
	var sel_tab = $(".universal-tabs-head a.selected").index()+1;
	var sel_data = $(".universal-tabs-container .universal-tabs-body .universal-tabs-body-frame:nth-child("+sel_tab+")").css("display", "block");
	$(".universal-tabs-head a").click(function(){
		$(".universal-tabs-head a").removeClass("selected");
		$(this).addClass("selected");
		
		$(".universal-tabs-body .universal-tabs-body-frame").css("display", "none");
		var sel_tab = $(this).index()+1;
		var sel_data = $(this).closest(".universal-tabs-container").find(".universal-tabs-body .universal-tabs-body-frame:nth-child("+sel_tab+")");
		sel_data.css("display", "block");
		
		return false;
	});
	
	$("span.password-view").click(function(){
		var pass = $(this).closest(".auth-form").find("input#user-pass");
		if(pass.val() !== ""){
			$(this).toggleClass("selected");
			if(pass.attr("type") == "password"){
				pass.attr("type", "text");
			}else{
				pass.attr("type", "password");
			}
			pass.focus();
		}
	});
	
	// let [i, j, k] = Obj;
	// console.log(i);
	
	let auth_data = [[0, 0], ["", ""]];
	$("div#home div#auth-state a").click(function(){ // Sign Up/Login
		$("div#home div#auth-state a").removeClass("selected");
		$(this).addClass("selected");
		auth_data[0][0] = $(this).index();
		
		$("div#home div#auth-form div.auth-type:nth-child(1) .auth-elem").css("display", "inline-block");
		
		$("div#home div#auth-form div.auth-type:nth-child(2) .auth-elem").css("display", "none");
		$("div#home div#auth-form div.auth-type:nth-child(2) .auth-elem:nth-child("+(auth_data[0][1]+1)+")").css("display", "block");
		if(auth_data[0][0] == 1){
			$("div#home div#auth-form div.auth-type:nth-child(1) .auth-elem").css("display", "none");
			$("div#home div#auth-form div.auth-type:nth-child(2) .auth-elem").css("display", "inline-block");
		}
	});
	$("div#home div#auth-form div.auth-type:nth-child(1) .auth-elem").click(function(){ // Login: With email/number
		$("div#home div#auth-form div.auth-type:nth-child(1) .auth-elem").removeClass("selected");
		$(this).addClass("selected");
		auth_data[0][1] = $(this).index();
		
		$("div#home div#auth-form div.auth-type:nth-child(2) .auth-elem").css("display", "none");
		$("div#home div#auth-form div.auth-type:nth-child(2) .auth-elem:nth-child("+(auth_data[0][1]+1)+")").css("display", "block");
	});
	$("div#home div#auth-form input[type=button]").click(function(){
		let email = $("div#home div#auth-form div.auth-type:nth-child(2) .auth-elem:nth-child(1)").val();
		let cont = (auth_data[0][0] == 1 || auth_data[0][0] == 0 && auth_data[0][1] == 1) ? $("div#home div#auth-form div.auth-type:nth-child(2) .auth-elem:nth-child(2)").val() : "", base_data = [[email, cont], 1], proceed = 1;
		
		if(auth_data[0][0] == 0){ // In the Login
			let this_data = (auth_data[0][1] == 0) ? email : cont;
			if(this_data == ""){
				proceed = 0;
			}
			base_data = [[this_data, auth_data[0][1]], 2];
		}else{ // In the sign up
			let entry = (email == "" || cont == "") ? true : false;
			if(email == "" && cont == "" || entry){
				proceed = 0;
			}
		}
		
		console.log(auth_data[0][0]);
		// VALIDATE SIGNUP:
		// When clicked a second or more, detect by the first.
		// Provide a code to be required on the next stage. To ensure the user on the signup page is same as the email.
		if(proceed == 1){
			auth_processor(base_data, "auth");
		}
	});
	
	$("div#access form input[type=password]").keyup(function(){
		let access_type = $(this).closest("form"), in_pass = access_type.find("input#pass").val(), confirm_pass = "", out_pass = "";
		
		if($(this).val() != ""){
			if(access_type.attr("id") == 1){ // Sign Up
				if($(this).attr("id") == "confirm-pass"){
					in_pass = access_type.find("input#pass").val();
					confirm_pass = $(this).val();
				}else{
					in_pass = $(this).val();
					confirm_pass = access_type.find("input#confirm-pass").val();
				}
				auth_data[1] = [in_pass, confirm_pass];
			}
			
			if(access_type.attr("id") == 0){ // Login
				auth_data[1][auth_data[0][0]] = $(this).val();
			}
		}
	});
	// 2YkocT#R
	
	$("div#access form input#grant-access").click(function(){
		let access_type = $(this).closest("form");
		auth_data[0][0] = access_type.attr("id");
		
				// console.log(auth_data[0][0]);
		if(auth_data[0][0] == 1){
			if(auth_data[1][0] == auth_data[1][1] && auth_data[1][1] != ""){
				access_type.find("input#grant-access").attr("type", "submit");
			}else{
				console.log("Sorry, the password does not match..");
				access_type.find("input#grant-access").attr("type", "button");
			}
		}else{
			access_type.find("input#grant-access").attr("type", "submit");
			console.log("Okay, so we weren't in the login....");
		}
	});
	
	function auth_processor(action, mode){
		action = treat_empty_arrs(action, true);
		var postData = {action: action, mode: mode};
		
		$.ajax({
			url: "../core/core/core_2.php",
			type: "POST",
			data: postData,
			dataType : "json",
			beforeSend: function() {
				//message_div(duration=false);
			},
			success: successFn,
			error: errorFn,
			
			// code to run regardless of success or failure
			complete: function( xhr, status ) {
				//console.log("The request is complete!");
			}
		});
		
		function successFn(data){
			
			// kwadjodanq@gmail.com
			// 0558244996
			if(data.data[1][0] == 1 || data.data[1][0] == 2){ // 2 is login here but 0 on the next page.
				// SEND THIS LINK TO THE CLIENT'S EMAIL INSTEAD
				if(data.data[1][0] == 2){
					data.data[1][0] = 0;
				}
				window.location.href = "page_0.php?action=1&access-key="+data.data[1][1][1]+"&on="+data.data[1][1][0]+"&access-type="+data.data[1][0];
			}
		}
		
		function errorFn(xhr, status, strErr){
			console.log("This error happened: "+strErr);
		}
	}
	
});