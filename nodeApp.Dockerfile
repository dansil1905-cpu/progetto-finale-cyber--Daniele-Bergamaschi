FROM node:18-alpine

WORKDIR /app

COPY package*.json ./

RUN npm install --only=production

COPY . .

# Principio di Hardening: Usiamo l'utente non-root "node" integrato in Alpine
USER node

EXPOSE 3000

CMD ["node", "index.js"]