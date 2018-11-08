# Team Meeting Notes

## Week 2 Notes:

- We want to generate **signatures** to detect APT behavior in a way that is not chain-like
- Project first steps:
	- Figure out the enviroment we need to build
	- Setup a ton of monitoring in hopes of figuring out what happens and when
- **Ultiamte Goal** --> Automate this detection
- [Splunk Academic Link](https://www.splunk.com/en_us/solutions/industries/higher-education/academic-licenses.html)
- We decided to go with ELK (Elastic Search / Logstash / Kibana )
- **PAPER** --> artifacts + findings + configuration for detection
- We are leaning heavily on the windows enviroment

## Week 3 Notes:

- Thoughts on our APT detection approach
	- Two clients are used for lateral movement [domain controller, older hosts for remote code execution ]
- Thoughts on intial access:
	- Access via SMB? (is it logged?)
- *Powershell* on Windows 7 and up, XP seems dificult to get to work
- [FIREEYE APT PAGE ](https://www.fireeye.com/current-threats/apt-groups.html)
- :mega: **This Week's Action Items** :mega:
	- [ ] Progress report

## Week 4 Notes:

- ELK is the platform we have committed to for logging and connecting to the API
- US nation state activity is elminated from scope on this project
- We want to identify 2 APT groups per person
- We **need** to integrate the ATT&CK framework with ELK
- **DRAFT 1** is should be started. Looking at the intro & background
- :mega: **This Week's Action Items** :mega:
	- [ ] First Rough Draft
	- [ ] Peer Review Doc
	- [ ] Schedule Mtg. with Randy
	- [ ] Research 2 APT groups per person
	- [ ] Finalize progress report 1

## Week 5 Notes:

- Inital meeting with Randy had to be rescheduled for the following week

## Week 6 Notes:

- Next steps:
	- Build a probabilistic model that can graph the states between detection
	- Map the connections to logins and see if theirs noteable behavior
	- Workflow Development:
		- Have Alerts from SEIM
		- Use Decision Tool (Operating on prob. model)
		- Query API (homemade) for to input/output from DB
- Images:
	- Windows 7, Server 2016 (Domain Controller), Ubuntu (Docker w/ ELK)

## Week 7 Notes:

- State Machine & Data Analytics
	- When we superimpose the behvaiors onto the map, what does it look like on the graph?
	- Taking any set of paths, does it closely resemble a certain type of action?
- **Testing Data** - can we detect known and existing attack paths?
- Given **new** paths, how does it perform?
	- Based on these tweaks how does it classify the results?
- **Graph**
	- MITRE CTI Format - taking STIX format and dumping it into DB
	- Interlocking and weaving techniques into the application layer
	- We want to avoid end tuples...
		- N("END") tuple, ex) x,y,z... are coordinates with an "n" number of dimensions
			- Sometimes refered to as a relation
- **RLES Deployment**
	- Come up with stock VMs and submit a template request
	- Anticipated problem: IP addressing, its a NAT network
	- Proposed Solution: Create a private network with pfSense and bridge it

- In the VMs:
	- How are we going to get data out of SEIM and send to API?
		- Idea 1: do it via POST/GET requests
		- Idea 2: use SMTP to send email with "updates"... sending the logs that need to be collected
			- Requires [ setting up an email server, connecting SEIM to emails...]
 	- We decided on IDEA 1! :check:

- :mega: **This Week's Action Items** :mega:
	- [ ]  How do we get data out of the SEIM --> hopeful via POST/GET requests
	- [ ]  Building the API (Python/or/PHP) to collect data
	- [ ]  Send an email to IT about RLES acess for enviroment

## Week 8 Notes:
 
 - #### :mega: **Team Progress Report** :mega:
	- :white_check_mark: RLES access granted
	- :white_check_mark: Watcher is up and running
	- :thumbsup: Model is starting to take shape
 		- Working around duplicate traits
    		- Suggestion: Scale back to specific traits
		- Still working on figuring out the possible transitions
 - Database Tables:
 	- Event ID (primary key)
 	- APT
 	- Event Name
 	- Master Log
 	- [extra] non-APT events?
 - :mega: **This Week's Action Items** :mega:
	- [ ] Build an API that takes log data and passes to tool 
	- [ ] A tool that takes log data and makes APT determinations
	- [ ] Web console for interacting with tools
	- [ ] Schedule another meeting with Randy
		- Purpose: Progress update, planning, and see if he wants to have a future update call
 - #### Possible Future Works:
	- Determining baseline behavior

## Week 9 Notes:

 - :mega: **Team Progress Report** :mega:
	- :white_check_mark: We can simulate an APT
	- :white_check_mark: We can send logs to the API
	- :white_check_mark: We can query API for DB storage
 - We need to:
	- Start working on the final paper draft
		- For example [ incorpate diagrams, new methodology approach, add to background ]
 - Deploying in test network
	- Network is behind a NAT
	- We will be using ansible playbooks for setting up collection in Windows
 
**Network Diagram Notes:**

```   
   NAT (The internet) -> collection -> Edge Router (w/ virtual IPs in a DMZ)
  					^^^
   Edge Router---------------------------| 
	   |
	    ~~> DMZ -> Internal Router (IR)
    		 |
		 ~> Technology Information System (TIS)
		 |		|
		 |		~> Active Directory
		 |
		 ~> Enterprise Network
		 |	   |
		 |	   ~> Windows 10
		 |
		 ~> Garage
		 |
		 ~> Developmention	
		 |
		 ~> Faculty Segment (out of scope for students)
		 	   |
			   ~> Windows RM (Remote Management)			**Windows RM** - similar to SSH; will be used with ansible for pushing out scripts

```


##Week 10 Notes

## :mega: **Team Progress Report** :mega:
	- [issue] ELK is getting 1500 hits in 10 mins, we think it has to do with credential dumping, 
		- however, it only happens on one of the two vms, not both
	- Web console is started, main landing page is created 
	- Model has collected new data for analysis

#### Meeting Notes
	- Deploying to Metropolis Transit is being pushed back to next week. 
		- We want to deploy a few ansible scripts for testing it out in the deployment of vms, just running the playbook
	- Windows 7 and 10 Ansible scripts should be the same
		- The scripts are being deploying with windowsRM
	- API status: can accept data, test enviroment setup
		- intregration from DB and tool to (between) the API works
	- Model is working
		- Except for the undefined behavior being added
		- See earlier notes about probability formulas being used
		- One outlier - POSEIDON GROUP
			- they match 97.8% of events we have seen
			- they are hyper inflated because of the low number of availble TTPs we have
			- Normally flushed out by the model, but because of the credential dumping we think it has played a role
			- the problem is that Poseidon doesn't have a lot of TTPs to match on and credential dumping is weighted heavily
		- Time variance is not currently part of the model
			- We may want to add in a time range during various periods
				- ex) maybe on fridays we see a spike, but known maitenance happens then so its false positive
					
	- sysmon phones data back to the SEIM, and SEIM forwards data to API
		- sysmon knows its a partically TTP
		- if rule name exists, send over to SEIM

## :mega: **This Week's Action Items** :mega:
	- Let's deploy a bunch of windows 10 items, with no special configuration outside of the script to see if it fixes log issue
		- We think its an artifact of one of the configurations on the box
	- Add existing log data to web app for the demo
	
## Week 11 Notes

## :mega: **Team Progress Report** :mega:
	- Met with Prof. Pelletier about our capstone progress
	- Web app log page now shows results generated from last test
	- Model is being refined; recieved feedback on how to improve the probabilistic model

#### Meeting Notes
	- Discussed the process for new data collection in Metropolis Transit
	- Discussed the possible colloboration of our project with another student working on APT threats
	- Discussed future deployment processes and how we will modify the RLES infrastructure template
## :mega: **This Week's Action Items** :mega:
	- redeploy the infrastrucre to collect new data
	- analysis resutls to see how we can improve the probabilistic model
	- build an API  for making stat queries 
		- build an Angular service for querying said API
