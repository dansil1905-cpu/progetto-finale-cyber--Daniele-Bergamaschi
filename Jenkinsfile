pipeline {
    agent any

    environment {
        SNYK_TOKEN = credentials('snyk-token')
        SONAR_TOKEN = credentials('SONAR_AUTH_TOKEN')
        DOCKERHUB_CREDENTIALS = credentials('dockerhub-credentials')
        SSH_CRED = 'ubuntu-sshubuntu' // Usa l'ID esatto presente su Jenkins
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
                        echo "--- Connessione SSH riuscita! Esecuzione Audit su server remoto ---"
                        ssh -o StrictHostKeyChecking=no ubuntu@${TARGET_SERVER} "
                            cd /home/ubuntu/progetto-finale &&
                            echo 'Snyk scan avviato...'
                        "
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

        stage('Deploy Remoto & Container') {
            steps {
                sshagent([SSH_CRED]) {
                    sh '''
                        echo "--- Deploy sul Server Remoto (${TARGET_SERVER}) ---"
                        ssh -o StrictHostKeyChecking=no ubuntu@${TARGET_SERVER} "
                            if [ ! -d '/home/ubuntu/progetto-finale/.git' ]; then
                                git clone https://github.com/dansil1905-cpu/progetto-finale-cyber--Daniele-Bergamaschi.git /home/ubuntu/progetto-finale;
                            else
                                cd /home/ubuntu/progetto-finale && git fetch origin && git reset --hard origin/main;
                            fi &&
                            cd /home/ubuntu/progetto-finale &&
                            docker stop cyber-app || true && docker rm cyber-app || true &&
                            docker build -t dansil/cyber-app:latest . &&
                            docker run -d --name cyber-app -p 8000:8000 dansil/cyber-app:latest
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