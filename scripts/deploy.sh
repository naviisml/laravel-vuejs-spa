echo "Deploying..."

# Pull the new changes from GitHub
git pull

# Build the image with the new changes
docker build . -t 42dashboard

# Shut down the existing containers
docker-compose down

# Start the new containers
docker-compose up -d

# Shut down the existing containers
docker-compose exec app ./scripts/build.sh

echo "Deploying complete!"
