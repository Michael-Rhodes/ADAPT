import { Component, OnInit, ViewChild } from '@angular/core';
import { HttpClient, HttpResponse} from '@angular/common/http';
import { Observable } from 'rxjs';
import { GraphDataService } from '../graphData.service';
import { BaseChartDirective } from 'ng2-charts/ng2-charts';

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
@Component({
  selector: 'app-charts',
  templateUrl: './charts.component.html',
  styleUrls: ['./charts.component.css']
})
export class ChartsComponent implements OnInit {
  public logData = new Array();
  public aptGroupNames = new Array();
  public aptFinalValues = new Array();

  constructor(private api: GraphDataService){}

  public barChartOptions = {
    scaleShowVerticalLines: true,
    responsive: true
  };
  public barChartLables;
  public barChartType = 'bar';
  public barChartLegend = true;
  public barChartData = [
    {
      data: [], label: 'APT Data'
    },
  ];
  public chartColors: Array<any> = [
    { // first color
      backgroundColor: 'grey',
      borderColor: 'rgba(225,10,24,0.2)',
      pointBackgroundColor: 'rgba(225,10,24,0.2)',
      pointBorderColor: '#fff',
      pointHoverBorderColor: 'rgba(225,10,24,0.2)'
    }];
  // events
  public chartClicked(e:any):void {
    console.log(e);
  }
  public chartHovered(e:any):void {
    console.log(e);
  }
  public getGroupNames():void {
    for(var i = 0; i < 58; i ++){
      console.log(this.logData[i]['group']);
    }
  }

  public pieChartLabels:string[] = ['Collection', 'Command-and-Control', 'Credential-Access', 
          'Defense-Evasion', 'Discovery', 'Execution', 'Exfiltration', 'Initial-Access', 
          'Lateral-Movement', 'Persistence', 'Privilege-Escalation'];
  public pieChartData:number[] = [8, 10, 5, 4, 6, 1, 9, 0, 7, 2, 3];
  public pieChartType:string = 'pie';
 
  public getAPIData():void {
     // Call the API
     this.api.callAPI().subscribe((data) => {
     //console.log(data); // this should get you the body of the request. this is a DEBUG
      for( let entry in data){
        //console.log("added in NAMES: "+ data[entry]['group']);
        this.aptGroupNames.push(data[entry]['group']);
        this.aptFinalValues.push(data[entry]['final_value']);
      }
      this.barChartLables = this.aptGroupNames;
      this.barChartData[0].data = this.aptFinalValues;
    }, (err) => {
      // Do your error handling here
      console.log('error');
      alert('There was an error loading the data.');
    });
  }
  public lineChartColors:Array<any> = [
    { // grey
      backgroundColor: 'rgba(148,159,177,0.2)',
      borderColor: 'rgba(148,159,177,1)',
      pointBackgroundColor: 'rgba(148,159,177,1)',
      pointBorderColor: '#fff',
      pointHoverBackgroundColor: '#fff',
      pointHoverBorderColor: 'rgba(148,159,177,0.8)'
    }
  ];
  public lineChartLegend:boolean = true;
  public lineChartType:string = 'line';
  ngOnInit() {  
    //get data 1st!
    this.getAPIData();
  }
}
