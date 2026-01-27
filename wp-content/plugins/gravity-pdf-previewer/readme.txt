=== Gravity PDF Previewer ===

== Frequently Asked Questions ==

= How do I receive support? =

User's with a valid, active license key can receive support for this plugin by filling out the form at [GravityPDF.com](https://gravitypdf.com/support/).

== Changelog ==

= Version 4.1.0 =
* Feature: Display PDF Previewer field example in the Form Editor and Block Editor
* Feature: Add support for the new AJAX submission process in Gravity Forms 2.9+
* Feature: Improve activation error messages when Gravity PDF isn't installed or the correct version
* Housekeeping: Only disable the PDF password when the 'download' feature is enabled on a PDF Previewer field
* Housekeeping: Improve PDF viewer performance
* Housekeeping: Upgrade PDF.js to v4.6.82
* Bug: Ignore browser preference when PDF Previewer field theme setting is not set to "auto"
* Bug: Page number toolbar text can be translated
* Bug: Fix PHP notice if RTL PDF setting is not defined

= Version 4.0.0 =
* Housekeeping: Gravity PDF 6.0 or higher is now required for this extension
* Housekeeping: Improve PDF viewer performance
* Housekeeping: Upgrade PDF.js to v4.3.136
* Housekeeping: Add `gfpdf_previewer_auto_refresh_delay` JS filter to alter the PDF auto-refresh interval
* Housekeeping: Use fieldset/legend HTML for Previewer field when form does not have legacy markup enabled
* Housekeeping: Lazy-load previewer CSS styles
* Bug: Only load localized script data once per request
* Bug: Fix PDF Preview display issues when active form fields are included in a template
* Bug: Show grabbing pointer icon when using the gab to pan feature
* Bug: Display Post Image and Image Hopper Post Image field image/meta data in PDF Preview
* Bug: Prevent broken image displaying in PDF Preview when Post Image and Image Hopper Post Image have no file uploaded
* Bug: Fix duplicate or deleted Image Hopper display issues in PDF Preview
* Bug: Fix PDF Preview display issue when using Gravity Wiz Page Transition perk + soft validation
* Security: Prevent arbitrary Javascript execution vulnerability if a malicious PDF was loaded into PDF.js

= Version 3.3.0 =
* Feature: Added setting to PDF Previewer field to disable auto-refresh
* Housekeeping: Stop PDF Previewer field being processed by Gravity PDF Core when generating documents
* Housekeeping: Security Hardening
* Bug: Fix PHP error when processing File Upload fields in later version of PHP
* Bug: Fix minor display problem with Previewer field security settings in the Form Editor

= Version 3.2.0 =
* Feature: Add Visibility setting support to Previewer field (Visible/Hidden/Administrative)
* Bug: Fix Previewer field missing from Gravity Flow Display Field setting in User Input step
* Bug: Fix broken image when editing an entry and previewing Image Hopper field
* Bug: Fix missing files when editing an entry with Gravity Wiz Entry Blocks
* Bug: Fix missing files when editing an entry with Gravity Wiz Nested Forms
* Housekeeping: Upgrade Javascript packages

= Version 3.1.4 =
* Bug: Resolve conditional logic display issue when using the Previewer with GravityView
* Bug: Resolve conditional logic display issue when using the Previewer with Gravity Flow

= Version 3.1.3 =
* Bug: Fix data merging regression introduced in 3.1.1 when using Previewer on GravityView Edit Entry page

= Version 3.1.2 =
* Bug: Fix PHP notice when Single File Upload field doesn't have an uploaded file

= Version 3.1.1 =
* Bug: Fix missing field problem when using Previewer on GravityView Edit Entry page

= Version 3.1.0 =
* Feature: Add Gravity Forms 2.7 Orbital Themes Compatibility
* Housekeeping: Re-add support for rendering PDF Previewers in legacy browsers
* Dev: Add Javascript Filter Hooks: `gfpdf_previewer_skip_auto_refresh`, `gfpdf_previewer_field_settings`, `gfpdf_previewer_page_viewer_options`, and `gfpdf_previewer_current_form_data`
* Bug: Improve the click zone of inline PDF links

