# ELK Docker Container
This container retrieves the logs from WinlogBeat running on the Windows Hosts and forwards them to the data injestion API. Before running this container, add the IP address or hostname of the API to ```30-output.conf```.

## How to run:
1. Update ```30-output.conf```
2. Elasticsearch, mmapfs, requires the ability to create many memory-mapped areas. If you are running Linux and not running on a production server, the ```vm.max_map_count``` may not be high enough for Elasticsearch to use mmap effectively. Elasticsearch requires at least 262,144 memory-mapped areas. To ensure the container runs properly, run ```sudo sysctl -w vm.max_map_count=262144```
3. Run ```docker-compose up```
