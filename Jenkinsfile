// Part II - containerized build pipeline (Git + Pipeline + Docker Pipeline plugins)
pipeline {
    agent any

    triggers {
        githubPush()          // fired by the GitHub webhook
    }

    options {
        timestamps()
        timeout(time: 10, unit: 'MINUTES')
    }

    environment {
        COMPOSE_FILE    = 'docker-compose.jenkins.yml'
        COMPOSE_PROJECT = 'a2ci'
        APP_URL         = 'http://localhost:8200'
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
                script {
                    // The collaborator who made the push receives the build result.
                    env.PUSHER_EMAIL = sh(script: "git log -1 --pretty=format:%ae", returnStdout: true).trim()
                    env.PUSHER_NAME  = sh(script: "git log -1 --pretty=format:%an", returnStdout: true).trim()
                    env.COMMIT_MSG   = sh(script: "git log -1 --pretty=format:%s",  returnStdout: true).trim()
                }
            }
        }

        stage('Lint PHP in container') {
            steps {
                script {
                    // Docker Pipeline plugin: run the build step inside a throw-away container.
                    docker.image('php:8.2-cli').inside {
                        sh 'for f in app/*.php; do php -l "$f"; done'
                    }
                }
            }
        }

        stage('Deploy with docker compose') {
            steps {
                sh '''
                    docker compose -p ${COMPOSE_PROJECT} -f ${COMPOSE_FILE} pull web || true
                    docker compose -p ${COMPOSE_PROJECT} -f ${COMPOSE_FILE} up -d --force-recreate
                '''
            }
        }

        stage('Verify') {
            steps {
                sh '''
                    for i in $(seq 1 30); do
                        if curl -fs ${APP_URL}/health.php; then echo; exit 0; fi
                        sleep 5
                    done
                    echo "Application did not become healthy"; exit 1
                '''
            }
        }
    }

    post {
        always {
            emailext(
                to: "${env.PUSHER_EMAIL}",
                subject: "[Jenkins] ${env.JOB_NAME} #${env.BUILD_NUMBER}: ${currentBuild.currentResult}",
                mimeType: 'text/plain',
                body: """Hello ${env.PUSHER_NAME},

Your push to ${env.JOB_NAME} has finished building.

Result : ${currentBuild.currentResult}
Commit : ${env.COMMIT_MSG}
Build  : ${env.BUILD_URL}
App    : http://13.51.196.90:8200

-- Jenkins on AWS EC2
"""
            )
        }
    }
}
