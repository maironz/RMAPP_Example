// RMAjaxManager.js

/* Example usage of RMAjaxManager / Esempio di utilizzo di RMAjaxManager

  // Create an instance of RMAjaxManager
  const ajaxManager = new RMAjaxManager();
  const keyValuePair = new Map([
    ['method', 'exampleMethod'],
    ['param1', 'value1'],
    ['param2', 'value2']
  ]);

  // Send the AJAX request
  ajaxManager.send(keyValuePair);

  // Listen for the custom event
  document.addEventListener('ajaxResponse', function(event) {
    console.log('AJAX Response:', event.detail.response);
    console.log('Method:', event.detail.method);
  });
*/

class RMAjaxManager{
	// Destination file of the call
	#destination = window.location.origin + "/../runner/RMapp/public/RMAjaxDispatcher.php";
	#backupDestination = "";
  #rMTranslationHelpers = new RMTranslationHelpers(translations);
	// example variable to redefine to pass via Ajax
	keyValuePair = new Map([
	  ['cookie', 'val cookie'],
	  ['variable name', 'variable value']
	]);
  // Communication status 0 = error in this function, 1 = good, 2 error page, 3 error server, 4 error server connection, 5 timeout
	// Stato della comunicazione 0=errore in questa funzione, 1=buona, 2 errore pagina, 3 errore del server 4 errore del server, 5 timeout
	commStatus = 0;
  // Response status 0 = error in the call, 1 = ok, 2 = nok
	// Stato della risposta 0=errore nel richiamo, 1=ok, 2=nok
	respStatus = 0;
  // Response description "" = error in the call, "ok" = transaction ok, "text" = error description
	// Descrizione della risposta ""=errore nel richiamo, "ok"= transazione ok, "testo"=descrizione dell'errore
	descRespStatus = "";
  // Base response model
	// Modello base di risposta
	resp = '{"respStatus":0, "descRespStatus":"' + this.#rMTranslationHelpers.getTranslation('alert_error') + '"}';
  //self.#rMTranslationHelpers.getTranslation('alert_error')

	constructor() {}
  
	send(keyValuePair){
		this.commStatus = 0;
    const self = this;

		var response;
		var data = JSON.stringify(Object.fromEntries(keyValuePair));
    
    const tryRequest = (destination, xhttp_int) => {
      xhttp_int.open("POST",destination , true);
      xhttp_int.timeout = 5000; // Set timeout to 5 seconds (5000 milliseconds)
      xhttp_int.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      var method = keyValuePair.get('method');
      xhttp_int.send(data);
      xhttp_int.onerror = function() {
        if (destination === self.#destination) {
          tryRequest(self.#backupDestination, new XMLHttpRequest());
        } else {
          self.commStatus = 4;// server error / errore del server
          var messaggio = self.#rMTranslationHelpers.getTranslation('server_connection_error');
          messaggio = messaggio + "status=" + xhttp_int.status +"\n";
          messaggio = messaggio + "commStatus=4\n";
          messaggio = messaggio + "data=" + data +"\n";
          messaggio = messaggio + "response=" + xhttp_int.responseText  +"\n";
          alert(messaggio);
        }
      };
      xhttp_int.onreadystatechange = function() {
        if (xhttp_int.readyState == 4 && xhttp_int.status == 200) {
          self.commStatus = 1;//buona comunicazione //good communication
          // Response in JSon to the ajax request to be set in php
          // Risposte in JSon alla richiesta ajax da impostare in php
          try {
            response = JSON.parse(xhttp_int.response);
          } catch (e) {
            var messaggio = self.#rMTranslationHelpers.getTranslation('invalid_json');
            messaggio = messaggio + "xhttp status=" + xhttp_int.response;
            messaggio = messaggio + "xhttp statusText=" + xhttp_int.statusText +"\n";
            alert(messaggio);
          }
          // The first parameter is always the type of response status
          // Il primo parametro è sempre il tipo di risposta status
          self.respStatus = response.respStatus;
          self.descRespStatus  = response.descRespStatus;
          // Emit a custom event with the response
          // Emette un evento personalizzato con la risposta
          const event = new CustomEvent('ajaxResponse', {
            detail: {
              response: response,
              method: keyValuePair.get('method') // Aggiungi il metodo alla risposta // Add the method to the response
            }
          });
          window.dispatchEvent(event);
        } else {
          //not 200
          if(xhttp_int.status> 299 && xhttp_int.status<500){
            self.commStatus = 2;//errore pagina //page error
            var messaggio = self.#rMTranslationHelpers.getTranslation('client_error');
            messaggio = messaggio + "xhttp status=" + xhttp_int.status +"\n";
            messaggio = messaggio + "xhttp statusText=" + xhttp_int.statusText +"\n";
            messaggio = messaggio + "data=" + data +"\n";
            messaggio = messaggio + "response=" + xhttp_int.responseText +"\n";
            alert(messaggio);
          } else {
            if(xhttp_int.status>= 500){
              self.commStatus = 3;//errore del server //server error
              var messaggio = self.#rMTranslationHelpers.getTranslation('server_error');
              messaggio = messaggio + "xhttp status=" + xhttp_int.status +"\n";
              messaggio = messaggio + "xhttp statusText=" + xhttp_int.statusText +"\n";
              messaggio = messaggio + "data=" + data +"\n";
              messaggio = messaggio + "response=" + xhttp_int.responseText +"\n";
              alert(messaggio);
            }
          }
        }
      };
      xhttp_int.ontimeout = function() {
        self.commStatus = 5; // timeout
        alert(self.#rMTranslationHelpers.getTranslation('request_time_error'));
      };
    };
    tryRequest(this.#destination, new XMLHttpRequest());
	}
}

