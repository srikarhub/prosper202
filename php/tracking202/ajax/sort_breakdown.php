<?php include_once($_SERVER['DOCUMENT_ROOT'] . '/202-config/connect.php'); 

AUTH::require_user();


//set the timezone for the user, for entering their dates.
	AUTH::set_timezone($_SESSION['user_timezone']);

//show breakdown
	runBreakdown(true);

	
//show real or filtered clicks
	$mysql['user_id'] = $db->real_escape_string($_SESSION['user_id']);
	$user_sql = "SELECT user_pref_breakdown, user_pref_show, user_cpc_or_cpv FROM 202_users_pref WHERE user_id=".$mysql['user_id'];
	$user_result = _mysqli_query($user_sql, $dbGlobalLink); //($user_sql);
	$user_row = $user_result->fetch_assoc();	
	$breakdown = $user_row['user_pref_breakdown'];
	
	if ($user_row['user_pref_show'] == 'all') { $click_flitered = ''; }
	if ($user_row['user_pref_show'] == 'real') { $click_filtered = " AND click_filtered='0' "; }
	if ($user_row['user_pref_show'] == 'filtered') { $click_filtered = " AND click_filtered='1' "; }
	if ($user_row['user_pref_show'] == 'filtered_bot') { $click_filtered = " AND click_bot='1' "; }
	if ($user_row['user_pref_show'] == 'leads') { $click_filtered = " AND click_lead='1' "; } 
	
	if ($user_row['user_cpc_or_cpv'] == 'cpv')  $cpv = true;
	else 										$cpv = false; 
	
	
