<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	require_once('./php/UIHandler.php');
	require_once('./php/CRMDefaults.php');
    require_once('./php/LanguageHandler.php');
    include('./php/Session.php');

	$ui = \creamy\UIHandler::getInstance();
	$lh = \creamy\LanguageHandler::getInstance();
	$user = \creamy\CreamyUser::currentUser();
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Goautodial Contacts & Call Recordings</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- bootstrap wysihtml5 - text editor -->
        <link href="css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css" rel="stylesheet" type="text/css" />
        <!-- Creamy style -->
        <link href="css/creamycrm.css" rel="stylesheet" type="text/css" />
        <!-- Circle Buttons style -->
        <link href="css/circle-buttons.css" rel="stylesheet" type="text/css" />
        <!-- Wizard Form style -->
        <link href="css/wizard-form.css" rel="stylesheet" type="text/css" />
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="css/easyWizard.css">
        <!-- DATA TABLES -->
        <link href="css/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
		<!-- Bootstrap Player -->
		<link href="css/bootstrap-player.css" rel="stylesheet" type="text/css" />
        <?php print $ui->creamyThemeCSS(); ?>

        <!-- datetime picker --> 
		<link rel="stylesheet" href="theme_dashboard/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="js/html5shiv.js"></script>
          <script src="js/respond.min.js"></script>
        <![endif]-->
        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js" type="text/javascript"></script>
        <script src="js/jquery-ui.min.js" type="text/javascript"></script>
        <!-- Bootstrap WYSIHTML5 -->
        <script src="js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js" type="text/javascript"></script>

        <!-- Data Tables -->
        <script src="js/plugins/datatables/jquery.dataTables.js" type="text/javascript"></script>
        <script src="js/plugins/datatables/dataTables.bootstrap.js" type="text/javascript"></script>

        <!-- Date Picker -->
        <script type="text/javascript" src="theme_dashboard/eonasdan-bootstrap-datetimepicker/build/js/moment.js"></script>
		<script type="text/javascript" src="theme_dashboard/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>

		<!-- CHOSEN-->
   		<link rel="stylesheet" href="theme_dashboard/chosen_v1.2.0/chosen.min.css">
   		<!-- SELECT2-->
   		<link rel="stylesheet" href="theme_dashboard/select2/dist/css/select2.css">
   		<link rel="stylesheet" href="theme_dashboard/select2-bootstrap-theme/dist/select2-bootstrap.css">

	<!-- Bootstrap Player -->
	<script src="js/bootstrap-player.js" type="text/javascript"></script>

        <!-- Creamy App -->
        <script src="js/app.min.js" type="text/javascript"></script>

        <!-- =============== APP STYLES ===============-->
			<link rel="stylesheet" href="theme_dashboard/css/app.css" id="maincss">

        <!-- preloader -->
        <link rel="stylesheet" href="css/customizedLoader.css">

        <script type="text/javascript">
			$(window).ready(function() {
				$(".preloader").fadeOut("slow");
			})
		</script>
		<style>
		/*
		* CUSTOM CSS for disable function
		*/
			.c-checkbox input[type=checkbox]:disabled + span,
			.c-radio input[type=checkbox]:disabled + span,
			.c-checkbox input[type=radio]:disabled + span,
			.c-radio input[type=radio]:disabled + span {
				border-color: none !important;
    			background-color: none !important;
			}
			.c-checkbox input[type=checkbox]:checked + span,
			.c-radio input[type=checkbox]:checked + span,
			.c-checkbox input[type=radio]:checked + span,
			.c-radio input[type=radio]:checked + span
			 {
				border-color: #3f51b5 !important;
    			background-color: #3f51b5 !important;
			}
		</style>
    </head>
    <?php print $ui->creamyBody(); ?>
        <div class="wrapper">
        <!-- header logo: style can be found in header.less -->
		<?php print $ui->creamyHeader($user); ?>
            <!-- Left side column. contains the logo and sidebar -->
			<?php print $ui->getSidebar($user->getUserId(), $user->getUserName(), $user->getUserRole(), $user->getUserAvatar()); ?>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header content-heading">
                    <h1>
                        <?php $lh->translateText("contacts_call_recordings"); ?>
                        <small><?php $lh->translateText("contacts_call_recordings_management"); ?></small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="./index.php"><i class="fa fa-phone"></i> <?php $lh->translateText("home"); ?></a></li>
                       <li><?php $lh->translateText("telephony"); ?></li>
						<li class="active"><?php $lh->translateText("contacts_call_recordings"); ?>
                    </ol>
                </section>
