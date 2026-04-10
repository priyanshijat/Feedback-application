pipeline {
    agent any

    stages {

        stage('Build Docker Image') {
            steps {
                sh 'docker build -t feedbackapp .'
            }
        }

        stage('Deploy') {
            steps {
                sh '''
                docker stop feedback-app || true
                docker rm feedback-app || true

                docker run -d -p 8002:80 \
                --name feedback-app \
                --env-file /var/lib/jenkins/.env \
                feedbackapp
                '''
            }
        }  

    }
}