//run the order by settings	
	$html['order'] = htmlentities($_POST['order'], ENT_QUOTES, 'UTF-8');

	$html['sort_breakdown_order'] = 'breakdown asc'; 
	if ($_POST['order'] == 'breakdown asc') { 
		$html['sort_breakdown_order'] = 'breakdown desc';
		$mysql['order'] = 'ORDER BY sort_breakdown_from DESC'; 
	} elseif ($_POST['order'] == 'breakdown desc') { 
		$html['sort_breakdown_order'] = 'breakdown asc';
		$mysql['order'] = 'ORDER BY sort_breakdown_from ASC';       
	}

	$html['sort_breakdown_clicks_order'] = 'sort_breakdown_clicks asc'; 
	if ($_POST['order'] == 'sort_breakdown_clicks asc') { 
		$html['sort_breakdown_clicks_order'] = 'sort_breakdown_clicks desc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_clicks` DESC'; 
	} elseif ($_POST['order'] == 'sort_breakdown_clicks desc') { 
		$html['sort_breakdown_clicks_order'] = 'sort_breakdown_clicks asc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_clicks` ASC';       
	}

	$html['sort_breakdown_click_throughs_order'] = 'sort_breakdown_click_throughs asc';
	if ($_POST['order'] == 'sort_breakdown_click_throughs asc') {
		$html['sort_breakdown_click_throughs_order'] = 'sort_breakdown_click_throughs desc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_click_throughs` DESC';
	} elseif ($_POST['order'] == 'sort_breakdown_click_throughs desc') {
		$html['sort_breakdown_click_throughs_order'] = 'sort_breakdown_click_throughs asc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_click_throughs` ASC';
	}

	$html['sort_breakdown_ctr_order'] = 'sort_breakdown_ctr asc';
	if ($_POST['order'] == 'sort_breakdown_ctr asc') {
		$html['sort_breakdown_ctr_order'] = 'sort_breakdown_ctr desc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_ctr` DESC';
	} elseif ($_POST['order'] == 'sort_breakdown_ctr desc') {
		$html['sort_breakdown_ctr_order'] = 'sort_breakdown_ctr asc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_ctr` ASC';
	}

	$html['sort_breakdown_leads_order'] = 'sort_breakdown_leads asc'; 
	if ($_POST['order'] == 'sort_breakdown_leads asc') { 
		$html['sort_breakdown_leads_order'] = 'sort_breakdown_leads desc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_leads` DESC'; 
	} elseif ($_POST['order'] == 'sort_breakdown_leads desc') { 
		$html['sort_breakdown_leads_order'] = 'sort_breakdown_leads asc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_leads` ASC';       
	}

	$html['sort_breakdown_su_ratio_order'] = 'sort_breakdown_su_ratio asc'; 
	if ($_POST['order'] == 'sort_breakdown_su_ratio asc') { 
		$html['sort_breakdown_su_ratio_order'] = 'sort_breakdown_su_ratio desc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_su_ratio` DESC'; 
	} elseif ($_POST['order'] == 'sort_breakdown_su_ratio desc') { 
		$html['sort_breakdown_su_ratio_order'] = 'sort_breakdown_su_ratio asc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_su_ratio` ASC';       
	}

	$html['sort_breakdown_payout_order'] = 'sort_breakdown_payout asc'; 
	if ($_POST['order'] == 'sort_breakdown_payout asc') { 
		$html['sort_breakdown_payout_order'] = 'sort_breakdown_payout desc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_payout` DESC'; 
	} elseif ($_POST['order'] == 'sort_breakdown_payout desc') { 
		$html['sort_breakdown_payout_order'] = 'sort_breakdown_payout asc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_payout` ASC';       
	}

	$html['sort_breakdown_epc_order'] = 'sort_breakdown_epc asc'; 
	if ($_POST['order'] == 'sort_breakdown_epc asc') { 
		$html['sort_breakdown_epc_order'] = 'sort_breakdown_epc desc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_epc` DESC'; 
	} elseif ($_POST['order'] == 'sort_breakdown_epc desc') { 
		$html['sort_breakdown_epc_order'] = 'sort_breakdown_epc asc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_epc` ASC';       
	}

	$html['sort_breakdown_cpc_order'] = 'sort_breakdown_cpc asc'; 
	if ($_POST['order'] == 'sort_breakdown_cpc asc') { 
		$html['sort_breakdown_cpc_order'] = 'sort_breakdown_cpc desc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_cpc` DESC'; 
	} elseif ($_POST['order'] == 'sort_breakdown_cpc desc') { 
		$html['sort_breakdown_cpc_order'] = 'sort_breakdown_cpc asc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_cpc` ASC';       
	}

	$html['sort_breakdown_income_order'] = 'sort_breakdown_income asc'; 
	if ($_POST['order'] == 'sort_breakdown_income asc') { 
		$html['sort_breakdown_income_order'] = 'sort_breakdown_income desc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_income` DESC'; 
	} elseif ($_POST['order'] == 'sort_breakdown_income desc') { 
		$html['sort_breakdown_income_order'] = 'sort_breakdown_income asc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_income` ASC';       
	}

	$html['sort_breakdown_cost_order'] = 'sort_breakdown_cost asc'; 
	if ($_POST['order'] == 'sort_breakdown_cost asc') { 
		$html['sort_breakdown_cost_order'] = 'sort_breakdown_cost desc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_cost` DESC'; 
	} elseif ($_POST['order'] == 'sort_breakdown_cost desc') { 
		$html['sort_breakdown_cost_order'] = 'sort_breakdown_cost asc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_cost` ASC';       
	}

	$html['sort_breakdown_net_order'] = 'sort_breakdown_net asc'; 
	if ($_POST['order'] == 'sort_breakdown_net asc') { 
		$html['sort_breakdown_net_order'] = 'sort_breakdown_net desc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_net` DESC'; 
	} elseif ($_POST['order'] == 'sort_breakdown_net desc') { 
		$html['sort_breakdown_net_order'] = 'sort_breakdown_net asc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_net` ASC';       
	}

	$html['sort_breakdown_roi_order'] = 'sort_breakdown_roi asc'; 
	if ($_POST['order'] == 'sort_breakdown_roi asc') { 
		$html['sort_breakdown_roi_order'] = 'sort_breakdown_roi desc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_roi` DESC'; 
	} elseif ($_POST['order'] == 'sort_breakdown_roi desc') { 
		$html['sort_breakdown_roi_order'] = 'sort_breakdown_roi asc';
		$mysql['order'] = 'ORDER BY `sort_breakdown_roi` ASC';       
	}

	if (empty($mysql['order'])) { 
		$mysql['order'] = ' ORDER BY sort_breakdown_from ASC';   
	}	
	
