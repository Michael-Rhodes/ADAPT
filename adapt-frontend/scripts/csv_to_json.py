import csv
import json

csv_file = open('testdata.csv', 'r')
json_file = open('testdata.json', 'w')

fieldnames = ("APT Group Name", "Matching Events", "Total Events", "% of Events", "Matching TTPs", "Available TTPs", "% of TTPs", "Coverage", "% of Coverage", "Final Value")

reader = csv.DictReader(csv_file, fieldnames)
for row in reader:
    json.dump(row, json_file)
    json_file.write(', \n')