= Version 3.0.1 =
* Bug: Only initialize the Previewer when it is visible in the viewport
* Bug: Fix PHP5.6 fatal error in the Form Editor
* Bug: Fix z-index toolbar display problems when using Previewer with Gravity Wiz Nested Forms
* Bug: Fix problem correctly displaying PDF generation error screen
* Bug: Fix PDF worker caching issue when upgrading the plugin
* Bug: Fix conflict with Light Blue API add-on for Gravity Forms
* Bug: Restore Previewer v1 anchor / link support, but only when Text Copy Protection is disabled
* Bug: Exclude file inputs in form data when generating PDF (quicker API calls and fixes Single File Upload display issues)
* Bug: Add missing strings to existing French, Spanish, and German translation files
* Housekeeping: Add AI translations for Chinese, Dutch, Portuguese, and Russian
* Housekeeping: Reorganize the .sass / .css styles (provides better theme compatibility)
* Housekeeping: Fix PHP8.1 deprecation warnings

= Version 3.0.0 =
* Breaking Change (developer): Prefix all CSS variables to prevent conflicts with other plugins or themes

* Feature: Add mouse wheel zoom functionality while holding CTRL or Command keys
* Feature: Add grab-scroll support when holding spacebar whilst text protection is deactivated
* Feature: Improve UX for small screen devices
* Feature: Improve zooming support
* Feature: Improve PDF page rendering performance
* Feature: Improve page number display for the various view types
* Feature: Improved touch device support
* Feature: Improve compatibility with the Gravity PDF Watermark add-on

* Security: Late escape all HTML output

* Bug: Fix display issues with Multi Select fields
* Bug: Fix manual refresh issue when zoomed in 400%+
* Bug: Fix scrolling issue on iOS devices
* Bug: Fix white border issue around full-color pages
* Bug: Add better viewport detection to only render the Previewer when required
* Bug: Fix page-width scaling bug when the Previewer is set to Horizontal
* Bug: Resolve jQuery 3.0 deprecation notice
* Bug: Resolve JavaScript error when using AJAX-powered Gravity Form

= Version 2.0.3, August 25, 2022 =
* Bug: Fix .pot file build tool so the plugin can be translated correctly

= Version 2.0.2, August 25, 2022 =
* Bug: Fix browser caching issue that caused version mismatch error with the PDF Worker
* Bug: Fix duplicate auto-refresh of PDF Preview
* Bug: Display PDF preview when using plain permalinks
* Bug: Better theme compatibility for Previewer
* Housekeeping: Add Update URI header to prevent WordPress.org causing accidental plugin override due to conflicting plugin slug

= Version 2.0.1, April 19, 2022 =
* Bug: Fix RTL display issue with legacy and Tier 2 PDF templates
* Bug: Fix page scaling issue with for HiDPI screens
* Bug: Track change and blur events in the form for better PDF auto-refreshing
* Bug: Track signature add-on events for better PDF auto-refreshing
* Bug: Fix PDF viewer width/height calculations when form is hidden on page load
* Bug: Resolve memory leak when refreshing PDF viewer

= Version 2.0.0, April 4, 2022 =
* Feature: New front-end UI with light and dark modes
* Feature: New Horizontal display mode
* Feature: Odd and Even Spread display mode
* Feature: Default Zoom Level control setting
* Feature: Optional right-click protection (enabled by default to prevent saving PDF pages as images)
* Feature: Optional text-copying protection (enabled by default)
* Feature: Faster PDF refresh rate on page load or form change
* Feature: Filename of downloaded PDF now matches PDF Filename setting
* Feature: Faster and more accurate PDF loading and rendering
* Feature: Scroll by dragging/touching the pages
* Feature: Support for Gravity Forms Repeater fields in PDF preview
* Housekeeping: Upgrade PDF.js to 2.12
* Housekeeping: Reduce PDF.js console.log verbosity
* Bug: Assign the correct user to the temporary entry created when generating a PDF
* Bug: Fix column display issue in Colossus
* Bug: Fixes Microsoft Edge false-positive when rendering PDF (affected a small number of users)
* Bug: Fix PHP Mod_Security false-positive when rendering PDF (affected a small number of users)
* Bug: Better error handling when PDF cannot be loaded
* Bug: Fix PDF download issue when permalinks are disabled

= Version 1.2.12, March 4, 2022 =
* Bug: Fix Image Hopper display issues when using the WooCommerce Gravity Forms add-on

