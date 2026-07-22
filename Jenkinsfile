pipeline {
    agent any

    environment {
        SNYK_TOKEN = credentials('snyk-token')
        SONAR_TOKEN = credentials('SONAR_AUTH_TOKEN')
        TARGET_SERVER = '18.199.149.214'
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Security & Dependency Audit') {
            steps {
                // Audit dipendenze PHP (Laravel)
                sh 'composer audit || true'
                // Scan Snyk codice sorgente
                sh 'snyk test || true'
            }
        }

        stage('SonarQube Code Analysis') {
            steps {
                sshagent(['deploy-keyubuntu']) {
                    sh '''
                        ssh -o StrictHostKeyChecking=no ubuntu@${TARGET_SERVER} "
                            if [ ! -d '/home/ubuntu/progetto-finale' ]; then
                                git clone https://github.com/dansil1905-cpu/progetto-finale-cyber--Daniele-Bergamaschi.git /home/ubuntu/progetto-finale;
                            else
                                cd /home/ubuntu/progetto-finale && git pull origin main;
                            fi &&
                            cd /home/ubuntu/progetto-finale &&
                            docker run --rm --network='host' \\
                                -v /home/ubuntu/progetto-finale:/usr/src \\
                                sonarsource/sonar-scanner-cli \\
                                -Dsonar.projectKey=progetto-finale-cyber \\
                                -Dsonar.token=${SONAR_TOKEN} \\
                                -Dsonar.sources=. \\
                                -Dsonar.exclusions='**/vendor/**,**/node_modules/**,**/.git/**' || true
                        "
                    '''
                }
            }
        }

        stage('Build & Trivy Container Scan') {
            steps {
                sshagent(['deploy-keyubuntu']) {
                    sh '''
                        ssh -o StrictHostKeyChecking=no ubuntu@${TARGET_SERVER} "
                            cd /home/ubuntu/progetto-finale &&
                            docker build -t dansil/cyber-app:latest . &&
                            docker run --rm -v /var/run/docker.sock:/var/run/docker.sock \\
                                aquasec/trivy:latest image --severity HIGH,CRITICAL dansil/cyber-app:latest || true
                        "
                    '''
                }
            }
        }

        stage('Deploy Remoto') {
            steps {
                sshagent(['deploy-keyubuntu']) {
                    sh '''
                        ssh -o StrictHostKeyChecking=no ubuntu@${TARGET_SERVER} "
                            cd /home/ubuntu/progetto-finale &&
                            docker stop cyber-app || true && docker rm cyber-app || true &&
                            docker run -d --name cyber-app -p 8000:8000 dansil/cyber-app:latest
                        "
                    '''
                }
            }
        }
    }

    post {
        always {
            echo 'Pipeline DevSecOps Laravel completata!'
        }
    }
}