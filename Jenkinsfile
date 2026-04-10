pipeline {
    agent any

    environment {
        ECR_REPO = "428185450398.dkr.ecr.eu-north-1.amazonaws.com/feedback-app-repo"
        IMAGE_TAG = "latest"
        AWS_DEFAULT_REGION = "eu-north-1"
    }

    stages {

        stage('Clone Code') {
            steps {
                git 'https://github.com/priyanshijat/Feedback-application.git'
            }
        }

        stage('Build Docker Image') {
            steps {
                sh 'docker build -t feedbackapp .'
            }
        }

        stage('Login to ECR') {
            steps {
                sh '''
                aws ecr get-login-password --region $AWS_DEFAULT_REGION | \
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

        stage('Deploy to EC2') {
            steps {
                sh '''
                docker stop feedback-app || true
                docker rm feedback-app || true

                docker pull $ECR_REPO:latest

                docker run -d -p 8002:80 \
                --name feedback-app \
                --env-file /home/ubuntu/.env \
                $ECR_REPO:latest
                '''
            }
        }
    }
}
