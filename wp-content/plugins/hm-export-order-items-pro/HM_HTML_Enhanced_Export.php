<?php
/**
 * Author: WP Zone
 * License: GNU General Public License version 3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 */
namespace HM_XOIWCP_Export;
	class HTMLEnhanced {

		private $handle, $hasTHead=false, $inTBody=false;
		
		public function __construct($handle) {
			$this->handle = $handle;
			@header('Content-Type: text/html; charset=utf-8');
			fwrite($this->handle, '
				<html>
					<head>
						<style type="text/css">
							body {
								font-family: sans-serif;
							}
							th, td {
								text-align: left;
								padding: 5px 10px;
							}
						</style>
						<link rel="stylesheet" type="text/css" href="'.esc_url(plugins_url('js/datatables/datatables.min.css', __FILE__)).'" />
						<script type="text/javascript" src="'.esc_url(plugins_url('js/datatables/datatables.min.js', __FILE__)).'"></script>
						<script>$(document).ready(function() { $(\'table\').DataTable({pageLength:25,order:[],colReorder:true,fixedHeader:true,responsive:true,select:true}); });</script>
					</head>
					<body>
						<table>
			');
		}
		
		public function __destruct() {
			if ($this->inTBody)
				fwrite($this->handle, '</tbody>');
			fwrite($this->handle, '</table></body></html>');
		}
		
		public function putRow($data, $header=false, $footer=false) {
			if ($header && !$this->hasTHead) {
				fwrite($this->handle, '<thead>');
			} else if ($footer) {
				if ($this->inTBody) {
					fwrite($this->handle, '</tbody>');
					$this->inTBody = false;
				}
				fwrite($this->handle, '<tfoot>');
			} else {
				if (!$this->hasTHead) {
					$columnNames = array();
					for ($i = 1; $i <= count($data); ++$i)
						$columnNames[] = 'Column '.$i;
					$this->putRow($columnNames, true);
				}
				if (!$this->inTBody) {
					fwrite($this->handle, '<tbody>');
					$this->inTBody = true;
				}
			}
			
			fwrite($this->handle, '<tr>');
			foreach ($data as $field) {
				fwrite($this->handle, ($header ? '<th>' : '<td>').esc_html($field).($header ? '</th>' : '</td>'));
			}
			fwrite($this->handle, '</tr>');
			
			if ($header && !$this->hasTHead) {
				fwrite($this->handle, '</thead>');
				$this->hasTHead = true;
			} else if ($footer) {
				fwrite($this->handle, '</tfoot>');
			}
		}
		

	}


?>