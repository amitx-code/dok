const fallbackCopyTextToClipboard = (text) => {
  const textArea = document.createElement('textarea')
  textArea.value = text
  document.body.appendChild(textArea)
  textArea.focus()
  textArea.select()

  try {
    const successful = document.execCommand('copy')
    document.body.removeChild(textArea)
    return successful

  } catch (err) {
    document.body.removeChild(textArea)
    return false
  }
}
const copyTextToClipboard = (text) => {
  if (!navigator.clipboard) {
    return fallbackCopyTextToClipboard(text)
  }
  navigator.clipboard.writeText(text).then(function () {
    console.log('Async: Copying to clipboard was successful!')
    return true

  }, function (err) {
    console.error('Async: Could not copy text: ', err)
    return false
  })
}

export default function init (selectorButton, text) {
  $('[data-clipboard-action="copy"]').each(function () {
    const $button = $(this);
    $button.click(e => {
      copyTextToClipboard($($button.attr('data-clipboard-target')).val());
    })
  })
  /*
  $(selectorButton).click(e => {
      copyTextToClipboard(text);
      return false;
  })
  */
}