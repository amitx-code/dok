const init = () => {

  $('#formPayment').commonForm(data => {
    document.location.href = data.url;
  })

}

export { init }