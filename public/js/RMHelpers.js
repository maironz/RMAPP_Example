// RMHelpers.js
class RMHelpers {

  /** Dynamically load SweetAlert.
   * Example:
   * window.addEventListener("DOMContentLoaded", () => {
   *   RMHelpers.loadSweetAlert(() => {
   *     console.log("SweetAlert is ready to use.");
   *   });
   * });
   */
  static loadSweetAlert(callback = null) {
    // Controlla se SweetAlert è già disponibile
    if (typeof Swal === 'function') {
      if (callback) callback();
      return;
    }

    // Dynamically load the library
    const script = document.createElement('script');
    script.src = "https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.all.min.js";
    script.onload = () => {
      if (callback) callback();
    };
    script.onerror = () => {
      console.error("Failed to load SweetAlert. Default alerts will be used.");
    };

    document.head.appendChild(script);
  }

  /** Show a basic alert as a fallback.
   * @param {string} title - The title of the alert.
   * @param {string} text - The text content of the alert.
   */
  static standardAlert(title, text){
    alert(`${title}\n\n${text}`);
  }

/** Display an alert using SweetAlert or fallback to a basic alert.
   * Five built-in icon "warning", "error", "success", "info" and "question"
   * Example:
   * RMHelpers.showAlert({
   *   title: 'Confirmation',
   *   text: 'Are you sure?',
   *   icon: 'warning',
   *   showCancelButton: true,
   * });
   * @param {object} options - SweetAlert options.
   */
  static showAlert(options = {}) {
    const defaults = {
      title: '',
      titleText: '',
      html: '',
      text: '',
      icon: 'success',
      iconColor: undefined,
      iconHtml: undefined,
      animation: true,
      showClass: {
        popup: 'swal2-show',
        backdrop: 'swal2-backdrop-show',
        icon: 'swal2-icon-show'
      },
      hideClass: {
        popup: 'swal2-hide',
        backdrop: 'swal2-backdrop-hide',
        icon: 'swal2-icon-hide'
      },
      footer: '',
      backdrop: true,
      toast: false,
      target: 'body',
      input: undefined,
      width: '32em',
      padding: '0 0 1.25em',
      color: undefined,
      //background: undefined,
      position: 'center',
      grow: false,
      customClass: {
        confirmButton: 'buttsweetalert',
        cancelButton: 'buttsweetalert',
        denyButton: 'buttsweetalert'
      },
      timer: 8000,
      timerProgressBar: false,
      heightAuto: true,
      allowOutsideClick: true,
      allowEscapeKey: true,
      stopKeydownPropagation: true,
      keydownListenerCapture: false,
      showConfirmButton: true,
      showDenyButton: false,
      showCancelButton: false,
      confirmButtonText: 'OK',
      denyButtonText: 'No',
      cancelButtonText: 'Cancel',
      denyButtonColor: undefined,
      cancelButtonColor: undefined,
      confirmButtonAriaLabel: '',
      denyButtonAriaLabel: '',
      cancelButtonAriaLabel: '',
      buttonsStyling: false,
      reverseButtons: false,
      focusConfirm: true,
      returnFocus: true,
      focusDeny: false,
      focusCancel: false,
      showCloseButton: false,
      closeButtonHtml: '&times;',
      closeButtonAriaLabel: 'Close this dialog',
      loaderHtml: '',
      showLoaderOnConfirm: false,
      showLoaderOnDeny: false,
      scrollbarPadding: true,
      preConfirm: undefined,
      preDeny: undefined,
      returnInputValueOnDeny: false,
      //imageUrl: undefined,
      //imageWidth: '10em',
      //imageHeight: '10em',
      //imageAlt: '',
      inputLabel: '',
      inputPlaceholder: '',
      inputValue: '',
    };
    // Create a new settings object that uses all the default properties and those replaced by options
    const settings = { ...defaults, ...options };
    if (typeof Swal === 'function') {
      Swal.fire(settings);
    } else {
      this.standardAlert(settings.title, settings.text);
    }
  }

  /** Close any active SweetAlert. */
  static closeAlert() {
    if (typeof Swal === 'function') {
      Swal.close();
    }
  }

  /** Show a SweetAlert confirmation dialog followed by another alert.
   * Example:
   * RMHelpers.showAlertWithConfirmation(
   *   { title: 'First Alert', text: 'Do you want to proceed?', icon: 'warning', showCancelButton: true },
   *   { title: 'Second Alert', text: 'Action confirmed!', icon: 'success' }
   * );
   * @param {object} firstAlert - Options for the first alert.
   * @param {object} secondAlert - Options for the second alert.
   */
  static showAlertWithConfirmation(firstAlert = {}, secondAlert = {}) {
    if (typeof Swal === 'function') {
      Swal.fire(firstAlert).then((result) => {
        if (result.isConfirmed && secondAlert.title) {
          RMHelpers.showAlert(secondAlert);
        }
      });
    } else {
      this.standardAlert(firstAlert.title, firstAlert.text);
    }
  }

  /** Show a SweetAlert confirmation dialog with a custom callback.
   * Example:
   * RMHelpers.showAlertWithFunction(
   *   { title: 'First Alert', text: 'Do you want to proceed?', icon: 'warning', showCancelButton: true },
   *   () => { console.log('Custom callback executed!'); }
   * );
   * @param {object} firstAlert - Options for the alert.
   * @param {function} customCallback - A function to execute after the alert is confirmed.
   */
  static showAlertWithFunction(firstAlert = {}, customCallback = null) {
    if (typeof Swal === 'function') {
      Swal.fire(firstAlert).then((result) => {
        if (result.isConfirmed && typeof customCallback === 'function') {
          customCallback();
        }
      });
    } else {
      this.standardAlert(firstAlert.title, firstAlert.text);
      if (typeof customCallback === 'function') {
        customCallback(); // Chiama la funzione custom nel fallback
      }
    }
  }

  /** Validates if the input is a positive integer.
   *
   * This function checks whether the given input is:
   * 1. A number.
   * 2. Greater than or equal to 0.
   * 3. An integer (i.e., no decimal places).
   *
   * @param {any} positiveint - The value to be validated.
   * @returns {boolean} - Returns `true` if the value is a positive integer, `false` otherwise.
   *
   * @example
   * validatePositiveInt(5); // Returns: true
   * validatePositiveInt(-3); // Returns: false
   * validatePositiveInt('10'); // Returns: false
   * validatePositiveInt(10.5); // Returns: false
   */
  static validatePositiveInt(positiveint){
    if(isNaN(positiveint)) return false;
    if(positiveint < 0) return false;
    if(parseInt(positiveint) != positiveint) return false;
    return true;
  }


}