<?php
$lists = $ui->API_goGetAllLists();
$callrecs = $ui->API_getListAllRecordings();
$leads = $ui->API_GetLeads($user->getUserName());
?>
                <!-- Main content -->
                <section class="content">
                	<div class="row">
						<div class="col-lg-9">
							<div class="form-group mb-xl">
								<input type="text" placeholder="Search Phone, Agent or Last Name" id="search" class="form-control mb">
								<div class="clearfix">
									<button type="button" class="pull-left btn btn-default" id="search_button"> Search</button>
									<div class="pull-right">
										<label class="checkbox-inline c-checkbox" for="search_customer">
											<input id="search_customer" name="table_filter" type="checkbox" disabled>
											<span class="fa fa-check"></span> Customers
										</label>
										<label class="checkbox-inline c-checkbox" for="search_contacts">
											<input id="search_contacts" name="table_filter" type="checkbox" checked disabled>
											<span class="fa fa-check"></span> Contacts
										</label>
										<label class="checkbox-inline c-checkbox" for="search_recordings">
											<input id="search_recordings" name="table_filter" type="checkbox">
											<span class="fa fa-check"></span> Recordings
										</label>
										<label class="checkbox-inline c-checkbox" for="search_tickets">
											<input id="search_tickets" name="table_filter" type="checkbox" disabled>
											<span class="fa fa-check"></span> Tickets
										</label>
										<label class="checkbox-inline c-checkbox" for="search_chats">
											<input id="search_chats" name="table_filter" type="checkbox" disabled>
											<span class="fa fa-check"></span> Chats
										</label>
									</div>
								</div>
							</div>
		                	<div class="panel panel-default">
								<div class="panel-body">
									<div class="contacts_div">
									<!-- Contacts panel tab -->
									<legend>Contacts</legend>
									
										<!--==== Contacts ====-->
										<table class="table table-striped table-bordered table-hover" id="table_contacts">
										   <thead>
											  <tr>
												 <th>Lead ID</th>
												 <th class='hide-on-medium hide-on-low'>Full Name</th>
												 <th class='hide-on-medium hide-on-low'>Phone Number</th>
												 <th class='hide-on-medium hide-on-low'>Status</th>
												 <th>Action</th>
											  </tr>
										   </thead>
										   <tbody>
											   	<?php
											   		for($i=0;$i<=count($leads->list_id);$i++){

												   		if($leads->phone_number[$i] != ""){

														$action_lead = $ui->ActionMenuForContacts($leads->lead_id[$i]);

											   	?>	
														<tr>
															<td><?php echo $leads->lead_id[$i];?></a></td>
															<td class='hide-on-medium hide-on-low'><?php echo $leads->first_name[$i].' '.$leads->middle_initial[$i].' '.$leads->last_name[$i];?></td>
															<td class='hide-on-medium hide-on-low'><?php echo $leads->phone_number[$i];?></td>
															<td class='hide-on-medium hide-on-low'><?php echo $leads->status[$i];?></td>
															<td><?php echo $action_lead;?></td>
														</tr>
												<?php
														}
													}
												?>
										   </tbody>
										</table>
									</div>

									<div class="callrecordings_div" style="display:none;">	
									<!-- Call Recordings panel tab -->
										<legend>Call Recordings</legend>

										<!--==== Call Recordings ====-->
										<table class="table table-striped table-bordered table-hover" id="table_callrecordings">
										   <thead>
											  <tr>
												 <th>Date</th>
												 <th class='hide-on-medium hide-on-low'>Customer</th>
												 <th class='hide-on-medium hide-on-low'>Phone Number</th>
												 <th class='hide-on-medium hide-on-low'>Agent</th>
												 <th class='hide-on-medium hide-on-low'>Duration</th>
												 <th>Action</th>
											  </tr>
										   </thead>
										   <tbody>
											   	<?php
											   		for($i=0;$i < count($callrecs->list_id);$i++){

												   		$d1 = strtotime($callrecs->start_last_local_call_time[$i]);
														$d2 = strtotime($callrecs->end_last_local_call_time[$i]);

														$diff = abs($d2 - $d1);

														$action_Call = $ui->getUserActionMenuForCallRecording($callrecs->uniqueid[$i], $callrecs->location[$i]);

											   	?>	
														<tr>
															<td><?php echo $callrecs->end_last_local_call_time[$i];?></a></td>
															<td class='hide-on-medium hide-on-low'><?php echo $callrecs->full_name[$i];?></td>
															<td class='hide-on-medium hide-on-low'><?php echo $callrecs->phone_number[$i];?></td>
															<td class='hide-on-medium hide-on-low'><?php echo $callrecs->users[$i];?></td>
															<td><?php echo gmdate('H:i:s', $diff); ?></td>
															<td><?php echo $action_Call;?></td>
														</tr>
												<?php
													}
												?>
										   </tbody>
										</table>
									</div>
			               		</div><!-- /.body -->
		               		</div><!-- /.panel -->
	               		</div><!-- /.col-lg-9 -->