//grab breakdown report	
	$breakdown_sql = "SELECT * FROM 202_sort_breakdowns WHERE user_id='".$mysql['user_id']."' " .  $mysql['order'];  
	$breakdown_result = $db->query($breakdown_sql) or record_mysql_error($breakdown_sql);  ?>

<div class="row">
	<div class="col-xs-12" style="margin-top: 10px;">
	<table class="table table-bordered table-hover" id="stats-table">
		<thead>
		    <tr style="background-color: #f2fbfa;">  
				<th colspan="2" style="text-align:left"><a class="onclick_color" onclick="loadContent('/tracking202/ajax/sort_breakdown.php','','<?php echo $html['sort_breakdown_order']; ?>');">Time</a></th>
				<th><a class="onclick_color" onclick="loadContent('/tracking202/ajax/sort_breakdown.php','','<?php echo $html['sort_breakdown_clicks_order']; ?>');">Clicks</a></th>
				<th><a class="onclick_color" onclick="loadContent('/tracking202/ajax/sort_breakdown.php','','<?php echo $html['sort_breakdown_click_throughs_order']; ?>');">Click Throughs</a></th>
				<th><a class="onclick_color" onclick="loadContent('/tracking202/ajax/sort_breakdown.php','','<?php echo $html['sort_breakdown_ctr_order']; ?>');">LP CTR</a></th> 
				<th><a class="onclick_color" onclick="loadContent('/tracking202/ajax/sort_breakdown.php','','<?php echo $html['sort_breakdown_leads_order']; ?>');">Leads</a></th>
				<th><a class="onclick_color" onclick="loadContent('/tracking202/ajax/sort_breakdown.php','','<?php echo $html['sort_breakdown_su_ratio_order']; ?>');">Avg S/U</a></th>
				<th><a class="onclick_color" onclick="loadContent('/tracking202/ajax/sort_breakdown.php','','<?php echo $html['sort_breakdown_payout_order']; ?>');">Avg Payout</a></th>
				<th><a class="onclick_color" onclick="loadContent('/tracking202/ajax/sort_breakdown.php','','<?php echo $html['sort_breakdown_epc_order']; ?>');">Avg EPC</a></th> 
				<th><a class="onclick_color" onclick="loadContent('/tracking202/ajax/sort_breakdown.php','','<?php echo $html['sort_breakdown_avg_cpc_order']; ?>');">Avg CPC</a></th>
				<th><a class="onclick_color" onclick="loadContent('/tracking202/ajax/sort_breakdown.php','','<?php echo $html['sort_breakdown_income_order']; ?>');">Income</a></th>
				<th><a class="onclick_color" onclick="loadContent('/tracking202/ajax/sort_breakdown.php','','<?php echo $html['sort_breakdown_cost_order']; ?>');">Cost</a></th>
				<th><a class="onclick_color" onclick="loadContent('/tracking202/ajax/sort_breakdown.php','','<?php echo $html['sort_breakdown_net_order']; ?>');">Net</a></th>
				<th><a class="onclick_color" onclick="loadContent('/tracking202/ajax/sort_breakdown.php','','<?php echo $html['sort_breakdown_roi_order']; ?>');">ROI</a></th>
			</tr>
		</thead>
		<tbody>

		<?php while ($breakdown_row = $breakdown_result->fetch_array(MYSQL_ASSOC)) { 
			
			//also harvest a total stats
			$stats_total['clicks'] = $stats_total['clicks'] + $breakdown_row['sort_breakdown_clicks'];
			$stats_total['click_throughs'] = $stats_total['click_throughs'] + $breakdown_row['sort_breakdown_click_throughs']; 
			$stats_total['leads'] = $stats_total['leads'] + $breakdown_row['sort_breakdown_leads']; 
			$stats_total['payout'] = $stats_total['payout'] + $breakdown_row['sort_breakdown_payout']; 
			$stats_total['income'] = $stats_total['income'] + $breakdown_row['sort_breakdown_income']; 
			$stats_total['cost'] = $stats_total['cost'] + $breakdown_row['sort_breakdown_cost']; 
			$stats_total['net'] = $stats_total['net'] + $breakdown_row['sort_breakdown_net']; 
			
			if ($breakdown == 'hour') {
				$html['sort_breakdown_time'] = date('M d, Y \a\t g:ia', $breakdown_row['sort_breakdown_from']);
			} elseif ($breakdown == 'day') { 
				$html['sort_breakdown_time'] = date('M d, Y', $breakdown_row['sort_breakdown_from']);      
			} elseif ($breakdown == 'month') { 
				$html['sort_breakdown_time'] = date('M Y', $breakdown_row['sort_breakdown_from']);      
			} elseif ($breakdown == 'year') { 
				$html['sort_breakdown_time'] = date('Y', $breakdown_row['sort_breakdown_from']);      
			}
			
			$html['sort_breakdown_clicks'] = htmlentities($breakdown_row['sort_breakdown_clicks'], ENT_QUOTES, 'UTF-8');
			$html['sort_breakdown_click_throughs'] = htmlentities($breakdown_row['sort_breakdown_click_throughs'], ENT_QUOTES, 'UTF-8');
			$html['sort_breakdown_ctr'] = htmlentities($breakdown_row['sort_breakdown_ctr'].'%', ENT_QUOTES, 'UTF-8');
			$html['sort_breakdown_leads'] = htmlentities($breakdown_row['sort_breakdown_leads'], ENT_QUOTES, 'UTF-8');
			$html['sort_breakdown_su_ratio'] = htmlentities($breakdown_row['sort_breakdown_su_ratio'].'%', ENT_QUOTES, 'UTF-8');
			$html['sort_breakdown_payout'] = htmlentities(dollar_format($breakdown_row['sort_breakdown_payout']), ENT_QUOTES, 'UTF-8');
			$html['sort_breakdown_epc'] = htmlentities(dollar_format($breakdown_row['sort_breakdown_epc']), ENT_QUOTES, 'UTF-8');
			$html['sort_breakdown_avg_cpc'] = htmlentities(dollar_format($breakdown_row['sort_breakdown_avg_cpc'], $cpv), ENT_QUOTES, 'UTF-8');
			$html['sort_breakdown_income'] = htmlentities(dollar_format($breakdown_row['sort_breakdown_income']), ENT_QUOTES, 'UTF-8');
			$html['sort_breakdown_cost'] = htmlentities(dollar_format($breakdown_row['sort_breakdown_cost'], $cpv), ENT_QUOTES, 'UTF-8');
			$html['sort_breakdown_net'] = htmlentities(dollar_format($breakdown_row['sort_breakdown_net'], $cpv), ENT_QUOTES, 'UTF-8'); 
			$html['sort_breakdown_roi'] = htmlentities($breakdown_row['sort_breakdown_roi'].'%', ENT_QUOTES, 'UTF-8'); ?>
		
			<tr>
				<td colspan="2" style="text-align:left; padding-left:10px;"><?php echo $html['sort_breakdown_time']; ?></td>
				<td><?php echo number_format($html['sort_breakdown_clicks']); ?></td>
				<td><?php echo number_format($html['sort_breakdown_click_throughs']); ?></td>
				<td><?php echo $html['sort_breakdown_ctr']; ?></td>
				<td><?php echo $html['sort_breakdown_leads']; ?></td> 
				<td><?php echo $html['sort_breakdown_su_ratio']; ?></td>
				<td><?php echo $html['sort_breakdown_payout']; ?></td> 
				<td><?php echo $html['sort_breakdown_epc']; ?></td>
				<td><?php echo $html['sort_breakdown_avg_cpc']; ?></td>
				<td><?php echo $html['sort_breakdown_income']; ?></td>
				<td>(<?php echo $html['sort_breakdown_cost']; ?>)</td>
				<td><span class="label label-<?php if ($breakdown_row['sort_breakdown_net'] > 0) { echo 'primary'; } elseif ($breakdown_row['sort_breakdown_net'] < 0) { echo 'important'; } else { echo 'default'; } ?>"><?php echo $html['sort_breakdown_net'] ; ?></span></td>
				<td><span class="label label-<?php if ($breakdown_row['sort_breakdown_net'] > 0) { echo 'primary'; } elseif ($breakdown_row['sort_breakdown_net'] < 0) { echo 'important'; } else { echo 'default'; } ?>"><?php echo $html['sort_breakdown_roi'] ; ?></span></td>
			</tr>
		<?php } error_reporting(0); ?>
		
		<?php  $rows = $breakdown_result->num_rows;
			$html['clicks'] = htmlentities(number_format($stats_total['clicks']), ENT_QUOTES, 'UTF-8');
			$html['click_throughs'] = htmlentities(number_format($stats_total['click_throughs']), ENT_QUOTES, 'UTF-8');
			$html['ctr'] = htmlentities(round($stats_total['click_throughs'] / $stats_total['clicks'] * 100, 2) . '%', ENT_QUOTES, 'UTF-8');  
			$html['leads'] = htmlentities($stats_total['leads'], ENT_QUOTES, 'UTF-8');  
			$html['su_ratio'] = htmlentities(round($stats_total['leads']/$stats_total['clicks']*100,2) . '%', ENT_QUOTES, 'UTF-8');     
			$html['payout'] =  htmlentities(dollar_format(($stats_total['payout']/$stats_total['leads'])), ENT_QUOTES, 'UTF-8');   
			$html['epc'] =  htmlentities(dollar_format(($stats_total['income']/$stats_total['clicks'])), ENT_QUOTES, 'UTF-8');
			$html['cpc'] =  htmlentities(dollar_format(($stats_total['cost']/$stats_total['clicks']), $cpv), ENT_QUOTES, 'UTF-8');
			$html['income'] =  htmlentities(dollar_format(($stats_total['income']), $cpv), ENT_QUOTES, 'UTF-8');
			$html['cost'] =  htmlentities(dollar_format(($stats_total['cost']), $cpv), ENT_QUOTES, 'UTF-8'); 
			$html['net'] = htmlentities(dollar_format( ($stats_total['income']-$stats_total['cost']), $cpv), ENT_QUOTES, 'UTF-8');
			$html['roi'] = htmlentities(round((($stats_total['income']-$stats_total['cost'])/$stats_total['cost']*100),2) . '%', ENT_QUOTES, 'UTF-8'); 
			
			error_reporting(6135); ?> 
			
		<tr style="background-color: #F8F8F8;" id="totals">
			<td colspan="2" style="text-align:left; padding-left:10px"><strong>Totals for report</strong></td>
			<td><strong><?php echo $html['clicks']; ?></strong></td>
			<td><strong><?php echo $html['click_throughs']; ?></strong></td>
			<td><strong><?php echo $html['ctr']; ?></strong></td>
			<td><strong><?php echo $html['leads']; ?></strong></td>
			<td><strong><?php echo $html['su_ratio']; ?></strong></td>  
			<td><strong><?php echo $html['payout']; ?></strong></td>   
			<td><strong><?php echo $html['epc']; ?></strong></td>  
			<td><strong><?php echo $html['cpc']; ?></strong></td>  
			<td><strong><?php echo $html['income']; ?></strong></td>
			<td><strong>(<?php echo $html['cost']; ?>)</strong></td>
			<td><span class="label label-<?php if ($stats_total['net'] > 0) { echo 'primary'; } elseif ($stats_total['net'] < 0) { echo 'important'; } else { echo 'default'; } ?>"><?php echo $html['net']; ?></span></td>
			<td><span class="label label-<?php if ($stats_total['net'] > 0) { echo 'primary'; } elseif ($stats_total['net'] < 0) { echo 'important'; } else { echo 'default'; } ?>"><?php echo $html['roi']; ?></span></td>
		</tr>
		</tbody>
	</table>
	</div>
</div>
	<?php

	if (user_cache_time($mysql['user_id']) == 0) {
		$cache_time = false;
	} else {
		$cache_time = user_cache_time($mysql['user_id'])/60;
	}

	if ($cache_time == 1) {
		$time = "minute";
	} elseif ($cache_time == 60) {
		$time = "hour";
	} else {
		$time = $cache_time. " minutes";
	} 

	?>
	<?php if (!$cache_time) { ?>
		<center style="margin-top:10px"><span style="font-size:10px">Stats are updated instant</span></center>
	<?php } else {?>
		<center style="margin-top:10px"><span style="font-size:10px">Stats are updated every <?php echo $time; ?></span></center>
	<?php } ?>

