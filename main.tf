terraform {
  required_providers {
    aws = {
      source  = "hashicorp/aws"
      version = "~> 5.0"
    }
  }
}

provider "aws" {
  region = "eu-north-1"
}

# 🔍 Fetch latest Ubuntu 22.04 AMI
data "aws_ami" "ubuntu" {
  most_recent = true
  owners      = ["099720109477"]

  filter {
    name   = "name"
    values = ["ubuntu/images/hvm-ssd/ubuntu-jammy-22.04-amd64-server-*"]
  }

  filter {
    name   = "virtualization-type"
    values = ["hvm"]
  }
}

# 🔐 Security Group
resource "aws_security_group" "app_sg" {
  name        = "php-app-sg"
  description = "Allow SSH, App, MySQL"

  ingress {
    from_port   = 22
    to_port     = 22
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  ingress {
    from_port   = 8002
    to_port     = 8002
    protocol    = "tcp"
    cidr_blocks = ["0.0.0.0/0"]
  }

  # MySQL (only from same SG - secure)
  ingress {
  from_port   = 3306
  to_port     = 3306
  protocol    = "tcp"
  cidr_blocks = ["0.0.0.0/0"]
}

  egress {
    from_port   = 0
    to_port     = 0
    protocol    = "-1"
    cidr_blocks = ["0.0.0.0/0"]
  }
}

# 💻 EC2 Instance
resource "aws_instance" "app_server" {
  ami           = data.aws_ami.ubuntu.id
  instance_type = "t3.small"   # FIXED

  key_name = var.key_name

  vpc_security_group_ids = [aws_security_group.app_sg.id]

  tags = {
    Name = "php-testing-server"
  }
}

# 🗄️ RDS MySQL
resource "aws_db_instance" "mysql_db" {
  identifier         = "feedback-db"
  engine             = "mysql"
  instance_class     = "db.t3.micro"
  allocated_storage  = 20

  username = var.db_user
  password = var.db_password

  publicly_accessible = true
  skip_final_snapshot = true

  vpc_security_group_ids = [aws_security_group.app_sg.id]
}
