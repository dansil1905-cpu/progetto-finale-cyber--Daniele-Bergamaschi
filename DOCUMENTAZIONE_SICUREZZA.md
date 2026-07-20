# Documentazione di Sicurezza - Progetto Finale Cyber Security

## 1. Introduzione
Il presente documento descrive le contromisure di sicurezza implementate nell'applicazione monorepo sviluppata con framework **Laravel**, strutturata attorno a due moduli principali: **Cyber Blog** e **Financial App**. L'architettura segue rigorosamente il principio del *Security by Design*.

## 2. Mitigazione delle Minacce OWASP Top 10

### 2.1 SQL Injection (SQLi)
*   **Rischio:** Manipolazione malevola delle query al database tramite input utente non validati.
*   **Mitigazione:** L'applicazione fa uso esclusivo di **Eloquent ORM** e del Query Builder di Laravel, i quali utilizzano nativamente *prepared statements* (parametri associati). L'accesso diretto a stringhe SQL grezze è totalmente bandito.

### 2.2 Cross-Site Scripting (XSS)
*   **Rischio:** Iniezione di script lato client (JavaScript malevolo) eseguiti nel browser della vittima.
*   **Mitigazione:** I template engine **Blade** di Laravel effettuano automaticamente l'escape di qualsiasi output stampato a schermo tramite la sintassi `{{ $variable }}`. Eventuali input HTML controllati passano attraverso un processo di sanitizzazione.

### 2.3 Cross-Site Request Forgery (CSRF)
*   **Rischio:** Esecuzione di comandi indesiderati trasmessi da un utente autenticato in modo non autorizzato.
*   **Mitigazione:** Il middleware globale `VerifyCsrfToken` di Laravel è attivo su tutte le richieste POST, PUT, PATCH e DELETE, validando i token crittografici associati alla sessione utente.

### 2.4 Esposizione di Dati Sensibili (Data Breach)
*   **Rischio:** Lettura in chiaro di dati finanziari o personali all'interno del database SQLite.
*   **Mitigazione:** I dati finanziari gestiti dalla *Financial App* sono protetti tramite il meccanismo di **Attribute Encryption** di Eloquent (AES-256-CBC), garantendo che i dati siano cifrati a riposo (*at rest*).

## 3. Controllo degli Accessi (RBAC)
Il modulo *Cyber Blog* implementa un sistema di autorizzazione basato su ruoli (Admin / Editor) gestito tramite le **Laravel Policies**, impedendo l'accesso orizzontale e verticale non autorizzato alle risorse di gestione e revisione articoli.

## 4. Pipeline CI/CD e Hardening dell'Infrastruttura
*   **Containerizzazione:** Il Dockerfile di produzione (`php.Dockerfile`) adotta il principio del minor privilegio eseguendo il demone PHP-FPM con un utente non-root (`laravel`).
*   **Security Scanning:** La pipeline Jenkins integra scansionatori automatici di vulnerabilità:
    *   **Snyk:** Per la scansione del codice sorgente e delle dipendenze Composer.
    *   **Aqua Trivy:** Per l'analisi dell'immagine Docker finale alla ricerca di CVE critiche.