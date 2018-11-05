import csv

input_file = open('testdata.csv', 'r')

for line in input_file:
    row = line.split(",")
    group_name = row[0]
    match_event = row[1]
    total_event = row[2]
    percent_of_event = row[3]
    matching_ttps = row[4]
    available_ttps = row[5]
    percent_of_ttps = row[6]
    coverage = row[7]
    percent_of_coverage = row[8]
    final_value = row[9]
    td_open = "<td> "
    td_close= "</td>"
    print("<tr class=\"table-primary\">")
    print("<th scope=\"row\"> " + group_name + " </th>")
    print(td_open + match_event + td_close)
    print(td_open + total_event  + td_close)
    print(td_open + percent_of_event + td_close)
    print(td_open + matching_ttps + td_close)
    print(td_open + available_ttps + td_close)
    print(td_open + percent_of_ttps + td_close)
    print(td_open + coverage + td_close)
    print(td_open + percent_of_coverage + td_close)
    print(td_open + final_value + td_close)
    print("</tr>")
