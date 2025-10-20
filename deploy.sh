#!/bin/bash
set -e

echo "==> Building unicripter container..."
docker-compose build

echo "==> Extracting assets from container to host..."
docker run --rm -v "$(pwd)/public:/target" unicripter:latest sh -c "cp -r /var/www/html/public/* /target/"

echo "==> Restarting container..."
docker-compose down
docker-compose up -d

echo "==> Waiting for container to be healthy..."
sleep 3

echo "==> Deployment complete!"
echo "==> App available at: https://unicripter.dxicode.com"
