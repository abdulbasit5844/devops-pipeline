# devops-pipeline

CSC418 Assignment 2: containerized PHP + MariaDB employee directory.

| Part | Compose file | Web | DB | Containers |
|------|--------------|-----|----|------------|
| I – Docker Hub image | `docker-compose.yml` | `:8100` | `:3307` | `a2_web`, `a2_db` |
| II – Jenkins pipeline | `docker-compose.jenkins.yml` | `:8200` | `:3308` | `a2_ci_web`, `a2_ci_db` |

Pushing to `master` fires the GitHub webhook, Jenkins runs the `Jenkinsfile`, and the build result is emailed to the commit author.
