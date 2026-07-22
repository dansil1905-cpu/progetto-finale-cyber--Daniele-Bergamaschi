# Progetto Finale Specializzazione Cyber Security

**Candidato:** Daniele Bergamaschi  
**Repository:** `progetto-finale-cyber--Daniele-Bergamaschi`

---

## 🛡️ Architettura dell'Applicazione

L'applicazione è sviluppata in **Laravel 11** e implementa due macro-moduli protetti:

1. **Cyber Blog:** 
   - Gestione dinamiche di lettura, scrittura e revisione articoli.
   - Ruoli utenti protetti tramite Middleware dedicati (`UserIsRevisore`, `UserIsAdmin`).
   - Gli articoli inseriti nascono con stato `pending` e richiedono approvazione da un revisore per la pubblicazione.

2. **travel-agent-api (Financial App):**
   - Gestione sicura di dati finanziari e sensibili degli utenti via API REST.
   - Autenticazione delle richieste via token con **Laravel Sanctum** (`/api/tokens/create`).
   - Cifratura dei dati sensibili nel database a riposo via `Crypt::encryptString`.
   - Mascheramento carte di credito (`****-****-****-1234`).

---

## 🔒 Sicurezza CI/CD & DevSecOps (OWASP Top 10 CI/CD)

La pipeline Jenkins integra le direttive del corso:
- **PPE Mitigation & PBAC:** Esecuzione builds su **Jenkins Agent** isolato.
- **Credential Hygiene:** Utilizzo dello Store Credenziali per `SNYK_TOKEN` e segreti.
- **SCA Check:** Scansione dipendenze PHP/Composer tramite `composer audit`.
- **Flow Control:** Gestione approvazione release su branch `main` tramite GitHub Pull Requests e Code Review.

---

## 🚀 Deploy & Infrastruttura IaC

Infrastruttura AWS configurata via Terraform (`/terraform/main.tf`) con backend remoto S3 + DynamoDB State Locking per la consistenza dell'ambiente.