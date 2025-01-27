// Contract.js
var ajaxCommunication = new RMAjaxManager();
// set the communication variable
var keyValuePair = new Map([
  ['cookie', 'val cookie'],
  ['variable name', 'variable value'],
]);
var originalValue = '';
var cookie = ''; 
var sessionName = '';
const rMTranslationHelpers = new RMTranslationHelpers(translations);

// Function to create listeners on table inputs
function addTableListener() {
  const table = document.getElementById('tabella_contratti');

  // Stores the original value when the element gets focus / memorizza il valore originale quando l'elemento ottiene il focus
  table.addEventListener('focusin', function(event) {
    target = event.target;
    if (target.tagName.toLowerCase() !== 'select' && target.hasAttribute('data-field')) {
      originalValue = target.textContent;
      //console.log(' originalValue:', originalValue);
    }
  });

  // Prepare data when element loses focus / prepara i dati quando l'elemento perde il focus
  table.addEventListener('focusout', function(event) {
    target = event.target;
    if (target.tagName.toLowerCase() !== 'select' && target.hasAttribute('data-field')) {
      const newValue = target.textContent;
      if (!validateField(target.getAttribute('data-field'),newValue)){
        RMHelpers.showAlert({
          title: rMTranslationHelpers.getTranslation('alert_error'),
          text: rMTranslationHelpers.getTranslation('invalid_data'),
          icon: 'error'
        });
        //reset the value
        target.textContent = originalValue;
      } else {
        prepareAndSendData(newValue);
      }
  }
  });

  // Prepare data when element loses focus / prepara i dati quando il valore di un select cambia
  table.addEventListener('change', function(event) {
    target = event.target;
    if (target.tagName.toLowerCase() === 'select' && target.hasAttribute('data-field')) {
      const newValue = target.value;
      prepareAndSendData(newValue);
    }
  });

  table.addEventListener('keydown', function(event) {
    if (event.key === 'Tab' || event.key === 'Enter') {
      event.preventDefault();
      const activeElement = document.activeElement;
      activeElement.blur();
    }
  });
}

// Function to prepare data to send to the server
function prepareAndSendData(newValue) {
  if (target.hasAttribute('data-field')) {
    if (newValue !== originalValue) {
      const row = target.closest('tr');
      // text contained in the ID cell / testo contenuto nella cella ID
      const id = row.querySelector('[data-field="ID"]').textContent.trim();
      var field = target.getAttribute('data-field');
      let combinedValue = newValue;
      if (field.endsWith('_ore')) {
        const minutesField = row.querySelector(`[data-field="${field.replace('_ore', '_minuti')}"]`);
        if (minutesField) {
          const minutesValue = minutesField.value.padStart(2, '0');
          const hoursValue = newValue.padStart(2, '0');
          combinedValue = `${hoursValue}:${minutesValue}:00`;
        }
        field = field.replace('_ore', '');
      } else if (field.endsWith('_minuti')) {
        const hoursField = row.querySelector(`[data-field="${field.replace('_minuti', '_ore')}"]`);
        if (hoursField) {
          const hoursValue = hoursField.value.padStart(2, '0');
          const minutesValue = newValue.padStart(2, '0');
          combinedValue = `${hoursValue}:${minutesValue}:00`;
        }
        field = field.replace('_minuti', '');
      }
      //console.log(' row:', row, ' field:', field, ' combinedValue:', combinedValue);
      updateFields(row, field, combinedValue);
    }
  }
}

// Function to validate the data to be send
function validateField(attribute, value){
  switch (attribute){
    case ('descrizione'):
      return true;
    case('oresettimanali'):
    case('giorni_ferie_annuali'):
    case('ore_permesso_annuali'):
      if(RMHelpers.validatePositiveInt(value)){
        if(value<128) return true;
      }
    break;
  default:
  }
  return false;
}

// Function to send modified data
function updateFields(row, field, newValue) {
  keyValuePair = new Map([
    ['cookie', cookie],
    ['sessionName', sessionName],
    ['method', "updateContract"],
    ['id', row.getAttribute('data-id')],
    ['field', field],
    ['value', newValue]
  ]);
  
  // Open SweetAlert2 modal to indicate loading
  RMHelpers.showAlert({
    title: rMTranslationHelpers.getTranslation('start_waiting_title'),
    text: rMTranslationHelpers.getTranslation('start_waiting_description'),
    allowOutsideClick: false,
    allowEscapeKey: false,
    showConfirmButton: false,
    didOpen: () => {
      Swal.showLoading(); // Show loading spinner
    }
  });

  // Send the AJAX request
  ajaxCommunication.send(keyValuePair);
}

// Function to manage the response to the ajax call updateFields
function updateFieldsResponse(response) {
  // Close the loading modal
  RMHelpers.closeAlert();

  respStatus = response.respStatus;
  descRespStatus  = response.descRespStatus;
  // Check the response status
  if (response.respStatus === 1) {
    RMHelpers.showAlert({
      title: rMTranslationHelpers.getTranslation('confirmation_message'),
      text: response.descRespStatus,
      icon: 'success'
    });
  } else {
    RMHelpers.showAlert({
      title: rMTranslationHelpers.getTranslation('alert_error'),
      text: response.descRespStatus,
      icon: 'error'
    });
  }
}

// Function to create new contract
function newContract(){
  keyValuePair = new Map([
    ['cookie', cookie],
    ['sessionName', sessionName],
    ['method', "newContract"]
  ]);
  ajaxCommunication.send(keyValuePair);
}

// Function to manage the response to the ajax call newContract
function newContractResponse(response) {
  const respStatus = response.respStatus;
  const descRespStatus = response.descRespStatus;

  RMHelpers.showAlertWithFunction({
    title: rMTranslationHelpers.getTranslation('confirmation_message'),
    //timer: 8000,
    text: descRespStatus + " " + rMTranslationHelpers.getTranslation('page_updated_shortly'),
    icon: 'success',
    customClass: {confirmButton: '.buttsweetalert'}
  }, () => {
    location.reload(); // Esegui location.reload() solo dopo la chiusura dell'alert
  });
}

// Function to manage the response to the ajax call
window.addEventListener('ajaxResponse', function(event) {
    const response = event.detail.response; // Ottieni la risposta
    const method = event.detail.method; // Ottieni il metodo della chiamata
    if (method == "updateContract"){
      updateFieldsResponse(response);
    }else if (method == "newContract"){
      newContractResponse(response);
    }
});

// Scroll Arrow Visibility Manager
window.addEventListener('resize', function() {
  RMTemplateGraphics.checkScrollbar('tabella_contratti','scroll-button');
});

 // Function to initialize the logic
window.addEventListener("DOMContentLoaded", function() {
	cookie = document.getElementById("output-box").getAttribute('sessionid');
	sessionName = document.getElementById("output-box").getAttribute('sessionname');
	document.getElementById("output-box").removeAttribute('sessionid');
	document.getElementById("output-box").removeAttribute('sessionname');
  addTableListener();
});

// Function to update the arrow to indicate the extension of the table
window.addEventListener("DOMContentLoaded", () => {
  RMTemplateGraphics.checkScrollbar('tabella_contratti','scroll-button');
  RMHelpers.loadSweetAlert(() => {
    //console.log("SweetAlert is ready to use.");
  });
});
