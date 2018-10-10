echo "Updating and Upgrading"
sudo apt update
sudo apt upgrade

echo "installing tools: [ Git, Vim, Docker, & Tmux]"
sudo apt install -y git vim tmux
sudo apt-get remove docker docker-engine docker.io
sudo apt-get install -y\
    apt-transport-https \
    ca-certificates \
    curl \
    software-properties-common
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
sudo apt-get update
sudo apt-get install -y docker-ce
sudo groupadd docker
sudo usermod -aG docker $USER

echo "Checking Docker installation..."
docker run hello-world

echo "A reboot should be run after this script"

echo "###"
echo "Cloning ADAPT repo"
git clone https://github.com/Michael-Rhodes/ADAPT.git
sudo sysctl -w vm.max_map_count=262144

echo "Installing Docker-Compose from Docker website.."
sudo curl -L "https://github.com/docker/compose/releases/download/1.22.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
echo "Making docker-compose executable"
sudo chmod +x /usr/local/bin/docker-compose
"Installing bash completion packages"
sudo curl -L https://raw.githubusercontent.com/docker/compose/1.22.0/contrib/completion/bash/docker-compose -o /etc/bash_completion.d/docker-compose
"Refreshing bash config"
source ~/.bash_profile

echo "Checking the Installation..."
docker-compose --version

