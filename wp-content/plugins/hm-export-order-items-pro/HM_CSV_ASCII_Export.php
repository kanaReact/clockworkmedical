<?php
/**
 * Author: WP Zone
 * License: GNU General Public License version 3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 */
namespace HM_XOIWCP_Export;
	class CSV_ASCII {

		private $handle;
		
		public function __construct($handle) {
			$this->handle = $handle;
		}
		
		public function putRow($data, $header=false) {
			foreach ($data as $key => $value)
				$data[$key] = mb_convert_encoding($value, 'ISO-8859-1');
			fputcsv($this->handle, $data);
		}
		

	}


?>