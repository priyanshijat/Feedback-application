#!/bin/bash

# Stop old container
docker stop feedback-app || true
docker rm feedback-app || true

# Pull latest image
docker pull <YOUR_ECR_URI>:latest

# Run new container
docker run -d -p 8002:80 \
  --name feedback-app \
  --env-file /home/ubuntu/.env \
  428185450398.dkr.ecr.eu-north-1.amazonaws.com/feedback-app-repo:latest
