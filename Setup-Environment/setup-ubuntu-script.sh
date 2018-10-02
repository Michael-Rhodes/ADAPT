echo "Updating and Upgrading"
sudo apt update
sudo apt upgrade

echo "installing tools: [ Git, Vim, Docker, & Tmux]"
sudo apt install git vim docker tmux

echo "A reboot should be run after this script"

echo "###"
echo "Cloning ADAPT repo"
git clone https://github.com/Michael-Rhodes/ADAPT.git

echo "Installing Docker-Compose from Docker website.."
sudo curl -L "https://github.com/docker/compose/releases/download/1.22.0/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
echo "Making docker-compose executable"
sudo chmod +x /usr/local/bin/docker-compose
"Installing bash completion packages"
sudo curl -L https://raw.githubusercontent.com/docker/compose/1.22.0/contrib/completion/bash/docker-compose -o /etc/bash_completion.d/docker-compose
"Refreshing bash config"
source ~/.bash_profile

"Checking the Installation..."
docker-compose --version
"Does the docker version display? [y/n]"
read user_input
if [ user_input = "y"]; then
	echo "Installation Successful!"
else
	echo "Installation Failed..."
fi

