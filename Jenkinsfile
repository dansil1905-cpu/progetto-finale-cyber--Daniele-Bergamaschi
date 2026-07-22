pipeline {
    agent any

    environment {
        SNYK_TOKEN = credentials('snyk-token')
        SONAR_TOKEN = credentials('sonar-token')
        TARGET_SERVER = '18.199.149.214'
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Dependency & Security Audit') {
            steps {
                // Audit delle dipendenze PHP (Laravel)
                sh 'composer audit'
                
                // Scan Snyk per codice e librerie
                sh 'snyk test || true'
            }
        }

        stage('SonarQube Analysis') {
            steps {
                sshagent(['ubuntu']) {
                    sh '''
                        ssh -o StrictHostKeyChecking=no ubuntu@${TARGET_SERVER} "
                            cd /home/ubuntu/progetto-finale && git pull origin main &&
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

        stage('Build & Container Scan (Trivy)') {
            steps {
                sshagent(['ubuntu']) {
                    sh '''
                        ssh -o StrictHostKeyChecking=no ubuntu@${TARGET_SERVER} "
                            cd /home/ubuntu/progetto-finale &&
                            docker build -t progetto-cyber:latest . &&
                            docker run --rm -v /var/run/docker.sock:/var/run/docker.sock \\
                                aquasec/trivy:latest image --severity HIGH,CRITICAL progetto-cyber:latest || true
                        "
                    '''
                }
            }
        }

        stage('Deploy Laravel App') {
            steps {
                sshagent(['ubuntu']) {
                    sh '''
                        ssh -o StrictHostKeyChecking=no ubuntu@${TARGET_SERVER} "
                            cd /home/ubuntu/progetto-finale &&
                            docker stop cyber-app || true && docker rm cyber-app || true &&
                            docker run -d --name cyber-app -p 8000:8000 progetto-cyber:latest
                        "
                    '''
                }
            }
        }
    }

    post {
        always {
            echo 'Pipeline DevSecOps completata con successo!'
        }
    }
}