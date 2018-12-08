 ## Ubuntu VM Setup
Installs git, docker, and docker-compose.

 1. Make the script executable

 ```
 chmod +x /Setup-Environment/setup-ubuntu-script.sh
 ```

 2. In **ADAPT/** directory, run this script:

 ```
 ./Setup-Environment/setup-ubuntu-script.sh
 ```

 **If** the docker-compose version is **visible** after running the script, then the tools built successfully.

## Ansible
This directory contains playbooks to install Sysmon and Winlogbeat on Windows hosts. Before running, update ```elk_ip``` in ```win_sysmon_winlogbeat.yml``` to the address and port of the Logstash listener.
