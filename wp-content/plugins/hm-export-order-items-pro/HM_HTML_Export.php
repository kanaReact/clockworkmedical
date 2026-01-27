<?php
/**
 * Author: WP Zone
 * License: GNU General Public License version 3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 */
namespace HM_XOIWCP_Export;
	class HTML {

		private $handle;
		
		public function __construct($handle) {
			$this->handle = $handle;
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
					</head>
					<body>
						<table>
			');
		}
		
		public function __destruct() {
			fwrite($this->handle, '</table></body></html>');
		}
		
		public function putRow($data, $header=false) {
			fwrite($this->handle, '<tr>');
			foreach ($data as $field) {
				fwrite($this->handle, ($header ? '<th>' : '<td>').esc_html($field).($header ? '</th>' : '</td>'));
			}
			fwrite($this->handle, '</tr>');
		}
		

	}

?>