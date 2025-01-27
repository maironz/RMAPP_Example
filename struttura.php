<?php die(); ?>
/RMapp                               # Cartella principale
    /config
        index.html                     # File per la protezione della cartella
        LogData.php                    # File di log delle applicazioni
        Settings.php                   # Configurazioni del database
    /Helpers
        Helpers.php                    # Funzioni di supporto            
        index.html                     # File per la protezione della cartella
        LangHelper.php                 # File per la configurazione delle lingue
    /libs                              # Librerie esterne
        index.html                     # File per la protezione della cartella
    /src
        /Contracts
            ContractController.php     # Logica di controllo per contratti
            ContractModel.php          # Modello per contratti
            ContractServices.php       # Servizi ausiliari, logica di backend complessa
            ContractView.php           # Vista per interagire con i contratti
            index.html                 # File per la protezione della cartella
        /Employees
            EmployeeModel.php          # Modello per dipendenti
            EmployeeController.php     # Logica di controllo per dipendenti (include Ajax)
            EmployeeView.php           # Vista per interagire con la tabella dipendenti
            EmployeeServices.php       # Servizi ausiliari, logica di backend complessa
            index.html                 # File per la protezione della cartella
            
        /Users
            UserModel.php              # Modello per utenti
            UserController.php         # Logica di controllo per utenti
            UserView.php               # Vista per interagire con gli utenti
            UserServices.php           # Servizi ausiliari, logica di backend complessa
            index.html                 # File per la protezione della cartella
    /public
        /assets
            /css
                RMTemplate.css         # Configurazione di base per il css
                index.html             # File per la protezione della cartella
            /js
                index.html             # File per la protezione della cartella
                RMAjaxManager.js       # Gestore delle chiamate Ajax
                RMTemplateGraphics.js  # Configurazioni di base di supporto alla grafica
                Employee.js            # Funzioni di manipolazione e invio dei dati
                Contract.js            # Funzioni di manipolazione e invio dei dati
                User.js                # Funzioni di manipolazione e invio dei dati
        index.php                      # Punto d'ingresso principale
        Contracts.php                  # Vista per i contratti
        Employees.php                  # Vista per i dipendenti
        Users.php                      # Vista per gli utenti
        RMAjaxDispatcher.php           # Dispatcher per le chiamate Ajax

    /Resources
        /lang
            Lang.php                   # Logica di selezione della lingua
            en.php                     # File di traduzione (inglese)
            it.php                     # File di traduzione (italiano)
        /pictures                      # Cartella delle immagini
    /System
        Log.php                        # Gestore dei log
        Connection.php                 # Gestore delel connessioni con il DB
    /test
        EmployeeTest.php               # Test per i dipendenti
        ContractTest.php               # Test per i contratti
        UserTest.php                   # Test per gli utenti
        index.html                 # File per la protezione della cartella
    /vendor
        Autoload.php                   # caricamento della struttura del programma

    index.html                         # File per la protezione della cartella