= Version 1.2.11, May 25, 2021 =
* Bug: Fix Gravity Flow User Input step display problems with the Previewer
* Bug: Fix edge-case bug with GP Read Only plugin
* Bug: Fix invalid link to PDF list page running Gravity PDF 6+

= Version 1.2.10, April 20, 2021 =
* Feature: Add field icon support in the Form Editor for Gravity Forms 2.5
* Bug: Fix PHP Notice about an undefined array value

= Version 1.2.9, February 15, 2021 =
* Bug: Adjust relative PDF URL to account for Multisite subdirectory usage

= Version 1.2.8, January 28, 2020 =
* Bug: Fix string-to-number type conversion bug that can cause random entry data loss under specific conditions.
* Bug: Use relative URL when loading PDF previews to reduce the chance Windows Defender in Edge throws a security warning

= Version 1.2.7, August 10, 2020 =
* Bug: Fix PHP notice in WordPress 5.5

= Version 1.2.6, September 18, 2019 =
* Bug: Fix the file upload display in Core / Universal templates so the filenames are correctly displayed in the Previewer
* Bug: Allow the GPDFAPI::get_form_data() API call to function correctly in Previewer-generated templates
* Bug: Fix display issue with Gravity Perk's Nested Forms 1.0 plugin release

= Version 1.2.5.1, April 16, 2019 =
* Bug: Fix upgrade notice that won't disappear even when running the latest version

= Version 1.2.5, April 15, 2019 =
* Bug: Fix display issue when browser downloaded PDF in chunks

= Version 1.2.4, February 1, 2019 =

* Bug: Fix regression in 1.2.3 that prevented the auto-refresh feature working on AJAX forms

= Version 1.2.3, December 17, 2018 =

* Bug: Fix Preview reload issue when submitting AJAX forms on a Mac [GH#45]
* Bug: Fix PHP notice when $form variable isn't the expected object [GH#47]

= Version 1.2.2, November 21, 2018 =

* Bug: Fix Plugin Update Nag when already on latest version

= Version 1.2.1, November 20, 2018 =

* Bug: Ensure Rich Text Field content displayed correctly in Previewer

= Version 1.2.0, October 8, 2018 =

* Feature: Add support for the WooCommerce Gravity Forms Product add-on
* Bug: Ensure the Preview is automatically loaded when there's no scroll bar on the page

= Version 1.1.1, June 29, 2018 =

* Bug: Prevent PHP error when developers tap into the `gform_pre_render` filter
* Bug: Disable PDF security preventing copying / printing of the PDF when the PDF Previewer download feature is enabled

= Version 1.1.0, February 14, 2018 =

* Feature: Add Gravity Flow v2.0+ User Input Step support.
* Feature: Add setting to allow end-user to download generated PDF (defaults to off)
* Feature: Add full support for uploaded files in GravityView
* Feature: Define `DOING_PDF_PREVIEWER` PHP constant when generating PDFs for Previewer.
* Bug: Prevent Previewer showing up in Core / Universal templates when `Show Empty Fields` option enabled.

= Version 1.0.2, November 9, 2017 =

* Bug: Fix problem where the PDF watermark and custom height settings were ignored for new Previewer fields

= Version 1.0.1, October 30, 2017 =

* Feature: Trigger `gform_pre_submission` action before temporary entry is created to allow raw $_POST data to be modified
* Bug: Prevent any miscellaneous output when generating the preview PDF
* Bug: Clear temporary entry meta data to prevent product information being cached
* Bug: Add `!important` statements to our loading spinner CSS to prevent display issues caused by themes
* Bug: Mark our Previewer field as `read only` in Gravity Forms to prevent is showing up in conditional logic, merge tags or the entry details page

= Version 1.0, August 17, 2017 =

* Feature: Add French, Spanish and German translations
* Bug: Fix double-encoding issue in the Preview PDF field strings
* Bug: Adjust pre-loading checks so they correctly display when there\'s a problem

= Version 0.2, August 11, 2017 =

* Bug: Default to first PDF for the preview if none selected
* Bug: Fix Watermark double toggle problem
* Feature: Add more robust logging support
* Dev: Upgrade bootstrap to utilise Gravity PDF 4.3 Add-on Code
* Dev: Remove unnecessary files from the plugin

= Version 0.1, August 1, 2017 =

* Initial Release
