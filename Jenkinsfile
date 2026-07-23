pipeline {
    agent any

    environment {
        SNYK_TOKEN = credentials('snyk-token')
        SONAR_TOKEN = credentials('SONAR_AUTH_TOKEN')
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
                sshagent(['ubuntu']) {
                    sh '''
                        ssh -o StrictHostKeyChecking=no ubuntu@${TARGET_SERVER} "
                            if [ ! -d '/home/ubuntu/progetto-finale' ]; then
                                git clone https://github.com/dansil1905-cpu/progetto-finale-cyber--Daniele-Bergamaschi.git /home/ubuntu/progetto-finale;
                            else
                                cd /home/ubuntu/progetto-finale && git fetch origin && git reset --hard origin/main;
                            fi &&
                            cd /home/ubuntu/progetto-finale &&
                            echo 'Esecuzione Snyk Scan via Docker...' &&
                            docker run --rm -v /home/ubuntu/progetto-finale:/app -e SNYK_TOKEN=${SNYK_TOKEN} snyk/snyk:php snyk test --severity-threshold=high || true
                        "
                    '''
                }
            }
        }

        stage('SonarQube Code Analysis') {
            steps {
                sshagent(['ubuntu']) {
                    sh '''
                        ssh -o StrictHostKeyChecking=no ubuntu@${TARGET_SERVER} "
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
                sshagent(['ubuntu']) {
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
                sshagent(['ubuntu']) {
                    sh '''
                        ssh -o StrictHostKeyChecking=no ubuntu@${TARGET_SERVER} "
                            cd /home/ubuntu/progetto-finale &&
                            docker stop cyber-app || true && docker rm cyber-app || true &&
                            docker run -d --name cyber-app -p 8000:8000 dansil/cyber-app:latest || true
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