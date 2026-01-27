<?php
/**
 * Author: WP Zone
 * License: GNU General Public License version 3 or later
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 */
namespace HM_XOIWCP_Export;
	class XLS {
		
		const SHEET_NAME = 'Sheet1', FONT = 'Calibri', TITLE_FONT_SIZE = 14, BODY_FONT_SIZE = 11;
		private $tempDir, $writer, $title, $headerRow, $dataRows = [], $columnWidths = [];
		
		public function __construct() {
			ini_set('display_errors', 0);
			
			if ( !class_exists('\AGS_XOIWCP_XLSXWriter') ) {
				include_once(dirname(__FILE__).'/lib/PHP_XLSXWriter/xlsxwriter.class.php');
			}
			
			$this->tempDir = ags_xoiwcp_get_temp_dir();
			
			$this->writer = new \AGS_XOIWCP_XLSXWriter();
			$this->writer->setTempDir( $this->tempDir );
		}
		
		public function putTitle($title) {
			$this->title = $title;
		}
		
		public function putRow($data, $header=false, $footer=false) {
			$data = array_values($data);
			if ($header) {
				$this->headerRow = $data;
			} else {
				$this->dataRows[] = $data;
			}
			array_walk($data, [$this, 'updateColumnWidth']);
		}
		
		public function outputXLSX($path) {
			
			$this->writer->writeSheetHeader(
				self::SHEET_NAME,
				array_fill( 0, count($this->columnWidths), 'GENERAL' ),
				$this->columnWidths
				? [
					'widths' => $this->columnWidths,
					'suppress_row' => true
				]
				: [
					'suppress_row' => true
				]
			);
			
			if ($this->title) {
				$this->writer->writeSheetRow(
					self::SHEET_NAME,
					[
						$this->title
					],
					[
						'height' => self::TITLE_FONT_SIZE * 1.3,
						[ 'valign' => 'top', 'font' => self::FONT, 'font-size' => self::TITLE_FONT_SIZE, 'font-style' => 'bold' ]
					]
				);
			}
			
			if ( $this->headerRow ) {
				$this->writer->writeSheetRow(
					self::SHEET_NAME,
					$this->headerRow,
					array_fill( 0, count($this->headerRow), [ 'valign' => 'top', 'font' => self::FONT, 'font-size' => self::BODY_FONT_SIZE, 'font-style' => 'bold' ] )
				);
			}
			
			foreach ( $this->dataRows as $row ) {
				$this->writer->writeSheetRow(
					self::SHEET_NAME,
					$row,
					array_fill( 0, count($this->headerRow), [ 'valign' => 'top', 'font' => self::FONT, 'font-size' => self::BODY_FONT_SIZE ] )
				);
			}
			
			if ($path === 'php://output') {
				$this->writer->writeToStdOut();
			} else {
				$this->writer->writeToFile($path);
			}
			
			unset($this->writer);
			
			// Just in case
			foreach ( scandir($this->tempDir) as $dirItem ) {
				$dirItem = $this->tempDir . DIRECTORY_SEPARATOR . $dirItem;
				if ( is_file( $dirItem ) ) {
					@unlink( $dirItem );
				}
			}
			
			rmdir($this->tempDir);
			
		}
		
		
		/*
		The updateColumnWidth function contains code under the following license:
		
		MIT License

		Copyright (c) 2019 PhpSpreadsheet Authors

		Permission is hereby granted, free of charge, to any person obtaining a copy
		of this software and associated documentation files (the "Software"), to deal
		in the Software without restriction, including without limitation the rights
		to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
		copies of the Software, and to permit persons to whom the Software is
		furnished to do so, subject to the following conditions:

		The above copyright notice and this permission notice shall be included in all
		copies or substantial portions of the Software.

		THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
		IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
		FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
		AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
		LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
		OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
		SOFTWARE.
		*/
		private function updateColumnWidth($columnText, $columnIndex) {
			// https://github.com/PHPOffice/PhpSpreadsheet/blob/master/src/PhpSpreadsheet/Shared/Font.php
			
			$fontName = self::FONT;
			$fontSize = self::BODY_FONT_SIZE;
			$basisLength = mb_strlen( is_numeric($columnText) ? round($columnText, 2) : $columnText, 'UTF-8') + 1;
			
			// Calculate column width in pixels. We assume fixed glyph width. Result varies with font name and size.
			switch ($fontName) {
				case 'Calibri':
					// value 8.26 was found via interpolation by inspecting real Excel files with Calibri 11 font.
					$columnWidth = (int) (8.26 * $basisLength);
					$columnWidth = $columnWidth * $fontSize / 11; // extrapolate from font size

					break;
				case 'Arial':
					// value 8 was set because of experience in different exports at Arial 10 font.
					$columnWidth = (int) (8 * $basisLength);
					$columnWidth = $columnWidth * $fontSize / 10; // extrapolate from font size

					break;
				case 'Verdana':
					// value 8 was found via interpolation by inspecting real Excel files with Verdana 10 font.
					$columnWidth = (int) (8 * $basisLength);
					$columnWidth = $columnWidth * $fontSize / 10; // extrapolate from font size

					break;
				default:
					// just assume Calibri
					$columnWidth = (int) (8.26 * $basisLength);
					$columnWidth = $columnWidth * $fontSize / 11; // extrapolate from font size

					break;
			}
			
			// https://github.com/PHPOffice/PhpSpreadsheet/blob/master/src/PhpSpreadsheet/Shared/Drawing.php
			// https://github.com/PHPOffice/PhpSpreadsheet/blob/master/src/PhpSpreadsheet/Shared/Font.php
			$columnWidth = ((int) $columnWidth) * 9.14062500 / 64;
			
			if ( !isset($this->columnWidths[$columnIndex]) || $this->columnWidths[$columnIndex] < $columnWidth ) {
				$this->columnWidths[$columnIndex] = $columnWidth;
			}
			
		}
		
	}

?>