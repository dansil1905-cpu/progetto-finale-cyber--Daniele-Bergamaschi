import express from 'express'; 
import helmet from 'helmet'; 
import rateLimit from 'express-rate-limit'; 
import crypto from 'crypto'; 

const app = express(); 
const PORT = process.env.PORT || 3000; 

app.use(helmet()); 
 
const limiter = rateLimit({ 
    windowMs: 15 * 60 * 1000,
    max: 100, 
    standardHeaders: true, 
    legacyHeaders: false, 
    message: 'Troppe richieste effettuate da questo IP, riprova più tardi.' 
}); 
app.use(limiter); 

app.use(express.json()); 
 
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
 
    res.json({ 
        success: true, 
        count: 1, 
        posts: [ 
            { id: 1, title: 'Introduzione alla Sicurezza DevSecOps', status: 'approved' } 
        ] 
    }); 
}); 

app.post('/api/financial/records', (req, res) => { 
    const { card_number, balance, sensitive_info } = req.body; 

    if (!card_number || !balance || !sensitive_info) { 
        return res.status(400).json({ success: false, message: 'Campi obbligatori mancanti.' }); 
    } 

    const maskedCard = '****-****-****-' + String(card_number).slice(-4); 
     
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