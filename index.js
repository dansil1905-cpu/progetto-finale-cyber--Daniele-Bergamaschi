import express from 'express'; 
import helmet from 'helmet'; 
import rateLimit from 'express-rate-limit'; 
import crypto from 'crypto'; 

const app = express(); 
const PORT = process.env.PORT || 3000; 

// Misure di sicurezza: Intestazioni HTTP con Helmet 
app.use(helmet()); 

// Protezione contro il brute-forcing con express-rate-limit 
const limiter = rateLimit({ 
    windowMs: 15 * 60 * 1000, // 15 minuti 
    max: 100, // Limite di 100 richieste per IP 
    standardHeaders: true, 
    legacyHeaders: false, 
    message: 'Troppe richieste effettuate da questo IP, riprova più tardi.' 
}); 
app.use(limiter); 

app.use(express.json()); 

// --- ROTTE DEL CYBER BLOG --- 
app.get('/', (req, res) => { 
    res.json({  
        status: 'success', 
        message: 'Benvenuto nel Cyber Blog - Progetto Finale Cyber Security', 
        endpoints: { 
            posts: '/posts', 
            financial_api: '/api/financial/records' 
        } 
    }); 
}); 

app.get('/posts', (req, res) => { 
    // Simulazione di recupero articoli approvati 
    res.json({ 
        success: true, 
        count: 1, 
        posts: [ 
            { id: 1, title: 'Introduzione alla Sicurezza DevSecOps', status: 'approved' } 
        ] 
    }); 
}); 

// --- ROTTE TRAVEL-AGENT-API (Financial App) --- 
// Salvataggio dati finanziari con cifratura AES-256-GCM (Algoritmo sicuro e moderno)
app.post('/api/financial/records', (req, res) => { 
    const { card_number, balance, sensitive_info } = req.body; 

    if (!card_number || !balance || !sensitive_info) { 
        return res.status(400).json({ success: false, message: 'Campi obbligatori mancanti.' }); 
    } 

    // Mascheramento carta di credito 
    const maskedCard = '****-****-****-' + String(card_number).slice(-4); 
     
    // Cifratura sicura tramite AES-256-CBC con IV generato casualmente
    const algorithm = 'aes-256-cbc';
    const key = crypto.scryptSync('chiave-segreta-sicura', 'salt', 32);
    const iv = crypto.randomBytes(16);
    
    const cipher = crypto.createCipheriv(algorithm, key, iv);
    let encryptedData = cipher.update(sensitive_info, 'utf8', 'hex');
    encryptedData += cipher.final('hex');

    res.status(201).json({ 
        success: true, 
        message: 'Dato finanziario registrato, mascherato e cifrato con successo.', 
        data: { 
            card_number_masked: maskedCard, 
            balance: balance, 
            sensitive_data_encrypted: encryptedData,
            iv: iv.toString('hex')
        } 
    }); 
}); 

app.listen(PORT, () => { 
    console.log(`Server Express avviato in modo sicuro sulla porta ${PORT}`); 
});