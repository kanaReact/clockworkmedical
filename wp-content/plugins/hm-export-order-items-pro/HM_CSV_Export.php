<?php
/**
 * Author: WP Zone
 * License: GNU General Public License version 3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 */
namespace HM_XOIWCP_Export;
	class CSV {

		private $handle, $delimiter, $surround, $escapeSearch, $escapeReplace, $isFirstRow = true;
		
		public function __construct($handle, $options=array()) {
			$this->handle = $handle;
			$this->delimiter = (isset($options['delimiter']) ? $options['delimiter'] : ',');
			$this->surround = (isset($options['surround']) ? $options['surround'] : '"');
			if (!empty($this->surround) && (!isset($options['escape']) || !empty($options['escape']))) {
				$escape = (isset($options['escape']) ? $options['escape'] : '\\');
				$this->escapeSearch = array($escape, $this->surround);
				$this->escapeReplace = array($escape.$escape, $escape.$this->surround);
			}
		}
		
		public function putTitle($title) {
			$this->putRow(array($title));
		}
		
		public function putRow($data, $header=false, $footer=false) {
			if ($this->isFirstRow) {
				include_once(__DIR__.'/lib/League.Csv/ByteSequence.php');
				fwrite($this->handle, \League\Csv\ByteSequence::BOM_UTF8);
				$this->isFirstRow = false;
			}
			$row = '';
			foreach ($data as $field) {
				$row .= (empty($row) ? '' : $this->delimiter).$this->surround.(empty($this->escapeSearch) ? $field : str_replace($this->escapeSearch, $this->escapeReplace, $field)).$this->surround;
			}
			fwrite($this->handle, $row."\n");
		}
		
		public function close() { }
	}
?>