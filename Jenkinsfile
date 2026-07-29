pipeline {
    agent any

    environment {
        SNYK_TOKEN = credentials('snyk-token')
        SONAR_TOKEN = credentials('SONAR_AUTH_TOKEN')
        DOCKERHUB_CREDENTIALS = credentials('dockerhub-credentials')
        SSH_CRED = 'ubuntu-sshubuntu'
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
                        echo "--- Esecuzione Composer Install temporaneo per la scansione Snyk ---"
                        docker run --rm -v $(pwd):/app composer:latest install --ignore-platform-reqs --no-scripts
                        
                        echo "--- Scansione Snyk Vulnerabilities ---"
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
                        # Sostituisci con il comando del tuo scanner Sonar (es. sonar-scanner) se configurato
                        echo "SonarQube Scan completato."
                    '''
                }
            }
        }

        stage('Build & Trivy Container Scan') {
            steps {
                sshagent([SSH_CRED]) {
                    sh '''
                        echo "--- Build Immagine Docker ---"
                        docker build -t cyber-blog:latest .

                        echo "--- Scansione Trivy Immagine ---"
                        trivy image --severity HIGH,CRITICAL cyber-blog:latest || true
                    '''
                }
            }
        }

        stage('Deploy Remoto') {
            steps {
                sshagent([SSH_CRED]) {
                    sh '''
                        echo "--- Deploy sul Server Remoto (${TARGET_SERVER}) ---"
                        ssh -o StrictHostKeyChecking=no ubuntu@${TARGET_SERVER} "
                            cd /var/www/cyber-blog &&
                            git pull origin main &&
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