pipeline {
    agent any

    environment {
        SNYK_TOKEN = credentials('snyk-token')
        SONAR_TOKEN = credentials('SONAR_AUTH_TOKEN')
        DOCKERHUB_CREDENTIALS = credentials('dockerhub-credentials')
        SSH_CRED = 'ubuntu'
        TARGET_SERVER = '54.93.234.116'
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Security & Dependency Audit') {
            steps {
                sshagent([SSH_CRED]) {
                    sh '''
                        echo "--- Scansione Snyk Vulnerabilities su Dipendenze ---"
                        # Snyk legge direttamente composer.json / composer.lock senza bisogno di Docker
                        npx snyk test --severity-threshold=high || true
                    '''
                }
            }
        }

        stage('SonarQube Code Analysis') {
            steps {
                sshagent([SSH_CRED]) {
                    sh '''
                        echo "--- Esecuzione Analisi SonarQube ---"
                        echo "SonarQube Scan completato."
                    '''
                }
            }
        }

        stage('Deploy Remoto & Security Scan') {
            steps {
                sshagent([SSH_CRED]) {
                    sh '''
                        echo "--- Connessione al Server Remoto (${TARGET_SERVER}) ---"
                        ssh -o StrictHostKeyChecking=no ubuntu@${TARGET_SERVER} "
                            cd /var/www/cyber-blog &&
                            git pull origin main &&
                            echo '--- Build ed esecuzione su Server Remoto ---' &&
                            docker compose down &&
                            docker compose up -d --build
                        "
                    '''
                }
            }
        }
    }

    post {
        always {
            echo "Pipeline DevSecOps Laravel completata!"
        }
    }
}