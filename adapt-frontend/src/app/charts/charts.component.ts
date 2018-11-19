import { Component, OnInit } from '@angular/core';
import { HttpClient, HttpResponse} from '@angular/common/http';
import { Observable } from 'rxjs';

export interface LogData {
  // Matching Events	Total Events	% of Events	Matching TTPs	Available TTPs	% of TTPs	Coverage	% of Coverage	Final value
  // I changed order:: final value moved up to second col.
  group: string;
  final_value: string;
  matching_events: string;
  total_events: string;
  percent_of_events: string;
  matching_ttps: string;
  available_ttps: string;
  percent_of_ttps: string;
  coverage: string;
  percent_of_coverage: string;
  
}
const LOG_DATA: LogData[] = [
  {"percent_of_ttps": " 16.22", "matching_events": " 19454", "total_events": " 19953", "matching_ttps": "  12", "percent_of_events": " 97.5", "percent_of_coverage": " 54.55", "coverage": " 6/11", "final_value": " 8.62", "group": "Lazarus Group", "available_ttps": "74"}, 
  {"percent_of_ttps": " 16.18", "matching_events": " 19418", "total_events": " 19953", "matching_ttps": "  11", "percent_of_events": " 97.32", "percent_of_coverage": " 54.55", "coverage": " 6/11", "final_value": " 8.59", "group": "menuPass", "available_ttps": "68"}, 
  {"percent_of_ttps": " 12.64", "matching_events": " 19445", "total_events": " 19953", "matching_ttps": "  11", "percent_of_events": " 97.45", "percent_of_coverage": " 54.55", "coverage": " 6/11", "final_value": " 6.72", "group": "APT28", "available_ttps": "87"}, 
  {"percent_of_ttps": " 18.97", "matching_events": " 19562", "total_events": " 19953", "matching_ttps": "  11", "percent_of_events": " 98.04", "percent_of_coverage": " 54.55", "coverage": " 6/11", "final_value": " 10.14", "group": "Patchwork", "available_ttps": "58"}, 
  {"percent_of_ttps": " 23.81", "matching_events": " 19368", "total_events": " 19953", "matching_ttps": "  10", "percent_of_events": " 97.07", "percent_of_coverage": " 54.55", "coverage": " 6/11", "final_value": " 12.61", "group": "Deep Panda", "available_ttps": "42"}, 
  {"percent_of_ttps": " 14.08", "matching_events": " 19539", "total_events": " 19953", "matching_ttps": "  10", "percent_of_events": " 97.93", "percent_of_coverage": " 54.55", "coverage": " 6/11", "final_value": " 7.52", "group": "APT29", "available_ttps": "71"}, 
  {"percent_of_ttps": " 18.52", "matching_events": " 19592", "total_events": " 19953", "matching_ttps": "  10", "percent_of_events": " 98.19", "percent_of_coverage": " 54.55", "coverage": " 6/11", "final_value": " 9.92", "group": "Threat Group-3390", "available_ttps": "54"}, 
  {"percent_of_ttps": " 14.93", "matching_events": " 19398", "total_events": " 19953", "matching_ttps": "  10", "percent_of_events": " 97.22", "percent_of_coverage": " 45.45", "coverage": " 5/11", "final_value": " 6.6", "group": "OilRig", "available_ttps": "67"}, 
  {"percent_of_ttps": " 15", "matching_events": " 19207", "total_events": " 19953", "matching_ttps": "  9", "percent_of_events": " 96.26", "percent_of_coverage": " 54.55", "coverage": " 6/11", "final_value": " 7.88", "group": "Leviathan", "available_ttps": "60"}, 
  {"percent_of_ttps": " 15.52", "matching_events": " 270", "total_events": " 19953", "matching_ttps": "  9", "percent_of_events": " 1.35", "percent_of_coverage": " 45.45", "coverage": " 5/11", "final_value": " 0.1", "group": "Turla", "available_ttps": "58"}, 
  {"percent_of_ttps": " 21.95", "matching_events": " 19370", "total_events": " 19953", "matching_ttps": "  9", "percent_of_events": " 97.08", "percent_of_coverage": " 45.45", "coverage": " 5/11", "final_value": " 9.69", "group": "Dragonfly 2.0", "available_ttps": "41"}, 
  {"percent_of_ttps": " 14.04", "matching_events": " 19350", "total_events": " 19953", "matching_ttps": "  8", "percent_of_events": " 96.98", "percent_of_coverage": " 45.45", "coverage": " 5/11", "final_value": " 6.19", "group": "APT3", "available_ttps": "57"}, 
  {"percent_of_ttps": " 20", "matching_events": " 19102", "total_events": " 19953", "matching_ttps": "  8", "percent_of_events": " 95.73", "percent_of_coverage": " 45.45", "coverage": " 5/11", "final_value": " 8.7", "group": "Axiom", "available_ttps": "40"}, 
  {"percent_of_ttps": " 28", "matching_events": " 19124", "total_events": " 19953", "matching_ttps": "  7", "percent_of_events": " 95.85", "percent_of_coverage": " 36.36", "coverage": " 4/11", "final_value": " 9.76", "group": "Magic Hound", "available_ttps": "25"}, 
  {"percent_of_ttps": " 23.33", "matching_events": " 19301", "total_events": " 19953", "matching_ttps": "  7", "percent_of_events": " 96.73", "percent_of_coverage": " 45.45", "coverage": " 5/11", "final_value": " 10.26", "group": "APT1", "available_ttps": "30"}, 
  {"percent_of_ttps": " 31.82", "matching_events": " 282", "total_events": " 19953", "matching_ttps": "  7", "percent_of_events": " 1.41", "percent_of_coverage": " 45.45", "coverage": " 5/11", "final_value": " 0.2", "group": "Honeybee", "available_ttps": "22"}, 
  {"percent_of_ttps": " 33.33", "matching_events": " 186", "total_events": " 19953", "matching_ttps": "  7", "percent_of_events": " 0.93", "percent_of_coverage": " 36.36", "coverage": " 4/11", "final_value": " 0.11", "group": "DarkHydrus", "available_ttps": "21"}, 
  {"percent_of_ttps": " 23.33", "matching_events": " 356", "total_events": " 19953", "matching_ttps": "  7", "percent_of_events": " 1.78", "percent_of_coverage": " 45.45", "coverage": " 5/11", "final_value": " 0.19", "group": "admin@338", "available_ttps": "30"}, 
  {"percent_of_ttps": " 17.95", "matching_events": " 551", "total_events": " 19953", "matching_ttps": "  7", "percent_of_events": " 2.76", "percent_of_coverage": " 45.45", "coverage": " 5/11", "final_value": " 0.23", "group": "TA459", "available_ttps": "39"}, 
  {"percent_of_ttps": " 25.93", "matching_events": " 491", "total_events": " 19953", "matching_ttps": "  7", "percent_of_events": " 2.46", "percent_of_coverage": " 45.45", "coverage": " 5/11", "final_value": " 0.29", "group": "RTM", "available_ttps": "27"}, 
  {"percent_of_ttps": " 16.67", "matching_events": " 19390", "total_events": " 19953", "matching_ttps": "  7", "percent_of_events": " 97.18", "percent_of_coverage": " 54.55", "coverage": " 6/11", "final_value": " 8.83", "group": "FIN7", "available_ttps": "42"}, 
  {"percent_of_ttps": " 12.96", "matching_events": " 133", "total_events": " 19953", "matching_ttps": "  7", "percent_of_events": " 0.67", "percent_of_coverage": " 45.45", "coverage": " 5/11", "final_value": " 0.04", "group": "Elderwood", "available_ttps": "54"}, 
  {"percent_of_ttps": " 25.93", "matching_events": " 19078", "total_events": " 19953", "matching_ttps": "  7", "percent_of_events": " 95.61", "percent_of_coverage": " 54.55", "coverage": " 6/11", "final_value": " 13.52", "group": "Molerats", "available_ttps": "27"}, 
  {"percent_of_ttps": " 16.22", "matching_events": " 487", "total_events": " 19953", "matching_ttps": "  6", "percent_of_events": " 2.44", "percent_of_coverage": " 45.45", "coverage": " 5/11", "final_value": " 0.18", "group": "Dark Caracal", "available_ttps": "37"}, 
  {"percent_of_ttps": " 19.35", "matching_events": " 19017", "total_events": " 19953", "matching_ttps": "  6", "percent_of_events": " 95.31", "percent_of_coverage": " 36.36", "coverage": " 4/11", "final_value": " 6.71", "group": "Ke3chang", "available_ttps": "31"}, 
  {"percent_of_ttps": " 25", "matching_events": " 293", "total_events": " 19953", "matching_ttps": "  6", "percent_of_events": " 1.47", "percent_of_coverage": " 36.36", "coverage": " 4/11", "final_value": " 0.13", "group": "Naikon", "available_ttps": "24"}, 
  {"percent_of_ttps": " 19.35", "matching_events": " 19232", "total_events": " 19953", "matching_ttps": "  6", "percent_of_events": " 96.39", "percent_of_coverage": " 36.36", "coverage": " 4/11", "final_value": " 6.78", "group": "Strider", "available_ttps": "31"}, 
  {"percent_of_ttps": " 27.27", "matching_events": " 371", "total_events": " 19953", "matching_ttps": "  6", "percent_of_events": " 1.86", "percent_of_coverage": " 45.45", "coverage": " 5/11", "final_value": " 0.23", "group": "Lotus Blossom", "available_ttps": "22"}, 
  {"percent_of_ttps": " 21.43", "matching_events": " 352", "total_events": " 19953", "matching_ttps": "  6", "percent_of_events": " 1.76", "percent_of_coverage": " 36.36", "coverage": " 4/11", "final_value": " 0.14", "group": "DragonOK", "available_ttps": "28"}, 
  {"percent_of_ttps": " 17.65", "matching_events": " 19352", "total_events": " 19953", "matching_ttps": "  6", "percent_of_events": " 96.99", "percent_of_coverage": " 36.36", "coverage": " 4/11", "final_value": " 6.22", "group": "FIN8", "available_ttps": "34"}, 
  {"percent_of_ttps": " 31.58", "matching_events": " 145", "total_events": " 19953", "matching_ttps": "  6", "percent_of_events": " 0.73", "percent_of_coverage": " 36.36", "coverage": " 4/11", "final_value": " 0.08", "group": "APT19", "available_ttps": "19"}, 
  {"percent_of_ttps": " 23.08", "matching_events": " 129", "total_events": " 19953", "matching_ttps": "  6", "percent_of_events": " 0.65", "percent_of_coverage": " 45.45", "coverage": " 5/11", "final_value": " 0.07", "group": "PittyTiger", "available_ttps": "26"}, 
  {"percent_of_ttps": " 30", "matching_events": " 230", "total_events": " 19953", "matching_ttps": "  6", "percent_of_events": " 1.15", "percent_of_coverage": " 45.45", "coverage": " 5/11", "final_value": " 0.16", "group": "Rancor", "available_ttps": "20"}, 
  {"percent_of_ttps": " 13.33", "matching_events": " 19060", "total_events": " 19953", "matching_ttps": "  6", "percent_of_events": " 95.52", "percent_of_coverage": " 54.55", "coverage": " 6/11", "final_value": " 6.95", "group": "APT37", "available_ttps": "45"}, 
  {"percent_of_ttps": " 17.14", "matching_events": " 19054", "total_events": " 19953", "matching_ttps": "  6", "percent_of_events": " 95.49", "percent_of_coverage": " 45.45", "coverage": " 5/11", "final_value": " 7.44", "group": "PLATINUM", "available_ttps": "35"}, 
  {"percent_of_ttps": " 33.33", "matching_events": " 19016", "total_events": " 19953", "matching_ttps": "  6", "percent_of_events": " 95.3", "percent_of_coverage": " 45.45", "coverage": " 5/11", "final_value": " 14.44", "group": "Dragonfly", "available_ttps": "18"}, 
  {"percent_of_ttps": " 20", "matching_events": " 324", "total_events": " 19953", "matching_ttps": "  6", "percent_of_events": " 1.62", "percent_of_coverage": " 45.45", "coverage": " 5/11", "final_value": " 0.15", "group": "Cobalt Group", "available_ttps": "30"}, 
  {"percent_of_ttps": " 20", "matching_events": " 327", "total_events": " 19953", "matching_ttps": "  6", "percent_of_events": " 1.64", "percent_of_coverage": " 36.36", "coverage": " 4/11", "final_value": " 0.12", "group": "Dust Storm", "available_ttps": "30"}, 
  {"percent_of_ttps": " 17.14", "matching_events": " 432", "total_events": " 19953", "matching_ttps": "  6", "percent_of_events": " 2.17", "percent_of_coverage": " 27.27", "coverage": " 3/11", "final_value": " 0.1", "group": "APT32", "available_ttps": "35"}, 
  {"percent_of_ttps": " 22.22", "matching_events": " 19283", "total_events": " 19953", "matching_ttps": "  6", "percent_of_events": " 96.64", "percent_of_coverage": " 54.55", "coverage": " 6/11", "final_value": " 11.71", "group": "Carbanak", "available_ttps": "27"}, 
  {"percent_of_ttps": " 15.79", "matching_events": " 19493", "total_events": " 19953", "matching_ttps": "  6", "percent_of_events": " 97.69", "percent_of_coverage": " 45.45", "coverage": " 5/11", "final_value": " 7.01", "group": "BRONZE BUTLER", "available_ttps": "38"}, 
  {"percent_of_ttps": " 16.67", "matching_events": " 19296", "total_events": " 19953", "matching_ttps": "  5", "percent_of_events": " 96.71", "percent_of_coverage": " 45.45", "coverage": " 5/11", "final_value": " 7.33", "group": "MuddyWater", "available_ttps": "30"}, 
  {"percent_of_ttps": " 26.32", "matching_events": " 144", "total_events": " 19953", "matching_ttps": "  5", "percent_of_events": " 0.72", "percent_of_coverage": " 45.45", "coverage": " 5/11", "final_value": " 0.09", "group": "Putter Panda", "available_ttps": "19"}, 
  {"percent_of_ttps": " 35.71", "matching_events": " 19066", "total_events": " 19953", "matching_ttps": "  5", "percent_of_events": " 95.55", "percent_of_coverage": " 27.27", "coverage": " 3/11", "final_value": " 9.31", "group": "Stealth Falcon", "available_ttps": "14"}, 
  {"percent_of_ttps": " 29.41", "matching_events": " 126", "total_events": " 19953", "matching_ttps": "  5", "percent_of_events": " 0.63", "percent_of_coverage": " 36.36", "coverage": " 4/11", "final_value": " 0.07", "group": "Moafee", "available_ttps": "17"}, 
  {"percent_of_ttps": " 33.33", "matching_events": " 224", "total_events": " 19953", "matching_ttps": "  5", "percent_of_events": " 1.12", "percent_of_coverage": " 36.36", "coverage": " 4/11", "final_value": " 0.14", "group": "Gorgon Group", "available_ttps": "15"}, 
  {"percent_of_ttps": " 26.32", "matching_events": " 19230", "total_events": " 19953", "matching_ttps": "  5", "percent_of_events": " 96.38", "percent_of_coverage": " 36.36", "coverage": " 4/11", "final_value": " 9.22", "group": "Sowbug", "available_ttps": "19"}, 
  {"percent_of_ttps": " 21.74", "matching_events": " 217", "total_events": " 19953", "matching_ttps": "  5", "percent_of_events": " 1.09", "percent_of_coverage": " 36.36", "coverage": " 4/11", "final_value": " 0.09", "group": "Sandworm Team", "available_ttps": "23"}, 
  {"percent_of_ttps": " 19.23", "matching_events": " 291", "total_events": " 19953", "matching_ttps": "  5", "percent_of_events": " 1.46", "percent_of_coverage": " 36.36", "coverage": " 4/11", "final_value": " 0.1", "group": "APT18", "available_ttps": "26"}, 
  {"percent_of_ttps": " 17.39", "matching_events": " 236", "total_events": " 19953", "matching_ttps": "  4", "percent_of_events": " 1.18", "percent_of_coverage": " 18.18", "coverage": " 2/11", "final_value": " 0.04", "group": "Orangeworm", "available_ttps": "23"}, 
  {"percent_of_ttps": " 50", "matching_events": " 168", "total_events": " 19953", "matching_ttps": "  4", "percent_of_events": " 0.84", "percent_of_coverage": " 27.27", "coverage": " 3/11", "final_value": " 0.11", "group": "Charming Kitten", "available_ttps": "8"}, 
  {"percent_of_ttps": " 57.14", "matching_events": " 19285", "total_events": " 19953", "matching_ttps": "  4", "percent_of_events": " 96.65", "percent_of_coverage": " 36.36", "coverage": " 4/11", "final_value": " 20.08", "group": "Poseidon Group", "available_ttps": "7"}, 
  {"percent_of_ttps": " 13.79", "matching_events": " 73", "total_events": " 19953", "matching_ttps": "  4", "percent_of_events": " 0.37", "percent_of_coverage": " 36.36", "coverage": " 4/11", "final_value": " 0.02", "group": "APT30", "available_ttps": "29"}, 
  {"percent_of_ttps": " 30.77", "matching_events": " 349", "total_events": " 19953", "matching_ttps": "  4", "percent_of_events": " 1.75", "percent_of_coverage": " 27.27", "coverage": " 3/11", "final_value": " 0.15", "group": "Thrip", "available_ttps": "13"}, 
  {"percent_of_ttps": " 17.65", "matching_events": " 19066", "total_events": " 19953", "matching_ttps": "  3", "percent_of_events": " 95.55", "percent_of_coverage": " 27.27", "coverage": " 3/11", "final_value": " 4.6", "group": "FIN6", "available_ttps": "17"}, 
  {"percent_of_ttps": " 27.27", "matching_events": " 19223", "total_events": " 19953", "matching_ttps": "  3", "percent_of_events": " 96.34", "percent_of_coverage": " 27.27", "coverage": " 3/11", "final_value": " 7.17", "group": "Suckfly", "available_ttps": "11"}, 
  {"percent_of_ttps": " 27.27", "matching_events": " 191", "total_events": " 19953", "matching_ttps": "  3", "percent_of_events": " 0.96", "percent_of_coverage": " 18.18", "coverage": " 2/11", "final_value": " 0.05", "group": "CopyKittens", "available_ttps": "11"}, 
  {"percent_of_ttps": " 21.43", "matching_events": " 19007", "total_events": " 19953", "matching_ttps": "  3", "percent_of_events": " 95.26", "percent_of_coverage": " 27.27", "coverage": " 3/11", "final_value": " 5.57", "group": "Cleaver", "available_ttps": "14"}, 
  {"percent_of_ttps": " 17.65", "matching_events": " 61", "total_events": " 19953", "matching_ttps": "  3", "percent_of_events": " 0.31", "percent_of_coverage": " 27.27", "coverage": " 3/11", "final_value": " 0.01", "group": "Gamaredon Group", "available_ttps": "17"}, 
  {"percent_of_ttps": " 15.79", "matching_events": " 87", "total_events": " 19953", "matching_ttps": "  3", "percent_of_events": " 0.44", "percent_of_coverage": " 27.27", "coverage": " 3/11", "final_value": " 0.02", "group": "Scarlet Mimic", "available_ttps": "19"}, 
  {"percent_of_ttps": " 33.33", "matching_events": " 120", "total_events": " 19953", "matching_ttps": "  3", "percent_of_events": " 0.6", "percent_of_coverage": " 27.27", "coverage": " 3/11", "final_value": " 0.05", "group": "FIN10", "available_ttps": "9"}, 
  {"percent_of_ttps": " 23.08", "matching_events": " 105", "total_events": " 19953", "matching_ttps": "  3", "percent_of_events": " 0.53", "percent_of_coverage": " 36.36", "coverage": " 4/11", "final_value": " 0.04", "group": "APT33", "available_ttps": "13"}, 
  {"percent_of_ttps": " 22.22", "matching_events": " 51", "total_events": " 19953", "matching_ttps": "  2", "percent_of_events": " 0.26", "percent_of_coverage": " 18.18", "coverage": " 2/11", "final_value": " 0.01", "group": "Night Dragon", "available_ttps": "9"}, 
  {"percent_of_ttps": " 28.57", "matching_events": " 51", "total_events": " 19953", "matching_ttps": "  2", "percent_of_events": " 0.26", "percent_of_coverage": " 18.18", "coverage": " 2/11", "final_value": " 0.01", "group": "APT17", "available_ttps": "7"}, 
  {"percent_of_ttps": " 28.57", "matching_events": " 229", "total_events": " 19953", "matching_ttps": "  2", "percent_of_events": " 1.15", "percent_of_coverage": " 18.18", "coverage": " 2/11", "final_value": " 0.06", "group": "Winnti Group", "available_ttps": "7"}, 
  {"percent_of_ttps": " 66.67", "matching_events": " 236", "total_events": " 19953", "matching_ttps": "  2", "percent_of_events": " 1.18", "percent_of_coverage": " 18.18", "coverage": " 2/11", "final_value": " 0.14", "group": "PROMETHIUM", "available_ttps": "3"}, 
  {"percent_of_ttps": " 11.11", "matching_events": " 19175", "total_events": " 19953", "matching_ttps": "  2", "percent_of_events": " 96.1", "percent_of_coverage": " 18.18", "coverage": " 2/11", "final_value": " 1.94", "group": "FIN5", "available_ttps": "18"}, 
  {"percent_of_ttps": " 20", "matching_events": " 10", "total_events": " 19953", "matching_ttps": "  1", "percent_of_events": " 0.05", "percent_of_coverage": " 9.09", "coverage": " 1/11", "final_value": " 0", "group": "Darkhotel", "available_ttps": "5"}, 
  {"percent_of_ttps": " 25", "matching_events": " 48", "total_events": " 19953", "matching_ttps": "  1", "percent_of_events": " 0.24", "percent_of_coverage": " 9.09", "coverage": " 1/11", "final_value": " 0.01", "group": "Threat Group-1314", "available_ttps": "4"}, 
  {"percent_of_ttps": " 9.09", "matching_events": " 18949", "total_events": " 19953", "matching_ttps": "  1", "percent_of_events": " 94.97", "percent_of_coverage": " 9.09", "coverage": " 1/11", "final_value": " 0.78", "group": "Leafminer", "available_ttps": "11"}, 
  {"percent_of_ttps": " 10", "matching_events": " 47", "total_events": " 19953", "matching_ttps": "  1", "percent_of_events": " 0.24", "percent_of_coverage": " 18.18", "coverage": " 2/11", "final_value": " 0", "group": "NEODYMIUM", "available_ttps": "10"}, 
  {"percent_of_ttps": " 20", "matching_events": " 3", "total_events": " 19953", "matching_ttps": "  1", "percent_of_events": " 0.02", "percent_of_coverage": " 9.09", "coverage": " 1/11", "final_value": " 0", "group": "APT16", "available_ttps": "5"}
  ];

@Component({
  selector: 'app-charts',
  templateUrl: './charts.component.html',
  styleUrls: ['./charts.component.css']
})
export class ChartsComponent implements OnInit {

  
  constructor() { }

  public barChartOptions = {
    scaleShowVerticalLines: false,
    responsive: true
  };
  public barChartLabels = ['group', 'final_value', 'matching_events', 'total_events', 'percent_of_events', 'matching_ttps', 'available_ttps', 'percent_of_ttps', 'coverage', 'percent_of_coverage' ];
  //barcharLables = LOG_DATA.every()
  public barChartType = 'bar';
  public barChartLegend = true;
  public barChartData = [
    {
      data: [65, 59, 80, 81, 56, 55, 40], label: 'APT Data'
    },
  ];
 


  // events
  public chartClicked(e:any):void {
    console.log(e);
  }
 
  public chartHovered(e:any):void {
    console.log(e);
  }
  ngOnInit() {  
}

}
