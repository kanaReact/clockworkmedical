(function ($) {

  function setupWatermarkToggle (field) {
    $('#pdf-watermark-setting').prop('checked', field['pdfwatermarktoggle'] == true)

    if (field['pdfwatermarktoggle'] != true) {
      $('#pdf_watermark_container').hide()
    } else {
      $('#pdf_watermark_container').show()
    }

    $('#pdf-watermark-setting').unbind('click').click(function () {
      $('#pdf_watermark_container').slideToggle()
    })
  }

  function setupWatermarkText (field) {
    $('#pdf_watermark_text').val(field['pdfwatermarktext'])
  }

  function setupPdfSelector (field) {
    if ($('#pdf_selector option[value=\'' + field['pdfpreview'] + '\']').length > 0) {
      $('#pdf_selector').val(field['pdfpreview'])
    }

    $('#pdf_selector').trigger('change')
  }

  function setupThemeSelector (field) {
    if ($('#pdf_theme option[value=\'' + field['pdftheme'] + '\']').length > 0) {
      $('#pdf_theme').val(field['pdftheme'])
    } else {
      $('#pdf_theme').val('dark')
    }

    $('#pdf_theme').trigger('change')
  }

  function setupPageScrollingSelector (field) {
    if ($('#pdf_page_scrolling option[value=\'' + field['pdfpagescrolling'] + '\']').length > 0) {
      $('#pdf_page_scrolling').val(field['pdfpagescrolling'])
    } else {
      $('#pdf_page_scrolling').prop('selectedIndex', 0)
    }

    $('#pdf_page_scrolling').trigger('change')
  }

  function setupSpreadSelector (field) {
    if (field['pdfpagescrolling'] === 'vertical') {
      $('.pdf_spread_setting').show()
    } else {
      $('.pdf_spread_setting').hide()
    }

    if ($('#pdf_spread option[value=\'' + field['pdfspread'] + '\']').length > 0) {
      $('#pdf_spread').val(field['pdfspread'])
    } else {
      $('#pdf_spread').prop('selectedIndex', 0)
    }

    $('#pdf_spread').trigger('change')
  }

  function setupZoomLevelSelector (field) {
    if ($('#pdf_zoom_level option[value=\'' + field['pdfzoomlevel'] + '\']').length > 0) {
      $('#pdf_zoom_level').val(field['pdfzoomlevel'])
    } else {
      $('#pdf_zoom_level').prop('selectedIndex', 0)
    }

    $('#pdf_zoom_level').trigger('change')
  }

  function setupPreviewHeight (field) {
    $('#pdf_preview_height').val(field['pdfpreviewheight'])
  }

  function setupWatermarkFont (field) {
    if ($('#pdf_watermark_font option[value=\'' + field['pdfwatermarkfont'] + '\']').length > 0) {
      $('#pdf_watermark_font').val(field['pdfwatermarkfont'])
    }

    $('#pdf_watermark_font').trigger('change')
  }

  function setupDownload (field) {
    $('#pdf-download-setting').prop('checked', field['pdfdownload'] == true)
  }

  function setupSecurity (field) {
    $('#pdf-right-click-protection-setting').prop('checked', field['pdfrightclickprotection'] == true)
    $('#pdf-text-copying-protection-setting').prop('checked', field['pdftextcopyingprotection'] == true)
  }

  function setupAutomaticRefresh (field) {
    $('#pdf-automatic-refresh-setting').prop('checked', field['pdfautomaticrefresh'] == true)
  }

  $(document).bind('gform_load_field_settings', function (event, field) {
    if (field.type === 'pdfpreview') {
      setupPdfSelector(field)
      setupThemeSelector(field)
      setupPageScrollingSelector(field)
      setupSpreadSelector(field)
      setupZoomLevelSelector(field)
      setupPreviewHeight(field)
      setupWatermarkToggle(field)
      setupWatermarkText(field)
      setupWatermarkFont(field)
      setupDownload(field)
      setupSecurity(field)
      setupAutomaticRefresh(field)
    }
  })
})(jQuery)

function SetPdfFieldProperty (id, value) {
  var fieldId = field.id
  var previewElement = document.getElementById('gpdf_input_' + fieldId)

  SetFieldProperty(id, value)

  switch (id) {
    case 'pdfpagescrolling':
      if (value === 'vertical') {
        jQuery('.pdf_spread_setting').show()
      } else {
        jQuery('.pdf_spread_setting').hide()
      }
      break

    case 'pdftheme':
      if (value === 'light') {
        previewElement.classList.remove('dark-mode')
      }

      if (value === 'dark') {
        previewElement.classList.add('dark-mode')
      }

      if (value === 'auto') {
        var browsersPreferDark = window.matchMedia('(prefers-color-scheme: dark)').matches
        if (browsersPreferDark) {
          previewElement.classList.add('dark-mode')
        } else {
          previewElement.classList.remove('dark-mode')
        }
      }
      break

    case 'pdfdownload':
      previewElement.style.setProperty('--gpdf-prev-download', value == true ? 'block' : 'none')
      break

    case 'pdfpreviewheight':
      previewElement.style.setProperty('--gpdf-prev-viewer-container-height', !isNaN(value) ? value + 'px' : '600px')
      break

    case 'pdfzoomlevel':
      /* fake the scale */
      var scale = 1.33
      switch (value) {
        case 'page-width':
          scale = 1.25
          break

        case 'page-fit':
          scale = 0.5
          break

        case 'page-actual':
          // do nothing
          break

        default:
          scale *= value
      }

      previewElement.style.setProperty('--gpdf-prev-scale-factor', scale)
      break

    case 'pdfwatermarktoggle':
      previewElement.style.setProperty('--gpdf-prev-watermark', value == true ? 'block' : 'none')
      break

    case 'pdfwatermarktext':
      previewElement.querySelector('.pdf-page-watermark').innerText = value
      break
  }
}