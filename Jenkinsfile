pipeline {
    agent any

    environment {
        ECR_REPO = "123456789012.dkr.ecr.eu-north-1.amazonaws.com/feedback-app-repo"
        REGION = "eu-north-1"
    }

    stages {

        stage('Build Docker Image') {
            steps {
                sh 'docker build -t feedbackapp .'
            }
        }

        stage('Login to ECR') {
            steps {
                sh '''
                aws ecr get-login-password --region $REGION | \
                docker login --username AWS --password-stdin $ECR_REPO
                '''
            }
        }

        stage('Tag Image') {
            steps {
                sh 'docker tag feedbackapp:latest $ECR_REPO:latest'
            }
        }

        stage('Push to ECR') {
            steps {
                sh 'docker push $ECR_REPO:latest'
            }
        }
         
        stage('Scan Image with Trivy') {
            steps {
                sh '''
                trivy image --severity HIGH,CRITICAL --exit-code 1 feedbackapp:latest || true
                '''
            }
        }
        stage('Deploy') {
            steps {
                sh '''
                docker stop feedback-app || true
                docker rm feedback-app || true

                docker pull $ECR_REPO:latest

                docker run -d -p 8002:80 \
                --name feedback-app \
                --env-file /var/lib/jenkins/.env \
                $ECR_REPO:latest
                '''
            }
        }
    }
}
