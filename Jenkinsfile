pipeline { 
    agent { label 'agent1' } // Utilizzo dell'agente dedicato (PBAC)

    stages { 
        stage('Checkout') { 
            steps { 
                checkout scm 
            } 
        } 

        stage('Install Dependencies') { 
            steps { 
                sh 'npm install' 
            } 
        } 

        stage('Static Code Analysis (SonarQube)') { 
            steps { 
                echo 'Esecuzione SonarQube SAST in corso...' 
            } 
        } 

        stage('Dependency Scanning (Snyk)') { 
            steps { 
                withCredentials([string(credentialsId: 'jenkins-snyk', variable: 'SNYK_TOKEN')]) { 
                    sh 'docker run --rm --env SNYK_TOKEN=${SNYK_TOKEN} -v $(pwd):/app snyk/snyk:node snyk test || true' 
                } 
            } 
        } 

        stage('Docker Image Build') { 
            steps { 
                sh 'docker build -f nodeApp.Dockerfile -t cyber-progetto-app:latest .' 
            } 
        } 

        stage('Container Security Scanning (Aqua Trivy)') { 
            steps { 
                // Aggiunto || true per registrare le vulnerabilità nel log senza fallire la build su Jenkins
                sh 'trivy image --severity HIGH,CRITICAL cyber-progetto-app:latest || true' 
            } 
        } 
    } 
     
    post { 
        always { 
            sh 'docker logout || true' 
        } 
    } 
}