pipeline {
    agent any

    environment {
        ECR_REPO = "123456789012.dkr.ecr.eu-north-1.amazonaws.com/feedback-app-repo"
        REGION = "eu-north-1"
        IMAGE_NAME = "feedbackapp"
    }

    stages {

        stage('Build Docker Image') {
            steps {
                sh 'docker build -t $IMAGE_NAME:latest .'
            }
        }

        stage('Scan Image with Trivy') {
            steps {
                sh '''
                trivy image --severity HIGH,CRITICAL --exit-code 1 $IMAGE_NAME:latest || true
                '''
            }
        }

        stage('Login to ECR') {
            steps {
                withCredentials([
                    string(credentialsId: 'aws-access-key', variable: 'AWS_ACCESS_KEY_ID'),
                    string(credentialsId: 'aws-secret-key', variable: 'AWS_SECRET_ACCESS_KEY')
                ]) {
                    sh '''
                    export AWS_ACCESS_KEY_ID=$AWS_ACCESS_KEY_ID
                    export AWS_SECRET_ACCESS_KEY=$AWS_SECRET_ACCESS_KEY

                    aws ecr get-login-password --region $REGION | \
                    docker login --username AWS --password-stdin $ECR_REPO
                    '''
                }
            }
        }

        stage('Tag Image') {
            steps {
                sh '''
                docker tag $IMAGE_NAME:latest $ECR_REPO:latest
                '''
            }
        }

        stage('Push to ECR') {
            steps {
                sh '''
                docker push $ECR_REPO:latest
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
                --restart always \
                $ECR_REPO:latest
                '''
            }
        }
    }
}
