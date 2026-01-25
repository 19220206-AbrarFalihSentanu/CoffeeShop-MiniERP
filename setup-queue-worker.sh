#!/bin/bash

# Script untuk setup Queue Worker di Linux/Ubuntu Server
# Gunakan script ini untuk production setup

echo "========================================"
echo "Eureka Kopi - Queue Worker Setup"
echo "========================================"

# Get project path
PROJECT_PATH=$(pwd)
echo "Project Path: $PROJECT_PATH"

# Check if supervisor installed
if ! command -v supervisord &> /dev/null; then
    echo "Supervisor not installed. Installing..."
    sudo apt-get update
    sudo apt-get install -y supervisor
fi

# Create supervisor config file
SUPERVISOR_CONF="/etc/supervisor/conf.d/eureka-kopi-queue.conf"
LOGS_DIR="$PROJECT_PATH/storage/logs"

# Create logs directory if not exists
mkdir -p $LOGS_DIR

echo "Creating supervisor config at $SUPERVISOR_CONF"

# Create the config file
sudo tee $SUPERVISOR_CONF > /dev/null <<EOF
[program:eureka-kopi-queue]
process_name=%(program_name)s_%(process_num)02d
command=php $PROJECT_PATH/artisan queue:work database --sleep=3 --tries=3
autostart=true
autorestart=true
numprocs=1
redirect_stderr=true
stdout_logfile=$LOGS_DIR/queue-worker.log
stopasgroup=true
killasgroup=true
user=$(whoami)
directory=$PROJECT_PATH
EOF

echo "Config created successfully!"

# Reload supervisor
echo "Reloading supervisor..."
sudo supervisorctl reread
sudo supervisorctl update

# Start queue worker
echo "Starting queue worker..."
sudo supervisorctl start eureka-kopi-queue:*

# Check status
echo ""
echo "========================================"
echo "Queue Worker Status:"
echo "========================================"
sudo supervisorctl status eureka-kopi-queue:*

echo ""
echo "✅ Setup complete!"
echo ""
echo "Useful commands:"
echo "  Check status: sudo supervisorctl status eureka-kopi-queue:*"
echo "  View logs: tail -f $LOGS_DIR/queue-worker.log"
echo "  Stop: sudo supervisorctl stop eureka-kopi-queue:*"
echo "  Start: sudo supervisorctl start eureka-kopi-queue:*"
echo "  Restart: sudo supervisorctl restart eureka-kopi-queue:*"
