<?php /** @var $l OC_L10N */ ?>
<div id="controls">
	<div class="breadcrumb_trash">
		<div class="crumb svg" data-dir="/"><a href="#"><img class="svg" src="/core/img/places/home.svg" alt="Home"></a></div>
		<div class="crumb"><a href="#">Deleted files</a></div>
		<div class="crumb last"><a href="#" class="cb_cal_text cb_cal_button">Today</a></div>
		<div class="actions creatable datepicker_wrapper">
			<input type="text" id="cb_cal_input1" class="cb_cal_form"><input type="text" id="cb_cal_input2" class="cb_cal_form">
			<a href="#" class="button calendar cb_cal_button"><span class="icon icon-calendar-dark"></span><span class="hidden-visually">Calendar</span></a>
		</div>
	</div>
	<div id="file_action_panel"></div>
</div>
<div id='notification'></div>

<div id="emptycontent" class="hidden">
	<div class="icon-delete"></div>
	<h2><?php p($l->t('No deleted files')); ?></h2>
	<p><?php p($l->t('You will be able to recover deleted files from here')); ?></p>
</div>

<input type="hidden" name="dir" value="" id="dir">

<div class="nofilterresults hidden">
	<div class="icon-search"></div>
	<h2><?php p($l->t('No entries found in this folder')); ?></h2>
	<p></p>
</div>

<table id="filestable">
	<thead>
		<tr>
			<th id='headerName' class="hidden column-name">
				<div id="headerName-container">
					<a class="name sort columntitle" data-sort="name"><span><?php p($l->t( 'Name' )); ?></span><span class="sort-indicator"></span></a>
				</div>
			</th>
			<th id='headerName' class="hidden column-name">
				<div id="headerName-container">
					<a class="size sort columntitle" data-sort="size"><span><?php p($l->t( 'Size' )); ?></span><span class="sort-indicator"></span></a>
				</div>
			</th>
			<th id="headerDate" class="hidden column-mtime">
				<a id="modified" class="columntitle" data-sort="mtime"><span><?php p($l->t( 'Deleted' )); ?></span><span class="sort-indicator"></span></a>
				<span class="selectedActions">
					<a href="" class="">
						<span></span>
					</a>
				</span>
			</th>
		</tr>
	</thead>
	<tbody id="fileList">
	</tbody>
	<tfoot>
	</tfoot>
</table>