<?php
$lists = $ui->API_goGetAllLists();
$agents = $ui->API_goGetAllUserLists();
$disposition = $ui->API_getAllDispositions();
?>
	               		<div class="col-lg-3">
	           				<h3 class="m0 pb-lg">Filters</h3>
	           				<form id="search_form">

		                        <div class="form-group">
		                           <label>Add Filters:</label>
		                           <div class="mb">
		                              	<select id="add_filters" multiple="multiple" class="select2-3 form-control" style="width:100%;">
		                                    <option value="filter_disposition" class="contacts_filters">Disposition</option>
		                                    <option value="filter_list" class="contacts_filters">List ID</option>
		                                    <option value="filter_address" class="contacts_filters">Address </option>
		                                    <option value="filter_city" class="contacts_filters">City </option>
		                                    <option value="filter_state" class="contacts_filters">State </option>
		                                    <option value="filter_agent" class="callrecordings_filters" disabled>Agent </option>
		                             	</select>
		                           </div>
		                        </div>


		                    <!-- CONTACT FILTERS -->
		                    <div class="all_contact_filters">
		                    	<div class="disposition_filter_div" style="display:none;">
								    <div class="form-group">
										<label>Disposition: </label>
										<div class="mb">
											<select name="disposition_filter" id="disposition_filter" class="form-control">
													<option value="">- - - NO DISPOSITION SELECTED - - -</option>
												<?php
												//if($disposition->campaign_id[$i] == $campaign->campaign_id[$i]){
													for($a=0; $a<count($disposition->status); $a++){
												?>
														<option value="<?php echo $disposition->status[$a];?>"><?php echo $disposition->status[$a].' - '.$disposition->status_name[$a];?></option>
												<?php
													}
												?>	
											</select>
										</div>
									</div>
								</div>
		                        <div class="list_filter_div" style="display:none;">
									<div class="form-group">
										<label>List ID: </label>
										<div class="mb">
											<select name="list_filter" id="list_filter" class="form-control">
													<option value="">- - - NO LIST SELECTED - - -</option>
												<?php
													for($i=0; $i < count($lists->list_id);$i++){
														echo "<option value='".$lists->list_id[$i]."'> ".$lists->list_name[$i]." </option>";
													}
												?>			
											</select>
										</div>
									</div>
								</div>
								<div class="address_filter_div" style="display:none;">
									<div class="form-group">
										<label>Address: </label>
										<div class="mb">
											<input type="text" class="form-control" id="address_filter" name="address_filter" placeholder="Address" />
										</div>
									</div>
								</div>
								<div class="city_filter_div" style="display:none;">
									<div class="form-group">
										<label>City: </label>
										<div class="mb">
											<input type="text" class="form-control" id="city_filter" name="city_filter" placeholder="City" />
										</div>
									</div>
								</div>
								<div class="state_filter_div" style="display:none;">
									<div class="form-group">
										<label>State: </label>
										<div class="mb">
											<input type="text" class="form-control" id="state_filter" name="state_filter" placeholder="State" />
										</div>
									</div>
								</div>
	               				
	               				<div class="form-group">
		               				<label>Start Date:</label>
						            <div class="form-group">
						                <div class='input-group date' id='datetimepicker1'>
						                    <input type='text' class="form-control" id="start_filterdate" placeholder="<?php echo date("m/d/Y H:i:s ");?>"/>
						                    <span class="input-group-addon">
						                        <!-- <span class="glyphicon glyphicon-calendar"></span>-->
												<span class="fa fa-calendar"></span>
						                    </span>
						                </div>
						            </div>
						        </div>
						        <div class="form-group">
						            <label>End Date:</label>
						            <div class="form-group">
						                <div class='input-group date' id='datetimepicker2'>
						                    <input type='text' class="form-control" id="end_filterdate" placeholder="<?php echo date("m/d/Y H:i:s");?>" value="<?php echo date("m/d/Y H:i:s");?>"/>
						                    <span class="input-group-addon">
						                        <!-- <span class="glyphicon glyphicon-calendar"></span>-->
						                        <span class="fa fa-calendar"></span>
						                    </span>
						                </div>
						            </div>
							    </div>
							</div>


							<!-- CALL RECORDINGS FILTER -->
		                    <div class="all_callrecording_filters">
		                        <div class="callrecordings_filter_div" style="display:none;">
		               				<div class="form-group">
			               				<label>Start Date:</label>
							            <div class="form-group">
							                <div class='input-group date' id='datetimepicker1'>
							                    <input type='text' class="form-control" id="start_filterdate" placeholder="<?php echo date("m/d/Y H:i:s ");?>"/>
							                    <span class="input-group-addon">
							                        <!-- <span class="glyphicon glyphicon-calendar"></span>-->
													<span class="fa fa-calendar"></span>
							                    </span>
							                </div>
							            </div>
							        </div>
							        <div class="form-group">
							            <label>End Date:</label>
							            <div class="form-group">
							                <div class='input-group date' id='datetimepicker2'>
							                    <input type='text' class="form-control" id="end_filterdate" placeholder="<?php echo date("m/d/Y H:i:s");?>" value="<?php echo date("m/d/Y H:i:s");?>"/>
							                    <span class="input-group-addon">
							                        <!-- <span class="glyphicon glyphicon-calendar"></span>-->
							                        <span class="fa fa-calendar"></span>
							                    </span>
							                </div>
							            </div>
								    </div>
								</div>

							    
								<div class="agent_filter_div" style="display:none;">
									<div class="form-group">
										<label>Agent: </label>
										<div class="mb">
											<select name="agent_filter" id="agent_filter" class="form-control">
												<option value="" selected DISABLED> -- SELECT AN AGENT -- </option> 
												<?php
													for($i=0; $i < count($agents->user_id);$i++){
														echo "<option value='".$agents->userno[$i]."'> ".$agents->full_name[$i]." </option>";
													}
												?>			
											</select>
										</div>
									</div>
								</div>
							</div>

							<fieldset>
							    <!--
							    <div class="campaign_filter_div" style="display:none;">
								    <div class="form-group">
										<label>Campaign: </label>
										<div class="mb">
											<select name="campaign_filter" class="form-control">
												<?php
												/*
													for($i=0; $i < count($campaign->campaign_id);$i++){
														echo "<option value='".$campaign->campaign_id[$i]."'> ".$campaign->campaign_name[$i]." </option>";
													}
												*/
												?>			
											</select>
										</div>
									</div>
								</div>
								-->
							</fieldset>

							</form>
						    <!--<button type="button" class="pull-left btn btn-default" id="search_button">Apply</button>-->
	           			</div><!-- ./filters -->
           			</div><!-- /. row -->
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
	
	<!-- FIXED ACTION BUTTON -->
	<div class="action-button-circle" data-toggle="modal" data-target="#list-modal">
		<?php print $ui->getCircleButton("list_and_call_recording", "plus"); ?>
	</div>

	<!-- Modal -->
	<div id="call-playback-modal" class="modal fade" role="dialog">
	  <div class="modal-dialog">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title"><b>Call Recording Playback</b></h4>
	      </div>
	      <div class="modal-body">
		<div class="audio-player"></div>
	      	<!-- <audio controls>
			<source src="http://www.w3schools.com/html/horse.ogg" type="audio/ogg" />
			<source src="http://www.w3schools.com/html/horse.mp3" type="audio/mpeg" />
			<a href="http://www.w3schools.com/html/horse.mp3">horse</a>
		</audio> -->
	      </div>
	      <div class="modal-footer">
		<a href="" class="btn btn-primary download-audio-file" download>Download File</a>
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
	    </div>
	    <!-- End of modal content -->
	  </div>
	</div>
	<!-- End of modal -->
		<!-- Forms and actions -->
		<script src="js/jquery.validate.min.js" type="text/javascript"></script>
		<script src="js/easyWizard.js" type="text/javascript"></script> 
		<!-- CHOSEN-->
   		<script src="theme_dashboard/chosen_v1.2.0/chosen.jquery.min.js"></script>
   		<!-- SELECT2-->
   		<script src="theme_dashboard/select2/dist/js/select2.js"></script>

		<script type="text/javascript">

			$(document).ready(function() {
				// Global var for counter
				var giCount = 2;

				// initialization of datatables
				var init_contacts_table = $('#table_contacts').DataTable({
                	"bDestroy" : true
                });

				var init_callrecs_table = $('#table_callrecordings').DataTable({
                	"bDestroy" : true
                });

				// initialize single selecting
				$('#select2-1').select2({
			        theme: 'bootstrap'
			    });
			    // initialize multiple selecting
				$('.select2-3').select2({
			        theme: 'bootstrap'
			    });

				// limits checkboxes to single selecting
				$("input:checkbox").on('click', function() {
				  var $box = $(this);
				  if ($box.is(":checked")) {
				    var group = "input:checkbox[name='" + $box.attr("name") + "']";
				    $(group).prop("checked", false);
				    $box.prop("checked", true);
				  } else {
				    $box.prop("checked", false);
				  }
				});

				/****
				** Change between Contacts and Recordings
				****/
					// shows contacts datatable if Contact tickbox is checked
					$('#search_contacts').on('change', function() {
						$("#search_contacts").prop("disabled", true);
		            	$("#search_recordings").prop("disabled", false);

						if($('#search_contacts').is(":checked")){
							$(".contacts_div").show(); // show contact table
							$(".callrecordings_div").hide(); // hide table

							$(".all_contact_filters").show(); // hide filter
							$(".all_callrecording_filters").hide(); // hide filter

							// hide callrecordings
							$(".callrecordings_filter_div").hide();

							$(".contacts_filters").prop("disabled", false); // enable contact filters
							$(".callrecordings_filters").prop("disabled", true); // disable recording filters
		            	}else{
		            		$(".contacts_div").hide();
		            		$(".all_contact_filters").hide();

		            		$(".contacts_filters").prop("disabled", true); // disable contact filters
							$(".callrecordings_filters").prop("disabled", false); // enable recording filters
		            	}
					});

					// shows call recordings datatable if Recordings tickbox is checked
					$('#search_recordings').on('change', function() {
						$("#search_contacts").prop("disabled", false);
		            	$("#search_recordings").prop("disabled", true);

		            	$(".contacts_filters").prop("selected", false); 

						if($('#search_recordings').is(":checked")){
							$(".callrecordings_div").show(); // show recordings table
							$(".callrecordings_filter_div").show(); // show recording filter

							// hide contacts
							$(".contacts_div").hide();
		            		$(".all_contact_filters").hide();

							$(".all_contact_filters").hide(); // hide filter
							$(".all_callrecording_filters").show(); // hide filter

							$(".contacts_filters").prop("disabled", true); // disable contact filters
							$(".callrecordings_filters").prop("disabled", false); // enable recording filters
							
		            	}else{
		            		$(".callrecordings_div").hide();
		            		$(".all_callrecording_filters").hide();

		            		$(".contacts_filters").prop("disabled", false); // enable contact filters
							$(".callrecordings_filters").prop("disabled", true); // disable recording filters
		            	}

					});

				/***
				** Add Filters
				***/
					// add filters
					$("#add_filters").change(function(){
				        $(this).find("option:selected").each(function(){
				        		
				            if($(this).attr("value")=="filter_campaign"){
				                $(".campaign_filter_div").show();
				            }

				            if($(this).attr("value")=="filter_list"){
				                $(".list_filter_div").show();
				            }

				            if($(this).attr("value")=="filter_disposition"){
				                $(".disposition_filter_div").show();
				            }

				            if($(this).attr("value")=="filter_address"){
				                $(".address_filter_div").show();
				            }

				            if($(this).attr("value")=="filter_city"){
				                $(".city_filter_div").show();
				            }

				            if($(this).attr("value")=="filter_state"){
				                $(".state_filter_div").show();
				            }

				            if($(this).attr("value")=="filter_agent"){
				                $(".agent_filter_div").show();
				            }

				        });
				    }).change();
				
				/****
				** Contact filters
				****/ 

					// ----- Disposition
						$('#disposition_filter').on('change', function() {
		            		var disposition_filter_val = $('#disposition_filter').val();
		            		var list_filter_val = $('#list_filter').val();
		            		var address_filter_val = $("#address_filter").val();
		            		var city_filter_val = $("#city_filter").val();
		            		var state_filter_val = $("#state_filter").val();

		            		$.ajax({
							    url: "filter_contacts.php",
							    type: 'POST',
							    data: {
							    	search_contacts : $('#search').val(),
							    	disposition : disposition_filter_val,
							    	list : list_filter_val,
							    	address : address_filter_val,
							    	city : city_filter_val,
							    	state : state_filter_val
							    },
								success: function(data) {

									console.log(data);

									if(data != ""){
										$('#table_contacts').html(data);
										$('#table_contacts').DataTable({
						                	"bDestroy" : true
						                });
									}else{
										init_contacts_table.fnClearTable();
									}
								}
							});
							
						});

					// ----- List ID
						$('#list_filter').on('change', function() {
		            		var disposition_filter_val = $('#disposition_filter').val();
		            		var list_filter_val = $('#list_filter').val();
		            		var address_filter_val = $("#address_filter").val();
		            		var city_filter_val = $("#city_filter").val();
		            		var state_filter_val = $("#state_filter").val();

		            		$.ajax({
							    url: "filter_contacts.php",
							    type: 'POST',
							    data: {
							    	search_contacts : $('#search').val(),
							    	disposition : disposition_filter_val,
							    	list : list_filter_val,
							    	address : address_filter_val,
							    	city : city_filter_val,
							    	state : state_filter_val
							    },
								success: function(data) {

									console.log(data);

									if(data != ""){
								        
										$('#table_contacts').html(data);
										$('#table_contacts').DataTable({
						                	"bDestroy" : true
						                });
									}else{
										init_contacts_table.fnClearTable();
									}
								}
							});
							
						});

					// ----- Address
						$("#address_filter").keyup(function() {
							clearTimeout($.data(this, 'timer'));
							var wait = setTimeout(address_filter_ajax, 3000);
							$(this).data('timer', wait);
						});

						function address_filter_ajax() {
						    var disposition_filter_val = $('#disposition_filter').val();
		            		var list_filter_val = $('#list_filter').val();
		            		var address_filter_val = $("#address_filter").val();
		            		var city_filter_val = $("#city_filter").val();
		            		var state_filter_val = $("#state_filter").val();

						    $.ajax({
							    url: "filter_contacts.php",
							    type: 'POST',
							    data: {
							    	search_contacts : $('#search').val(),
							    	disposition : disposition_filter_val,
							    	list : list_filter_val,
							    	address : address_filter_val,
							    	city : city_filter_val,
							    	state : state_filter_val
							    },
								success: function(data) {

									console.log(data);

									if(data != ""){
										$('#table_contacts').html(data);
										$('#table_contacts').DataTable({
						                	"bDestroy" : true
						                });
									}else{
										init_contacts_table.fnClearTable();
									}
								}
							});
						}

					// ----- City
						$("#city_filter").keyup(function() {
							clearTimeout($.data(this, 'timer'));
							var wait = setTimeout(city_filter_ajax, 3000);
							$(this).data('timer', wait);
						});

						function city_filter_ajax() {
						    var disposition_filter_val = $('#disposition_filter').val();
		            		var list_filter_val = $('#list_filter').val();
		            		var address_filter_val = $("#address_filter").val();
		            		var city_filter_val = $("#city_filter").val();
		            		var state_filter_val = $("#state_filter").val();

						    $.ajax({
							    url: "filter_contacts.php",
							    type: 'POST',
							    data: {
							    	search_contacts : $('#search').val(),
							    	disposition : disposition_filter_val,
							    	list : list_filter_val,
							    	address : address_filter_val,
							    	city : city_filter_val,
							    	state : state_filter_val
							    },
								success: function(data) {

									console.log(data);

									if(data != ""){
										$('#table_contacts').html(data);
										$('#table_contacts').DataTable({
						                	"bDestroy" : true
						                });
									}else{
										init_contacts_table.fnClearTable();
									}
								}
							});
						}

					// ----- State
						$("#state_filter").keyup(function() {
							clearTimeout($.data(this, 'timer'));
							var wait = setTimeout(state_filter_ajax, 3000);
							$(this).data('timer', wait);
						});

						function state_filter_ajax() {
						    var disposition_filter_val = $('#disposition_filter').val();
		            		var list_filter_val = $('#list_filter').val();
		            		var address_filter_val = $("#address_filter").val();
		            		var city_filter_val = $("#city_filter").val();
		            		var state_filter_val = $("#state_filter").val();

						    $.ajax({
							    url: "filter_contacts.php",
							    type: 'POST',
							    data: {
							    	search_contacts : $('#search').val(),
							    	disposition : disposition_filter_val,
							    	list : list_filter_val,
							    	address : address_filter_val,
							    	city : city_filter_val,
							    	state : state_filter_val
							    },
								success: function(data) {

									console.log(data);

									if(data != ""){
										$('#table_contacts').html(data);
										$('#table_contacts').DataTable({
						                	"bDestroy" : true
						                });
									}else{
										init_contacts_table.fnClearTable();
									}
								}
							});
						}

				/****
				** Call Recording filters
				****/ 

					// ---- DATETIME PICKER INITIALIZATION

						$('#datetimepicker1').datetimepicker({
						icons: {
		                      time: 'fa fa-clock-o',
		                      date: 'fa fa-calendar',
		                      up: 'fa fa-chevron-up',
		                      down: 'fa fa-chevron-down',
		                      previous: 'fa fa-chevron-left',
		                      next: 'fa fa-chevron-right',
		                      today: 'fa fa-crosshairs',
		                      clear: 'fa fa-trash'
		                    }
						});

		                $('#datetimepicker2').datetimepicker({
		                icons: {
		                      time: 'fa fa-clock-o',
		                      date: 'fa fa-calendar',
		                      up: 'fa fa-chevron-up',
		                      down: 'fa fa-chevron-down',
		                      previous: 'fa fa-chevron-left',
		                      next: 'fa fa-chevron-right',
		                      today: 'fa fa-crosshairs',
		                      clear: 'fa fa-trash'
		                    }
		                });

	                // ---- DATE FILTERS

		                $("#datetimepicker1").on("dp.change", function(e) {
		                	var start_filterdate_val = $('#start_filterdate').val();
		                	var end_filterdate_val = $('#end_filterdate').val();
		                	var agent_filter_val = $('#agent_filter').val();

		                	$.ajax({
							    url: "filter_callrecs.php",
							    type: 'POST',
							    data: {
							    	search_recordings : $('#search').val(),
							    	start_filterdate : start_filterdate_val,
							    	end_filterdate : end_filterdate_val,
							    	agent_filter : agent_filter_val
							    },
								success: function(data) {
									console.log(data);
									if(data != ""){
										$('#table_callrecordings').html(data);
										$('#table_callrecordings').DataTable({
						                	"bDestroy" : true
						                });
									}else{
										init_callrecs_table.fnClearTable();
									}
								}
							});
						});

		                $("#datetimepicker2").on("dp.change", function(e) {
		                	var start_filterdate_val = $('#start_filterdate').val();
		                	var end_filterdate_val = $('#end_filterdate').val();
		                	var agent_filter_val = $('#agent_filter').val();
		                	$.ajax({
							    url: "filter_callrecs.php",
							    type: 'POST',
							    data: {
							    	search_recordings : $('#search').val(),
							    	start_filterdate : start_filterdate_val,
							    	end_filterdate : end_filterdate_val,
							    	agent_filter : agent_filter_val
							    },
								success: function(data) {
									console.log(data);
									if(data != ""){
										$('#table_callrecordings').html(data);
										$('#table_callrecordings').DataTable({
						                	"bDestroy" : true
						                });
									}else{
										init_callrecs_table.fnClearTable();
									}
								}
							});
						});

		            // AGENT FILTER
		            	$('#agent_filter').on('change', function() {
		            		var agent_filter_val = $('#agent_filter').val();
		            		var start_filterdate_val = $('#start_filterdate').val();
		                	var end_filterdate_val = $('#end_filterdate').val();

		            		$.ajax({
							    url: "filter_callrecs.php",
							    type: 'POST',
							    data: {
							    	search_recordings : $('#search').val(),
							    	start_filterdate : start_filterdate_val,
							    	end_filterdate : end_filterdate_val,
							    	agent_filter : agent_filter_val
							    },
								success: function(data) {
									$('#search_button').text("Search");
	                				$('#search_button').prop("disabled", false)
									console.log(data);
									if(data != ""){
								        
										$('#table_callrecordings').html(data);
										$('#table_callrecordings').DataTable({
						                	"bDestroy" : true
						                });
									}else{
										init_callrecs_table.fnClearTable();
									}
								}
							});
							
						});

	                /****
	                ** Search function
	                ****/
		                $(document).on('click','#search_button',function() {
		                //init_contacts_table.destroy();

		                	if($('#search').val() == ""){
		                		$('#search_button').prop("disabled", false);
		                	}else{
			                	$('#search_button').text("Searching...");
			                	$('#search_button').prop("disabled", true);
		                	}

		                	// if contacts is checked
		                	if($('#search_contacts').is(":checked")){
								$.ajax({
								    url: "search.php",
								    type: 'POST',
								    data: {
								    	search_contacts : $('#search').val()
								    },
									success: function(data) {
										$('#search_button').text("Search");
		                				$('#search_button').prop("disabled", false)
										console.log(data);
										if(data != ""){
									        
											$('#table_contacts').html(data);
											$('#table_contacts').DataTable({
							                	"bDestroy" : true
							                });
										}else{
											init_contacts_table.fnClearTable();
										}
									}
								});
			            	}

			            	if($('#search_recordings').is(":checked")){
			            		var start_filterdate_val = $('#start_filterdate').val();
	                			var end_filterdate_val = $('#end_filterdate').val();
	                			var agent_filter_val = $('#agent_filter').val();

								$.ajax({
								    url: "search.php",
								    type: 'POST',
								    data: {
								    	search_recordings : $('#search').val(),
								    	start_filterdate : start_filterdate_val,
								    	end_filterdate : end_filterdate_val,
								    	agent_filter : agent_filter_val
								    },
									success: function(data) {
										$('#search_button').text("Search");
		                				$('#search_button').prop("disabled", false)
										console.log(data);
										if(data != ""){
											$('#table_callrecordings').html(data);
											$('#table_callrecordings').DataTable({
							                	"bDestroy" : true
							                });
										}else{
											init_callrecs_table.fnClearTable();
										}
									}
								});
			            	}
							
						});
				
				/*****
				*** Edit functions
				*****/
				$(document).on('click','.edit-contact',function() {
					var url = './editcontacts.php';
					var id = $(this).attr('data-id');
					//alert(extenid);
					var form = $('<form action="' + url + '" method="post"><input type="hidden" name="modifyid" value="'+id+'" /></form>');
					//$('body').append(form);  // This line is not necessary
					$(form).submit();
				});

				/*****
				** For playing Call Recordings
				*****/
				$('.play_audio').click(function(){
					var audioFile = $(this).attr('data-location');
					
					var sourceFile = '<audio class="audio_file" controls>';
					    sourceFile += '<source src="'+ audioFile +'" type="audio/mpeg" download="true"/>';
					    sourceFile += '</audio>';
					    
					$('.download-audio-file').attr('href', audioFile);
					$('.audio-player').html(sourceFile);
					$('#call-playback-modal').modal('show');
					
					var aud = $('.audio_file').get(0);
					aud.play();
				});
				
				$('#call-playback-modal').on('hidden.bs.modal', function () {
					var aud = $('.audio_file').get(0);
					aud.pause();
				});
				
			});
		</script>
    </body>
</html>